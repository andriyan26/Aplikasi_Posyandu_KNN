<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $pemeriksaans = Pemeriksaan::with('balita')
            ->whereMonth('tanggal_pemeriksaan', $bulan)
            ->whereYear('tanggal_pemeriksaan', $tahun)
            ->latest()
            ->get();

        return view('report.index', compact('pemeriksaans', 'bulan', 'tahun'));
    }

    public function downloadPdf(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $pemeriksaans = Pemeriksaan::with('balita')
            ->whereMonth('tanggal_pemeriksaan', $bulan)
            ->whereYear('tanggal_pemeriksaan', $tahun)
            ->latest()
            ->get();

        $namaBulan = Carbon::createFromFormat('m', $bulan)->translatedFormat('F');

        $pdf = Pdf::loadView('report.pdf', compact('pemeriksaans', 'bulan', 'tahun', 'namaBulan'));
        
        return $pdf->download("Laporan_Pemeriksaan_Posyandu_{$namaBulan}_{$tahun}.pdf");
    }
}
