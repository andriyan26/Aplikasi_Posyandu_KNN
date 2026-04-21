<x-app-layout>
    <x-slot name="header">
        TAMBAH KADER
    </x-slot>

    <div class="max-w-3xl mx-auto pb-10">
        <div class="bg-white dark:bg-slate-800 rounded-[24px] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden relative z-20 transition-colors duration-300">
            <div class="px-6 py-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Tambah Data Kader Baru</h3>
            </div>

            <form action="{{ route('kader.store') }}" method="POST" class="p-6 md:p-8">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Kader <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 bg-white dark:bg-slate-900 dark:text-white transition-colors">
                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 bg-white dark:bg-slate-900 dark:text-white transition-colors">{{ old('alamat') }}</textarea>
                        @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status_aktif" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 bg-white dark:bg-slate-900 dark:text-white transition-colors">
                            <option value="Aktif" {{ old('status_aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ old('status_aktif') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status_aktif') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                        <a href="{{ route('kader.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">Simpan Kader</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
