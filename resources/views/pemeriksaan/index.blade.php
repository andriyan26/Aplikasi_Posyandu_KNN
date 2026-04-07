<x-app-layout>
    <x-slot name="header">
        DATA PEMERIKSAAN BALITA
    </x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-[24px] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden relative z-20 mb-10 transition-colors duration-300">

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 mx-8 mt-8 rounded">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-6 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-800/50 gap-4">
            <div>
                <h3 class="text-xl font-bold text-slate-700 dark:text-white">Riwayat Pemeriksaan</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Total: <b>{{ $pemeriksaans->total() }}</b> Pemeriksaan tercatat</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <form action="{{ route('pemeriksaan.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Show</label>
                        <select name="per_page" onchange="this.form.submit()" class="rounded-xl border-slate-200 dark:border-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 py-2 pl-3 pr-8 bg-white dark:bg-slate-900 dark:text-slate-300 shadow-sm transition-colors">
                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>

                    <div class="relative flex-1 md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama balita..." class="w-full rounded-xl border-slate-200 dark:border-slate-700 text-sm focus:ring-blue-500 focus:border-blue-500 py-2 pl-4 pr-10 bg-white dark:bg-slate-900 dark:text-slate-300 shadow-sm transition-colors">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>

                <a href="{{ route('pemeriksaan.create') }}" class="bg-[#6366f1] hover:bg-[#4f46e5] text-white text-sm font-bold py-1.5 px-5 rounded-full shadow-sm hover:shadow transition-all flex items-center gap-2 shrink-0 border border-indigo-600/10">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Catat Baru
                </a>
            </div>
        </div>

        <div class="overflow-x-auto p-4 sm:p-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider bg-slate-50 dark:bg-slate-900/50">
                        <th class="px-6 py-4 font-bold rounded-l-xl text-center w-16">No.</th>
                        <th class="px-6 py-4 font-bold text-center">Tanggal</th>
                        <th class="px-6 py-4 font-bold">Nama Balita</th>
                        <th class="px-6 py-4 font-bold text-center">Usia</th>
                        <th class="px-6 py-4 font-bold text-center">BB (kg)</th>
                        <th class="px-6 py-4 font-bold text-center">TB (cm)</th>
                        <th class="px-6 py-4 font-bold text-center">LiLA (cm)</th>
                        <th class="px-6 py-4 font-bold text-center">LiKep (cm)</th>
                        <th class="px-6 py-4 font-bold text-center">Risiko Stunting</th>
                        <th class="px-6 py-4 font-bold text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse($pemeriksaans as $p)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-150 border-b border-slate-50 dark:border-slate-700/30">
                        <td class="px-6 py-5 text-center font-bold text-slate-400 dark:text-slate-600">
                            {{ ($pemeriksaans->currentPage() - 1) * $pemeriksaans->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-5 text-center text-slate-700 dark:text-slate-400 font-medium">
                            {{ \Carbon\Carbon::parse($p->tanggal_pemeriksaan)->format('d F Y') }}
                        </td>
                        <td class="px-6 py-5">
                            <p class="font-bold text-slate-800 dark:text-white">{{ $p->balita->nama ?? '-' }}</p>
                            <p class="text-[10px] uppercase font-extrabold text-slate-400 dark:text-slate-500 tracking-widest">Orang Tua: {{ $p->balita->nama_orang_tua ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-5 text-center text-slate-700 dark:text-slate-400">{{ number_format((float)$p->usia_saat_periksa, 1, '.', '') }}</td>
                        <td class="px-6 py-5 text-center text-slate-700 dark:text-slate-400">{{ number_format((float)$p->berat_badan, 1, '.', '') }}</td>
                        <td class="px-6 py-5 text-center text-slate-700 dark:text-slate-400">{{ number_format((float)$p->tinggi_badan, 1, '.', '') }}</td>
                        <td class="px-6 py-5 text-center text-slate-700 dark:text-slate-400">{{ number_format((float)$p->lingkar_lengan_atas, 1, '.', '') }}</td>
                        <td class="px-6 py-5 text-center text-slate-700 dark:text-slate-400">{{ number_format((float)$p->lingkar_kepala, 1, '.', '') }}</td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusClasses = match(strtolower($p->status_stunting)) {
                                    'tinggi' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-100 dark:border-red-800',
                                    'normal' => 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border-green-100 dark:border-green-800',
                                    'stunted' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-800',
                                    'severely stunted' => 'bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-700',
                                    default => 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-100 dark:border-slate-700',
                                };
                            @endphp
                            <span class="{{ $statusClasses }} font-extrabold px-3 py-1 rounded-full text-[10px] uppercase tracking-widest border">
                                {{ $p->status_stunting }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center gap-2 flex-nowrap">
                                <a href="{{ route('pemeriksaan.show', $p->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-800/40 p-2 rounded-lg transition shadow-sm" title="Lihat Detail Pengukuran">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('pemeriksaan.pdf_single', $p->id) }}" target="_blank" class="text-rose-500 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/20 dark:hover:bg-rose-800/40 p-2 rounded-lg transition shadow-sm" title="Download Data by PDF">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-500 font-medium">
                            @if(request('search'))
                                Tidak ditemukan hasil untuk pencarian "{{ request('search') }}".
                            @else
                                Belum ada data riwayat pemeriksaan.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-6">
                @if(method_exists($pemeriksaans, 'links'))
                    {{ $pemeriksaans->links() }}
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
