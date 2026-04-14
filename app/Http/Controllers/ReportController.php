<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\Balita;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $bulan = str_pad($request->get('bulan', date('m')), 2, '0', STR_PAD_LEFT);
        $tahun = $request->get('tahun', date('Y'));
        $balita_id = $request->get('balita_id', '');
        $bulan_akhir = str_pad($request->get('bulan_akhir', $bulan), 2, '0', STR_PAD_LEFT);

        if ($balita_id) {
            $pemeriksaans = Pemeriksaan::with('balita')
                ->where('balita_id', $balita_id)
                ->whereYear('tanggal_pemeriksaan', $tahun)
                ->whereMonth('tanggal_pemeriksaan', '>=', $bulan)
                ->whereMonth('tanggal_pemeriksaan', '<=', $bulan_akhir)
                ->orderBy('tanggal_pemeriksaan', 'asc')
                ->get();
            $balitas = collect();
        } else {
            $balitas = Balita::with(['pemeriksaans' => function($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pemeriksaan', $bulan)
                  ->whereYear('tanggal_pemeriksaan', $tahun)
                  ->latest();
            }])->orderBy('nama')->get();
            $pemeriksaans = collect();
        }

        $semua_balita = Balita::orderBy('nama')->get();

        return view('report.index', compact('balitas', 'pemeriksaans', 'bulan', 'tahun', 'balita_id', 'bulan_akhir', 'semua_balita'));
    }

    public function downloadPdf(Request $request)
    {
        $bulan = str_pad($request->get('bulan', date('m')), 2, '0', STR_PAD_LEFT);
        $tahun = $request->get('tahun', date('Y'));
        $balita_id = $request->get('balita_id', '');
        $bulan_akhir = str_pad($request->get('bulan_akhir', $bulan), 2, '0', STR_PAD_LEFT);

        $namaBulan = Carbon::createFromFormat('m', $bulan)->translatedFormat('F');
        $namaBulanAkhir = Carbon::createFromFormat('m', $bulan_akhir)->translatedFormat('F');

        if ($balita_id) {
            $pemeriksaans = Pemeriksaan::with('balita')
                ->where('balita_id', $balita_id)
                ->whereYear('tanggal_pemeriksaan', $tahun)
                ->whereMonth('tanggal_pemeriksaan', '>=', $bulan)
                ->whereMonth('tanggal_pemeriksaan', '<=', $bulan_akhir)
                ->orderBy('tanggal_pemeriksaan', 'asc')
                ->get();
            $balitas = collect();
            
            $balita = Balita::find($balita_id);
            $safeName = $balita ? preg_replace('/[^a-zA-Z0-9_\-]/', '_', $balita->nama) : 'Anak';
            $filename = "Laporan_Perkembangan_{$safeName}_{$tahun}.pdf";
        } else {
            $balitas = Balita::with(['pemeriksaans' => function($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pemeriksaan', $bulan)
                  ->whereYear('tanggal_pemeriksaan', $tahun)
                  ->latest();
            }])->orderBy('nama')->get();
            $pemeriksaans = collect();
            
            $filename = "Laporan_Bulanan_Posyandu_{$namaBulan}_{$tahun}.pdf";
        }

        $pdf = Pdf::loadView('report.pdf', compact('balitas', 'pemeriksaans', 'bulan', 'tahun', 'namaBulan', 'balita_id', 'bulan_akhir', 'namaBulanAkhir'))->setPaper('a4', 'portrait');
        
        return $pdf->download($filename);
    }
}
