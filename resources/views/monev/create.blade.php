@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">➕ Tambah Data Monev</h3>
    <p class="text-gray-600">Tambah data monitoring dan evaluasi untuk {{ $beneficiary->nama }}</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Info Penerima -->
    <div class="bg-blue-50 border-b border-blue-200 p-6">
        <h5 class="font-semibold text-blue-800 mb-3">📋 Data Penerima</h5>
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
            <div><strong>Kelompok Tani:</strong> {{ $beneficiary->kelompok_tani ?: '-' }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('monev.store', $beneficiary->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Monev *</label>
                    <input type="date" name="tanggal_monev" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Pelaksana *</label>
                    <input type="text" name="pelaksana" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ auth()->user()->nama }}" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Hasil Evaluasi *</label>
                    <textarea name="hasil_evaluasi" class="w-full border border-gray-300 rounded-lg px-3 py-2" rows="4" placeholder="Hasil monitoring dan evaluasi..." required></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Rekomendasi</label>
                    <select name="rekomendasi" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Pilih Rekomendasi</option>
                        <option value="Lanjut">Lanjut Program</option>
                        <option value="Perbaikan">Perlu Perbaikan</option>
                        <option value="Evaluasi Ulang">Evaluasi Ulang</option>
                        <option value="Stop">Stop Program</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Status Monev *</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="selesai">Selesai</option>
                        <option value="pending">Pending</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Dokumentasi (Foto/Laporan)</label>
                    <input type="file" name="dokumentasi" class="w-full border border-gray-300 rounded-lg px-3 py-2" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                    <div class="text-gray-500 text-sm mt-1">Format: JPG, PNG, PDF, DOC (max 5MB)</div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t">
            <a href="{{ route('monev.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                ← Kembali
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                💾 Simpan Data Monev
            </button>
        </div>
    </form>
</div>
@endsection