<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pemeriksaan.index') }}" class="text-slate-400 hover:text-white transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            DETAIL PENGUKURAN
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto pb-12 px-4 sm:px-6 relative z-20">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden relative">
            <!-- Header Section -->
            <div class="bg-blue-600 px-8 py-8 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs font-bold tracking-widest backdrop-blur-sm">
                                KODE: {{ $pemeriksaan->balita->kode ?? 'N/A' }}
                            </span>
                            <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs font-bold tracking-widest backdrop-blur-sm">
                                NIK: {{ $pemeriksaan->balita->nik ?? '-' }}
                            </span>
                        </div>
                        <h2 class="text-3xl font-black text-white tracking-tight">{{ $pemeriksaan->balita->nama ?? 'Nama Balita' }}</h2>
                        <p class="text-blue-100 mt-1 font-medium bg-white/10 inline-block px-3 py-0.5 rounded-lg border border-white/20 mt-3">Anak dari: <span class="font-bold text-white">{{ $pemeriksaan->balita->nama_orang_tua ?? '-' }}</span></p>
                    </div>
                    
                    <a href="{{ route('pemeriksaan.pdf_single', $pemeriksaan->id) }}" target="_blank" class="bg-white hover:bg-slate-50 text-blue-700 font-black py-3 px-6 rounded-xl flex items-center justify-center gap-2 transition hover:-translate-y-1 shadow-lg shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        CETAK LAPORAN PDF
                    </a>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Info Waktu -->
                    <div class="bg-slate-50 dark:bg-slate-900/30 rounded-2xl p-6 border border-slate-100 dark:border-slate-800">
                        <h4 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Informasi Waktu</h4>
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700/50">
                                <span class="text-slate-500 dark:text-slate-400 font-medium font-bold">Tgl. Pemeriksaan</span>
                                <span class="text-slate-800 dark:text-slate-200 font-black">{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700/50">
                                <span class="text-slate-500 dark:text-slate-400 font-medium font-bold">Usia Pemeriksaan</span>
                                <span class="text-slate-800 dark:text-slate-200 font-black">
                                    {{ number_format((float)$pemeriksaan->usia_saat_periksa, 1) }} <span class="text-sm font-bold text-slate-400">Tahun</span>
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500 dark:text-slate-400 font-medium font-bold">Metode Deteksi</span>
                                <span class="text-indigo-600 dark:text-indigo-400 font-black flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    AI KNN System
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Stunting -->
                    <div class="bg-slate-50 dark:bg-slate-900/30 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 flex flex-col justify-center items-center text-center">
                        <h4 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Kesimpulan Risiko Stunting</h4>
                        
                        @php
                            $status = strtolower($pemeriksaan->status_stunting);
                            $badge = match($status) {
                                'tinggi', 'severely stunted' => 'bg-red-100 text-red-700 border-red-200 shadow-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800',
                                'normal', 'rendah' => 'bg-green-100 text-green-700 border-green-200 shadow-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800',
                                'stunted', 'sedang' => 'bg-amber-100 text-amber-700 border-amber-200 shadow-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800',
                                default => 'bg-slate-100 text-slate-700 border-slate-200 shadow-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                            };
                            $icon = match($status) {
                                'tinggi', 'severely stunted' => '<svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                                'normal', 'rendah' => '<svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                                'stunted', 'sedang' => '<svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                                default => '<svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            };
                        @endphp
                        
                        <div class="{{ $badge }} flex flex-col items-center justify-center p-6 rounded-2xl w-full border-2 shadow-sm">
                            {!! $icon !!}
                            <h3 class="text-2xl font-black uppercase tracking-widest">{{ $pemeriksaan->status_stunting ?? 'BELUM ADA' }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Grid Matriks Antropometri -->
                <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800">
                    <h4 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-6 text-center">Matriks Pengukuran Antropometri</h4>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Berat Badan -->
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-5 text-center border border-indigo-100 dark:border-indigo-800/30">
                            <div class="w-10 h-10 bg-indigo-200 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            </div>
                            <h5 class="text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider mb-1">Berat Badan</h5>
                            <div class="text-2xl font-black text-indigo-700 dark:text-indigo-400">
                                {{ number_format((float)$pemeriksaan->berat_badan, 1) }} <span class="text-sm">kg</span>
                            </div>
                        </div>

                        <!-- Tinggi Badan -->
                        <div class="bg-cyan-50 dark:bg-cyan-900/20 rounded-2xl p-5 text-center border border-cyan-100 dark:border-cyan-800/30">
                            <div class="w-10 h-10 bg-cyan-200 dark:bg-cyan-800 text-cyan-700 dark:text-cyan-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                            </div>
                            <h5 class="text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider mb-1">Tinggi Badan</h5>
                            <div class="text-2xl font-black text-cyan-700 dark:text-cyan-400">
                                {{ number_format((float)$pemeriksaan->tinggi_badan, 1) }} <span class="text-sm">cm</span>
                            </div>
                        </div>

                        <!-- Lingkar Lengan Atas -->
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-5 text-center border border-purple-100 dark:border-purple-800/30">
                            <div class="w-10 h-10 bg-purple-200 dark:bg-purple-800 text-purple-700 dark:text-purple-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="font-black">LA</span>
                            </div>
                            <h5 class="text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider mb-1">LiLA</h5>
                            <div class="text-2xl font-black text-purple-700 dark:text-purple-400">
                                {{ number_format((float)$pemeriksaan->lingkar_lengan_atas, 1) }} <span class="text-sm">cm</span>
                            </div>
                        </div>

                        <!-- Lingkar Kepala -->
                        <div class="bg-rose-50 dark:bg-rose-900/20 rounded-2xl p-5 text-center border border-rose-100 dark:border-rose-800/30">
                            <div class="w-10 h-10 bg-rose-200 dark:bg-rose-800 text-rose-700 dark:text-rose-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="font-black">LK</span>
                            </div>
                            <h5 class="text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider mb-1">Lingkar Kepala</h5>
                            <div class="text-2xl font-black text-rose-700 dark:text-rose-400">
                                {{ number_format((float)$pemeriksaan->lingkar_kepala, 1) }} <span class="text-sm">cm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
