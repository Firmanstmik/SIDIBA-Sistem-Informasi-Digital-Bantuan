@extends('layouts.app')

@section('content')
<div class="mb-6">
  <h3 class="text-2xl font-bold text-gray-800">📊 Data Bantuan Publik - SIDIBA</h3>
  <p class="text-gray-600">Sistem Informasi Data Bantuan Dinas Pertanian Kabupaten Lombok Tengah</p>
</div>

<!-- Statistik Ringkas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
    <h5 class="text-lg font-semibold text-gray-700 mb-2">👥 Total Penerima</h5>
    <p class="text-4xl font-bold text-gray-900">0</p>
  </div>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
    <h5 class="text-lg font-semibold text-gray-700 mb-2">🌱 Kelompok Tani</h5>
    <p class="text-4xl font-bold text-gray-900">0</p>
  </div>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
    <h5 class="text-lg font-semibold text-gray-700 mb-2">🎁 Jenis Bantuan</h5>
    <p class="text-4xl font-bold text-gray-900">0</p>
  </div>
</div>

<!-- Info Login -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
    <p class="text-blue-700 mb-4">Untuk mengelola data, silakan login terlebih dahulu</p>
    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
        🔑 Login ke Sistem
    </a>
</div>
@endsection