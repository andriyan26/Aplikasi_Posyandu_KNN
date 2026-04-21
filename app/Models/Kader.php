<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kader extends Model
{
    use HasFactory;

    protected $table = 'kader';
    protected $primaryKey = 'id_kader';

    protected $fillable = [
        'nama',
        'alamat',
        'status_aktif',
        'barcode_ttd'
    ];

    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_kader', 'id_kader');
    }
}
