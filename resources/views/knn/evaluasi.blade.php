<x-app-layout>
    <x-slot name="header">
        DATA TRAINING & EVALUASI MODEL KNN
    </x-slot>

    <div class="w-full max-w-7xl mx-auto pb-12 px-4 sm:px-6">

        <!-- Action Row (K-Value & Upload CSV) - RE-CONSTRAINED -->
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch mb-10">

            <!-- Left: Parameter K -->
            <div
                class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 flex flex-col hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-3 mb-5">
                    <div
                        class="h-10 w-10 shrink-0 bg-blue-50 dark:bg-blue-900/40 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-[0.2em]">Parameter K
                    </h3>
                </div>
                <form action="{{ route('knn.evaluasi') }}" method="GET" class="space-y-4 mt-auto">
                    <input type="number" name="k_value" value="{{ request('k_value', 3) }}" min="1" max="50"
                        class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white text-sm font-bold p-3 focus:ring-blue-500 focus:border-blue-500 transition-all text-center">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-widest py-3.5 rounded-2xl shadow-lg shadow-blue-200 dark:shadow-none transition transform active:scale-95">
                        Uji Akurasi
                    </button>
                </form>
            </div>

            <!-- Right: Upload Dataset -->
            <div
                class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 flex flex-col hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-3 mb-5">
                    <div
                        class="h-10 w-10 shrink-0 bg-emerald-50 dark:bg-emerald-900/40 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-[0.2em]">Latih via
                        CSV</h3>
                </div>
                <form action="{{ route('knn.import') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4 mt-auto">
                    @csrf
                    <div class="relative group">
                        <input type="file" name="file_csv" accept=".csv" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div
                            class="w-full border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl py-3 px-4 text-center text-slate-400 dark:text-slate-500 group-hover:border-emerald-500 transition-colors flex items-center justify-center gap-2">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" stroke-width="2.5"></path>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">Pilih Berkas</span>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-widest py-3.5 rounded-2xl shadow-lg shadow-blue-200 dark:shadow-none transition transform active:scale-95">
                        Unggah Data
                    </button>
                </form>
            </div>
        </div>

        @if($evaluasi && $totalData > 0)
            <!-- Dashboard Metrics -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Accuracy -->
                <div
                    class="flex bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden group">
                    <div class="w-1.5 bg-blue-600 shrink-0"></div>
                    <div class="p-5 flex-1">
                        <p class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-[0.2em] mb-1">
                            Overall Akurasi</p>
                        <div class="flex items-baseline gap-1">
                            <p class="text-3xl font-black text-slate-800 dark:text-white italic">{{ $evaluasi['accuracy'] }}
                            </p>
                            <p class="text-xs font-bold text-slate-400">%</p>
                        </div>
                        <div class="mt-4">
                            <div class="h-1 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full transition-all duration-1000"
                                    style="width: {{ $evaluasi['accuracy'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Precision -->
                <div
                    class="flex bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
                    <div class="w-1.5 bg-indigo-500 shrink-0"></div>
                    <div class="p-5 flex-1">
                        <p class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-[0.2em] mb-1">
                            Precision</p>
                        <p class="text-3xl font-black text-slate-800 dark:text-white italic">{{ $evaluasi['precision'] }}%
                        </p>
                        <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-tighter italic">Ketapan
                            Prediksi</p>
                    </div>
                </div>

                <!-- Recall -->
                <div
                    class="flex bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
                    <div class="w-1.5 bg-emerald-500 shrink-0"></div>
                    <div class="p-5 flex-1">
                        <p class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-[0.2em] mb-1">
                            Recall</p>
                        <p class="text-3xl font-black text-slate-800 dark:text-white italic">{{ $evaluasi['recall'] }}%</p>
                        <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-tighter italic">Sensitivitas
                            Model</p>
                    </div>
                </div>

                <!-- F1-Score -->
                <div
                    class="flex bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
                    <div class="w-1.5 bg-amber-500 shrink-0"></div>
                    <div class="p-5 flex-1">
                        <p class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-[0.2em] mb-1">
                            F1-Score</p>
                        <p class="text-3xl font-black text-slate-800 dark:text-white italic">{{ $evaluasi['f1_score'] }}%
                        </p>
                        <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-tighter italic">Keseimbangan
                            Harmonik</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h3
                            class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-[0.2em] flex items-center gap-2">
                            <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span>
                            CM Matrix
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase">Live Metrics</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full border-separate border-spacing-2">
                            <thead>
                                <tr>
                                    <td></td>
                                    @foreach(['Rendah', 'Sedang', 'Tinggi'] as $class)
                                        <td class="text-center text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                            {{ $class }} (P)
                                        </td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Rendah', 'Sedang', 'Tinggi'] as $actual)
                                    <tr>
                                        <td
                                            class="text-right text-[9px] font-black text-slate-400 uppercase tracking-widest pr-2">
                                            {{ $actual }} (A)
                                        </td>
                                        @foreach(['Rendah', 'Sedang', 'Tinggi'] as $predicted)
                                            <td>
                                                <div
                                                    class="w-full h-14 flex items-center justify-center rounded-2xl font-black text-sm transition-all hover:scale-105 {{ $actual === $predicted ? 'bg-blue-600 text-white shadow-xl shadow-blue-200 dark:shadow-none' : 'bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-600' }}">
                                                    {{ $evaluasi['confusion_matrix'][$actual][$predicted] ?? 0 }}
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 flex justify-center gap-8 text-[9px] font-bold text-slate-400 uppercase italic">
                        <div class="flex items-center gap-2"><span class="h-2 w-2 bg-blue-600 rounded"></span> True Positive
                        </div>
                        <div class="flex items-center gap-2"><span
                                class="h-2 w-2 bg-slate-100 dark:bg-slate-800 rounded"></span> False Prediction</div>
                    </div>
                </div>

                <!-- Accuracy Graph -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-[0.2em] mb-8">
                        Visualisasi Trend Akurasi</h3>
                    <div class="w-full h-56">
                        <canvas id="accuracyChart"></canvas>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State - RE-CONSTRAINED -->
            <div
                class="max-w-2xl mx-auto bg-white dark:bg-slate-800 rounded-[40px] shadow-sm p-12 text-center border border-slate-100 dark:border-slate-700/50 shadow-blue-50/50 relative overflow-hidden mb-10">
                <div
                    class="absolute -top-12 -right-12 w-48 h-48 bg-blue-50 dark:bg-blue-900/10 rounded-full blur-3xl opacity-50">
                </div>

                <div class="relative z-10">
                    <div
                        class="w-16 h-16 bg-amber-50 dark:bg-amber-900/20 rounded-2xl flex items-center justify-center mx-auto mb-6 text-amber-500 shadow-inner">
                        <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2 uppercase tracking-wide">Data
                        Pelatihan Masih Kosong</h3>
                    <p
                        class="text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed text-[11px] mb-8 font-bold italic">
                        Sistem membutuhkan dataset historis untuk melatih algoritma KNN. Unggah berkas CSV untuk memulai
                        analisis stunting.</p>

                    <div class="flex justify-center">
                        <a href="{{ route('knn.template') }}"
                            class="group relative inline-flex items-center gap-4 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl shadow-xl shadow-blue-200 dark:shadow-none transition-all hover:-translate-y-1">
                            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-70 leading-none mb-1">
                                    Dapatkan Contoh</p>
                                <p class="text-xs font-black uppercase tracking-widest leading-none">Unduh Template CSV</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Dataset Preview (Professional Table Style) -->
        <div
            class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden shadow-slate-100/50">
            <div
                class="p-6 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50 dark:bg-slate-900/10 border-b border-slate-100 dark:border-slate-800 gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 bg-slate-800 dark:bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest leading-none">
                            Preview Dataset Training</h3>
                        <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter italic">Total
                            {{ $totalData }} entries dalam sistem
                        </p>
                    </div>
                </div>
                @if($totalData > 0)
                    <form action="{{ route('knn.destroy_all') }}" method="POST"
                        onsubmit="return confirm('Hapus semua data latih?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-6 py-2.5 bg-red-50 dark:bg-red-900/20 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] hover:bg-red-100 dark:hover:bg-red-900/40 transition-all flex items-center gap-2 border border-red-100 dark:border-red-900/30">
                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Reset Data
                        </button>
                    </form>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead
                        class="bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="p-5 text-center">No</th>
                            <th class="p-5 text-left">Nama</th>
                            <th class="p-5 text-center">Usia</th>
                            <th class="p-5 text-center">BB (kg)</th>
                            <th class="p-5 text-center">TB (cm)</th>
                            <th class="p-5 text-center">LiLA (cm)</th>
                            <th class="p-5 text-center">LiKep (cm)</th>
                            <th class="p-5 text-right italic">Risiko Stunting</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($dataLatih as $item)
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                <td class="p-5 text-center text-xs font-bold text-slate-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="p-5">
                                    <p class="text-xs font-black text-slate-700 dark:text-slate-200">{{ $item->nama }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5 italic text-slate-500">
                                        Data Latih ({{ $item->jenis_kelamin }})</p>
                                </td>
                                <td class="p-5 text-center text-[11px] text-slate-600 dark:text-slate-400 font-bold italic">
                                    {{ number_format((float) $item->usia, 1, '.', '') }}
                                </td>
                                <td class="p-5 text-center text-[11px] text-slate-600 dark:text-slate-400 font-bold italic">
                                    {{ number_format((float) $item->berat_badan, 1, '.', '') }}
                                </td>
                                <td class="p-5 text-center text-[11px] text-slate-600 dark:text-slate-400 font-bold italic">
                                    {{ number_format((float) $item->tinggi_badan, 1, '.', '') }}
                                </td>
                                <td class="p-5 text-center text-[11px] text-slate-600 dark:text-slate-400 font-bold italic">
                                    {{ number_format((float) $item->lingkar_lengan_atas, 1, '.', '') }}
                                </td>
                                <td class="p-5 text-center text-[11px] text-slate-600 dark:text-slate-400 font-bold italic">
                                    {{ number_format((float) $item->lingkar_kepala, 1, '.', '') }}
                                </td>
                                <td class="p-5 text-right">
                                    <span
                                        class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm {{ $item->status_stunting == 'Rendah' ? 'bg-green-500 text-white shadow-green-200' : ($item->status_stunting == 'Sedang' ? 'bg-amber-500 text-white shadow-amber-200' : 'bg-red-500 text-white shadow-red-200') }}">
                                        {{ $item->status_stunting }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="p-16 text-center text-slate-400 dark:text-slate-700 text-xs font-black uppercase tracking-[0.2em] italic">
                                    Belum ada data pelatihan teridentifikasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($dataLatih->hasPages())
                <div class="p-6 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800">
                    {{ $dataLatih->links() }}
                </div>
            @endif
        </div>
    </div>

    @if($evaluasi && $totalData > 0)
        <x-slot name="scripts">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('accuracyChart').getContext('2d');
                    const graphData = @json($graphData);
                    const labels = graphData.map(d => 'K=' + d.k);
                    const data = graphData.map(d => d.accuracy);

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Akurasi (%)',
                                data: data,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.6,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#2563eb',
                                pointBorderWidth: 2.5,
                                pointRadius: 4,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { intersect: false, mode: 'index' },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    max: 100,
                                    min: 0,
                                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                                    ticks: { font: { size: 9, family: 'sans-serif', weight: 'bold' }, color: '#94a3b8', stepSize: 20 }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 9, family: 'sans-serif', weight: 'bold' }, color: '#94a3b8' }
                                }
                            },
                            plugins: { legend: { display: false } }
                        }
                    });
                });
            </script>
        </x-slot>
    @endif
</x-app-layout>