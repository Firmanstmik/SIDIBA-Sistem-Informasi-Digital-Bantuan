<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monev extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_id',
        'tanggal_monev',
        'pelaksana',
        'hasil_evaluasi',
        'rekomendasi',
        'dokumentasi',
        'status',
    ];

    protected $casts = [
        'tanggal_monev' => 'date',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }
}