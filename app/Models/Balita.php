<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    protected $fillable = ['nama', 'tanggal_lahir', 'jenis_kelamin', 'nama_orang_tua'];

    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class);
    }
}
