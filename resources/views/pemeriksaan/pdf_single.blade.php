<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Pemeriksaan {{ $pemeriksaan->balita->nama ?? 'Balita' }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1e3a8a;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 14px;
        }
        .section-title {
            background-color: #f1f5f9;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            color: #334155;
            border-left: 4px solid #3b82f6;
            margin-bottom: 15px;
            margin-top: 30px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px 0;
            font-size: 13px;
        }
        .info-table td:nth-child(1) {
            width: 30%;
            font-weight: bold;
            color: #64748b;
        }
        .info-table td:nth-child(2) {
            width: 2%;
            color: #cbd5e1;
            text-align: center;
        }
        .info-table td:nth-child(3) {
            width: 68%;
            font-weight: bold;
            color: #1e293b;
        }
        
        .measurements-container {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .measurements-container td {
            text-align: center;
            padding: 15px 10px;
            border: 1px solid #e2e8f0;
            width: 25%;
        }
        .measure-label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .measure-value {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
        }
        .measure-unit {
            font-size: 12px;
            color: #94a3b8;
        }
        
        .result-box {
            margin-top: 40px;
            border: 2px dashed #cbd5e1;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
        }
        .result-title {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .result-value {
            font-size: 32px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-rendah { color: #16a34a; }
        .status-sedang { color: #d97706; }
        .status-tinggi { color: #dc2626; }
        
        .footer {
            margin-top: 60px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-date {
            margin-bottom: 15px;
            font-size: 12px;
            color: #334155;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #94a3b8;
            padding-top: 5px;
            font-size: 12px;
            font-weight: bold;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>POSYANDU BELIMBING</h1>
        <p>Laporan Resmi Hasil Pengukuran Antropometri Balita</p>
    </div>

    <!-- Data Identitas -->
    <div class="section-title">A. Identitas Balita</div>
    <table class="info-table">
        <tr>
            <td>Kode Balita</td>
            <td>:</td>
            <td>{{ $pemeriksaan->balita->kode_balita ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Balita</td>
            <td>:</td>
            <td style="font-size: 15px;">{{ $pemeriksaan->balita->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Orang Tua</td>
            <td>:</td>
            <td>{{ $pemeriksaan->balita->nama_orang_tua ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ ($pemeriksaan->balita->jenis_kelamin ?? 'L') == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
        </tr>
    </table>

    <!-- Data Pemeriksaan -->
    <div class="section-title">B. Informasi Pemeriksaan</div>
    <table class="info-table">
        <tr>
            <td>Tanggal Pemeriksaan</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Usia Saat Diperiksa</td>
            <td>:</td>
            <td>{{ number_format((float)$pemeriksaan->usia_saat_periksa, 1) }} Tahun</td>
        </tr>
        <tr>
            <td>Metode Diagnosa</td>
            <td>:</td>
            <td>Algoritma K-Nearest Neighbor (KNN) Multi-Variable</td>
        </tr>
    </table>

    <!-- Hasil Pengukuran Matriks -->
    <table class="measurements-container">
        <tr>
            <td>
                <span class="measure-label">Berat Badan</span>
                <span class="measure-value">{{ number_format((float)$pemeriksaan->berat_badan, 1) }}</span> <span class="measure-unit">kg</span>
            </td>
            <td>
                <span class="measure-label">Tinggi Badan</span>
                <span class="measure-value">{{ number_format((float)$pemeriksaan->tinggi_badan, 1) }}</span> <span class="measure-unit">cm</span>
            </td>
            <td>
                <span class="measure-label">Lingkar Lengan A.</span>
                <span class="measure-value">{{ number_format((float)$pemeriksaan->lingkar_lengan_atas, 1) }}</span> <span class="measure-unit">cm</span>
            </td>
            <td>
                <span class="measure-label">Lingkar Kepala</span>
                <span class="measure-value">{{ number_format((float)$pemeriksaan->lingkar_kepala, 1) }}</span> <span class="measure-unit">cm</span>
            </td>
        </tr>
    </table>

    @php
        $statusStr = strtolower($pemeriksaan->status_stunting);
        $statusClass = 'status-sedang';
        if(in_array($statusStr, ['rendah', 'normal'])) $statusClass = 'status-rendah';
        if(in_array($statusStr, ['tinggi', 'severely stunted'])) $statusClass = 'status-tinggi';
    @endphp

    <!-- Hasil Klasifikasi -->
    <div class="result-box">
        <div class="result-title">Kesimpulan Klasifikasi Risiko Stunting:</div>
        <div class="result-value {{ $statusClass }}">
            {{ $pemeriksaan->status_stunting ?? 'BELUM DIKETAHUI' }}
        </div>
    </div>

    <!-- Tanda Tangan -->
    <div class="footer">
        <div class="signature-box">
            <div class="signature-date">Posyandu Belimbing, {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->translatedFormat('d F Y') }}</div>
            
            <div style="height: 80px; margin-top: 10px; text-align: center;">
                @if($pemeriksaan->kader && $pemeriksaan->kader->barcode_ttd)
                    @php
                        $ttdPath = public_path('assets/Barcode TTD/' . $pemeriksaan->kader->barcode_ttd);
                        $ttdBase64 = '';
                        if (file_exists($ttdPath)) {
                            $ttdBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath));
                        }
                    @endphp
                    @if($ttdBase64)
                        <img src="{{ $ttdBase64 }}" height="70" alt="Barcode TTD" style="display:inline-block; max-width: 150px;">
                    @else
                        <span style="color:#aaa; font-size:10px;">(Tanda Tangan Belum Diatur)</span>
                    @endif
                @endif
            </div>

            <div class="signature-line" style="margin-top: 10px; text-transform: uppercase;">
                {{ $pemeriksaan->kader->nama ?? 'Petugas Kader' }}
            </div>
            <div style="font-size: 10px; color: #64748b; margin-top: 5px;">ID Kader: {{ $pemeriksaan->kader->id_kader ?? '-' }}</div>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
