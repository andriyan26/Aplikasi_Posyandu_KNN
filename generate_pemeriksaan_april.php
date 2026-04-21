<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Balita;
use App\Models\Pemeriksaan;
use App\Models\DataLatih;
use App\Services\KnnService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

$tgl_periksa = '2026-04-21';

$knnService = app(KnnService::class);
$trainingItems = DataLatih::all();
$trainData = [];
if ($trainingItems->count() >= 3) {
    foreach ($trainingItems as $item) {
        $trainData[] = [
            'features' => [
                $item->usia, rtrim(rtrim(number_format($item->berat_badan, 2, '.', ''), '0'), '.'), 
                rtrim(rtrim(number_format($item->tinggi_badan, 2, '.', ''), '0'), '.'), 
                rtrim(rtrim(number_format($item->lingkar_lengan_atas, 2, '.', ''), '0'), '.'), 
                rtrim(rtrim(number_format($item->lingkar_kepala, 2, '.', ''), '0'), '.')
            ],
            'label' => $item->status_stunting
        ];
    }
}

DB::table('pemeriksaan')->truncate();

$id_kader = 1;
$now = Carbon::now();

function getIdeal($age_years) {
    if ($age_years < 0) $age_years = 0;
    if ($age_years < 1) return ['bb' => 3.5 + ($age_years*6.5), 'tb' => 50 + ($age_years*25)];
    if ($age_years < 2) return ['bb' => 10 + (($age_years-1)*2), 'tb' => 75 + (($age_years-1)*12)];
    if ($age_years < 3) return ['bb' => 12 + (($age_years-2)*2), 'tb' => 87 + (($age_years-2)*9)];
    if ($age_years < 4) return ['bb' => 14 + (($age_years-3)*2), 'tb' => 96 + (($age_years-3)*7)];
    if ($age_years < 5) return ['bb' => 16 + (($age_years-4)*2), 'tb' => 103 + (($age_years-4)*7)];
    return ['bb' => 18 + (($age_years-5)*2), 'tb' => 110 + (($age_years-5)*6)];
}

$count = 0;
foreach (Balita::all() as $balita) {
    if (!$balita->tanggal_lahir) continue;

    $diffInMonths = Carbon::parse($balita->tanggal_lahir)->diffInMonths(Carbon::parse($tgl_periksa));
    $usia = round($diffInMonths / 12, 1);
    if($usia <= 0) $usia = 0.1;

    $ideal = getIdeal($usia);
    
    // Make ~80% Healthy (Normal variance +/- 5%), 20% Stunted (Variance -15% TB, -20% BB)
    $isStunted = (rand(1, 100) > 80);
    
    if ($isStunted) {
        // Berat dan Tinggi jatuh di bawah standar
        $bb = round($ideal['bb'] * (rand(70, 85)/100), 1);
        $tb = round($ideal['tb'] * (rand(85, 92)/100), 1);
    } else {
        // Normal
        $bb = round($ideal['bb'] * (rand(95, 110)/100), 1);
        $tb = round($ideal['tb'] * (rand(96, 104)/100), 1);
    }

    $lila = round(14.5 + ($usia * 0.4) + (rand(-5, 5)/10), 1);
    $lk = round(45 + ($usia * 1) + (rand(-10, 10)/10), 1);

    $prediction = 'Rendah';
    if (!empty($trainData)) {
        $features = [$usia, $bb, $tb, $lila, $lk];
        $prediction = $knnService->predict($trainData, $features, 3);
    } else {
        $prediction = $isStunted ? 'Sedang' : 'Rendah';
    }

    Pemeriksaan::create([
        'kode_balita' => $balita->kode_balita,
        'id_kader' => $id_kader,
        'usia_saat_periksa' => $usia,
        'tanggal_pemeriksaan' => $tgl_periksa,
        'berat_badan' => $bb,
        'tinggi_badan' => $tb,
        'lingkar_lengan_atas' => $lila,
        'lingkar_kepala' => $lk,
        'status_stunting' => $prediction
    ]);
    
    // Sync to balita so it aligns perfectly
    $balita->usia = $usia;
    $balita->save();

    $count++;
}

echo "Successfully generated $count examinations for $tgl_periksa using WHO biological metrics!\n";
