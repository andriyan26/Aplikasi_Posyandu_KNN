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

$tgl_periksa = '2025-09-23';

$data = [
    'BAL-0001' => [12.3, 84, 17, 47],
    'BAL-0002' => [12, 85.1, 16, 50.2],
    'BAL-0003' => [9.1, 81, 15, 46.5],
    'BAL-0004' => [13.5, 93, 15.5, 47.5],
    'BAL-0005' => [7.85, 61, 18, 41.5],
    'BAL-0006' => [9.53, 65, 10.4, 41],
    'BAL-0007' => [21.4, 103.6, 21, 52],
    'BAL-0008' => [7.2, 71.5, 13.5, 44],
    'BAL-0009' => [14.5, 91.6, 18.5, 48.5],
    'BAL-0010' => [15.9, 105.8, 17, 50],
    'BAL-0011' => [7.9, 71.3, 15, 42.5],
    'BAL-0012' => [8.2, 75.4, 14.3, 46],
    'BAL-0013' => [18.4, 107.1, 17.3, 50],
    'BAL-0014' => [8.6, 70.2, 16, 42],
    'BAL-0015' => [6.5, 60.9, 15, 41],
    'BAL-0016' => [10.7, 81.3, 16.5, 48.5],
    'BAL-0017' => [9.4, 75.4, 15.8, 46.5],
    'BAL-0018' => [8.7, 68.5, 18, 44],
    'BAL-0019' => [6.5, 60.9, 15, 41],
    'BAL-0020' => [16.2, 98.4, 20, 47],
    'BAL-0021' => [10.2, 79.3, 15, 46],
    'BAL-0022' => [12.4, 90.2, 15.2, 50],
    'BAL-0023' => [7.4, 67.6, 13.5, 42],
    'BAL-0024' => [10.7, 82.5, 14.8, 47],
    'BAL-0025' => [12.5, 85, 16.8, 48],
    'BAL-0026' => [11.5, 87.6, 16.5, 48.5],
    'BAL-0027' => [7.7, 62.4, 17, 40],
    'BAL-0028' => [14.1, 93.1, 16.5, 51],
    'BAL-0029' => [9, 73.7, 13.5, 44],
    'BAL-0030' => [9.8, 77.1, 16, 48],
    'BAL-0031' => [6.8, 67.5, 13.7, 43],
    'BAL-0032' => [7.5, 66.5, 15, 43.5],
    'BAL-0033' => [9.9, 80.9, 15, 47],
    'BAL-0034' => [9.7, 82.3, 15.5, 47.6],
    'BAL-0035' => [9.4, 81.5, 14, 47.8],
    'BAL-0036' => [13.5, 93, 15.5, 47.5],
    'BAL-0037' => [15.3, 95.9, 17.17, 51],
    'BAL-0038' => [14.5, 104.1, 15, 53],
    'BAL-0039' => [14.5, 98.7, 16.5, 47],
    'BAL-0040' => [7.9, 61.8, 15.4, 43], // Shafano
    'BAL-0041' => [10.3, 85.2, 15.6, 50],
    'BAL-0042' => [12.4, 96.6, 16, 48],
    'BAL-0043' => [13.6, 95.8, 17.1, 46.5],
    'BAL-0044' => [8.8, 65.2, 15.5, 43.5],
    'BAL-0045' => [7.5, 67.6, 14.4, 44],
    'BAL-0046' => [10.2, 83, 15.2, 44],
    'BAL-0047' => [7.7, 71.3, 14.3, 43],
    'BAL-0048' => [21.9, 103, 21, 51],
    'BAL-0049' => [12.4, 93, 16, 48.7],
    'BAL-0050' => [15.3, 102.2, 15.7, 50],
    'BAL-0051' => [16.2, 106.8, 16.6, 50.2],
    'BAL-0052' => [11.4, 89, 14.5, 49.4],
    'BAL-0053' => [12.9, 91.5, 16.5, 47.5],
    'BAL-0054' => [13.5, 96.7, 15.5, 49],
    'BAL-0055' => [8.9, 73.6, 13.5, 46.1],
    'BAL-0056' => [15.1, 97.6, 17, 50],
    'BAL-0057' => [16.2, 102.3, 18, 51],
    'BAL-0058' => [12.4, 88, 16.8, 47],
    'BAL-0059' => [14.2, 94.7, 16, 53],
    'BAL-0060' => [14, 98.5, 16, 48],
    'BAL-0061' => [13.3, 93.7, 15, 48.5],
    'BAL-0062' => [12.1, 93, 16, 50], // khalisa merged with kailas BB
    'BAL-0063' => [12.1, 93, null, null],
    'BAL-0064' => [13.1, 88.7, 16.5, 50],
    'BAL-0065' => [14.2, 96.6, 18, 49],
    'BAL-0066' => [10.3, 88.7, 13.6, 47.3],
    'BAL-0067' => [15.4, 105.5, 16, 50.3],
    'BAL-0068' => [18, 99.7, 18.5, 50],
    'BAL-0069' => [8, 71.2, 14, 44.2],
    'BAL-0070' => [8.1, 70.3, 15.5, 45.5],
    'BAL-0071' => [10.8, 86.6, 16, 47.5],
    'BAL-0072' => [26.5, 106, 23.5, 55],
    'BAL-0073' => [10.5, 97.7, 14.5, 47.2],
    'BAL-0074' => [12.6, 94.1, 16.5, 48.5],
    'BAL-0075' => [12.6, 89.9, 16.3, 47.5],
    'BAL-0076' => [11.9, 89.4, 15, 47.2],
    'BAL-0077' => [10.5, 88.4, 14.8, 47.2],
    'BAL-0078' => [6.5, 66.2, 13.3, 47.2],
    'BAL-0079' => [15.6, 104, 16, 49.3],
    'BAL-0080' => [15.6, 104, 16, 49.3],
    'BAL-0081' => [14.6, 90.8, 17.5, 48],
    'BAL-0082' => [15.9, 106.9, 16, 52],
    'BAL-0083' => [11.1, 80, 16.5, 45],
    'BAL-0084' => [11.2, 91.5, 14.5, 49],
    'BAL-0085' => [14.6, 101.6, 15, 49.6],
    'BAL-0086' => [10.2, 85.3, 14.8, 46],
    'BAL-0087' => [11.7, 87.7, 16.5, 48],
    'BAL-0088' => [28.3, 110.1, 25, 53],
    'BAL-0089' => [12.9, 93.2, 16, 48],
    'BAL-0090' => [10.6, 80.6, 16, 40],
    'BAL-0091' => [16.7, 98.7, 18.5, 50],
    'BAL-0092' => [7.9, 61.8, 20.2, 50],
    'BAL-0093' => [11.4, 92.2, 16, 47],
    'BAL-0094' => [11.6, 85.7, 14.6, 47.5],
    'BAL-0095' => [22.8, 110.5, 23, 51],
    'BAL-0096' => [26.2, 108.4, 23.5, 51.2],
    'BAL-0097' => [16.7, 98.8, 18, 50],
    'BAL-0098' => [13.5, 99.5, 15, 48.5],
    'BAL-0099' => [17, 108.4, 17, 50.2],
    'BAL-0100' => [17.3, 97, 19, 50],
    'BAL-0101' => [13.55, 91, 16, 52],
    'BAL-0102' => [32, 115.6, 25, 53]
];

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

