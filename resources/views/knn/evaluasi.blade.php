<x-app-layout>
    <x-slot name="header">
        EVALUASI MODEL KNN
    </x-slot>

    <div class="flex flex-col gap-8 w-full max-w-5xl relative z-20">
        
        <!-- Pengaturan K -->
        <div class="bg-white dark:bg-slate-800 rounded-[20px] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden w-full transition-colors duration-300">
            <div class="p-8">
                <form action="{{ route('knn.evaluasi') }}" method="GET" class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">Nilai K (Jumlah Tetangga Terdekat)</label>
                        <input type="number" name="k_value" value="{{ request('k_value', 3) }}" min="1" max="50" class="w-full rounded-xl sm:text-lg font-bold border-slate-200 dark:border-slate-700 shadow-inner focus:ring-blue-500 focus:border-blue-500 p-4 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white text-center transition">
                    </div>
                    <div class="mt-2 md:mt-0 md:pt-6">
                        <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-[#b92b27] to-[#1565C0] text-white font-bold py-4 px-10 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 text-lg">
                            Uji Akurasi Data Latih
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($evaluasi)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Hasil Akurasi (Resubstitution) -->
            <div class="bg-gradient-to-br from-[#1A2980] to-[#26D0CE] dark:from-[#0f174a] dark:to-[#1a8a89] rounded-[24px] shadow-lg p-8 transform transition hover:scale-[1.02] duration-300 text-white relative overflow-hidden flex flex-col justify-center">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="relative z-10 text-center">
                    <h3 class="text-2xl font-black mb-2 tracking-wide uppercase">Hasil Evaluasi (K={{ $k_value }})</h3>
                    <p class="text-blue-100 dark:text-blue-200 mb-8 text-sm font-medium">Resubstitution Matrix (Pengujian Data Latih ke Diri Sendiri).</p>
                    
                    <div class="flex flex-wrap gap-4 mb-8">
                        <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl flex-1 border border-white/20 shadow-inner">
                            <p class="text-xs font-semibold uppercase tracking-widest text-blue-100/80 mb-2">Akurasi Model</p>
                            <p class="text-5xl font-black text-white drop-shadow-md">{{ $evaluasi['accuracy'] }}%</p>
                        </div>
                    </div>
 
                    <div class="grid grid-cols-2 gap-4">
                        <p class="font-bold text-sm bg-black/20 p-4 rounded-xl text-center border border-white/10"><span class="block text-xl font-black mb-1 text-green-400">{{ $evaluasi['correct'] }}</span> Prediksi Benar</p>
                        <p class="font-bold text-sm bg-black/20 p-4 rounded-xl text-center border border-white/10"><span class="block text-xl font-black mb-1 text-white">{{ $evaluasi['total'] }}</span> Total Data</p>
                    </div>
                </div>
            </div>
 
            <!-- Grafik Akurasi K -->
            <div class="bg-white dark:bg-slate-800 rounded-[24px] shadow-sm border border-slate-100 dark:border-slate-700/50 p-8 flex flex-col justify-center items-center transition-colors">
                <h3 class="text-lg font-bold text-slate-700 dark:text-white w-full text-left mb-6 uppercase tracking-wider">Grafik Pergerakan Akurasi (K)</h3>
                <div class="w-full relative h-[300px]">
                    <canvas id="accuracyChart"></canvas>
                </div>
            </div>

        </div>
        @else
        <div class="bg-white rounded-[24px] shadow-sm p-12 text-center mt-2 border border-slate-100">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <p class="text-slate-600 font-bold text-xl uppercase tracking-wide">Belum ada data pemerintahan / stunting.</p>
            <p class="text-slate-500 mt-2 text-sm max-w-lg mx-auto leading-relaxed">Pastikan Admin mengupload CSV data latih atau Kader input manual pemeriksaan minimal 3 data berstatus stunting untuk menjalankan model KNN.</p>
        </div>
        @endif
    </div>

    @if($evaluasi)
    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('accuracyChart').getContext('2d');
                
                const graphData = @json($graphData);
                const labels = graphData.map(d => 'K=' + d.k);
                const data = graphData.map(d => d.accuracy);

                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Akurasi (%)',
                            data: data,
                            borderColor: '#8b5cf6', // purple-500
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#8b5cf6',
                            pointHoverBackgroundColor: '#8b5cf6',
                            pointHoverBorderColor: '#fff',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: false,
                                suggestedMin: Math.min(...data) > 20 ? Math.min(...data) - 10 : 0,
                                suggestedMax: 100,
                                grid: { color: document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)', drawBorder: false },
                                ticks: { 
                                    font: { family: 'inter' },
                                    color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
                                }
                            },
                            x: {
                                grid: { display: false, drawBorder: false },
                                ticks: { 
                                    font: { family: 'inter' },
                                    color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            });
        </script>
    </x-slot>
    @endif
</x-app-layout>
