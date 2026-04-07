<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLatih extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'usia',
        'berat_badan',
        'tinggi_badan',
        'lingkar_lengan_atas',
        'lingkar_kepala',
        'status_stunting',
    ];
}
