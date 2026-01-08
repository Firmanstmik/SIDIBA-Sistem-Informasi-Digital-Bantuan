<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\Bantuan;
use App\Models\User;
use App\Models\Monev;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', '');
        
        // Query data statistik berdasarkan role user
        $query = Beneficiary::query();
        
        if (auth()->user()->role === 'user') {
            // User biasa hanya lihat data bidangnya sendiri
            $query->where('bidang', auth()->user()->bidang);
        }
        // Admin bisa lihat semua data (tidak ada filter bidang)

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $data = $query->get();
        
        // Hitung statistik berdasarkan role
        $total_penerima = $data->count();
        $total_kelompok = $data->whereNotNull('kelompok_tani')
                              ->where('kelompok_tani', '!=', '')
                              ->groupBy('kelompok_tani')
                              ->count();
        
        // Hitung total bantuan berdasarkan role
        if (auth()->user()->role === 'user') {
            $total_bantuan = Bantuan::where('bidang', auth()->user()->bidang)->count();
        } else {
            $total_bantuan = Bantuan::count();
        }
        
        // Hitung monev berdasarkan role
        $sudah_dimonev = $data->filter(function($item) {
            return $item->monevs()->exists();
        })->count();
        
        $belum_dimonev = $total_penerima - $sudah_dimonev;
        
        // Persentase monev
        $persentase_monev = $total_penerima > 0 
            ? round(($sudah_dimonev / $total_penerima) * 100, 1)
            : 0;

        // Tahun ini berdasarkan role
        $currentYear = date('Y');
        $currentYearQuery = Beneficiary::query();
        
        if (auth()->user()->role === 'user') {
            $currentYearQuery->where('bidang', auth()->user()->bidang);
        }
        
        $total_penerima_tahun_ini = $currentYearQuery->where('tahun', $currentYear)->count();

        // Data untuk chart (distribusi per bidang - hanya untuk admin)
        $distribusi_bidang = [];
        if (auth()->user()->role === 'admin') {
            $distribusi_bidang = Beneficiary::select('bidang')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('bidang')
                ->orderBy('total', 'desc')
                ->get();
        }

        // Data untuk chart (distribusi per tahun)
        $distribusi_tahun = Beneficiary::query();
        
        if (auth()->user()->role === 'user') {
            $distribusi_tahun->where('bidang', auth()->user()->bidang);
        }
        
        $distribusi_tahun = $distribusi_tahun->select('tahun')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->limit(5)
            ->get();

        $tahun_list = Beneficiary::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('dashboard', compact(
            'total_penerima',
            'total_kelompok', 
            'total_bantuan',
            'sudah_dimonev',
            'belum_dimonev',
            'persentase_monev',
            'total_penerima_tahun_ini',
            'distribusi_bidang',
            'distribusi_tahun',
            'tahun_list',
            'tahun'
        ));
    }

    public function home(Request $request)
    {
        return view('home');
    }
}