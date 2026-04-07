<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemeriksaan Posyandu</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; text-transform: uppercase; font-size: 11px; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN DATA PEMERIKSAAN POSYANDU</h1>
        <p>Bulan: {{ $namaBulan }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Nama Balita</th>
                <th class="text-center" width="10%">Usia (Th)</th>
                <th class="text-center" width="10%">BB (kg)</th>
                <th class="text-center" width="10%">TB (cm)</th>
                <th class="text-center" width="10%">LiLA (cm)</th>
                <th class="text-center" width="10%">LiKep (cm)</th>
                <th class="text-center" width="10%">Risiko</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemeriksaans as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_pemeriksaan)->format('d/m/Y') }}</td>
                <td>{{ $p->balita->nama ?? '-' }}</td>
                <td class="text-center">{{ number_format((float)$p->usia_saat_periksa, 1) }}</td>
                <td class="text-center">{{ number_format((float)$p->berat_badan, 1) }}</td>
                <td class="text-center">{{ number_format((float)$p->tinggi_badan, 1) }}</td>
                <td class="text-center">{{ number_format((float)$p->lingkar_lengan_atas, 1) }}</td>
                <td class="text-center">{{ number_format((float)$p->lingkar_kepala, 1) }}</td>
                <td class="text-center"><strong>{{ $p->status_stunting }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data pemeriksaan pada bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

</body>
</html>
