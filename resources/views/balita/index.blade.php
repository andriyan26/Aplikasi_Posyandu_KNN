<x-app-layout>
    <x-slot name="header">
        DATA BALITA POSYANDU
    </x-slot>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 mx-8 mt-8 rounded">
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif    
    
    <div class="bg-white dark:bg-slate-800 rounded-[24px] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden relative z-20 mb-10 transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-6 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-800/50 gap-4">
            <div>
                <h3 class="text-xl font-bold text-slate-700 dark:text-white">Daftar Balita</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Total: <b>{{ $balitas->total() }}</b> Balita terdaftar</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <form action="{{ route('balita.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
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

                <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-[#00b488] hover:bg-[#009b75] text-white text-sm font-bold py-1.5 px-5 rounded-full shadow-sm hover:shadow transition-all flex items-center gap-2 shrink-0 border border-green-600/10">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Data
                </button>
            </div>
        </div>

        <div class="overflow-x-auto p-4 sm:p-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider bg-slate-50 dark:bg-slate-900/50">
                        <th class="px-6 py-4 font-bold rounded-l-xl text-center w-16">No.</th>
                        <th class="px-6 py-4 font-bold text-center">Kode</th>
                        <th class="px-6 py-4 font-bold text-center">NIK</th>
                        <th class="px-6 py-4 font-bold">Nama Balita</th>
                        <th class="px-6 py-4 font-bold text-center">Usia (Tahun)</th>
                        <th class="px-6 py-4 font-bold text-center">L/P</th>
                        <th class="px-6 py-4 font-bold">Orang Tua</th>
                        <th class="px-6 py-4 font-bold text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse($balitas as $blt)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-150 border-b border-slate-50 dark:border-slate-700/30">
                        <td class="px-6 py-5 text-center font-bold text-slate-400 dark:text-slate-600">
                            {{ ($balitas->currentPage() - 1) * $balitas->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-5 text-center text-slate-700 dark:text-slate-400 font-medium">{{ $blt->kode }}</td>
                        <td class="px-6 py-5 text-center text-slate-700 dark:text-slate-400 font-medium">{{ $blt->nik }}</td>
                        <td class="px-6 py-5">
                            <p class="font-bold text-slate-800 dark:text-slate-200">{{ $blt->nama }}</p>
                        </td>
                        <td class="px-6 py-5 text-center text-slate-700 dark:text-slate-400 font-medium">{{ $blt->usia ?? '0.0' }}</td>
                        <td class="px-6 py-5 text-center">
                            @if($blt->jenis_kelamin == 'L')
                                <span class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-bold px-2.5 py-1 rounded-lg text-xs">L</span>
                            @else
                                <span class="bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 font-bold px-2.5 py-1 rounded-lg text-xs">P</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-slate-700 dark:text-slate-400 font-medium">{{ $blt->nama_orang_tua }}</td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('balita.edit', $blt) }}" class="text-amber-500 hover:text-amber-600 bg-amber-50 dark:bg-amber-900/20 p-2 rounded-lg transition" title="Edit">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('balita.destroy', $blt) }}" method="POST" class="inline" onsubmit="event.preventDefault(); Swal.fire({title: 'Hapus Data Balita?', text: 'Yakin ingin menghapus data balita {{ addslashes($blt->nama) }}? Tindakan ini tidak dapat dibatalkan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-3xl' }}).then((result) => { if (result.isConfirmed) { this.submit(); } });">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 bg-red-50 dark:bg-red-900/20 p-2 rounded-lg transition" title="Hapus">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400 font-medium bg-slate-50/50 dark:bg-slate-800/30 rounded-2xl">
                            @if(request('search'))
                                Balita "{{ request('search') }}" tidak ditemukan.
                            @else
                                Belum ada data balita.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-6">
                <!-- Pagination if exists -->
                @if(method_exists($balitas, 'links'))
                    {{ $balitas->links() }}
                @endif
            </div>
        </div>
    </div>


    <!-- Tag for modal inclusion if it existed in original code -->
    <!-- Modal Tambah Balita -->
    <div id="modal-tambah" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-tambah').classList.add('hidden')">
                <div class="absolute inset-0 bg-slate-900 opacity-60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-[24px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100 dark:border-slate-700 relative z-50 transition-colors">
                
                <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-white tracking-wide">Tambah Data Balita Baru</h3>
                    <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-white hover:text-slate-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('balita.store') }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">NIK</label>
                        <input type="text" name="nik" required class="w-full rounded-xl sm:text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap Balita</label>
                        <input type="text" name="nama" required class="w-full rounded-xl sm:text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                    </div>
 
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" required class="w-full rounded-xl sm:text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" required class="w-full rounded-xl sm:text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                                <option value="L" class="dark:bg-slate-900">Laki-laki (L)</option>
                                <option value="P" class="dark:bg-slate-900">Perempuan (P)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Orang Tua</label>
                        <input type="text" name="nama_orang_tua" required class="w-full rounded-xl sm:text-sm border-slate-300 dark:border-slate-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors">
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700 mt-4">
                        <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
