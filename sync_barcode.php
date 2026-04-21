<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kader;

$mapping = [
    'Saamah' => 'TTD SAYA_Saamah.png',
    'Hamidah' => 'TTD SAYA_Hamidah.png',
    'IImaah' => 'TTD SAYA_Ilmaah.png',
    'Nurul Parohah' => 'TTD SAYA NURUL PAROHAH.png',
    'Badriah' => 'TTD SAYA BADRIAH.png',
    'Kokom Komariyah' => 'TTD SAYA KOKOM KOMARIYAH.png',
    'Lia Astuti' => 'TTD SAYA LIA ASTUTI.png',
    'Lutfih' => 'TTD SAYA LUTFIAH.png',
    'Liiyaa' => 'TTD SAYA LIYAA.png',
    'Mutia Sari' => 'TTD SAYA  MUTIA SARI.png', // Note the extra space
    'Murniati' => 'TTD SAYA MURNIATI.png',
    'Nurma Riaema' => 'TTD SAYA NURMA RIAESMA.png',
    'Sumiyati' => 'TTD SAYA SUMIATI.png',
    'Sari Octavia' => 'TTD SAYA SARI OCTAVIA.png',
    'Saroyah' => 'TTD SAYA SAROYAH.png'
];

foreach(Kader::all() as $kader) {
    if (isset($mapping[$kader->nama])) {
        $kader->barcode_ttd = $mapping[$kader->nama];
        $kader->save();
        echo "Updated " . $kader->nama . " -> " . $kader->barcode_ttd . "\n";
    } else {
        echo "NOT FOUND: " . $kader->nama . "\n";
    }
}
