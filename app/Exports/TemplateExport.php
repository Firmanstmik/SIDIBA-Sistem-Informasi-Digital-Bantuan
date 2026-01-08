<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'nik',
            'nama', 
            'alamat',
            'kelompok_tani',
            'bidang',
            'jenis_bantuan',
            'tahun',
            'kuantitas',
            'status',
            'link',
            'latitude',
            'longitude'
        ];
    }
}