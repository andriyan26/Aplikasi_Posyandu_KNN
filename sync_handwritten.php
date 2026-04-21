<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Balita;

$data = [
    'BAL-0001' => ['nama' => 'Mauzila Saquna Zainuri', 'tgl' => '2023-01-17', 'ortu' => 'Hilman'],
    'BAL-0002' => ['nama' => 'Hisyam', 'tgl' => '2023-05-21', 'ortu' => 'Harun/Eka'],
    'BAL-0003' => ['nama' => 'El Zayyan Adelino', 'tgl' => '2024-04-06', 'ortu' => 'Yoga/Vina'],
    'BAL-0004' => ['nama' => 'Anisa Putri Ramadani', 'tgl' => '2020-04-17', 'ortu' => 'Warni/Sahroni'],
    'BAL-0005' => ['nama' => 'Muhammad Rayanza', 'tgl' => '2025-06-05', 'ortu' => 'Mutmainah/Eko Susanto'],
    'BAL-0006' => ['nama' => 'Queenza', 'tgl' => '2025-03-12', 'ortu' => 'Lestari'],
    'BAL-0007' => ['nama' => 'Maheera Clarissa', 'tgl' => '2023-09-09', 'ortu' => 'Dimas/Nurjanah'],
    'BAL-0008' => ['nama' => 'Bita A', 'tgl' => '2024-05-23', 'ortu' => 'Sumaryono/Adminingsih'],
    'BAL-0009' => ['nama' => 'Qian', 'tgl' => '2023-05-08', 'ortu' => 'Siti/Hery'],
    'BAL-0010' => ['nama' => 'Nagiska Wirani', 'tgl' => '2021-02-09', 'ortu' => 'Rinto/Nani'],
    'BAL-0011' => ['nama' => 'Zevanya Putri Susanto', 'tgl' => '2024-10-13', 'ortu' => 'Winarti/Rico'],
    'BAL-0012' => ['nama' => 'Natta', 'tgl' => '2024-01-10', 'ortu' => 'Eva/Malik'],
    'BAL-0013' => ['nama' => 'Razka', 'tgl' => '2021-06-04', 'ortu' => 'Sopiah/Mira'],
    'BAL-0014' => ['nama' => 'Senandung Qurota Ayun', 'tgl' => '2025-02-24', 'ortu' => 'Rusdi/Ikah'],
    'BAL-0015' => ['nama' => 'Ahmad Zuhairi Rabbani', 'tgl' => '2025-05-16', 'ortu' => 'Eni/Rohman'],
    'BAL-0016' => ['nama' => 'Ahmad Al-Biruni Rajab', 'tgl' => '2024-01-27', 'ortu' => 'Pipit/Solehuddin'],
    'BAL-0017' => ['nama' => 'Sabrina Mayasa', 'tgl' => '2024-04-27', 'ortu' => 'Suyitno/Heni'],
    'BAL-0018' => ['nama' => 'Ghania Anggun Nisa', 'tgl' => '2025-01-23', 'ortu' => 'Yandi/Yuliadi'],
    'BAL-0019' => ['nama' => 'Ahmad Zuhairi Rahmi', 'tgl' => '2025-05-16', 'ortu' => 'Eni/Rohman'],
    'BAL-0020' => ['nama' => 'Hasna', 'tgl' => '2021-08-10', 'ortu' => 'Santih/Nur'],
    'BAL-0021' => ['nama' => 'Nur Muhammad Alfatih', 'tgl' => '2023-12-06', 'ortu' => 'Arfani/Farhan'],
    'BAL-0022' => ['nama' => 'Kairi', 'tgl' => '2022-12-13', 'ortu' => 'Alin/Riyan'],
    'BAL-0023' => ['nama' => 'Aulia Ryra Azahra', 'tgl' => '2024-12-02', 'ortu' => 'Deni/Ari'],
    'BAL-0024' => ['nama' => 'M. Farhan', 'tgl' => '2023-10-26', 'ortu' => 'Deni/Ari'],
    'BAL-0025' => ['nama' => 'Mahira', 'tgl' => '2025-01-01', 'ortu' => 'Dimas/Nurjanah'],
    'BAL-0026' => ['nama' => 'Emil Arsya Muhamad', 'tgl' => '2023-02-15', 'ortu' => 'Bahrul Ulum/Melisa'],
    'BAL-0027' => ['nama' => 'Abraham', 'tgl' => '2025-06-13', 'ortu' => 'M. Farhan'],
    'BAL-0028' => ['nama' => 'Gheanor', 'tgl' => '2022-08-25', 'ortu' => 'Viandri/Mardo'],
    'BAL-0029' => ['nama' => 'Habibi', 'tgl' => '2024-11-19', 'ortu' => 'Sri Lestari'],
    'BAL-0030' => ['nama' => 'Bagas', 'tgl' => '2024-08-09', 'ortu' => 'Triyatmi/Triyanto'],
    'BAL-0031' => ['nama' => 'Alesa Dewi Andini', 'tgl' => '2024-08-24', 'ortu' => 'Dina/Hendra'],
    'BAL-0032' => ['nama' => 'Arshaka A. Suheli', 'tgl' => '2025-02-14', 'ortu' => 'Sri/Ade'],
    'BAL-0033' => ['nama' => 'Sarah Azizah Rizqiana', 'tgl' => '2023-08-26', 'ortu' => 'Siti Aisyah/Feri'],
    'BAL-0034' => ['nama' => 'Zinnia Kyla Akbar', 'tgl' => '2023-12-21', 'ortu' => 'Gt Mutia/Erfiansyah'],
    'BAL-0035' => ['nama' => 'Althaf Er Earaka', 'tgl' => '2023-12-18', 'ortu' => 'Wulan/Suryanto'],
    'BAL-0036' => ['nama' => 'Annisa Putri Ramadhan', 'tgl' => '2020-04-17', 'ortu' => 'Warni/Sahroni'],
    'BAL-0037' => ['nama' => 'M. Alizam', 'tgl' => '2022-07-08', 'ortu' => 'Samin/Linda'],
    'BAL-0038' => ['nama' => 'Ryuji Sanshaka Shuma', 'tgl' => '2024-09-30', 'ortu' => 'Yurie/Ihsan'],
    'BAL-0039' => ['nama' => 'Arumi', 'tgl' => '2024-05-10', 'ortu' => 'Dian/Eka'],

    'BAL-0040' => ['nama' => 'Shafano', 'tgl' => '2025-03-25', 'ortu' => 'Bayu'],
    'BAL-0041' => ['nama' => 'Arkila', 'tgl' => '2021-01-01', 'ortu' => 'A. Fahmi Medris'],
    'BAL-0042' => ['nama' => 'Atto', 'tgl' => '2022-03-08', 'ortu' => 'Hamidah'],
    'BAL-0043' => ['nama' => 'Ayesha Haura Putri', 'tgl' => '2022-09-06', 'ortu' => 'Sopiah/Roni'],
    'BAL-0044' => ['nama' => 'Kanaana Fatima Adrista', 'tgl' => '2025-04-04', 'ortu' => 'Yanti/Pebri'],
    'BAL-0045' => ['nama' => 'Kanaya', 'tgl' => '2024-11-04', 'ortu' => 'Fatimah/Hendra'],
    'BAL-0046' => ['nama' => 'Keisha Zoya Hanasta', 'tgl' => '2023-06-18', 'ortu' => 'Lenny M/Tedian'],
    'BAL-0047' => ['nama' => 'Naraya', 'tgl' => '2024-06-03', 'ortu' => 'Fitri/Anang'],
    'BAL-0048' => ['nama' => 'Adiba', 'tgl' => '2021-05-01', 'ortu' => 'Nurul/Rusdianto'],
    'BAL-0049' => ['nama' => 'Fawaz Zeyyan', 'tgl' => '2022-02-02', 'ortu' => 'Vina/Sugeng'],
    'BAL-0050' => ['nama' => 'Neyan Elisya', 'tgl' => '2024-10-18', 'ortu' => 'Sania/Handi S'],
    'BAL-0051' => ['nama' => 'M. Syafiq', 'tgl' => '2021-02-22', 'ortu' => 'M. Urkati'],
    'BAL-0052' => ['nama' => 'Ananda Sauqi Al Hafidz', 'tgl' => '2022-06-15', 'ortu' => 'Novi/Agung'],
    'BAL-0053' => ['nama' => 'Alesha', 'tgl' => '2022-05-31', 'ortu' => 'Santi/Komarudin'],
    'BAL-0054' => ['nama' => 'Senja Putri R', 'tgl' => '2021-10-06', 'ortu' => 'Anisa/Hamzah'],
    'BAL-0055' => ['nama' => 'Aletta', 'tgl' => '2024-09-17', 'ortu' => 'Kiki/Cecep'],
    'BAL-0056' => ['nama' => 'M. Al Ghava', 'tgl' => '2022-06-30', 'ortu' => 'Fajar Firmansyah/Amira'],
    'BAL-0057' => ['nama' => 'Novan Rafif Setiawan', 'tgl' => '2020-11-24', 'ortu' => 'Dasimah/Iwan'],
    'BAL-0058' => ['nama' => 'Ly. Rahmawati', 'tgl' => '2023-06-13', 'ortu' => 'Mursyifallah'],
    'BAL-0059' => ['nama' => 'M. Alya Rendra', 'tgl' => '2021-12-10', 'ortu' => 'Anisa/M. Soekarno'],
    'BAL-0060' => ['nama' => 'Aksa Fatih Alhidrus', 'tgl' => '2021-06-30', 'ortu' => 'Nurlaila Hidayat'],
    'BAL-0061' => ['nama' => 'Syakila Humaira', 'tgl' => '2023-01-02', 'ortu' => 'Yuliana/Agus Salim'],
    'BAL-0062' => ['nama' => 'Khalisa', 'tgl' => '2025-06-18', 'ortu' => 'Nurhainah/Hamid'],
    'BAL-0063' => ['nama' => 'Kailas', 'tgl' => '2023-01-01', 'ortu' => 'Sukria/Imah'],
    'BAL-0064' => ['nama' => 'Kenan Sabda Ramadhan', 'tgl' => '2023-04-11', 'ortu' => 'Fatmalia/Muamari'],
    'BAL-0065' => ['nama' => 'Davandra', 'tgl' => '2022-09-09', 'ortu' => 'Viana/Agung'],
    'BAL-0066' => ['nama' => 'M. Haidar Rizki', 'tgl' => '2022-08-09', 'ortu' => 'Ahmad Mustopa'],
    'BAL-0067' => ['nama' => 'Aqila Putri Nadhifa', 'tgl' => '2021-02-16', 'ortu' => 'Widya/Muhayar'],
    'BAL-0068' => ['nama' => 'M. Fakih Rizqullah', 'tgl' => '2024-11-01', 'ortu' => 'Shaiha/M. Jaefar'],
    'BAL-0069' => ['nama' => 'Eninuha Kamaniyah', 'tgl' => '2024-09-12', 'ortu' => 'Nurul/Andrianto'],
    'BAL-0070' => ['nama' => 'Syavia', 'tgl' => '2021-09-22', 'ortu' => 'Nadya/Arif'],
    'BAL-0071' => ['nama' => 'Alesha Zahra', 'tgl' => '2022-11-21', 'ortu' => 'Putri/Ramdhan'],
    'BAL-0072' => ['nama' => 'M. Pajar El-Malia', 'tgl' => '2021-10-22', 'ortu' => 'Marselino'],
    'BAL-0073' => ['nama' => 'M. Bara', 'tgl' => '2021-10-23', 'ortu' => 'Susan/Hendri'],
    'BAL-0074' => ['nama' => 'Abian Raffa', 'tgl' => '2022-01-13', 'ortu' => 'Rohati/Narkholis'],
    'BAL-0075' => ['nama' => 'Alesha', 'tgl' => '2025-09-09', 'ortu' => 'Lulu/Kardiman'],
    'BAL-0076' => ['nama' => 'M. Kafa Al Farizqi', 'tgl' => '2022-08-02', 'ortu' => 'Riyani/Fandi'],
    'BAL-0077' => ['nama' => 'Balqis', 'tgl' => '2022-10-13', 'ortu' => 'Deni Chandra'],
    'BAL-0078' => ['nama' => 'Raden Djayadi Wangsa', 'tgl' => '2022-01-01', 'ortu' => 'Orang Tua'],
    'BAL-0079' => ['nama' => 'Zaim Ahmad', 'tgl' => '2021-06-15', 'ortu' => 'Vina'],

    'BAL-0080' => ['nama' => 'Zaim Ahmad', 'tgl' => '2021-06-15', 'ortu' => 'Vina'],
    'BAL-0081' => ['nama' => 'Abizar Hafiz M', 'tgl' => '2023-04-27', 'ortu' => 'Alvina/Ilham'],
    'BAL-0082' => ['nama' => 'Arsya', 'tgl' => '2020-09-24', 'ortu' => 'Amsiah/Anwar'],
    'BAL-0083' => ['nama' => 'Rayyanza Alfarizi', 'tgl' => '2024-04-28', 'ortu' => 'Wulan/Yusup'],
    'BAL-0084' => ['nama' => 'Zia', 'tgl' => '2022-04-10', 'ortu' => 'Yuri/Ihsan'],
    'BAL-0085' => ['nama' => 'M. Latif', 'tgl' => '2021-10-26', 'ortu' => 'Orang Tua'],
    'BAL-0086' => ['nama' => 'Aqin Arumili Huby', 'tgl' => '2022-10-25', 'ortu' => 'Eka Saputri'],
    'BAL-0087' => ['nama' => 'M. Haizan Ramadhan', 'tgl' => '2023-03-08', 'ortu' => 'Farida/Apidun'],
    'BAL-0088' => ['nama' => 'Nalendra', 'tgl' => '2020-11-20', 'ortu' => 'Hilmawati'],
    'BAL-0089' => ['nama' => 'Arzanka', 'tgl' => '2022-08-18', 'ortu' => 'Widya/Rifan'],
    'BAL-0090' => ['nama' => 'Umaiza Khadijah', 'tgl' => '2023-06-26', 'ortu' => 'Maulida/Nanang'],
    'BAL-0091' => ['nama' => 'Zubair Muhamad', 'tgl' => '2023-09-23', 'ortu' => 'Orang Tua'],
    'BAL-0092' => ['nama' => 'Elziyan Rohim', 'tgl' => '2021-08-16', 'ortu' => 'Rohim'],
    'BAL-0093' => ['nama' => 'Askara', 'tgl' => '2022-04-03', 'ortu' => 'Mardiana/Kadir'],
    'BAL-0094' => ['nama' => 'Arsaka', 'tgl' => '2025-09-03', 'ortu' => 'Arin/Asep'],
    'BAL-0095' => ['nama' => 'M. Ihsan', 'tgl' => '2021-01-21', 'ortu' => 'Budi'],
    'BAL-0096' => ['nama' => 'M. Irfan', 'tgl' => '2021-01-04', 'ortu' => 'Orang Tua'],
    'BAL-0097' => ['nama' => 'Kelana', 'tgl' => '2022-01-01', 'ortu' => 'Rina/Yusa'],
    'BAL-0098' => ['nama' => 'Nuril Isa', 'tgl' => '2021-07-10', 'ortu' => 'Pupus Pajar'],
    'BAL-0099' => ['nama' => 'M. Aqlan A', 'tgl' => '2021-04-08', 'ortu' => 'Ovi/Hardiansyah'],
    'BAL-0100' => ['nama' => 'Zelyin', 'tgl' => '2022-05-30', 'ortu' => 'Sella/Alvi'],
    'BAL-0101' => ['nama' => 'Arsyad', 'tgl' => '2022-09-23', 'ortu' => 'Nandan'],
    'BAL-0102' => ['nama' => 'AlfaRezel', 'tgl' => '2020-12-23', 'ortu' => 'Sella/Alvi'],
];

$count = 0;
foreach ($data as $kode => $d) {
    $balita = Balita::where('kode_balita', $kode)->first();
    if ($balita) {
        $balita->nama = $d['nama'];
        if ($d['tgl'] != '2021-01-01') {
            $balita->tanggal_lahir = $d['tgl'];
        }
        if ($d['ortu'] != 'Orang Tua') {
            $balita->nama_orang_tua = $d['ortu'];
        }
        $balita->save();
        $count++;
    }
}

echo "Successfully updated $count records with transcribed data!\n";
