<?php

namespace App\Imports;

use App\Models\Beneficiary;
use App\Models\Bantuan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class BeneficiariesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errors = [];
        $successCount = 0;

        foreach ($rows as $index => $row) {
            try {
                // Validasi data required
                if (empty($row['nik']) || empty($row['nama']) || empty($row['jenis_bantuan']) || empty($row['tahun'])) {
                    $errors[] = "Baris " . ($index + 2) . ": Data required kosong";
                    continue;
                }

                // Cek duplikasi
                $existing = Beneficiary::where('nik', $row['nik'])
                                     ->where('jenis_bantuan', $row['jenis_bantuan'])
                                     ->where('tahun', $row['tahun'])
                                     ->first();

                if ($existing) {
                    $errors[] = "Baris " . ($index + 2) . ": Data duplikat (NIK: {$row['nik']}, Bantuan: {$row['jenis_bantuan']}, Tahun: {$row['tahun']})";
                    continue;
                }

                // Generate keterangan jika ada data sebelumnya
                $keterangan = null;
                $sameYear = Beneficiary::where('nik', $row['nik'])
                                     ->where('jenis_bantuan', $row['jenis_bantuan'])
                                     ->where('tahun', $row['tahun'])
                                     ->first();

                if ($sameYear) {
                    $keterangan = "Sudah menerima bantuan {$row['jenis_bantuan']} pada tahun {$row['tahun']}";
                }

                // Insert data
                Beneficiary::create([
                    'nik' => $row['nik'],
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'] ?? '',
                    'kelompok_tani' => $row['kelompok_tani'] ?? '',
                    'bidang' => $row['bidang'] ?? auth()->user()->bidang,
                    'jenis_bantuan' => $row['jenis_bantuan'],
                    'tahun' => $row['tahun'],
                    'kuantitas' => $row['kuantitas'] ?? 1,
                    'status' => $row['status'] ?? 'terdaftar',
                    'link' => $row['link'] ?? '',
                    'keterangan' => $keterangan,
                    'latitude' => $row['latitude'] ?? null,
                    'longitude' => $row['longitude'] ?? null,
                ]);

                $successCount++;

            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        // Simpan hasil import di session
        session()->flash('import_result', [
            'success' => $successCount,
            'errors' => $errors
        ]);
    }
}