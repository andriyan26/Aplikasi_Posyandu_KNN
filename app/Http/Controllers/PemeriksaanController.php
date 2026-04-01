<?php

namespace App\Http\Controllers;

use App\Models\DataLatih;
use App\Models\Pemeriksaan;
use App\Models\Balita;
use Illuminate\Http\Request;
use App\Services\ZScoreService;
use App\Services\KnnService;

class PemeriksaanController extends Controller
{
    protected $zScoreService;
    protected $knnService;

    public function __construct(ZScoreService $zScoreService, KnnService $knnService)
    {
        $this->zScoreService = $zScoreService;
        $this->knnService = $knnService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $per_page = $request->get('per_page', 5);

        $query = Pemeriksaan::with('balita')->latest();

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
        $balitas = Balita::all();
        return view('pemeriksaan.create', compact('balitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'lingkar_lengan_atas' => 'nullable|numeric',
            'lingkar_kepala' => 'nullable|numeric',
        ]);

        $balita = Balita::findOrFail($request->balita_id);
        
        // Usia dalam bulan
        $tglLahir = \Carbon\Carbon::parse($balita->tanggal_lahir);
        $tglPeriksa = \Carbon\Carbon::parse($request->tanggal_pemeriksaan);
        $umur_bulan = $tglLahir->diffInMonths($tglPeriksa);

        // Kalkulasi Z-Score Manual
        $z_score = $this->zScoreService->calculate(
            $request->tinggi_badan,
            $umur_bulan,
            $balita->jenis_kelamin
        );

        // Sumber data training KNN beralih ke Data Latih (dari CSV import)
        $trainingItems = DataLatih::all();
        
        $knnPrediction = 'Rendah'; // default

        // Jika data latih KNN ada lebih dari atau sama dengan 3
        if ($trainingItems->count() >= 3) {
            $trainData = [];
            foreach ($trainingItems as $item) {
                $trainData[] = [
                    'features' => [
                        $item->berat_badan,
                        $item->tinggi_badan,
                        $item->z_score ?? 0
                    ],
                    'label' => $item->status_stunting
                ];
            }

            $testFeatures = [
                $request->berat_badan,
                $request->tinggi_badan,
                $z_score
            ];

            // Default K = 3
            $knnPrediction = $this->knnService->predict($trainData, $testFeatures, 3);
        } else {
            // Jika tidak ada data latih, gunakan klasifikasi Z-Score referensi WHO
            $knnPrediction = $this->zScoreService->classify($z_score);
        }

        Pemeriksaan::create([
            'balita_id' => $request->balita_id,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'lingkar_lengan_atas' => $request->lingkar_lengan_atas,
            'lingkar_kepala' => $request->lingkar_kepala,
            'z_score' => $z_score,
            'status_stunting' => $knnPrediction
        ]);

        return redirect()->route('pemeriksaan.index')->with('success', "Pemeriksaan berhasil. Hasil Z-Score: {$z_score}, Klasifikasi: {$knnPrediction}");
    }
}
