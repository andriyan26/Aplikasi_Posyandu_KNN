<?php

namespace App\Services;

class KnnService
{
    /**
     * Cari nilai Min dan Max untuk setiap fitur dari Dataset
     */
    public function getMinMax(array $trainData): array
    {
        $minMax = [];
        if (empty($trainData)) return $minMax;

        $numFeatures = count($trainData[0]['features'] ?? []);
        
        for ($i = 0; $i < $numFeatures; $i++) {
            $minMax[$i] = ['min' => PHP_FLOAT_MAX, 'max' => -PHP_FLOAT_MAX];
        }

        foreach ($trainData as $item) {
            foreach ($item['features'] as $index => $val) {
                if ($val < $minMax[$index]['min']) $minMax[$index]['min'] = $val;
                if ($val > $minMax[$index]['max']) $minMax[$index]['max'] = $val;
            }
        }
        
        return $minMax;
    }

    /**
     * Hitung Jarak Euclidean 
     */
    public function euclideanDistance(array $point1, array $point2): float
    {
        $sum = 0;
        foreach ($point1 as $index => $val1) {
             $val2 = $point2[$index] ?? 0;
             $sum += pow($val1 - $val2, 2);
        }
        return sqrt($sum);
    }

    /**
     * Prediksi label berdasar k-tetangga terdekat dengan normalisasi (Otomatis jika fitur > 1)
     */
    public function predict(array $trainData, array $testFeatures, int $k = 3): string
    {
        // 1. Dapatkan batas Min-Max dari data latih
        $minMax = $this->getMinMax($trainData);
        
        // 2. Normalisasi fitur testing
        $testNormalized = [];
        foreach ($testFeatures as $index => $val) {
            $min = $minMax[$index]['min'] ?? 0;
            $max = $minMax[$index]['max'] ?? 0;
            $testNormalized[$index] = ($max == $min) ? 0 : ($val - $min) / ($max - $min);
        }

        $distances = [];

        foreach ($trainData as $trainItem) {
            // 3. Normalisasi fitur data latih terkait
            $trainNormalized = [];
            foreach ($trainItem['features'] as $index => $val) {
                $min = $minMax[$index]['min'] ?? 0;
                $max = $minMax[$index]['max'] ?? 0;
                $trainNormalized[$index] = ($max == $min) ? 0 : ($val - $min) / ($max - $min);
            }

            // 4. Hitung Jarak Euclidean setelah terskala [0, 1]
            $distance = $this->euclideanDistance($testNormalized, $trainNormalized);
            
            $distances[] = [
                'distance' => $distance,
                'label' => $trainItem['label']
            ];
        }

        // Urutkan jarak terdekat (ASC)
        usort($distances, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        // Ambil K elemen teratas
        $topK = array_slice($distances, 0, $k);

        // Cari label paling sering (Votting)
        $labelCounts = [];
        foreach ($topK as $item) {
            $label = $item['label'];
            if (!isset($labelCounts[$label])) {
                $labelCounts[$label] = 0;
            }
            $labelCounts[$label]++;
        }

        // Urutkan berdasarkan kemunculan terbanyak (DESC)
        arsort($labelCounts);

        return array_key_first($labelCounts) ?? 'Rendah'; // Default fallback
    }

    /**
     * Evaluasi model untuk mendapat matriks kebingungan (Akurasi, dll)
     */
    public function evaluateModel(array $trainData, array $testData, int $k = 3): array
    {
        $total = count($testData);
        if ($total === 0) {
            return [
                'accuracy' => 0,
                'precision' => 0,
                'recall' => 0,
                'f1_score' => 0,
                'correct' => 0,
                'total' => 0,
                'confusion_matrix' => [],
                'predictions' => []
            ];
        }

        $correct = 0;
        $predictions = [];
        $classes = ['Rendah', 'Sedang', 'Tinggi'];
        
        // Agar komputasi Evaluasi tidak me-recompute min-max berulang kali, kita normalisasi total di sini.
        // Walaupun predict() punya min-max otomatis, memanggil predict() ribuan kali akan memperlambat evaluasi.
        // Oleh karena itu, kita akan mem-bypass predict normal untuk scaling bulk:

        $minMax = $this->getMinMax($trainData);

        $normalize = function($features) use ($minMax) {
            $norm = [];
            foreach ($features as $index => $val) {
                $min = $minMax[$index]['min'] ?? 0;
                $max = $minMax[$index]['max'] ?? 0;
                $norm[$index] = ($max == $min) ? 0 : ($val - $min) / ($max - $min);
            }
            return $norm;
        };

        $normalizedTrainData = array_map(function($item) use ($normalize) {
            return [
                'features' => $normalize($item['features']),
                'label' => $item['label']
            ];
        }, $trainData);

        $normalizedTestData = array_map(function($item) use ($normalize) {
            return [
                'features' => $normalize($item['features']),
                'label' => $item['label']
            ];
        }, $testData);

        // Define inline scaled-predict closure for speed
        $scaledPredict = function($trainNorm, $testFeaturesNorm, $k) {
            $distances = [];
            foreach ($trainNorm as $trainItem) {
                $distances[] = [
                    'distance' => $this->euclideanDistance($testFeaturesNorm, $trainItem['features']),
                    'label' => $trainItem['label']
                ];
            }
            usort($distances, fn($a, $b) => $a['distance'] <=> $b['distance']);
            $topK = array_slice($distances, 0, $k);
            $counts = [];
            foreach ($topK as $it) {
                $counts[$it['label']] = ($counts[$it['label']] ?? 0) + 1;
            }
            arsort($counts);
            return array_key_first($counts) ?? 'Rendah';
        };
        
        // Initialize Confusion Matrix
        $matrix = [];
        foreach ($classes as $actual) {
            foreach ($classes as $predicted) {
                $matrix[$actual][$predicted] = 0;
            }
        }

        foreach ($normalizedTestData as $testItem) {
            $prediction = $scaledPredict($normalizedTrainData, $testItem['features'], $k);
            $actual = $testItem['label'];
            
            if ($prediction === $actual) {
                $correct++;
            }
            
            $matrix[$actual][$prediction]++;
            
            $predictions[] = [
                'actual' => $actual,
                'predicted' => $prediction
            ];
        }

        // Calculate Precision, Recall, F1 for each class (Macro Averaging)
        $precisionSum = 0;
        $recallSum = 0;
        $f1Sum = 0;
        $validClasses = 0;

        foreach ($classes as $class) {
            $tp = $matrix[$class][$class];
            
            $fp = 0;
            foreach ($classes as $otherClass) {
                if ($otherClass !== $class) $fp += $matrix[$otherClass][$class];
            }
            
            $fn = 0;
            foreach ($classes as $otherClass) {
                if ($otherClass !== $class) $fn += $matrix[$class][$otherClass];
            }

            $precision = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;
            $recall = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;
            $f1 = ($precision + $recall) > 0 ? 2 * ($precision * $recall) / ($precision + $recall) : 0;

            $precisionSum += $precision;
            $recallSum += $recall;
            $f1Sum += $f1;
            $validClasses++;
        }

        $accuracy = ($correct / $total) * 100;
        $avgPrecision = ($precisionSum / $validClasses) * 100;
        $avgRecall = ($recallSum / $validClasses) * 100;
        $avgF1 = ($f1Sum / $validClasses) * 100;

        return [
            'accuracy' => round($accuracy, 2),
            'precision' => round($avgPrecision, 2),
            'recall' => round($avgRecall, 2),
            'f1_score' => round($avgF1, 2),
            'correct' => $correct,
            'total' => $total,
            'k' => $k,
            'confusion_matrix' => $matrix,
            'predictions' => $predictions
        ];
    }
}
