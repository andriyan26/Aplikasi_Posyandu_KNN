<?php

namespace App\Http\Controllers;

use App\Models\DataLatih;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use App\Services\KnnService;
use App\Services\ZScoreService;
use Illuminate\Support\Facades\DB;

class KnnController extends Controller
{
    protected $knnService;
    protected $zScoreService;

    public function __construct(KnnService $knnService, ZScoreService $zScoreService)
    {
        $this->knnService = $knnService;
        $this->zScoreService = $zScoreService;
    }

    public function evaluasi(Request $request)
    {
        $k_value = $request->input('k_value', 3);
        
        $dataset = DataLatih::all();
        
        $formattedData = [];
        foreach ($dataset as $item) {
            $formattedData[] = [
                'features' => [
                    $item->berat_badan,
                    $item->tinggi_badan,
                    $item->z_score ?? 0
                ],
                'label' => $item->status_stunting
            ];
        }

        $totalData = count($formattedData);
        $evaluasi = null;
        $graphData = [];

        if ($totalData > 0) {
            $evaluasi = $this->knnService->evaluateModel($formattedData, $formattedData, $k_value);
            
            // Generate data for Graph K = 1, 3, 5, 7, 9
            foreach ([1, 3, 5, 7, 9] as $k_test) {
                $eval = $this->knnService->evaluateModel($formattedData, $formattedData, $k_test);
                $graphData[] = [
                    'k' => $k_test,
                    'accuracy' => $eval['accuracy']
                ];
            }
        }

        $dataLatih = DataLatih::latest()->paginate(10);

        return view('knn.evaluasi', compact('evaluasi', 'k_value', 'totalData', 'graphData', 'dataLatih'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        $header = fgetcsv($handle, 1000, ',');
        
        $count = 0;
        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // Format: nama, jenis_kelamin, berat_badan, tinggi_badan, umur_bulan (opt), status_stunting
                // Index: 0, 1, 2, 3, 4, 5
                
                $nama = $data[0] ?? 'Anonim';
                $jk = ($data[1] == 'L' || $data[1] == 'Laki-laki') ? 'L' : 'P';
                $bb = (float) ($data[2] ?? 0);
                $tb = (float) ($data[3] ?? 0);
                $umur = (float) ($data[4] ?? 0);
                $status = $data[5] ?? 'Rendah';

                // Hitung Z-Score jika memungkinkan
                $z_score = $this->zScoreService->calculate($tb, $umur, $jk);

                DataLatih::create([
                    'nama' => $nama,
                    'jenis_kelamin' => $jk,
                    'berat_badan' => $bb,
                    'tinggi_badan' => $tb,
                    'z_score' => $z_score,
                    'status_stunting' => $status,
                ]);
                $count++;
            }
            DB::commit();
            fclose($handle);
            return redirect()->back()->with('success', "Berhasil mengimport {$count} data latih.");
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', "Gagal import: " . $e->getMessage());
        }
    }

    public function destroyAll()
    {
        DataLatih::truncate();
        return redirect()->back()->with('success', "Semua data latih telah dihapus.");
    }

    /**
     * Download Template CSV
     */
    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_knn_posyandu.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['nama', 'jenis_kelamin', 'berat_badan', 'tinggi_badan', 'umur_bulan', 'status_stunting'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Sample Data
            fputcsv($file, ['Contoh Balita 1', 'L', '10.5', '75.2', '12', 'Rendah']);
            fputcsv($file, ['Contoh Balita 2', 'P', '14.2', '95.0', '36', 'Sedang']);
            fputcsv($file, ['Contoh Balita 3', 'L', '18.1', '110.5', '54', 'Tinggi']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
