<x-app-layout>
    <x-slot name="header">
        REPORT DATA POSYANDU
    </x-slot>

    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="max-w-7xl mx-auto pb-12 px-4 sm:px-6 relative z-20">
        
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 mb-10">
            <h3 class="text-xl font-bold text-slate-700 dark:text-white mb-4">Filter Laporan Bulanan</h3>
            
            <form action="{{ route('report.index') }}" method="GET" class="space-y-5" id="formReport">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <!-- Balita -->
                    <div>
                         <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pilih Balita (Cari Nama/Kode)</label>
                         <select name="kode_balita" id="kode_balita" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition cursor-pointer" onchange="toggleBulanAkhir()">
                             <option value="">-- Semua Balita / Laporan Bulanan --</option>
                             @foreach($semua_balita as $b)
                                 <option value="{{ $b->kode_balita }}" {{ request('kode_balita') == $b->kode_balita ? 'selected' : '' }}>[{{ $b->kode_balita }}] {{ $b->nama }}</option>
                             @endforeach
                         </select>
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tahun</label>
                        <select name="tahun" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition cursor-pointer">
                            @foreach(range(date('Y') - 5, date('Y') + 1) as $y)
                                <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bulan Awal -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2" id="label-bulan">Bulan Pemeriksaan</label>
                        <select name="bulan" id="bulan" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition cursor-pointer">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ request('bulan', date('m')) == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('m', $m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Bulan Akhir -->
                    <div id="wrapper-bulan-akhir" class="{{ request('kode_balita') ? '' : 'hidden' }}">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Sampai Bulan</label>
                        <select name="bulan_akhir" id="bulan_akhir" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition cursor-pointer">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ request('bulan_akhir', date('m')) == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('m', $m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:justify-end gap-3 w-full border-t border-slate-100 dark:border-slate-700 pt-5 mt-2">
                    <button type="submit" id="btn-terapkan" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-xl flex items-center justify-center gap-2 transition hover:-translate-y-0.5 hover:shadow-lg shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('report.pdf', request()->all()) }}" target="_blank" id="btn-download-pdf" class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white font-bold py-3.5 px-8 rounded-xl flex items-center justify-center gap-2 transition hover:-translate-y-0.5 hover:shadow-lg shadow-sm shadow-red-200 dark:shadow-none">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Download Laporan PDF</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700/50">
                <h3 class="text-xl font-bold text-slate-700 dark:text-white">
                    @if($kode_balita)
                        Laporan Perkembangan: <span class="text-blue-600 dark:text-blue-400">{{ $semua_balita->firstWhere('kode_balita', $kode_balita)->nama ?? '' }}</span>
                        <span class="block text-sm font-normal text-slate-500 mt-1">Periode: {{ \Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F') }} - {{ \Carbon\Carbon::createFromFormat('m', $bulan_akhir)->translatedFormat('F') }} {{ $tahun }}</span>
                    @else
                        Laporan Bulanan: <span class="text-blue-600 dark:text-blue-400">{{ \Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F') }} {{ $tahun }}</span>
                        <span class="block text-sm font-normal text-slate-500 mt-1">Total: {{ $balitas->count() }} Balita Terdaftar</span>
                    @endif
                </h3>
            </div>
            <div class="overflow-x-auto pb-4">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-black tracking-widest bg-slate-50/80 dark:bg-slate-900/50">
                            <th class="px-6 py-5 text-center rounded-tl-2xl w-16">No</th>
                            @if($kode_balita)
                                <th class="px-6 py-5">Tanggal Periksa</th>
                                <th class="px-6 py-5 text-center">Usia Periksa</th>
                            @else
                                <th class="px-6 py-5 whitespace-nowrap">Nama Balita</th>
                                <th class="px-6 py-5">Status Periksa</th>
                            @endif
                            <th class="px-6 py-5 text-center">BB (kg)</th>
                            <th class="px-6 py-5 text-center">TB (cm)</th>
                            <th class="px-6 py-5 text-center rounded-tr-2xl">Risiko Stunting</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @if($kode_balita)
                            {{-- Mode Laporan Anak --}}
                            @forelse($pemeriksaans as $p)
                                <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-800/50 transition border-b border-slate-50 dark:border-slate-700/50">
                                    <td class="px-6 py-5 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-5 text-slate-600 dark:text-slate-400 font-medium">{{ \Carbon\Carbon::parse($p->tanggal_pemeriksaan)->format('d F Y') }}</td>
                                    <td class="px-6 py-5 text-center font-bold text-slate-500 dark:text-slate-400">{{ number_format((float)$p->usia_saat_periksa, 1) }} Bulan</td>
                                    <td class="px-6 py-5 text-center font-bold text-slate-600 dark:text-slate-300">{{ number_format((float)$p->berat_badan, 1) }}</td>
                                    <td class="px-6 py-5 text-center font-bold text-slate-600 dark:text-slate-300">{{ number_format((float)$p->tinggi_badan, 1) }}</td>
                                    <td class="px-6 py-5 text-center">
                                        @php
                                            $statusClasses = match(strtolower($p->status_stunting ?? '')) {
                                                'tinggi' => 'bg-red-50 text-red-600 border border-red-200',
                                                'normal' => 'bg-green-50 text-green-600 border border-green-200',
                                                'stunted' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                                'rendah' => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                                default => 'bg-slate-50 text-slate-600 border border-slate-200',
                                            };
                                        @endphp
                                        <span class="{{ $statusClasses }} font-black px-3.5 py-1.5 rounded-full text-[9px] uppercase tracking-widest">{{ $p->status_stunting ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">Tidak ada data pemeriksaan pada periode ini.</td></tr>
                            @endforelse
                        @else
                            {{-- Mode Laporan Bulanan --}}
                            @forelse($balitas as $balita)
                                @php $p = $balita->pemeriksaans->first(); @endphp
                                <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-800/50 transition border-b border-slate-50 dark:border-slate-700/50 {{ !$p ? 'opacity-70 bg-red-50/10' : '' }}">
                                    <td class="px-6 py-5 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-5 font-black text-slate-800 dark:text-slate-200">{{ $balita->nama }}</td>
                                    <td class="px-6 py-5 text-slate-600 dark:text-slate-400 font-medium">
                                        @if($p)
                                            {{ \Carbon\Carbon::parse($p->tanggal_pemeriksaan)->format('d F Y') }}
                                        @else
                                            <span class="text-red-400 italic text-sm">Tidak Diperiksa</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center font-bold text-slate-600 dark:text-slate-300">{{ $p ? number_format((float)$p->berat_badan, 1) : '-' }}</td>
                                    <td class="px-6 py-5 text-center font-bold text-slate-600 dark:text-slate-300">{{ $p ? number_format((float)$p->tinggi_badan, 1) : '-' }}</td>
                                    <td class="px-6 py-5 text-center">
                                        @if($p)
                                            @php
                                                $s = strtolower($p->status_stunting ?? '');
                                                $statusClasses = match($s) {
                                                    'tinggi' => 'bg-red-50 text-red-600 border border-red-200',
                                                    'normal' => 'bg-green-50 text-green-600 border border-green-200',
                                                    'stunted' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                                    default => 'bg-slate-50 text-slate-600 border border-slate-200',
                                                };
                                            @endphp
                                            <span class="{{ $statusClasses }} font-black px-3.5 py-1.5 rounded-full text-[9px] uppercase tracking-widest">{{ $p->status_stunting }}</span>
                                        @else
                                            <span class="text-slate-400 text-xl font-black">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada balita terdaftar.</td></tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function toggleBulanAkhir() {
                var balitaSelect = document.getElementById('kode_balita');
                var wrapperAkhir = document.getElementById('wrapper-bulan-akhir');
                var labelBulan = document.getElementById('label-bulan');
                
                if (balitaSelect.value !== '') {
                    wrapperAkhir.classList.remove('hidden');
                    labelBulan.innerText = 'Mulai Bulan';
                } else {
                    wrapperAkhir.classList.add('hidden');
                    labelBulan.innerText = 'Bulan Pemeriksaan';
                }
            }
            
            // Initial call to set correct state
            document.addEventListener("DOMContentLoaded", toggleBulanAkhir);

            $(document).ready(function() {
                // Inisialisasi Select2 untuk Pencarian Balita
                $('#kode_balita').select2({
                    placeholder: "-- Semua Balita / Laporan Bulanan --",
                    allowClear: true,
                    width: '100%'
                });

                // Perbarui toggle saat Select2 berubah
                $('#kode_balita').on('change', function() {
                    toggleBulanAkhir();
                });

                // Logika mematikan tombol Download sebelum Terapkan Filter
                const originalHref = $('#btn-download-pdf').attr('href');
                let isModified = false;

                function disableDownload() {
                    if(!isModified) {
                        isModified = true;
                        $('#btn-download-pdf')
                            .attr('href', 'javascript:void(0)')
                            .removeAttr('target')
                            .addClass('opacity-50 cursor-not-allowed pointer-events-none bg-slate-400')
                            .removeClass('bg-red-500 hover:bg-red-600 hover:-translate-y-0.5 hover:shadow-lg shadow-red-200');
                        
                        $('#btn-download-pdf span').text('Harap Terapkan Filter');
                        
                        // Buat tombol Terapkan lebih menonjol
                        $('#btn-terapkan').addClass('animate-pulse shadow-blue-500/50');
                    }
                }

                $('#formReport select').on('change', function() {
                    disableDownload();
                });
            });
        </script>

    </div>
</x-app-layout>
