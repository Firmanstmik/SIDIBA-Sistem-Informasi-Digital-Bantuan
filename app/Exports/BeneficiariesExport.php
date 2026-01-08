<?php

namespace App\Exports;

use App\Models\Beneficiary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class BeneficiariesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tahun;
    protected $jenis;
    protected $bidang;

    public function __construct($tahun = '', $jenis = '', $bidang = '')
    {
        $this->tahun = $tahun;
        $this->jenis = $jenis;
        $this->bidang = $bidang;
    }

    public function collection()
    {
        $query = Beneficiary::query();
        
        // Filter berdasarkan role user
        if (Auth::user()->role === 'user') {
            $query->where('bidang', Auth::user()->bidang);
        } elseif ($this->bidang) {
            $query->where('bidang', $this->bidang);
        }
        
        // Filter tahun
        if ($this->tahun) {
            $query->where('tahun', $this->tahun);
        }
        
        // Filter jenis bantuan
        if ($this->jenis) {
            $query->where('jenis_bantuan', $this->jenis);
        }

        return $query->orderBy('tahun', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Penerima',
            'Alamat',
            'Kelompok Tani',
            'Bidang',
            'Jenis Bantuan',
            'Kuantitas',
            'Tahun',
            'Status',
            'Link Dokumen',
            'Latitude',
            'Longitude',
            'Keterangan'
        ];
    }

    public function map($beneficiary): array
    {
        static $i = 1;
        return [
            $i++,
            $beneficiary->nik,
            $beneficiary->nama,
            $beneficiary->alamat,
            $beneficiary->kelompok_tani ?: '-',
            $beneficiary->bidang,
            $beneficiary->jenis_bantuan,
            $beneficiary->kuantitas ?: 1,
            $beneficiary->tahun,
            $beneficiary->status,
            $beneficiary->link ?: '-',
            $beneficiary->latitude ?: '-',
            $beneficiary->longitude ?: '-',
            $beneficiary->keterangan ?: '-'
        ];
    }
}