<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penerima Bantuan - SIDIBA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-green-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold">🌾 SIDIBA</h1>
                    <p class="text-green-100">Sistem Informasi Bantuan Dinas Pertanian</p>
                </div>
                <nav class="flex space-x-4">
                    <a href="{{ route('home') }}" class="bg-green-700 px-4 py-2 rounded-lg font-medium">
                        📊 Data Penerima
                    <!-- Ganti link login menjadi: -->
<a href="/login" class="bg-white text-green-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition duration-200">
    🔐 Login
</a>

<!-- Ganti link peta menjadi: -->
<a href="/public/map" class="bg-green-500 hover:bg-green-700 px-4 py-2 rounded-lg font-medium transition duration-200">
    🗺️ Peta Bantuan
</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-green-500 to-green-600 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Data Penerima Bantuan Pertanian</h2>
            <p class="text-xl text-green-100 mb-6">Transparansi dan Akuntabilitas Distribusi Bantuan</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <div class="bg-green-400 bg-opacity-20 p-4 rounded-lg">
                    <div class="text-3xl font-bold">{{ $total_penerima }}</div>
                    <div class="text-green-100">Total Penerima</div>
                </div>
                <div class="bg-green-400 bg-opacity-20 p-4 rounded-lg">
                    <div class="text-3xl font-bold">{{ $total_bantuan }}</div>
                    <div class="text-green-100">Jenis Bantuan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="bg-white shadow-sm py-6">
        <div class="container mx-auto px-4">
            <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Tahun</label>
                    <select name="tahun" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Semua Tahun</option>
                        @foreach($tahun_list as $tahun_item)
                            <option value="{{ $tahun_item }}" {{ $tahun == $tahun_item ? 'selected' : '' }}>
                                {{ $tahun_item }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Jenis Bantuan</label>
                    <select name="jenis" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Semua Jenis</option>
                        @foreach($jenis_list as $jenis_item)
                            <option value="{{ $jenis_item }}" {{ $jenis == $jenis_item ? 'selected' : '' }}>
                                {{ $jenis_item }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                        🔍 Filter Data
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Data Table -->
    <section class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-green-600 text-white px-6 py-4">
                <h3 class="text-xl font-semibold">📋 Daftar Penerima Bantuan</h3>
                <p class="text-green-100">Data transparan penerima bantuan pertanian</p>
            </div>

            @if($data->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Nama Penerima</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Jenis Bantuan</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Kuantitas</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Satuan</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Tahun</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($data as $item)
                        @php
                            $bantuan = \App\Models\Bantuan::where('nama_bantuan', $item->jenis_bantuan)->first();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800">{{ $item->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $item->alamat }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $item->jenis_bantuan }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $item->kuantitas ?: 1 }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $bantuan ? $bantuan->satuan : '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $item->tahun }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $data->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📭</div>
                <h4 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada data</h4>
                <p class="text-gray-600">Tidak ditemukan data penerima bantuan dengan filter yang dipilih.</p>
            </div>
            @endif
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="text-blue-600 text-2xl mr-3">💡</div>
                <div>
                    <h4 class="font-semibold text-blue-800">Informasi</h4>
                    <p class="text-blue-700 text-sm">Data yang ditampilkan hanya penerima bantuan dengan status "Diterima" dan memiliki koordinat lokasi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <div class="mb-4">
                <h3 class="text-2xl font-bold mb-2">🌾 SIDIBA</h3>
                <p class="text-gray-400">Sistem Informasi Bantuan Dinas Pertanian</p>
            </div>
            <div class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} Dinas Pertanian. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>