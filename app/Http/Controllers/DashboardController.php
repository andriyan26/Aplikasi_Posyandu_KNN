<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balita;
use App\Models\Pemeriksaan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBalita = Balita::count();
        $totalPemeriksaan = Pemeriksaan::count();

        // Get latest stunting status distribution array
        $stuntingData = [
            'Rendah' => Pemeriksaan::where('status_stunting', 'Rendah')->count(),
            'Sedang' => Pemeriksaan::where('status_stunting', 'Sedang')->count(),
            'Tinggi' => Pemeriksaan::where('status_stunting', 'Tinggi')->count(),
        ];

        // Prepare data for the monthly Chart.js (Last 6 Months Kunjungan)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->startOfMonth()->subMonths($i);
            $monthEnd = Carbon::now()->endOfMonth()->subMonths($i);
            
            // Format "Bulan Tahun" / "Semester X" (We'll use month abbreviation)
            $label = $monthStart->translatedFormat('M Y');
            
            // Count examinations in this month
            $count = Pemeriksaan::whereBetween('tanggal_pemeriksaan', [$monthStart, $monthEnd])->count();
            
            $chartData['labels'][] = $label;
            $chartData['data'][] = $count;
        }

        return view('dashboard', compact('totalBalita', 'totalPemeriksaan', 'stuntingData', 'chartData'));
    }
}
