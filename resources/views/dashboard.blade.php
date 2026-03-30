<x-app-layout>
    <x-slot name="header">
        BERANDA
    </x-slot>

    <!-- Peringkat Tertinggi Card (Grafik) -->
    <div class="bg-white dark:bg-slate-800 rounded-[20px] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden mb-6 relative z-20 transition-colors duration-300">
        <!-- Badge Info Header -->
        <div class="bg-[#e8f5e9] dark:bg-green-900/10 px-6 py-4 flex items-center justify-between border-b border-green-100 dark:border-green-800/50">
            <p class="text-green-800 dark:text-green-400 text-sm font-semibold flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Grafik Jumlah Kunjungan Posyandu 6 Bulan Terakhir
            </p>
            <button class="text-green-600 hover:bg-green-200/50 p-1.5 rounded-full transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Legend (Mock) and Chart -->
        <div class="p-6">
            <div class="flex justify-center items-center gap-6 mb-6">
                <p class="text-sm font-medium flex items-center text-slate-600 dark:text-slate-400">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span> Kunjungan Balita
                </p>
            </div>
            
            <div class="w-full relative h-[350px]">
                <canvas id="kunjunganChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistik Cards List (Sisi Bawah) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Total Balita -->
        <div class="bg-white dark:bg-slate-800 rounded-[16px] shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 flex items-center transform transition duration-300 hover:translate-y-[-4px] hover:shadow-md">
            <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400 p-4 rounded-2xl mr-4 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-1">Total Balita</p>
                <h3 class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ $totalBalita }}</h3>
            </div>
        </div>
 
        <!-- Total Pemeriksaan -->
        <div class="bg-white dark:bg-slate-800 rounded-[16px] shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 flex items-center transform transition duration-300 hover:translate-y-[-4px] hover:shadow-md">
            <div class="bg-purple-50 dark:bg-purple-900/20 text-purple-500 dark:text-purple-400 p-4 rounded-2xl mr-4 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-1">Pemeriksaan</p>
                <h3 class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ $totalPemeriksaan }}</h3>
            </div>
        </div>
 
        <!-- Status Stunting Warning -->
        <div class="bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/10 rounded-[16px] shadow-sm border border-red-100 dark:border-red-800/50 p-6 flex flex-col justify-center transform transition duration-300 hover:translate-y-[-4px] hover:shadow-md transition-colors">
            <p class="text-red-800 dark:text-red-400 text-sm font-semibold uppercase tracking-wider mb-2">Risiko Stunting Tinggi</p>
            <div class="flex items-center gap-3">
                <h3 class="text-4xl font-extrabold text-red-600 dark:text-red-500">{{ $stuntingData['Tinggi'] ?? 0 }}</h3>
                <span class="text-red-400 dark:text-red-300 text-sm font-medium">Balita terindikasi</span>
            </div>
        </div>

    </div>

    <!-- Grafik Bottom Info -->
    <div class="mt-6 bg-white dark:bg-slate-800 rounded-[16px] p-6 shadow-sm border border-slate-100 dark:border-slate-700/50 transition-colors">
        <h3 class="font-bold text-slate-700 dark:text-white mb-4 border-b dark:border-slate-700 pb-2">Distribusi Mayoritas Status</h3>
        <div class="flex flex-wrap gap-4 text-center">
            <div class="flex-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 p-4 rounded-xl border border-green-100 dark:border-green-800/50">
                <p class="text-xs font-bold uppercase tracking-wider">Rendah / Normal</p>
                <p class="text-3xl font-black mt-1">{{ $stuntingData['Rendah'] ?? 0 }}</p>
            </div>
            <div class="flex-1 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 p-4 rounded-xl border border-amber-100 dark:border-amber-800/50">
                <p class="text-xs font-bold uppercase tracking-wider">Sedang / Waspada</p>
                <p class="text-3xl font-black mt-1">{{ $stuntingData['Sedang'] ?? 0 }}</p>
            </div>
            <div class="flex-1 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 p-4 rounded-xl border border-red-100 dark:border-red-800/50">
                <p class="text-xs font-bold uppercase tracking-wider">Tinggi / Bahaya</p>
                <p class="text-3xl font-black mt-1">{{ $stuntingData['Tinggi'] ?? 0 }}</p>
            </div>
        </div>
    </div>


    <!-- Chart Data Initialization -->
    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('kunjunganChart').getContext('2d');
                
                const labelsData = @json($chartData['labels']);
                const kunjunganData = @json($chartData['data']);

                // Create Gradient Fill
                let gradientFill = ctx.createLinearGradient(0, 0, 0, 400);
                gradientFill.addColorStop(0, 'rgba(56, 189, 248, 0.8)'); // Light blue gradient
                gradientFill.addColorStop(1, 'rgba(56, 189, 248, 0.05)');

                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labelsData,
                        datasets: [{
                            label: 'Jumlah Kunjungan',
                            data: kunjunganData,
                            borderColor: '#3b82f6', // solid blue line
                            backgroundColor: gradientFill, // gradient area fill
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4, // making it curve
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#3b82f6',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: Math.max(...kunjunganData) + 5,
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)',
                                    drawBorder: false,
                                },
                                ticks: {
                                    font: { family: 'inter', size: 11 },
                                    color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false,
                                },
                                ticks: {
                                    font: { family: 'inter', size: 12 },
                                    color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false // hidden custom above
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleFont: { size: 13, family: 'inter' },
                                bodyFont: { size: 14, font: 'bold', family: 'inter' },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false
                            }
                        }
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>
