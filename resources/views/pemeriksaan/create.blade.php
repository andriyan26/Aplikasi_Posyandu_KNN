<x-app-layout>
    <x-slot name="header">
        CATAT PEMERIKSAAN BARU
    </x-slot>

    <div class="max-w-3xl relative z-20">
        <div class="bg-white dark:bg-slate-800 rounded-[20px] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden mb-10 transition-colors">
            
            <!-- Card Header -->
            <div class="bg-[#6366f1] px-8 py-5 flex items-center gap-3">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <div>
                    <h3 class="text-lg font-bold text-white">Formulir Pemeriksaan Balita</h3>
                    <p class="text-indigo-200 text-sm">Data akan diklasifikasi otomatis menggunakan Algoritma KNN</p>
                </div>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mx-8 mt-6 rounded">
                    <ul class="text-red-700 text-sm list-disc pl-4 font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pemeriksaan.store') }}" method="POST" class="px-8 py-8 space-y-8">
                @csrf

                <!-- Section 1: Data Balita -->
                <div>
                    <h4 class="font-bold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2 uppercase text-xs tracking-widest">
                        <span class="h-5 w-5 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[10px] font-black">1</span>
                        Data Balita &amp; Tanggal Pemeriksaan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pilih Balita</label>
                            <select name="balita_id" required class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                                <option value="" disabled selected>-- Pilih Nama Balita --</option>
                                @foreach($balitas as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama }} (Ortu: {{ $b->nama_orang_tua }})</option>
                                @endforeach
                            </select>
                            @error('balita_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
 
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tanggal Pemeriksaan</label>
                            <input type="date" name="tanggal_pemeriksaan" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                            @error('tanggal_pemeriksaan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-slate-100 dark:border-slate-700"></div>

                <!-- Section 2: Pengukuran Antropometri -->
                <div>
                    <h4 class="font-bold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2 uppercase text-xs tracking-widest">
                        <span class="h-5 w-5 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[10px] font-black">2</span>
                        Data Pengukuran Antropometri
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Berat Badan <span class="text-indigo-500">(kg) *</span></label>
                            <input type="number" step="0.01" name="berat_badan" required placeholder="Contoh: 12.5" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                            @error('berat_badan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
 
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tinggi Badan <span class="text-indigo-500">(cm) *</span></label>
                            <input type="number" step="0.01" name="tinggi_badan" required placeholder="Contoh: 80.5" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                            @error('tinggi_badan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
 
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Lingkar Lengan Atas / LiLA
                                <span class="text-xs font-normal text-slate-400 dark:text-slate-500">(cm) - Opsional</span>
                            </label>
                            <input type="number" step="0.01" name="lingkar_lengan_atas" placeholder="Contoh: 14.5" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                        </div>
 
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Lingkar Kepala
                                <span class="text-xs font-normal text-slate-400 dark:text-slate-500">(cm) - Opsional</span>
                            </label>
                            <input type="number" step="0.01" name="lingkar_kepala" placeholder="Contoh: 46.0" class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Info Box KNN -->
                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 rounded-xl p-4 flex items-start gap-3 transition-colors">
                    <svg class="h-5 w-5 text-indigo-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs text-indigo-700 dark:text-indigo-400 font-medium">Setelah disimpan, sistem akan otomatis menghitung <strong>Z-Score</strong> dan mengklasifikasikan risiko stunting menggunakan <strong>Algoritma K-Nearest Neighbor (KNN)</strong> berdasarkan data riwayat yang ada.</p>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex justify-between items-center border-t border-slate-100 dark:border-slate-700">
                    <a href="{{ route('pemeriksaan.index') }}" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 font-bold hover:text-slate-700 dark:hover:text-slate-200 transition px-4 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                    <button type="submit" class="bg-[#6366f1] hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Simpan &amp; Hitung Stunting
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
