<x-app-layout>
    <x-slot name="header">
        DATA KADER
    </x-slot>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 mx-8 mt-8 rounded">
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif    
    
    <div class="bg-white dark:bg-slate-800 rounded-[24px] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden relative z-20 mb-10 transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-6 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-800/50 gap-4">
            <div>
                <h3 class="text-xl font-bold text-slate-700 dark:text-white">Daftar Kader Posyandu</h3>
            </div>
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <form action="{{ route('kader.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kader..." class="w-full rounded-xl border-slate-200 dark:border-slate-700 text-sm focus:ring-green-500 focus:border-green-500 py-2 pl-4 pr-10 bg-white dark:bg-slate-900 dark:text-slate-300 shadow-sm transition-colors">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-green-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>

                <a href="{{ route('kader.create') }}" class="bg-[#00b488] hover:bg-[#009b75] text-white text-sm font-bold py-1.5 px-5 rounded-full shadow-sm hover:shadow transition-all flex items-center gap-2 shrink-0 border border-green-600/10">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kader
                </a>
            </div>
        </div>

        <div class="overflow-x-auto p-4 sm:p-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider bg-slate-50 dark:bg-slate-900/50">
                        <th class="px-6 py-4 font-bold rounded-l-xl text-center w-16">No.</th>
                        <th class="px-6 py-4 font-bold">Nama Kader</th>
                        <th class="px-6 py-4 font-bold">Alamat</th>
                        <th class="px-6 py-4 font-bold text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-center">Barcode TTD</th>
                        <th class="px-6 py-4 font-bold text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse($kaders as $kader)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-150 border-b border-slate-50 dark:border-slate-700/30">
                        <td class="px-6 py-5 text-center font-bold text-slate-400 dark:text-slate-600">
                            {{ ($kaders->currentPage() - 1) * $kaders->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-5">
                            <p class="font-bold text-slate-800 dark:text-slate-200">{{ $kader->nama }}</p>
                        </td>
                        <td class="px-6 py-5 text-slate-700 dark:text-slate-400 font-medium">
                            {{ $kader->alamat ?? '-' }}
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($kader->status_aktif == 'Aktif')
                                <span class="bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 font-bold px-3 py-1 rounded-full text-xs border border-green-200 dark:border-green-800">Aktif</span>
                            @else
                                <span class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-bold px-3 py-1 rounded-full text-xs border border-red-200 dark:border-red-800">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($kader->barcode_ttd)
                                <div class="inline-block p-1 bg-white border border-slate-200 rounded-xl shadow-sm text-center">
                                    <div class="flex justify-center mb-1">
                                        <img src="{{ asset('assets/Barcode TTD/' . $kader->barcode_ttd) }}" class="h-10 object-contain" alt="Barcode TTD">
                                    </div>
                                    <div class="text-[9px] text-slate-400 mt-1 tracking-wider text-center font-mono truncate max-w-[100px] mx-auto">{{ $kader->barcode_ttd }}</div>
                                </div>
                            @else
                                <span class="text-xs text-slate-400">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('kader.edit', $kader) }}" class="text-amber-500 hover:text-amber-600 bg-amber-50 dark:bg-amber-900/20 p-2 rounded-lg transition" title="Edit">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('kader.destroy', $kader) }}" method="POST" class="inline" onsubmit="event.preventDefault(); Swal.fire({title: 'Hapus Data Kader?', text: 'Yakin ingin menghapus kader {{ addslashes($kader->nama) }}? Tindakan ini tidak dapat dibatalkan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-3xl' }}).then((result) => { if (result.isConfirmed) { this.submit(); } });">
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
                            Belum ada data kader.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/50 dark:bg-slate-900/30 p-4 rounded-xl border border-slate-100 dark:border-slate-700/50">
                <div class="flex items-center gap-4 order-2 md:order-1">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                        Menampilkan <span class="font-bold text-slate-700 dark:text-slate-200">{{ $kaders->firstItem() ?? 0 }}</span> s/d <span class="font-bold text-slate-700 dark:text-slate-200">{{ $kaders->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-700 dark:text-slate-200">{{ $kaders->total() }}</span> Kader
                    </p>
                </div>
                
                <div class="order-1 md:order-2">
                    @if(method_exists($kaders, 'links'))
                        {{ $kaders->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
