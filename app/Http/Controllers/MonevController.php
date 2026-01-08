<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monev;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\Storage;

class MonevController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', '');
        $bidang = $request->get('bidang', '');
        $search_nik = $request->get('search_nik', '');
        
        // Query untuk data penerima dengan count monev
        $query = Beneficiary::withCount('monevs')
                          ->withMax('monevs', 'tanggal_monev');
        
        if (auth()->user()->role === 'user') {
            $query->where('bidang', auth()->user()->bidang);
        } elseif ($bidang) {
            $query->where('bidang', $bidang);
        }
        
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        
        // Pencarian by NIK
        if ($search_nik) {
            $query->where('nik', 'like', '%' . $search_nik . '%');
        }

        $data = $query->orderBy('tahun', 'desc')->paginate(10);
        
        $tahun_list = Beneficiary::distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $bidang_list = Beneficiary::distinct()->orderBy('bidang')->pluck('bidang');

        return view('monev.index', compact(
            'data', 'tahun_list', 'bidang_list', 'tahun', 'bidang', 'search_nik'
        ));
    }

    public function create(Beneficiary $beneficiary)
    {
        return view('monev.create', compact('beneficiary'));
    }

    public function store(Request $request, Beneficiary $beneficiary)
    {
        $validated = $request->validate([
            'tanggal_monev' => 'required|date',
            'pelaksana' => 'required|string|max:255',
            'hasil_evaluasi' => 'required|string',
            'rekomendasi' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
            'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $dokumentasiPath = null;
        if ($request->hasFile('dokumentasi')) {
            $dokumentasiPath = $request->file('dokumentasi')->store('monev-docs', 'public');
        }

        Monev::create([
            'beneficiary_id' => $beneficiary->id,
            'tanggal_monev' => $validated['tanggal_monev'],
            'pelaksana' => $validated['pelaksana'],
            'hasil_evaluasi' => $validated['hasil_evaluasi'],
            'rekomendasi' => $validated['rekomendasi'],
            'status' => $validated['status'],
            'dokumentasi' => $dokumentasiPath,
        ]);

        return redirect()->route('monev.index')
                         ->with('success', 'Data monev berhasil disimpan!');
    }

    public function history(Beneficiary $beneficiary)
    {
        $history = Monev::where('beneficiary_id', $beneficiary->id)
                       ->orderBy('tanggal_monev', 'desc')
                       ->get();

        return view('monev.history', compact('history', 'beneficiary'));
    }

    public function report(Request $request)
    {
        $tahun = $request->get('tahun', '');
        
        // Query untuk statistik per bidang
        $query = Beneficiary::withCount('monevs');
        
        if (auth()->user()->role === 'user') {
            $query->where('bidang', auth()->user()->bidang);
        }
        
        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        // Hitung statistik manual
        $stats = $query->get()
                      ->groupBy('bidang')
                      ->map(function($items, $bidang) {
                          $total_penerima = $items->count();
                          $sudah_dimonev = $items->filter(function($item) {
                              return $item->monevs_count > 0;
                          })->count();
                          
                          $persentase = $total_penerima > 0 
                              ? round(($sudah_dimonev / $total_penerima) * 100, 2)
                              : 0;
                              
                          return (object) [
                              'bidang' => $bidang,
                              'total_penerima' => $total_penerima,
                              'sudah_dimonev' => $sudah_dimonev,
                              'persentase' => $persentase
                          ];
                      })
                      ->values();

        $rekomendasi_stats = Monev::whereNotNull('rekomendasi')
                                 ->where('rekomendasi', '!=', '')
                                 ->selectRaw('rekomendasi, COUNT(*) as jumlah')
                                 ->groupBy('rekomendasi')
                                 ->orderBy('jumlah', 'desc')
                                 ->limit(10)
                                 ->get();

        $tahun_list = Beneficiary::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('monev.report', compact(
            'stats', 'rekomendasi_stats', 'tahun_list', 'tahun'
        ));
    }

    public function viewDocument(Monev $monev)
    {
        if (!$monev->dokumentasi || !Storage::disk('public')->exists($monev->dokumentasi)) {
            abort(404);
        }

        return Storage::disk('public')->download($monev->dokumentasi);
    }

    /**
     * Download laporan HTML per penerima
     */
    public function downloadReport($id)
    {
        try {
            $beneficiary = Beneficiary::with(['monevs' => function($query) {
                $query->orderBy('tanggal_monev', 'desc');
            }])->findOrFail($id);

            $filename = 'Laporan_Monev_' . str_replace(' ', '_', $beneficiary->nama) . '_' . date('Y-m-d') . '.html';

            // Render Blade view
            $html = view('reports.monev_individual', compact('beneficiary'))->render();

            $headers = [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return response($html, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->route('monev.history', $id)
                             ->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel laporan monev per penerima
     */
    public function exportExcelIndividual($id)
    {
        try {
            $beneficiary = Beneficiary::with(['monevs' => function($query) {
                $query->orderBy('tanggal_monev', 'desc');
            }])->findOrFail($id);

            $filename = 'Laporan_Monev_' . str_replace(' ', '_', $beneficiary->nama) . '_' . date('Y-m-d') . '.xlsx';

            return $this->generateExcelIndividual($beneficiary, $filename);

        } catch (\Exception $e) {
            return redirect()->route('monev.history', $id)
                             ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Generate Excel file untuk individu
     */
    private function generateExcelIndividual($beneficiary, $filename)
    {
        // Create output
        $output = '';
        
        // Add BOM for UTF-8
        $output .= "\xEF\xBB\xBF";
        
        // Header untuk informasi penerima
        $output .= "LAPORAN MONITORING & EVALUASI\n";
        $output .= "Penerima: " . $beneficiary->nama . "\n";
        $output .= "Dicetak pada: " . date('d/m/Y H:i') . "\n\n";
        
        // Informasi Penerima
        $output .= "INFORMASI PENERIMA\n";
        $output .= "Nama," . $this->escapeCsv($beneficiary->nama) . "\n";
        $output .= "NIK," . $this->escapeCsv($beneficiary->nik) . "\n";
        $output .= "Nomor HP," . $this->escapeCsv($beneficiary->nomor_hape ?: '-') . "\n";
        $output .= "Bidang," . $this->escapeCsv($beneficiary->bidang) . "\n";
        $output .= "Jenis Bantuan," . $this->escapeCsv($beneficiary->jenis_bantuan) . "\n";
        $output .= "Tahun," . $beneficiary->tahun . "\n";
        $output .= "Kelompok Tani," . $this->escapeCsv($beneficiary->kelompok_tani ?: '-') . "\n";
        $output .= "Alamat," . $this->escapeCsv($beneficiary->alamat) . "\n";
        $output .= "Total Monev," . $beneficiary->monevs->count() . "\n\n";
        
        // Header untuk data monev
        $headers = [
            'No', 'Tanggal Monev', 'Pelaksana', 'Hasil Evaluasi', 
            'Rekomendasi', 'Status', 'Dokumentasi'
        ];
        
        $output .= "RIWAYAT MONITORING & EVALUASI\n";
        $output .= $this->arrayToCsv($headers);
        
        // Data monev
        foreach ($beneficiary->monevs as $index => $monev) {
            $row = [
                $index + 1,
                \Carbon\Carbon::parse($monev->tanggal_monev)->format('d/m/Y'),
                $this->escapeCsv($monev->pelaksana),
                $this->escapeCsv($monev->hasil_evaluasi),
                $this->escapeCsv($monev->rekomendasi ?: '-'),
                $this->escapeCsv(ucfirst($monev->status)),
                $monev->dokumentasi ? 'Ada' : 'Tidak Ada'
            ];
            
            $output .= $this->arrayToCsv($row);
        }

        // Statistik rekomendasi
        $rekomendasiStats = $beneficiary->monevs->groupBy('rekomendasi')->map->count();
        if ($rekomendasiStats->count() > 0) {
            $output .= "\nSTATISTIK REKOMENDASI\n";
            $output .= "Rekomendasi,Jumlah,Persentase\n";
            
            foreach ($rekomendasiStats as $rekomendasi => $count) {
                $percentage = $beneficiary->monevs->count() > 0 ? 
                    round(($count / $beneficiary->monevs->count()) * 100, 1) : 0;
                
                $output .= $this->arrayToCsv([
                    $rekomendasi ?: 'Tidak Ada',
                    $count,
                    $percentage . '%'
                ]);
            }
        }

        // Ringkasan
        $output .= "\nRINGKASAN\n";
        $output .= "Total Monev," . $beneficiary->monevs->count() . "\n";
        $output .= "Monev Selesai," . $beneficiary->monevs->where('status', 'selesai')->count() . "\n";
        $output .= "Monev Pending," . $beneficiary->monevs->where('status', 'pending')->count() . "\n";
        $output .= "Rekomendasi Lanjut," . $beneficiary->monevs->where('rekomendasi', 'Lanjut')->count() . "\n";
        $output .= "Rekomendasi Perbaikan," . $beneficiary->monevs->where('rekomendasi', 'Perbaikan')->count() . "\n";

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
}