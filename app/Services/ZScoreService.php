<?php

namespace App\Services;

class ZScoreService
{
    /**
     * Hitung Z-Score: (X - µ) / σ
     * Implementasi disederhanakan sesuai formula.
     */
    public function calculate(float $tinggi_badan, float $umur_bulan, string $jenis_kelamin): float
    {
        // (Contoh/Dummy) Median populasi µ dan standar deviasi σ dipengaruhi umur
        // Idealnya data ini diambil dari tabel referensi panjang baku WHO 2006.
        // Untuk simulasi ini menggunakan pendekatan linear untuk mencontohkan formula:
        if ($jenis_kelamin === 'L') {
            $median = 50.0 + ($umur_bulan * 1.5);
        } else {
            $median = 49.0 + ($umur_bulan * 1.4);
        }
        
        $sd = 2.5; 
        
        $z_score = ($tinggi_badan - $median) / $sd;
        
        return round($z_score, 2);
    }

    /**
     * Kelasifikasi murni berdasar standar (untuk menghitung label pada data latih jika diregenerate)
     * Kategori Stunting:
     * Rendah: Z-Score >= -2 SD (Kategori Normal dan Tinggi)
     * Sedang: -3 <= Z-Score < -2 (Kategori Stunted)
     * Tinggi: Z-Score < -3 (Kategori Severely Stunted)
     */
    public function classify(float $z_score): string
    {
        if ($z_score < -3) {
            return 'Tinggi';
        } elseif ($z_score >= -3 && $z_score < -2) {
            return 'Sedang';
        } else {
            return 'Rendah';
        }
    }
}
