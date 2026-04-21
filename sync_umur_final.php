<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Balita;
use App\Models\Pemeriksaan;
use Carbon\Carbon;

$tgl_pemeriksaan_tetap = '2025-09-23';

$count = 0;
foreach(Balita::all() as $b) {
    if (!$b->tanggal_lahir) continue;
    $tglLahir = Carbon::parse($b->tanggal_lahir);
    
    // Usia di Balita = Usia di hari pemeriksaan (2025-09-23)
    $m = $tglLahir->diffInMonths(Carbon::parse($tgl_pemeriksaan_tetap));
    $u = round($m/12, 1);
    if($u <= 0) $u = 0.1;
    
    $b->usia = $u;
    $b->save();
    $count++;
}

$countPem = 0;
foreach(Pemeriksaan::all() as $p) {
    $b = $p->balita;
    if ($b && $b->tanggal_lahir) {
        $m = Carbon::parse($b->tanggal_lahir)->diffInMonths(Carbon::parse($p->tanggal_pemeriksaan));
        $u = round($m/12, 1);
        if($u <= 0) $u = 0.1;

        $p->usia_saat_periksa = $u;
        $p->save();
        $countPem++;
    }
}

echo "Sinkronisasi UMUR selesai! Balita: $count, Pemeriksaan: $countPem\n";
