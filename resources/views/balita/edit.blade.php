<x-app-layout>
    <x-slot name="header">
        EDIT DATA BALITA
    </x-slot>

    <div class="max-w-2xl relative z-20">
        <div class="bg-white dark:bg-slate-800 rounded-[20px] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden mb-10 transition-colors">
            
            <!-- Card Header -->
            <div class="bg-amber-500 px-8 py-5 flex items-center gap-3">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <div>
                    <h3 class="text-lg font-bold text-white">Ubah Data Balita</h3>
                    <p class="text-amber-100 text-sm">Formulir perubahan informasi balita: <strong>{{ $balita->nama }}</strong></p>
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

            <!-- Form Body -->
            <form action="{{ route('balita.update', $balita) }}" method="POST" class="px-8 py-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Balita -->
                <div>
                    <label for="nama" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap Balita</label>
                    <input 
                        type="text" 
                        name="nama" 
                        id="nama"
                        value="{{ old('nama', $balita->nama) }}"
                        required
                        class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-amber-500 focus:border-amber-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition"
                        placeholder="Masukkan nama lengkap balita..."
                    >
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tanggal Lahir</label>
                    <input 
                        type="date" 
                        name="tanggal_lahir" 
                        id="tanggal_lahir"
                        value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($balita->tanggal_lahir)->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-amber-500 focus:border-amber-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition"
                    >
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Jenis Kelamin</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-3 flex-1 border border-slate-200 dark:border-slate-700 rounded-xl p-4 cursor-pointer hover:border-amber-300 hover:bg-amber-50 dark:hover:bg-slate-800 transition has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 dark:has-[:checked]:bg-amber-900/10">
                            <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'L' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-500">
                            <div>
                                <p class="font-bold text-slate-700 dark:text-slate-200 text-sm">Laki-laki</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">L</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 flex-1 border border-slate-200 dark:border-slate-700 rounded-xl p-4 cursor-pointer hover:border-amber-300 hover:bg-amber-50 dark:hover:bg-slate-800 transition has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 dark:has-[:checked]:bg-amber-900/10">
                            <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'P' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-500">
                            <div>
                                <p class="font-bold text-slate-700 dark:text-slate-200 text-sm">Perempuan</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">P</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Nama Orang Tua -->
                <div>
                    <label for="nama_orang_tua" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Orang Tua / Wali</label>
                    <input 
                        type="text" 
                        name="nama_orang_tua" 
                        id="nama_orang_tua"
                        value="{{ old('nama_orang_tua', $balita->nama_orang_tua) }}"
                        required
                        class="w-full rounded-xl text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-amber-500 focus:border-amber-500 p-3.5 bg-slate-50 dark:bg-slate-900 dark:text-white transition"
                        placeholder="Masukkan nama orang tua atau wali..."
                    >
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex justify-between items-center border-t border-slate-100 dark:border-slate-700">
                    <a href="{{ route('balita.index') }}" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 font-bold hover:text-slate-700 dark:hover:text-slate-200 transition px-4 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