$count = 0;
foreach ($data as $kode => $vals) {
    if (!isset($vals[0])) continue;
    $balita = Balita::where('kode_balita', $kode)->first();
    if(!$balita) continue;

    $bb = $vals[0];
    $tb = $vals[1];
    $lila = $vals[2];
    $lk = $vals[3];

    $diffInMonths = Carbon::parse($balita->tanggal_lahir)->diffInMonths(Carbon::parse($tgl_periksa));
    $usia = round($diffInMonths / 12, 1);
    if($usia <= 0) $usia = 0.1;

    $lila = $lila ?? 15;
    $lk = $lk ?? 48;

    $prediction = 'Rendah';
    if (!empty($trainData)) {
        $features = [$usia, $bb, $tb, $lila, $lk];
        $prediction = $knnService->predict($trainData, $features, 3);
    }

    Pemeriksaan::create([
        'kode_balita' => $kode,
        'id_kader' => $id_kader,
        'usia_saat_periksa' => $usia,
        'tanggal_pemeriksaan' => $tgl_periksa,
        'berat_badan' => $bb,
        'tinggi_badan' => $tb,
        'lingkar_lengan_atas' => $lila,
        'lingkar_kepala' => $lk,
        'status_stunting' => $prediction
    ]);
    
    // Sync to balita
    $balita->usia = $usia;
    $balita->save();

    $count++;
}

echo "Successfully injected $count REAL examinations based on provided logbook images!\n";
