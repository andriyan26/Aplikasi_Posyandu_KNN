<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaan';

    protected $fillable = [
        'balita_id',
        'usia_saat_periksa',
        'tanggal_pemeriksaan',
        'berat_badan',
        'tinggi_badan',
        'lingkar_lengan_atas',
        'lingkar_kepala',
        'status_stunting'
    ];

    public function balita()
    {
        return $this->belongsTo(Balita::class);
    }
}
