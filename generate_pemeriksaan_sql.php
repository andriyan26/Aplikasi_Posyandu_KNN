<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Balita;
use Carbon\Carbon;
use App\Services\KnnService;
use App\Models\DataLatih;

$balitas = Balita::all();
$tanggal_pemeriksaan = '2026-04-21';
$sql = "INSERT INTO `pemeriksaan` (`kode_balita`, `id_kader`, `usia_saat_periksa`, `tanggal_pemeriksaan`, `berat_badan`, `tinggi_badan`, `lingkar_lengan_atas`, `lingkar_kepala`, `status_stunting`, `created_at`, `updated_at`) VALUES \n";

$values = [];
$now = Carbon::now()->format('Y-m-d H:i:s');

// Helper for WHO growth estimation (very rough approximation for dummy data)
// Usia in years.
// BB normal (kg) ~ (Usia * 2) + 8
// TB normal (cm) ~ (Usia * 6) + 77
// LILA normal ~ 14 - 17
// Lingkar Kepala ~ 45 - 52

// Prepare KNN Service
$knnService = app(KnnService::class);
$trainingItems = DataLatih::all();
$trainData = [];
if ($trainingItems->count() >= 3) {
    foreach ($trainingItems as $item) {
        $trainData[] = [
            'features' => [
                $item->usia, $item->berat_badan, $item->tinggi_badan, 
                $item->lingkar_lengan_atas, $item->lingkar_kepala
            ],
            'label' => $item->status_stunting
        ];
    }
}

foreach ($balitas as $index => $b) {
    $tglLahir = Carbon::parse($b->tanggal_lahir);
    $tglPeriksa = Carbon::parse($tanggal_pemeriksaan);
    
    // Exact month diff
    $diffInMonths = $tglLahir->diffInMonths($tglPeriksa);
    $usia_desimal = round($diffInMonths / 12, 1);
    // minimum 0.1 to avoid pure 0 if born same month
    if($usia_desimal <= 0) $usia_desimal = 0.1;

    // Generate random realistic metrics
    // Make 80% Normal (Rendah risk), 20% Stunted (Sedang/Tinggi risk)
    $isStunted = (rand(1, 100) > 80);

    if ($isStunted) {
        $bb = round(($usia_desimal * 1.5) + 6 + (rand(-10, 10)/10), 1);
        $tb = round(($usia_desimal * 5) + 65 + (rand(-20, 20)/10), 1);
    } else {
        $bb = round(($usia_desimal * 2.2) + 8 + (rand(-15, 15)/10), 1);
        $tb = round(($usia_desimal * 6.5) + 75 + (rand(-25, 25)/10), 1);
    }

    $lila = round(14.5 + ($usia_desimal * 0.3) + (rand(-5, 5)/10), 1);
    $likep = round(45 + ($usia_desimal * 1.2) + (rand(-5, 5)/10), 1);

    // Ensure logic bounds
    if($bb < 2) $bb = 2.5; 
    if($tb < 40) $tb = 45.0;

    // Use KNN to get strictly matched label based on our models logic!
    $knnPrediction = 'Rendah';
    if (!empty($trainData)) {
        $testFeatures = [$usia_desimal, $bb, $tb, $lila, $likep];
        $knnPrediction = $knnService->predict($trainData, $testFeatures, 3);
    } else {
        $knnPrediction = $isStunted ? 'Sedang' : 'Rendah'; // fallback
    }

    $id_kader = 1; // Default Asumsi ada kader dengan ID 1 (Contoh: SAAMAH atau SAROYAH)
    
    $values[] = "('{$b->kode_balita}', {$id_kader}, {$usia_desimal}, '{$tanggal_pemeriksaan}', {$bb}, {$tb}, {$lila}, {$likep}, '{$knnPrediction}', '{$now}', '{$now}')";
}

$sql .= implode(",\n", $values) . ";\n";

file_put_contents(__DIR__.'/pemeriksaan_dummy.sql', $sql);
echo "SQL generated successfully: pemeriksaan_dummy.sql\n";
