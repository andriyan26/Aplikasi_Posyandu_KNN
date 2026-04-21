<?php

namespace App\Http\Controllers;

use App\Models\DataLatih;
use App\Models\Pemeriksaan;
use App\Models\Balita;
use App\Models\Kader;
use Illuminate\Http\Request;
use App\Services\KnnService;

class PemeriksaanController extends Controller
{
    protected $knnService;

    public function __construct(KnnService $knnService)
    {
        $this->knnService = $knnService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $per_page = $request->get('per_page', 5);

        $query = Pemeriksaan::with(['balita', 'kader'])->latest();

        if ($search) {
            $query->whereHas('balita', function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%");
            });
        }

        if ($per_page === 'all') {
            $pemeriksaans = $query->paginate($query->count());
        } else {
            $pemeriksaans = $query->paginate($per_page);
        }
        /** @var \Illuminate\Pagination\LengthAwarePaginator $pemeriksaans */
        $pemeriksaans->withQueryString();

        return view('pemeriksaan.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $balitas = Balita::where('status_balita', 'Masih Aktif')->get();
        $kaders = Kader::where('status_aktif', 'Aktif')->get();
        
        $kader_login = null;
        if(auth()->user()->role === 'kader') {
            $kader_login = Kader::whereRaw('LOWER(nama) = ?', [strtolower(auth()->user()->name)])->first();
        }

        return view('pemeriksaan.create', compact('balitas', 'kaders', 'kader_login'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_balita' => 'required|exists:balita,kode_balita',
            'id_kader' => 'required|exists:kader,id_kader',
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'lingkar_lengan_atas' => 'required|numeric',
            'lingkar_kepala' => 'required|numeric',
        ]);

        $balita = Balita::where('kode_balita', $request->kode_balita)->firstOrFail();
        
        // Usia dalam tahun format desimal (contoh: 2.1)
        $tglLahir = \Carbon\Carbon::parse($balita->tanggal_lahir);
        $tglPeriksa = \Carbon\Carbon::parse($request->tanggal_pemeriksaan);
        
        $diffInMonths = $tglLahir->diffInMonths($tglPeriksa);
        $usia_desimal = round($diffInMonths / 12, 1);

        // Update Usia Balita sesuai tanggal periksa terakhir
        $balita->update([
            'usia' => $usia_desimal
        ]);

        // Prediksi KNN
        $trainingItems = DataLatih::all();
        
        $knnPrediction = 'Rendah'; // default

        // Jika data latih KNN ada lebih dari atau sama dengan 3
        if ($trainingItems->count() >= 3) {
            $trainData = [];
            foreach ($trainingItems as $item) {
                $trainData[] = [
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

            $testFeatures = [
                $usia_desimal,
                $request->berat_badan,
                $request->tinggi_badan,
                $request->lingkar_lengan_atas,
                $request->lingkar_kepala
            ];

            // Default K = 3
            $knnPrediction = $this->knnService->predict($trainData, $testFeatures, 3);
        } else {
            // Fallback kalau tak ada dataset
            $knnPrediction = null; 
        }

        Pemeriksaan::create([
            'kode_balita' => $request->kode_balita,
            'id_kader' => $request->id_kader,
            'usia_saat_periksa' => $usia_desimal,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'lingkar_lengan_atas' => $request->lingkar_lengan_atas,
            'lingkar_kepala' => $request->lingkar_kepala,
            'status_stunting' => $knnPrediction
        ]);

        return redirect()->route('pemeriksaan.index')->with('success', "Pemeriksaan berhasil. Hasil Prediksi Status: {$knnPrediction}");
    }

    public function show(Pemeriksaan $pemeriksaan)
    {
        $pemeriksaan->load('balita', 'kader'); 
        return view('pemeriksaan.show', compact('pemeriksaan'));
    }

    public function pdfSingle(Pemeriksaan $pemeriksaan)
    {
        $pemeriksaan->load('balita', 'kader');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pemeriksaan.pdf_single', compact('pemeriksaan'));
        
        $fileName = 'Pemeriksaan_' . ($pemeriksaan->balita->nama ?? 'Balita') . '_' . \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->format('d-m-Y') . '.pdf';
        
        return $pdf->download($fileName);
    }
}
