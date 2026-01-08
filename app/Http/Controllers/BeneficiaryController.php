<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\Bantuan;

class BeneficiaryController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', '');
        $jenis = $request->get('jenis', '');
        $bidang = $request->get('bidang', '');
        $search_nik = $request->get('search_nik', '');
        $sort = $request->get('sort', 'newest'); // Default sorting terbaru
        
        $query = Beneficiary::query();
        
        if (auth()->user()->role === 'user') {
            $query->where('bidang', auth()->user()->bidang);
        } elseif ($bidang) {
            $query->where('bidang', $bidang);
        }
        
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        
        if ($jenis) {
            $query->where('jenis_bantuan', $jenis);
        }
        
        // Pencarian by NIK
        if ($search_nik) {
            $query->where('nik', 'like', '%' . $search_nik . '%');
        }
        
        // Apply sorting berdasarkan parameter
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc'); // Terlama ke terbaru
                break;
            case 'name_asc':
                $query->orderBy('nama', 'asc'); // Nama A-Z
                break;
            case 'name_desc':
                $query->orderBy('nama', 'desc'); // Nama Z-A
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc'); // Terbaru ke terlama (DEFAULT)
                break;
        }
        
        $data = $query->paginate(10);
        
        $tahun_list = Beneficiary::distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $jenis_list = Beneficiary::distinct()->orderBy('jenis_bantuan')->pluck('jenis_bantuan');
        $bidang_list = Beneficiary::distinct()->orderBy('bidang')->pluck('bidang');
        
        $bantuan_list = auth()->user()->role === 'user' 
            ? Bantuan::where('bidang', auth()->user()->bidang)->get()
            : Bantuan::all();

        return view('beneficiaries.index', compact(
            'data', 'tahun_list', 'jenis_list', 'bidang_list', 'bantuan_list',
            'tahun', 'jenis', 'bidang', 'search_nik', 'sort'
        ));
    }

    public function create()
    {
        $bantuan_list = auth()->user()->role === 'user' 
            ? Bantuan::where('bidang', auth()->user()->bidang)->get()
            : Bantuan::all();

        return view('beneficiaries.create', compact('bantuan_list'));
    }

    /**
     * Check NIK untuk validasi duplikat
     */
    public function checkNik(Request $request)
    {
        $nik = $request->get('nik');
        
        if (!$nik) {
            return response()->json(['exists' => false]);
        }

        $beneficiaries = Beneficiary::where('nik', $nik)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($beneficiaries->isEmpty()) {
            return response()->json(['exists' => false]);
        }

        // Return data semua bantuan yang sudah diterima oleh NIK ini
        $data = $beneficiaries->map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'alamat' => $item->alamat,
                'nomor_hape' => $item->nomor_hape,
                'kelompok_tani' => $item->kelompok_tani,
                'jenis_bantuan' => $item->jenis_bantuan,
                'tahun' => $item->tahun,
                'kuantitas' => $item->kuantitas ?? 1,
                'status' => $item->status,
                'link' => $item->link,
                'sumber_dana' => $item->sumber_dana,
                'keterangan' => $item->keterangan,
                'created_at' => $item->created_at->format('d/m/Y H:i'),
            ];
        });

        return response()->json([
            'exists' => true,
            'data' => $data,
            'total' => $beneficiaries->count()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:16',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_hape' => 'nullable|string|max:15',
            'kelompok_tani' => 'nullable|string|max:255',
            'jenis_bantuan' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2030',
            'kuantitas' => 'nullable|integer|min:1',
            'status' => 'required|string',
            'link' => 'nullable|url',
            'sumber_dana' => 'nullable|string|max:255',
            'sumber_dana_lainnya' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Handle sumber dana: jika LAINNYA dipilih, gunakan input manual
        if (isset($validated['sumber_dana']) && $validated['sumber_dana'] === 'LAINNYA' && !empty($validated['sumber_dana_lainnya'])) {
            $validated['sumber_dana'] = $validated['sumber_dana_lainnya'];
        }

        // Cek duplikasi
        $existing = Beneficiary::where('nik', $validated['nik'])
                             ->where('jenis_bantuan', $validated['jenis_bantuan'])
                             ->where('tahun', $validated['tahun'])
                             ->first();

        $keterangan = null;
        if ($existing) {
            $keterangan = "Sudah menerima bantuan {$validated['jenis_bantuan']} pada tahun {$validated['tahun']}";
        }

        Beneficiary::create([
            'nik' => $validated['nik'],
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
            'nomor_hape' => $validated['nomor_hape'],
            'kelompok_tani' => $validated['kelompok_tani'],
            'bidang' => auth()->user()->bidang,
            'jenis_bantuan' => $validated['jenis_bantuan'],
            'tahun' => $validated['tahun'],
            'kuantitas' => $validated['kuantitas'] ?? 1,
            'status' => $validated['status'],
            'link' => $validated['link'],
            'sumber_dana' => $validated['sumber_dana'] ?? null,
            'keterangan' => $keterangan,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return redirect()->route('beneficiaries.index')
                         ->with('success', 'Data penerima berhasil ditambahkan!');
    }

    public function edit(Beneficiary $beneficiary)
    {
        $bantuan_list = auth()->user()->role === 'user' 
            ? Bantuan::where('bidang', auth()->user()->bidang)->get()
            : Bantuan::all();

        return view('beneficiaries.edit', compact('beneficiary', 'bantuan_list'));
    }

    public function update(Request $request, Beneficiary $beneficiary)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:16',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_hape' => 'nullable|string|max:15',
            'kelompok_tani' => 'nullable|string|max:255',
            'jenis_bantuan' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2030',
            'kuantitas' => 'nullable|integer|min:1',
            'status' => 'required|string',
            'link' => 'nullable|url',
            'sumber_dana' => 'nullable|string|max:255',
            'sumber_dana_lainnya' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Handle sumber dana: jika LAINNYA dipilih, gunakan input manual
        if (isset($validated['sumber_dana']) && $validated['sumber_dana'] === 'LAINNYA' && !empty($validated['sumber_dana_lainnya'])) {
            $validated['sumber_dana'] = $validated['sumber_dana_lainnya'];
        }

        $beneficiary->update($validated);

        return redirect()->route('beneficiaries.index')
                         ->with('success', 'Data penerima berhasil diperbarui!');
    }

    public function destroy(Beneficiary $beneficiary)
    {
        $beneficiary->delete();

        return redirect()->route('beneficiaries.index')
                         ->with('success', 'Data penerima berhasil dihapus!');
    }

    public function show(Beneficiary $beneficiary)
    {
        return redirect()->route('beneficiaries.edit', $beneficiary);
    }

    public function exportManual(Request $request)
    {
        try {
            $tahun = $request->get('tahun', '');
            $jenis = $request->get('jenis', '');
            $bidang = $request->get('bidang', '');
            $sort = $request->get('sort', 'newest');
            
            $query = Beneficiary::query();
            
            if (auth()->user()->role === 'user') {
                $query->where('bidang', auth()->user()->bidang);
            } elseif ($bidang) {
                $query->where('bidang', $bidang);
            }
            
            if ($tahun) {
                $query->where('tahun', $tahun);
            }
            
            if ($jenis) {
                $query->where('jenis_bantuan', $jenis);
            }

            // Apply sorting yang sama dengan index
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('nama', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('nama', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $data = $query->get();

            $filename = 'data_penerima_bantuan';
            if ($tahun) $filename .= '_' . $tahun;
            if ($jenis) $filename .= '_' . str_replace(' ', '_', $jenis);
            $filename .= '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, [
                    'No', 'NIK', 'Nama Penerima', 'Alamat', 'Nomor HP', 'Kelompok Tani', 
                    'Bidang', 'Jenis Bantuan', 'Kuantitas', 'Tahun', 'Status',
                    'Link Dokumen', 'Latitude', 'Longitude', 'Keterangan', 'Tanggal Input'
                ]);

                // Data
                $i = 1;
                foreach ($data as $item) {
                    fputcsv($file, [
                        $i++,
                        $item->nik,
                        $item->nama,
                        $item->alamat,
                        $item->nomor_hape ?: '-',
                        $item->kelompok_tani ?: '-',
                        $item->bidang,
                        $item->jenis_bantuan,
                        $item->kuantitas ?: 1,
                        $item->tahun,
                        $item->status,
                        $item->link ?: '-',
                        $item->latitude ?: '-',
                        $item->longitude ?: '-',
                        $item->keterangan ?: '-',
                        $item->created_at->format('d/m/Y H:i')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->route('beneficiaries.index')
                             ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    // Method lainnya yang sudah ada (import, export, dll) tetap dipertahankan
    public function import(Request $request)
    {
        // Kode import yang sudah ada
    }

    public function export(Request $request)
    {
        // Kode export yang sudah ada
    }

    public function showImport()
    {
        return view('beneficiaries.import');
    }

    public function downloadTemplate()
    {
        // Kode download template yang sudah ada
    }

    public function publicIndex(Request $request)
    {
        $tahun = $request->get('tahun', '');
        $jenis = $request->get('jenis', '');
        
        $query = Beneficiary::where('status', 'diterima'); // Status benar 'diterima'

        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        
        if ($jenis) {
            $query->where('jenis_bantuan', $jenis);
        }

        $data = $query->orderBy('created_at', 'desc') // Default sorting terbaru
                     ->orderBy('nama')
                     ->paginate(20);

        // Untuk dropdown filter
        $tahun_list = Beneficiary::distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $jenis_list = Beneficiary::distinct()->orderBy('jenis_bantuan')->pluck('jenis_bantuan');
        
        // Hitung statistik
        $total_penerima = Beneficiary::where('status', 'diterima')->count();
        $total_bantuan = Beneficiary::where('status', 'diterima')->distinct('jenis_bantuan')->count();

        return view('public.index', compact(
            'data', 'tahun_list', 'jenis_list', 'tahun', 'jenis',
            'total_penerima', 'total_bantuan'
        ));
    }

    /**
     * Menampilkan peta bantuan untuk public (tanpa login)
     */
    public function publicMap(Request $request)
    {
        $tahun = $request->get('tahun', '');
        $jenis = $request->get('jenis', '');
        
        $query = Beneficiary::whereNotNull('latitude')
                           ->whereNotNull('longitude')
                           ->where('status', 'diterima');

        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        
        if ($jenis) {
            $query->where('jenis_bantuan', $jenis);
        }

        $beneficiaries = $query->orderBy('created_at', 'desc') // Default sorting terbaru
                              ->orderBy('nama')
                              ->get();

        // Untuk dropdown filter
        $tahun_list = Beneficiary::distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $jenis_list = Beneficiary::distinct()->orderBy('jenis_bantuan')->pluck('jenis_bantuan');

        return view('public.map', compact(
            'beneficiaries', 'tahun_list', 'jenis_list', 'tahun', 'jenis'
        ));
    }

    /**
     * API untuk mendapatkan data beneficiaries dalam format JSON (untuk peta)
     */
    public function getBeneficiariesJson(Request $request)
    {
        $tahun = $request->get('tahun', '');
        $jenis = $request->get('jenis', '');
        
        $query = Beneficiary::where('status', 'diterima'); // Status benar 'diterima'

        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        
        if ($jenis) {
            $query->where('jenis_bantuan', $jenis);
        }

        $beneficiaries = $query->orderBy('created_at', 'desc') // Default sorting terbaru
                              ->get()
                              ->map(function($item) {
            $bantuan = \App\Models\Bantuan::where('nama_bantuan', $item->jenis_bantuan)->first();
            
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'jenis_bantuan' => $item->jenis_bantuan,
                'kuantitas' => $item->kuantitas,
                'satuan' => $bantuan ? $bantuan->satuan : '-',
                'tahun' => $item->tahun,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'alamat' => $item->alamat,
                'nomor_hape' => $item->nomor_hape,
                'kelompok_tani' => $item->kelompok_tani,
                'status' => $item->status,
                'created_at' => $item->created_at->format('d/m/Y'),
            ];
        });

        return response()->json($beneficiaries);
    }

    public function exportExcel(Request $request)
    {
        try {
            $tahun = $request->get('tahun', '');
            $jenis = $request->get('jenis', '');
            $bidang = $request->get('bidang', '');
            $sort = $request->get('sort', 'newest');
            
            $query = Beneficiary::query();
            
            if (auth()->user()->role === 'user') {
                $query->where('bidang', auth()->user()->bidang);
            } elseif ($bidang) {
                $query->where('bidang', $bidang);
            }
            
            if ($tahun) {
                $query->where('tahun', $tahun);
            }
            
            if ($jenis) {
                $query->where('jenis_bantuan', $jenis);
            }

            // Apply sorting yang sama dengan index
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('nama', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('nama', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $data = $query->get();

            $filename = 'data_penerima_bantuan';
            if ($tahun) $filename .= '_' . $tahun;
            if ($jenis) $filename .= '_' . str_replace(' ', '_', $jenis);
            $filename .= '_' . date('Y-m-d_H-i-s') . '.xlsx';

            // Generate Excel file (sebenarnya CSV dengan header Excel)
            return $this->generateSimpleExcel($data, $filename);

        } catch (\Exception $e) {
            return redirect()->route('beneficiaries.index')
                             ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Generate simple Excel file using CSV format with Excel headers
     */
    private function generateSimpleExcel($data, $filename)
    {
        // Create output
        $output = '';
        
        // Add BOM for UTF-8
        $output .= "\xEF\xBB\xBF";
        
        // Add headers
        $headers = [
            'No', 'NIK', 'Nama Penerima', 'Alamat', 'Nomor HP', 'Kelompok Tani',
            'Bidang', 'Jenis Bantuan', 'Kuantitas', 'Tahun', 'Status',
            'Link Dokumen', 'Latitude', 'Longitude', 'Keterangan', 'Tanggal Input', 'Terakhir Update'
        ];
        
        $output .= $this->arrayToCsv($headers);
        
        // Add data rows
        foreach ($data as $index => $item) {
            $row = [
                $index + 1,
                $this->escapeCsv($item->nik),
                $this->escapeCsv($item->nama),
                $this->escapeCsv($item->alamat),
                $this->escapeCsv($item->nomor_hape ?: '-'),
                $this->escapeCsv($item->kelompok_tani ?: '-'),
                $this->escapeCsv($item->bidang),
                $this->escapeCsv($item->jenis_bantuan),
                $item->kuantitas ?: 1,
                $item->tahun,
                $this->getStatusText($item->status),
                $this->escapeCsv($item->link ?: '-'),
                $item->latitude ?: '-',
                $item->longitude ?: '-',
                $this->escapeCsv($item->keterangan ?: '-'),
                $item->created_at->format('d/m/Y H:i'),
                $item->updated_at->format('d/m/Y H:i')
            ];
            
            $output .= $this->arrayToCsv($row);
        }

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($output),
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'Pragma' => 'public'
        ];

        return response($output, 200, $headers);
    }

    /**
     * Convert array to CSV line
     */
    private function arrayToCsv($array)
    {
        return implode(',', $array) . "\n";
    }

    /**
     * Escape CSV values
     */
    private function escapeCsv($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
        
        // Escape quotes and wrap in quotes if contains comma, quote, or newline
        if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
            $value = '"' . str_replace('"', '""', $value) . '"';
        }
        
        return $value;
    }

    /**
     * Get status text for export
     */
    private function getStatusText($status)
    {
        $statuses = [
            'terdaftar' => 'Terdaftar',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai'
        ];
        
        return $statuses[$status] ?? $status;
    }

    /**
     * Export data ke Excel (format HTML table)
     */
    public function exportExcelHtml(Request $request)
    {
        try {
            $tahun = $request->get('tahun', '');
            $jenis = $request->get('jenis', '');
            $bidang = $request->get('bidang', '');
            $sort = $request->get('sort', 'newest');
            
            $query = Beneficiary::query();
            
            if (auth()->user()->role === 'user') {
                $query->where('bidang', auth()->user()->bidang);
            } elseif ($bidang) {
                $query->where('bidang', $bidang);
            }
            
            if ($tahun) {
                $query->where('tahun', $tahun);
            }
            
            if ($jenis) {
                $query->where('jenis_bantuan', $jenis);
            }

            // Apply sorting yang sama dengan index
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('nama', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('nama', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $data = $query->get();

            $filename = 'data_penerima_bantuan';
            if ($tahun) $filename .= '_' . $tahun;
            if ($jenis) $filename .= '_' . str_replace(' ', '_', $jenis);
            $filename .= '_' . date('Y-m-d_H-i-s') . '.xls';

            // Generate HTML table for Excel
            $html = $this->generateHtmlTable($data);

            $headers = [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => strlen($html),
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
                'Pragma' => 'public'
            ];

            return response($html, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->route('beneficiaries.index')
                             ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Generate HTML table for Excel export
     */
    private function generateHtmlTable($data)
    {
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <title>Data Penerima Bantuan</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #4CAF50; color: white; font-weight: bold; padding: 8px; border: 1px solid #ddd; }
        td { padding: 8px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Data Penerima Bantuan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama Penerima</th>
                <th>Alamat</th>
                <th>Nomor HP</th>
                <th>Kelompok Tani</th>
                <th>Bidang</th>
                <th>Jenis Bantuan</th>
                <th>Kuantitas</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Link Dokumen</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Keterangan</th>
                <th>Tanggal Input</th>
                <th>Terakhir Update</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($data as $index => $item) {
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($item->nik) . '</td>
                <td>' . htmlspecialchars($item->nama) . '</td>
                <td>' . htmlspecialchars($item->alamat) . '</td>
                <td>' . htmlspecialchars($item->nomor_hape ?: '-') . '</td>
                <td>' . htmlspecialchars($item->kelompok_tani ?: '-') . '</td>
                <td>' . htmlspecialchars($item->bidang) . '</td>
                <td>' . htmlspecialchars($item->jenis_bantuan) . '</td>
                <td>' . ($item->kuantitas ?: 1) . '</td>
                <td>' . $item->tahun . '</td>
                <td>' . $this->getStatusText($item->status) . '</td>
                <td>' . htmlspecialchars($item->link ?: '-') . '</td>
                <td>' . ($item->latitude ?: '-') . '</td>
                <td>' . ($item->longitude ?: '-') . '</td>
                <td>' . htmlspecialchars($item->keterangan ?: '-') . '</td>
                <td>' . $item->created_at->format('d/m/Y H:i') . '</td>
                <td>' . $item->updated_at->format('d/m/Y H:i') . '</td>
            </tr>';
        }

        $html .= '</tbody>
    </table>
    <br>
    <div>Total Data: ' . count($data) . '</div>
    <div>Diexport pada: ' . date('d/m/Y H:i') . '</div>
    <div>Oleh: ' . auth()->user()->name . '</div>
</body>
</html>';

        return $html;
    }
}