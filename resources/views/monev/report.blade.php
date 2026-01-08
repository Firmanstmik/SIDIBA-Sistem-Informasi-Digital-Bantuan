@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">📈 Laporan Monitoring & Evaluasi</h3>
    <p class="text-gray-600">Statistik dan laporan hasil monev bantuan</p>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <h6 class="font-medium text-gray-700">🔍 Filter Laporan</h6>
    </div>
    <div class="p-6">
        <form method="GET" class="flex space-x-4">
            <div class="flex-1">
                <select name="tahun" class="w-full border border-gray-300 rounded-lg px-3 py-2" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @foreach($tahun_list as $tahun_item)
                    <option value="{{ $tahun_item }}" {{ $tahun == $tahun_item ? 'selected' : '' }}>{{ $tahun_item }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                Terapkan
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Statistik Per Bidang -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-green-600 text-white px-6 py-4">
                <h6 class="font-medium">📊 Statistik Monev per Bidang</h6>
            </div>
            <div class="p-6">
                @if($stats->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-700 border-b">Bidang</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700 border-b">Total Penerima</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700 border-b">Sudah Dimonev</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700 border-b">Persentase</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700 border-b">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($stats as $stat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 border-b font-medium">{{ $stat->bidang }}</td>
                                <td class="px-4 py-3 border-b text-center">{{ $stat->total_penerima }}</td>
                                <td class="px-4 py-3 border-b text-center">{{ $stat->sudah_dimonev }}</td>
                                <td class="px-4 py-3 border-b">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full 
                                                @if($stat->persentase >= 80) bg-green-600
                                                @elseif($stat->persentase >= 50) bg-yellow-500
                                                @else bg-red-600
                                                @endif" 
                                                style="width: {{ $stat->persentase }}%">
                                            </div>
                                        </div>
                                        <span class="text-sm font-medium">{{ $stat->persentase }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 border-b">
                                    @if($stat->persentase >= 80)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Baik</span>
                                    @elseif($stat->persentase >= 50)
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Cukup</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Perlu Perhatian</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">📊</div>
                    <p class="text-gray-600">Belum ada data statistik</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Rekomendasi Terbanyak -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h6 class="font-medium">🏆 Rekomendasi Terbanyak</h6>
            </div>
            <div class="p-6">
                @if($rekomendasi_stats->count() > 0)
                    @foreach($rekomendasi_stats as $rec)
                    <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-100 last:border-b-0 last:mb-0 last:pb-0">
                        <span class="text-sm text-gray-700">{{ $rec->rekomendasi }}</span>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $rec->jumlah }}</span>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-sm">Belum ada data rekomendasi</p>
                @endif
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-green-600 text-white px-6 py-4">
                <h6 class="font-medium">📈 Ringkasan</h6>
            </div>
            <div class="p-6">
                @php
                    $total_penerima = $stats->sum('total_penerima');
                    $total_monev = $stats->sum('sudah_dimonev');
                    $persentase_total = $total_penerima > 0 ? round(($total_monev / $total_penerima) * 100, 1) : 0;
                @endphp
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Penerima:</span>
                        <span class="font-semibold text-lg">{{ $total_penerima }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Sudah Dimonev:</span>
                        <span class="font-semibold text-lg text-green-600">{{ $total_monev }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Persentase:</span>
                        <span class="font-semibold text-lg 
                            @if($persentase_total >= 80) text-green-600
                            @elseif($persentase_total >= 50) text-yellow-600
                            @else text-red-600
                            @endif
                        ">
                            {{ $persentase_total }}%
                        </span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full 
                            @if($persentase_total >= 80) bg-green-600
                            @elseif($persentase_total >= 50) bg-yellow-500
                            @else bg-red-600
                            @endif" 
                            style="width: {{ $persentase_total }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('monev.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
        ← Kembali ke Monev
    </a>
</div>
@endsection