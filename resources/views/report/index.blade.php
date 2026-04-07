<x-app-layout>
    <x-slot name="header">
        REPORT DATA POSYANDU
    </x-slot>

    <div class="max-w-7xl mx-auto pb-12 px-4 sm:px-6 relative z-20">
        
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 mb-10">
            <h3 class="text-xl font-bold text-slate-700 dark:text-white mb-4">Filter Laporan Bulanan</h3>
            
            <form action="{{ route('report.index') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-end">
                <div class="lg:col-span-3">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Bulan</label>
                    <select name="bulan" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition cursor-pointer">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ request('bulan', date('m')) == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromFormat('m', $m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="lg:col-span-3">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tahun</label>
                    <select name="tahun" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition cursor-pointer">
                        @foreach(range(date('Y') - 5, date('Y') + 1) as $y)
                            <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-6 flex flex-col sm:flex-row items-center sm:justify-end gap-3 w-full">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-xl flex items-center justify-center gap-2 transition hover:-translate-y-0.5 hover:shadow-lg shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Filter
                    </button>
                    <a href="{{ route('report.pdf', request()->all()) }}" target="_blank" class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white font-bold py-3.5 px-8 rounded-xl flex items-center justify-center gap-2 transition hover:-translate-y-0.5 hover:shadow-lg shadow-sm shadow-red-200 dark:shadow-none">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Laporan PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700/50">
                <h3 class="text-xl font-bold text-slate-700 dark:text-white">
                    Laporan: {{ \Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F') }} {{ $tahun }}
                </h3>
                <p class="text-slate-500 mt-1">Ditemukan {{ $pemeriksaans->count() }} data pemeriksaan.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-black tracking-widest bg-slate-50/80 dark:bg-slate-900/50">
                            <th class="px-6 py-5 text-center rounded-tl-2xl">No</th>
                            <th class="px-6 py-5">Tanggal</th>
                            <th class="px-6 py-5">Nama Balita</th>
                            <th class="px-6 py-5 text-center">Usia</th>
                            <th class="px-6 py-5 text-center">BB (kg)</th>
                            <th class="px-6 py-5 text-center">TB (cm)</th>
                            <th class="px-6 py-5 text-center rounded-tr-2xl">Risiko Stunting</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse($pemeriksaans as $p)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-800/50 transition border-b border-slate-50 dark:border-slate-700/50">
                            <td class="px-6 py-5 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-5 text-slate-600 dark:text-slate-400 font-medium">{{ \Carbon\Carbon::parse($p->tanggal_pemeriksaan)->format('d F Y') }}</td>
                            <td class="px-6 py-5 font-black text-slate-800 dark:text-slate-200">{{ $p->balita->nama ?? '-' }}</td>
                            <td class="px-6 py-5 text-center font-bold text-slate-500 dark:text-slate-400">{{ number_format((float)$p->usia_saat_periksa, 1) }}</td>
                            <td class="px-6 py-5 text-center font-bold text-slate-500 dark:text-slate-400">{{ number_format((float)$p->berat_badan, 1) }}</td>
                            <td class="px-6 py-5 text-center font-bold text-slate-500 dark:text-slate-400">{{ number_format((float)$p->tinggi_badan, 1) }}</td>
                            <td class="px-6 py-5 text-center">
                                @php
                                    $statusClasses = match(strtolower($p->status_stunting)) {
                                        'tinggi' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800',
                                        'normal' => 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-800',
                                        'stunted' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800',
                                        'rendah' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
                                        'sedang' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800',
                                        default => 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700',
                                    };
                                @endphp
                                <span class="{{ $statusClasses }} font-black px-3.5 py-1.5 rounded-full text-[9px] uppercase tracking-widest shadow-sm">
                                    {{ $p->status_stunting }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">Tidak ada data pemeriksaan pada bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
