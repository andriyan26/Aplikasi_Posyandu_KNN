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
        $correct = 0;
        $total = count($testData);
        $predictions = [];
        
        $truePositives = 0; // for complex precision calculating
        // In multi-class, normally we compute accuracy over all classes.

        foreach ($testData as $testItem) {
            $prediction = $this->predict($trainData, $testItem['features'], $k);
            if ($prediction === $testItem['label']) {
                $correct++;
            }
            $predictions[] = [
                'actual' => $testItem['label'],
                'predicted' => $prediction
            ];
        }

        $accuracy = ($total > 0) ? ($correct / $total) * 100 : 0;

        return [
            'accuracy' => round($accuracy, 2),
            'correct' => $correct,
            'total' => $total,
            'k' => $k,
            'predictions' => $predictions
        ];
    }
}
