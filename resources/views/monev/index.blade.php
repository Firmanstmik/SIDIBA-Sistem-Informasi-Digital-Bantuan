@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">📊 Monitoring & Evaluasi Bantuan</h3>
    <p class="text-gray-600">Manajemen monitoring dan evaluasi bantuan</p>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <h6 class="font-medium text-gray-700">🔍 Filter Data</h6>
    </div>
    <div class="p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">🔍 Cari NIK</label>
                    <input type="text" name="search_nik" value="{{ request('search_nik') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                           placeholder="Masukkan NIK...">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Tahun</label>
                    <select name="tahun" class="w-full border border-gray-300 rounded-lg px-3 py-2" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($tahun_list as $tahun_item)
                            <option value="{{ $tahun_item }}" {{ $tahun == $tahun_item ? 'selected' : '' }}>{{ $tahun_item }}</option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->role == 'admin')
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Bidang</label>
                    <select name="bidang" class="w-full border border-gray-300 rounded-lg px-3 py-2" onchange="this.form.submit()">
                        <option value="">Semua Bidang</option>
                        @foreach($bidang_list as $bidang_item)
                            <option value="{{ $bidang_item }}" {{ $bidang == $bidang_item ? 'selected' : '' }}>{{ $bidang_item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                        🔍 Cari
                    </button>
                </div>
                @else
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                        🔍 Cari
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('monev.report') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-center">
                        📈 Laporan
                    </a>
                </div>
                @endif
            </div>
            <div class="flex justify-end space-x-2">
                <a href="{{ route('monev.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-green-600 text-white px-6 py-4">
        <h6 class="font-medium">📋 Daftar Penerima untuk Monev</h6>
    </div>
    
    @if($data->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">#</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">NIK</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Nama Penerima</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Nomor HP</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Lokasi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Bidang</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Jenis Bantuan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Tahun</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Jumlah Monev</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Terakhir Monev</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($data as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                    <td class="px-4 py-3 font-mono text-sm">{{ $item->nik }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $item->nama }}</div>
                        <div class="text-sm text-gray-500">{{ $item->kelompok_tani ?: '-' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if($item->nomor_hape)
                            <a href="https://wa.me/62{{ substr($item->nomor_hape, 1) }}?text=Halo%20{{ urlencode($item->nama) }}%2C%20kami%20dari%20tim%20monitoring%20ingin%20berkomunikasi%20mengenai%20bantuan%20{{ urlencode($item->jenis_bantuan) }}"
                               target="_blank"
                               class="inline-flex items-center text-green-600 hover:text-green-800 font-medium text-sm"
                               title="Chat via WhatsApp">
                                📱 {{ $item->nomor_hape }}
                            </a>
                        @else
                            <span class="text-gray-500 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($item->latitude && $item->longitude)
                            <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}"
                               target="_blank"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium text-sm"
                               title="Lihat di Google Maps">
                                📍 Maps
                            </a>
                        @else
                            <span class="text-gray-500 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $item->bidang }}</span>
                    </td>
                    <td class="px-4 py-3">{{ $item->jenis_bantuan }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-gray-800 text-white text-xs font-medium px-2.5 py-0.5 rounded">{{ $item->tahun }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="{{ $item->monevs_count > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} text-xs font-medium px-2.5 py-0.5 rounded">
                            {{ $item->monevs_count }}x
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($item->monevs_max_tanggal_monev)
                            {{ \Carbon\Carbon::parse($item->monevs_max_tanggal_monev)->format('d/m/Y') }}
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex space-x-2">
                            <a href="{{ route('monev.create', $item->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded text-sm" title="Tambah Monev">
                                ➕
                            </a>
                            @if($item->monevs_count > 0)
                            <a href="{{ route('monev.history', $item->id) }}" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded text-sm" title="Riwayat Monev">
                                📋
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-6 py-4 border-t">
        {{ $data->links() }}
    </div>
    @else
    <div class="text-center py-8">
        <div class="text-4xl mb-4">📭</div>
        <h4 class="text-xl font-semibold text-gray-700 mb-2">Belum ada data penerima</h4>
        <p class="text-gray-600">Tambahkan data penerima bantuan terlebih dahulu di menu Data Bantuan.</p>
        <a href="{{ route('beneficiaries.index') }}" class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium">
            📋 Ke Data Bantuan
        </a>
    </div>
    @endif
</div>
@endsection