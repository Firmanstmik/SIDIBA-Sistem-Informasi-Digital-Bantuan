<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'nomor_hape',
        'kelompok_tani',
        'bidang',
        'jenis_bantuan',
        'tahun',
        'kuantitas',
        'status',
        'link',
        'sumber_dana',
        'keterangan',
        'latitude',
        'longitude',
    ];

    public function monevs()
    {
        return $this->hasMany(Monev::class);
    }
}