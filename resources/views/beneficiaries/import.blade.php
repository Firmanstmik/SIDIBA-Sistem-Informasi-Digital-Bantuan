@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">📤 Import Data Penerima dari Excel</h3>
    <p class="text-gray-600">Import data penerima bantuan dari file Excel</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="bg-blue-600 text-white px-6 py-4">
        <h5 class="font-semibold">Upload File Excel</h5>
    </div>

    <div class="p-6">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Error Details -->
        @if(session('import_errors'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h6 class="font-medium text-yellow-800 mb-2">Detail Error:</h6>
            <ul class="list-disc list-inside space-y-1">
                @foreach(session('import_errors') as $error)
                <li class="text-yellow-700">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Upload Form -->
        <form method="post" action="{{ route('beneficiaries.import.post') }}" enctype="multipart/form-data" class="mb-8">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Pilih File Excel</label>
                <input type="file" class="w-full border border-gray-300 rounded-lg px-3 py-2" name="file" accept=".xlsx,.xls" required>
                <div class="text-gray-500 text-sm mt-1">
                    Format yang didukung: .xlsx, .xls (Max 5MB)
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium">
                    📤 Import Data
                </button>
                <a href="{{ route('beneficiaries.downloadTemplate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium text-center">
                    ⬇️ Download Template
                </a>
            </div>
        </form>

        <!-- Instructions -->
        <div class="mb-6">
            <h6 class="font-medium text-gray-700 mb-3">📋 Petunjuk Import:</h6>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700 border-b">Kolom</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700 border-b">Keterangan</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700 border-b">Wajib</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700 border-b">Contoh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">nik</td>
                            <td class="px-4 py-2 border-b">Nomor Induk Kependudukan</td>
                            <td class="px-4 py-2 border-b">✅</td>
                            <td class="px-4 py-2 border-b font-mono text-sm">1271010101010001</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">nama</td>
                            <td class="px-4 py-2 border-b">Nama lengkap penerima</td>
                            <td class="px-4 py-2 border-b">✅</td>
                            <td class="px-4 py-2 border-b">Ahmad Yudi</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">alamat</td>
                            <td class="px-4 py-2 border-b">Alamat lengkap</td>
                            <td class="px-4 py-2 border-b">✅</td>
                            <td class="px-4 py-2 border-b">Jl. Merdeka No.1, Praya</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">kelompok_tani</td>
                            <td class="px-4 py-2 border-b">Nama kelompok tani</td>
                            <td class="px-4 py-2 border-b">❌</td>
                            <td class="px-4 py-2 border-b">Tani Maju</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">bidang</td>
                            <td class="px-4 py-2 border-b">Bidang bantuan</td>
                            <td class="px-4 py-2 border-b">❌*</td>
                            <td class="px-4 py-2 border-b">Bidang Produksi Pertanian</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">jenis_bantuan</td>
                            <td class="px-4 py-2 border-b">Jenis bantuan yang diberikan</td>
                            <td class="px-4 py-2 border-b">✅</td>
                            <td class="px-4 py-2 border-b">Bibit Padi Unggul</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">tahun</td>
                            <td class="px-4 py-2 border-b">Tahun pemberian bantuan</td>
                            <td class="px-4 py-2 border-b">✅</td>
                            <td class="px-4 py-2 border-b">2024</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">kuantitas</td>
                            <td class="px-4 py-2 border-b">Jumlah bantuan</td>
                            <td class="px-4 py-2 border-b">❌</td>
                            <td class="px-4 py-2 border-b">5</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">status</td>
                            <td class="px-4 py-2 border-b">Status penerima</td>
                            <td class="px-4 py-2 border-b">❌</td>
                            <td class="px-4 py-2 border-b">terdaftar</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">link</td>
                            <td class="px-4 py-2 border-b">Link dokumen</td>
                            <td class="px-4 py-2 border-b">❌</td>
                            <td class="px-4 py-2 border-b">https://example.com/doc1</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">latitude</td>
                            <td class="px-4 py-2 border-b">Koordinat latitude</td>
                            <td class="px-4 py-2 border-b">❌</td>
                            <td class="px-4 py-2 border-b font-mono text-sm">-8.705</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-b font-mono text-sm">longitude</td>
                            <td class="px-4 py-2 border-b">Koordinat longitude</td>
                            <td class="px-4 py-2 border-b">❌</td>
                            <td class="px-4 py-2 border-b font-mono text-sm">116.271</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-gray-500 text-sm mt-2">
                * Untuk user biasa, bidang akan otomatis diisi sesuai login
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h6 class="font-medium text-blue-800 mb-2">💡 Tips:</h6>
            <ul class="list-disc list-inside space-y-1 text-blue-700">
                <li>Gunakan template yang disediakan untuk memastikan format benar</li>
                <li>Pastikan NIK, jenis bantuan, dan tahun unik untuk menghindari duplikasi</li>
                <li>Data dengan NIK yang sama dan bantuan berbeda tetap bisa diimport</li>
                <li>Maximal 1000 data per import</li>
            </ul>
        </div>
    </div>

    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
        <a href="{{ route('beneficiaries.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
            ← Kembali ke Data Penerima
        </a>
    </div>
</div>
@endsection