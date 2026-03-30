<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\Balita;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        
        // Baca CSV sederhana tanpa package external
        $handle = fopen($file->path(), 'r');
        $header = fgetcsv($handle, 1000, ',');
        
        $count = 0;
        // Asumsi format CSV: Nama, Tanggal_Lahir, L/P, Orang_Tua, Tgl_Periksa, BB, TB, LiLA, L.Kepala, Z_Score, Status
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // Find or create balita
            $balita = Balita::firstOrCreate(
                ['nama' => $data[0], 'nama_orang_tua' => $data[3]],
                [
                    'tanggal_lahir' => $data[1],
                    'jenis_kelamin' => $data[2]
                ]
            );

            // Buat pemeriksaan
            Pemeriksaan::create([
                'balita_id' => $balita->id,
                'tanggal_pemeriksaan' => $data[4],
                'berat_badan' => $data[5],
                'tinggi_badan' => $data[6],
                'lingkar_lengan_atas' => $data[7] !== '' ? $data[7] : null,
                'lingkar_kepala' => $data[8] !== '' ? $data[8] : null,
                'z_score' => $data[9] !== '' ? $data[9] : null,
                'status_stunting' => $data[10] !== '' ? $data[10] : null
            ]);
            $count++;
        }
        fclose($handle);

        return back()->with('success', "{$count} data berhasil diimpor!");
    }
}
