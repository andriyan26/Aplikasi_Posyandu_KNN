<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use App\Services\KnnService;

class KnnController extends Controller
{
    protected $knnService;

    public function __construct(KnnService $knnService)
    {
        $this->knnService = $knnService;
    }

    public function evaluasi(Request $request)
    {
        $k_value = $request->input('k_value', 3);
        
        $dataset = Pemeriksaan::whereNotNull('status_stunting')->get();
        // Cukup bagi data jika cukup besar, atau gunakan Leave-One-Out atau K-Fold.
        // Di sini kita seolah-olah ambil semua untuk evaluasi (kemampuan resubstitution)
        // Atau buat skenario sederhana: Bagi 80% train 20% test untuk grafik evaluasi.

        $data = $dataset->toArray();
        $formattedData = [];
        
        foreach ($data as $item) {
            $formattedData[] = [
                'features' => [
                    $item['berat_badan'],
                    $item['tinggi_badan'],
                    $item['z_score']
                ],
                'label' => $item['status_stunting']
            ];
        }

        $totalData = count($formattedData);
        $evaluasi = null;
        $graphData = []; // Always initialize to prevent view errors

        if ($totalData > 0) {
            // Kita coba pakai resubstitution (uji pada diri sendiri) atau K-Fold sederhana.
            // Gunakan fungsi evaluateModel
            $evaluasi = $this->knnService->evaluateModel($formattedData, $formattedData, $k_value);
        }

        // Generate data for Graph K = 1, 3, 5, 7, 9
        $graphData = [];
        if ($totalData > 0) {
            foreach ([1, 3, 5, 7, 9] as $k_test) {
                $eval = $this->knnService->evaluateModel($formattedData, $formattedData, $k_test);
                $graphData[] = [
                    'k' => $k_test,
                    'accuracy' => $eval['accuracy']
                ];
            }
        }

        return view('knn.evaluasi', compact('evaluasi', 'k_value', 'totalData', 'graphData'));
    }
}
