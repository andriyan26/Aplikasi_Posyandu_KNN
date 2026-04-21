<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    protected $table = 'balita';
    protected $primaryKey = 'kode_balita';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode_balita', 'nama', 'usia', 'tanggal_lahir', 'jenis_kelamin', 'nama_orang_tua', 'status_balita'];

    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class, 'kode_balita', 'kode_balita');
    }
}
