@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">📋 Riwayat Monitoring & Evaluasi</h3>
            <p class="text-gray-600">Riwayat monev untuk {{ $beneficiary->nama }}</p>
        </div>
        <div class="flex space-x-2">
            <!-- Dropdown untuk download -->
            <div class="relative group">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center">
                    ⬇️ Download Laporan
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                    <a href="{{ route('monev.download-report', $beneficiary->id) }}" 
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg border-b border-gray-100">
                        📄 Format HTML
                    </a>
                    <a href="{{ route('monev.export-excel-individual', $beneficiary->id) }}" 
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-b-lg">
                        📊 Format Excel
                    </a>
                </div>
            </div>
            <a href="{{ route('monev.create', $beneficiary->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center">
                ➕ Tambah Monev
            </a>
        </div>
    </div>
</div>

<!-- Info Penerima -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
    <h5 class="font-semibold text-blue-800 mb-3">👤 Data Penerima</h5>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div><strong>Nama:</strong> {{ $beneficiary->nama }}</div>
        <div><strong>NIK:</strong> {{ $beneficiary->nik }}</div>
        <div>
            <strong>Nomor HP:</strong> 
            @if($beneficiary->nomor_hape)
                <a href="https://wa.me/62{{ substr($beneficiary->nomor_hape, 1) }}?text=Halo%20{{ urlencode($beneficiary->nama) }}%2C%20kami%20dari%20tim%20monitoring%20ingin%20berkomunikasi%20mengenai%20bantuan%20{{ urlencode($beneficiary->jenis_bantuan) }}"
                   target="_blank"
                   class="text-green-600 hover:text-green-800 font-medium"
                   title="Chat via WhatsApp">
                    📱 {{ $beneficiary->nomor_hape }}
                </a>
            @else
                <span class="text-gray-500">-</span>
            @endif
        </div>
        <div><strong>Bidang:</strong> {{ $beneficiary->bidang }}</div>
        <div><strong>Jenis Bantuan:</strong> {{ $beneficiary->jenis_bantuan }}</div>
        <div><strong>Tahun:</strong> {{ $beneficiary->tahun }}</div>
        <div class="md:col-span-3">
            <strong>Lokasi:</strong> 
            @if($beneficiary->latitude && $beneficiary->longitude)
                <a href="https://www.google.com/maps?q={{ $beneficiary->latitude }},{{ $beneficiary->longitude }}"
                   target="_blank"
                   class="text-blue-600 hover:text-blue-800 font-medium"
                   title="Lihat di Google Maps">
                    📍 {{ $beneficiary->alamat }}
                </a>
            @else
                <span class="text-gray-500">{{ $beneficiary->alamat }}</span>
            @endif
        </div>
        <div><strong>Total Monev:</strong> {{ $history->count() }} kali</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-green-600 text-white px-6 py-4">
        <h6 class="font-medium">📊 Riwayat Monev</h6>
    </div>

    @if($history->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Tanggal</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Pelaksana</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Hasil Evaluasi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Rekomendasi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Dokumentasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($history as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal_monev)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $item->pelaksana }}</td>
                    <td class="px-4 py-3">
                        <div class="max-w-xs truncate" title="{{ $item->hasil_evaluasi }}">
                            {{ Str::limit($item->hasil_evaluasi, 50) }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @if($item->rekomendasi)
                            <span class="
                                @if($item->rekomendasi == 'Lanjut') bg-green-100 text-green-800
                                @elseif($item->rekomendasi == 'Perbaikan') bg-yellow-100 text-yellow-800
                                @elseif($item->rekomendasi == 'Stop') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800
                                @endif
                                text-xs font-medium px-2.5 py-0.5 rounded
                            ">
                                {{ $item->rekomendasi }}
                            </span>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="
                            @if($item->status == 'selesai') bg-green-100 text-green-800
                            @elseif($item->status == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif
                            text-xs font-medium px-2.5 py-0.5 rounded
                        ">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($item->dokumentasi)
                            <a href="{{ route('monev.document', $item->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium"
                               target="_blank">
                                📎 Lihat
                            </a>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <div class="text-4xl mb-4">📭</div>
        <h4 class="text-xl font-semibold text-gray-700 mb-2">Belum ada data monev</h4>
        <p class="text-gray-600">Tambahkan data monev pertama untuk penerima ini.</p>
        <a href="{{ route('monev.create', $beneficiary->id) }}" class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium">
            ➕ Tambah Monev
        </a>
    </div>
    @endif
</div>

<div class="mt-4">
    <a href="{{ route('monev.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
        ← Kembali ke Daftar Monev
    </a>
</div>

<style>
    .group:hover .group-hover\:visible {
        visibility: visible;
    }
    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }
</style>
@endsection