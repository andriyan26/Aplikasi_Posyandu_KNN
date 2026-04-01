<?php

namespace App\Services;

class KnnService
{
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
     * Prediksi label berdasar k-tetangga terdekat
     * $trainData = [['features' => [weight, height, lila], 'label' => 'Rendah']]
     */
    public function predict(array $trainData, array $testFeatures, int $k = 3): string
    {
        $distances = [];

        foreach ($trainData as $trainItem) {
            $distance = $this->euclideanDistance($testFeatures, $trainItem['features']);
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
        
        // Initialize Confusion Matrix
        $matrix = [];
        foreach ($classes as $actual) {
            foreach ($classes as $predicted) {
                $matrix[$actual][$predicted] = 0;
            }
        }

        foreach ($testData as $testItem) {
            $prediction = $this->predict($trainData, $testItem['features'], $k);
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
