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
        @if($kode_balita)
            @php $namaAnak = \App\Models\Balita::where('kode_balita', $kode_balita)->first()->nama ?? 'Anak'; @endphp
            <h1>LAPORAN PERKEMBANGAN BALITA: {{ strtoupper($namaAnak) }}</h1>
            <p>Periode: {{ $namaBulan }} - {{ $namaBulanAkhir }} Tahun {{ $tahun }}</p>
        @else
            <h1>LAPORAN BULANAN DATA PEMERIKSAAN POSYANDU</h1>
            <p>Bulan: {{ $namaBulan }} {{ $tahun }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                @if($kode_balita)
                    <th width="20%">Tanggal Periksa</th>
                    <th class="text-center" width="15%">Usia (Bulan)</th>
                @else
                    <th width="35%">Nama Balita</th>
                    <th width="15%">Status / Tgl</th>
                @endif
                <th class="text-center" width="15%">BB (kg)</th>
                <th class="text-center" width="15%">TB (cm)</th>
                <th class="text-center" width="15%">Risiko Stunting</th>
            </tr>
        </thead>
        <tbody>
            @if($kode_balita)
                @forelse($pemeriksaans as $p)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pemeriksaan)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ number_format((float)$p->usia_saat_periksa, 1) }}</td>
                    <td class="text-center">{{ number_format((float)$p->berat_badan, 1) }}</td>
                    <td class="text-center">{{ number_format((float)$p->tinggi_badan, 1) }}</td>
                    <td class="text-center"><strong>{{ $p->status_stunting }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pemeriksaan pada periode ini.</td>
                </tr>
                @endforelse
            @else
                @forelse($balitas as $balita)
                @php $p = $balita->pemeriksaans->first(); @endphp
                <tr style="{{ !$p ? 'background-color: #fff5f5; color: #888;' : '' }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><strong>{{ $balita->nama }}</strong></td>
                    <td>
                        @if($p)
                            {{ \Carbon\Carbon::parse($p->tanggal_pemeriksaan)->format('d/m/Y') }}
                        @else
                            <em>Tidak Diperiksa</em>
                        @endif
                    </td>
                    <td class="text-center">{{ $p ? number_format((float)$p->berat_badan, 1) : '-' }}</td>
                    <td class="text-center">{{ $p ? number_format((float)$p->tinggi_badan, 1) : '-' }}</td>
                    <td class="text-center"><strong>{{ $p->status_stunting ?? '-' }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada balita terdaftar.</td>
                </tr>
                @endforelse
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

</body>
</html>
