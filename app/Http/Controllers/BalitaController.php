<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use Illuminate\Http\Request;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $per_page = $request->get('per_page', 5); // Default 5

        $query = Balita::query()->latest();

        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nama_orang_tua', 'LIKE', "%{$search}%");
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator $balitas */
        if ($per_page === 'all') {
            $balitas = $query->paginate($query->count());
        } else {
            $balitas = $query->paginate($per_page);
        }
        $balitas->withQueryString();

        return view('balita.index', compact('balitas'));
    }

    public function create()
    {
        return view('balita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_orang_tua' => 'required|string|max:255',
        ]);

        $data = $request->all();
        
        // Generate Auto Increment Kode (Format: BAL-0001)
        $lastBalita = Balita::orderBy('id', 'desc')->first();
        if ($lastBalita && $lastBalita->kode) {
            $lastNumber = (int) substr($lastBalita->kode, 4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $data['kode'] = 'BAL-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        Balita::create($data);

        return redirect()->route('balita.index')->with('success', 'Data Balita berhasil ditambahkan');
    }

    public function edit(Balita $balita)
    {
        return view('balita.edit', compact('balita'));
    }

    public function update(Request $request, Balita $balita)
    {
        $request->validate([
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_orang_tua' => 'required|string|max:255',
        ]);

        $balita->update($request->all());

        return redirect()->route('balita.index')->with('success', 'Data Balita berhasil diupdate');
    }

    public function destroy(Balita $balita)
    {
        $balita->delete();
        return redirect()->route('balita.index')->with('success', 'Data Balita berhasil dihapus');
    }

    public function exportData()
    {
        $balitas = Balita::all();
        $exportData = [];
        foreach ($balitas as $i => $balita) {
            $tanggal_lahir = \Carbon\Carbon::parse($balita->tanggal_lahir);
            $umur_bulan = $tanggal_lahir->diffInMonths(\Carbon\Carbon::now());
            
            $status = 'Masih dalam rentang balita';
            if ($umur_bulan > 60) {
                $status = 'Usia di atas 5 tahun';
            }

            $exportData[] = [
                'No' => $i + 1,
                'Kode' => $balita->kode,
                'NIK' => $balita->nik,
                'Nama Balita' => $balita->nama,
                'Usia' => $balita->usia,
                'Jenis Kelamin' => $balita->jenis_kelamin,
                'Nama Orang Tua' => $balita->nama_orang_tua,
                'Status Usia' => $status,
                'Tanggal Lahir' => $balita->tanggal_lahir,
            ];
        }

        return response()->json($exportData);
    }

    public function importPreview(Request $request)
    {
        $rows = $request->input('data');
        if (!$rows || !is_array($rows)) {
            return response()->json(['error' => 'Data tidak valid'], 400);
        }

        $newCount = 0;
        $updateCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $kode = $row['Kode'] ?? null;
            $nik = $row['NIK'] ?? null;
            $nama = $row['Nama Balita'] ?? null;
            $nama_ortu = $row['Nama Orang Tua'] ?? null;
            $jk = $row['Jenis Kelamin'] ?? null;

            if (!$nama || !$nama_ortu || !$jk) {
                $failedCount++;
                $errors[] = "Baris " . ($index + 1) . ": Nama, Nama Ortu, atau Jenis Kelamin kosong.";
                continue;
            }

            $existing = null;
            if ($kode && $kode !== '-') {
                $existing = Balita::where('kode', $kode)->first();
            }
            if (!$existing && $nik && $nik !== '-') {
                $existing = Balita::where('nik', $nik)->first();
            }
            if (!$existing) {
                $existing = Balita::where('nama', $nama)
                                  ->where('nama_orang_tua', $nama_ortu)
                                  ->where('jenis_kelamin', $jk)
                                  ->first();
            }

            if ($existing) {
                $updateCount++;
            } else {
                $newCount++;
            }
        }

        return response()->json([
            'new' => $newCount,
            'update' => $updateCount,
            'failed' => $failedCount,
            'errors' => $errors
        ]);
    }

    public function importProcess(Request $request)
    {
        $rows = $request->input('data');
        if (!$rows || !is_array($rows)) {
            return response()->json(['error' => 'Data tidak valid'], 400);
        }

        \DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $kode = $row['Kode'] ?? null;
                $nik = $row['NIK'] ?? '-';
                $nama = $row['Nama Balita'] ?? null;
                $usia = $row['Usia'] ?? 0;
                $jk = $row['Jenis Kelamin'] ?? null;
                $nama_ortu = $row['Nama Orang Tua'] ?? null;
                $tgl_lahir = $row['Tanggal Lahir'] ?? null;

                if (!$nama || !$nama_ortu || !$jk) continue;

                if (!$tgl_lahir) {
                    $tgl_lahir = \Carbon\Carbon::now()->subYears((float)$usia)->format('Y-m-d');
                } else {
                    try {
                        $tgl_lahir = \Carbon\Carbon::parse($tgl_lahir)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $tgl_lahir = \Carbon\Carbon::now()->subYears((float)$usia)->format('Y-m-d');
                    }
                }

                $existing = null;
                if ($kode && $kode !== '-') {
                    $existing = Balita::where('kode', $kode)->first();
                }
                if (!$existing && $nik && $nik !== '-') {
                    $existing = Balita::where('nik', $nik)->first();
                }
                if (!$existing) {
                    $existing = Balita::where('nama', $nama)
                                      ->where('nama_orang_tua', $nama_ortu)
                                      ->where('jenis_kelamin', $jk)
                                      ->first();
                }

                if ($existing) {
                    $existing->update([
                        'nik' => $nik,
                        'nama' => $nama,
                        'usia' => $usia,
                        'jenis_kelamin' => $jk,
                        'nama_orang_tua' => $nama_ortu,
                        'tanggal_lahir' => $tgl_lahir,
                    ]);
                } else {
                    $lastBalita = Balita::orderBy('id', 'desc')->first();
                    if ($lastBalita && $lastBalita->kode) {
                        $lastNumber = (int) substr($lastBalita->kode, 4);
                        $newNumber = $lastNumber + 1;
                    } else {
                        $newNumber = 1;
                    }
                    $kode = 'BAL-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

                    Balita::create([
                        'kode' => $kode,
                        'nik' => $nik,
                        'nama' => $nama,
                        'usia' => $usia,
                        'jenis_kelamin' => $jk,
                        'nama_orang_tua' => $nama_ortu,
                        'tanggal_lahir' => $tgl_lahir,
                    ]);
                }
            }
            \DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
