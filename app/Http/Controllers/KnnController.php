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

    public function __construct(KnnService $knnService)
    {
        $this->knnService = $knnService;
    }

    public function evaluasi(Request $request)
    {
        $k_value = $request->input('k_value', 3);
        
        $dataset = DataLatih::all();
        
        $formattedData = [];
        foreach ($dataset as $item) {
            $formattedData[] = [
                'features' => [
                    $item->usia,
                    $item->berat_badan,
                    $item->tinggi_badan,
                    $item->lingkar_lengan_atas,
                    $item->lingkar_kepala
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
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
                // Format: nama, jenis_kelamin, usia, berat_badan, tinggi_badan, lingkar_lengan_atas, lingkar_kepala, status_stunting
                
                $nama = $data[0] ?? 'Anonim';
                $jk = ($data[1] == 'L' || $data[1] == 'Laki-laki') ? 'L' : 'P';
                $usia = (float) ($data[2] ?? 0);
                $bb = (float) ($data[3] ?? 0);
                $tb = (float) ($data[4] ?? 0);
                $lila = (float) ($data[5] ?? 0);
                $linkep = (float) ($data[6] ?? 0);
                $status = $data[7] ?? 'Rendah';

                DataLatih::create([
                    'nama' => $nama,
                    'jenis_kelamin' => $jk,
                    'usia' => $usia,
                    'berat_badan' => $bb,
                    'tinggi_badan' => $tb,
                    'lingkar_lengan_atas' => $lila,
                    'lingkar_kepala' => $linkep,
                    'status_stunting' => $status,
                ]);
                $count++;
            }
            DB::commit();
            fclose($handle);
            return redirect()->route('knn.evaluasi', ['k_value' => $request->k_value ?? 3])->with('success', "Berhasil mengimport {$count} data latih.");
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', "Gagal import: " . $e->getMessage());
        }
    }

    public function destroyAll()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        DataLatih::truncate();
        return redirect()->back()->with('success', "Semua data latih telah dihapus.");
    }

    /**
     * Download Template CSV
     */
    public function downloadTemplate()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_knn_posyandu.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['nama', 'jenis_kelamin', 'usia', 'berat_badan', 'tinggi_badan', 'lingkar_lengan_atas', 'lingkar_kepala', 'status_stunting'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Sample Data
            fputcsv($file, ['Contoh Balita 1', 'L', '2.0', '10.5', '75.2', '13.5', '45.0', 'Rendah']);
            fputcsv($file, ['Contoh Balita 2', 'P', '3.1', '14.2', '95.0', '14.0', '48.0', 'Sedang']);
            fputcsv($file, ['Contoh Balita 3', 'L', '4.5', '18.1', '110.5', '15.5', '50.1', 'Tinggi']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
