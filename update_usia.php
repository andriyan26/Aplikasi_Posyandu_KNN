<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Balita;
use Carbon\Carbon;

$balitas = Balita::all();
$tanggal_pemeriksaan = '2026-04-21';

foreach ($balitas as $b) {
    if (!$b->tanggal_lahir) continue;
    
    $tglLahir = Carbon::parse($b->tanggal_lahir);
    $tglPeriksa = Carbon::parse($tanggal_pemeriksaan);
    
    $diffInMonths = $tglLahir->diffInMonths($tglPeriksa);
    $usia_desimal = round($diffInMonths / 12, 1);
    
    if($usia_desimal <= 0) $usia_desimal = 0.1;

    $b->update(['usia' => $usia_desimal]);
}
echo "Usia Balita batch updated successfully!\n";
