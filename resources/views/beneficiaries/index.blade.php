@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">👥 Data Penerima Bantuan</h3>
        <p class="text-gray-600">Manajemen data penerima bantuan pertanian</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('beneficiaries.import') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            📁 Import Excel
        </a>
        <div class="relative group">
            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center">
                ⬇️ Export Data
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <a href="{{ route('beneficiaries.export.csv') }}?tahun={{ $tahun }}&jenis={{ $jenis }}&bidang={{ $bidang }}" 
                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg">
                    📄 Export CSV
                </a>
                <a href="{{ route('beneficiaries.export.excel') }}?tahun={{ $tahun }}&jenis={{ $jenis }}&bidang={{ $bidang }}" 
                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    📊 Export Excel (CSV)
                </a>
                <a href="{{ route('beneficiaries.export.excel-html') }}?tahun={{ $tahun }}&jenis={{ $jenis }}&bidang={{ $bidang }}" 
                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-b-lg">
                    🏷️ Export Excel (HTML)
                </a>
            </div>
        </div>
        <a href="{{ route('beneficiaries.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
            ➕ Tambah Data
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <h6 class="font-medium text-gray-700">🔍 Filter Data</h6>
    </div>
    <div class="p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-{{ auth()->user()->role == 'admin' ? '5' : '4' }} gap-4">
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
                            <option value="{{ $tahun_item }}" {{ request('tahun') == $tahun_item ? 'selected' : '' }}>{{ $tahun_item }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Jenis Bantuan</label>
                    <select name="jenis" class="w-full border border-gray-300 rounded-lg px-3 py-2" onchange="this.form.submit()">
                        <option value="">Semua Jenis</option>
                        @foreach($jenis_list as $jenis_item)
                            <option value="{{ $jenis_item }}" {{ request('jenis') == $jenis_item ? 'selected' : '' }}>{{ $jenis_item }}</option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->role == 'admin')
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Bidang</label>
                    <select name="bidang" class="w-full border border-gray-300 rounded-lg px-3 py-2" onchange="this.form.submit()">
                        <option value="">Semua Bidang</option>
                        @foreach($bidang_list as $bidang_item)
                            <option value="{{ $bidang_item }}" {{ request('bidang') == $bidang_item ? 'selected' : '' }}>{{ $bidang_item }}</option>
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
                @endif
            </div>
            <div class="flex justify-end">
                <a href="{{ route('beneficiaries.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-green-600 text-white px-6 py-4">
        <div class="flex justify-between items-center">
            <h6 class="font-medium">📊 Daftar Penerima Bantuan</h6>
            <div class="text-sm bg-green-700 px-3 py-1 rounded-full">
                🔄 Data diurutkan: Terbaru → Terlama
            </div>
        </div>
    </div>
    
    @if($data->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">No</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">NIK</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Nomor HP</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Alamat</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Jenis Bantuan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Tahun</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Sumber Dana</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Keterangan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Tanggal Input</th>
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
                        @if($item->kelompok_tani)
                            <div class="text-sm text-gray-500">{{ $item->kelompok_tani }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($item->nomor_hape)
                            {{ $item->nomor_hape }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ Str::limit($item->alamat, 50) }}</td>
                    <td class="px-4 py-3">{{ $item->jenis_bantuan }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-gray-800 text-white text-xs font-medium px-2.5 py-0.5 rounded">{{ $item->tahun }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($item->sumber_dana)
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $item->sumber_dana }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($item->status == 'terdaftar')
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Terdaftar</span>
                        @elseif($item->status == 'diterima')
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Diterima</span>
                        @elseif($item->status == 'ditolak')
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ditolak</span>
                        @elseif($item->status == 'selesai')
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Selesai</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($item->keterangan)
                            <span class="text-orange-600" title="{{ $item->keterangan }}">
                                ⚠️ {{ Str::limit($item->keterangan, 30) }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        <div class="flex flex-col">
                            <span class="text-xs">Dibuat:</span>
                            <span>{{ $item->created_at->format('d/m/Y') }}</span>
                            <span class="text-xs">{{ $item->created_at->format('H:i') }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex space-x-2">
                            <a href="{{ route('beneficiaries.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                ✏️
                            </a>
                            <form action="{{ route('beneficiaries.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data {{ $item->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                    🗑️
                                </button>
                            </form>
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
        <h4 class="text-xl font-semibold text-gray-700 mb-2">Belum ada data</h4>
        <p class="text-gray-600 mb-4">Mulai tambah data penerima bantuan pertama Anda.</p>
        <a href="{{ route('beneficiaries.create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium">
            ➕ Tambah Data Pertama
        </a>
    </div>
    @endif
</div>

<!-- Sorting Options -->
<div class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
    <div class="flex items-center space-x-4">
        <span class="text-sm font-medium text-gray-700">Urutkan berdasarkan:</span>
        <div class="flex space-x-2">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" 
               class="px-3 py-1 text-sm rounded-lg {{ request('sort', 'newest') == 'newest' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                🔼 Terbaru
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" 
               class="px-3 py-1 text-sm rounded-lg {{ request('sort') == 'oldest' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                🔽 Terlama
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" 
               class="px-3 py-1 text-sm rounded-lg {{ request('sort') == 'name_asc' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                🔤 Nama A-Z
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}" 
               class="px-3 py-1 text-sm rounded-lg {{ request('sort') == 'name_desc' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                🔤 Nama Z-A
            </a>
        </div>
    </div>
</div>
@endsection