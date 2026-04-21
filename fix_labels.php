<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataLatih;
use Illuminate\Support\Facades\DB;

$files = glob(public_path('dataset_*.csv'));

DB::table('data_latih')->truncate();

$totalRendah = 0;
$totalSedang = 0;
$totalTinggi = 0;

foreach ($files as $file) {
    echo "Processing $file\n";
    $rows = array_map('str_getcsv', file($file));
    $header = array_shift($rows);
    
    $newRows = [];
    $newRows[] = '"' . implode('","', $header) . '"';

    foreach ($rows as $row) {
        if(count($row) < 8) continue;
        
        $nama = $row[0];
        $jk = $row[1];
        $usia = (float)$row[2];
        $bb = (float)$row[3];
        $tb = (float)$row[4];
        $lila = (float)$row[5];
        $lk = (float)$row[6];
        
        if ($usia <= 1.0) {
            $expected = 50 + ($usia * 25);
        } else if ($usia <= 3.5) {
            $expected = 75 + (($usia - 1) * 10);
        } else {
            $expected = 100 + (($usia - 3.5) * 6);
        }

        if ($jk == 'L') {
            $expected += 1.5;
        }

        $diff = $expected - $tb;
        $expected_bb = ($usia * 2) + 8;
        if ($usia < 1.0) $expected_bb = ($usia * 12) * 0.5 + 4; 
        
        $diff_bb = $expected_bb - $bb;

        if ($diff > 7.5 || ($diff > 5 && $diff_bb > 2)) {
            $status = 'Tinggi';
            $totalTinggi++;
        } else if ($diff > 3.0 || ($diff > 0 && $diff_bb > 1.5)) {
            $status = 'Sedang';
            $totalSedang++;
        } else {
            $status = 'Rendah';
            $totalRendah++;
        }
        
        if($usia < 0.2) $status = 'Rendah';
        
        $row[7] = $status;
        $newRows[] = '"' . implode('","', $row) . '"';

        DataLatih::create([
            'nama' => $nama,
            'jenis_kelamin' => $jk,
            'usia' => $usia,
            'berat_badan' => $bb,
            'tinggi_badan' => $tb,
            'lingkar_lengan_atas' => $lila,
            'lingkar_kepala' => $lk,
            'status_stunting' => $row[7],
        ]);
    }
    
    file_put_contents($file, implode("\n", $newRows) . "\n");
}

echo "\n--- HASIL RELABELING KESELURUHAN ---\n";
echo "Rendah: $totalRendah\n";
echo "Sedang: $totalSedang\n";
echo "Tinggi: $totalTinggi\n";
echo "------------------------------------\n";

?>
