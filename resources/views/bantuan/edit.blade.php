@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-800">✏️ Edit Jenis Bantuan</h3>
        <p class="text-gray-600">Edit data {{ $bantuan->nama_bantuan }}</p>
    </div>
    
    <form method="POST" action="{{ route('bantuan.update', $bantuan->id) }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Nama Bantuan</label>
            <input type="text" name="nama_bantuan" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ $bantuan->nama_bantuan }}" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-medium mb-2">Bidang *</label>
            <select name="bidang" id="bidangSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                <option value="">Pilih Bidang</option>
                <option value="Bidang Produksi Pertanian" {{ $bantuan->bidang == 'Bidang Produksi Pertanian' ? 'selected' : '' }}>Bidang Produksi Pertanian</option>
                <option value="Bidang Produksi Peternakan" {{ $bantuan->bidang == 'Bidang Produksi Peternakan' ? 'selected' : '' }}>Bidang Produksi Peternakan</option>
                <option value="Bidang Prasarana Pertanian" {{ $bantuan->bidang == 'Bidang Prasarana Pertanian' ? 'selected' : '' }}>Bidang Prasarana Pertanian</option>
                <option value="Bidang Agribisnis" {{ $bantuan->bidang == 'Bidang Agribisnis' ? 'selected' : '' }}>Bidang Agribisnis</option>
                <option value="Bidang Penyuluhan" {{ $bantuan->bidang == 'Bidang Penyuluhan' ? 'selected' : '' }}>Bidang Penyuluhan</option>
                <option value="Sekretariat" {{ $bantuan->bidang == 'Sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                <option value="lainnya">Lainnya (ketik sendiri)</option>
            </select>
            <input type="text" name="bidang_lainnya" id="bidangLainnya" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-2 {{ !in_array($bantuan->bidang, ['Bidang Produksi Pertanian', 'Bidang Produksi Peternakan', 'Bidang Prasarana Pertanian', 'Bidang Agribisnis', 'Bidang Penyuluhan', 'Sekretariat']) ? '' : 'hidden' }}" placeholder="Ketik bidang lainnya..." value="{{ !in_array($bantuan->bidang, ['Bidang Produksi Pertanian', 'Bidang Produksi Peternakan', 'Bidang Prasarana Pertanian', 'Bidang Agribisnis', 'Bidang Penyuluhan', 'Sekretariat']) ? $bantuan->bidang : '' }}">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Satuan</label>
            <input type="text" name="satuan" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ $bantuan->satuan }}" required>
        </div>
        
        <div class="flex space-x-3">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                💾 Simpan Perubahan
            </button>
            <a href="{{ route('bantuan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                ❌ Batal
            </a>
        </div>
    </form>
</div>
@endsection