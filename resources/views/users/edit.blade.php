@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-800">✏️ Edit User</h3>
        <p class="text-gray-600">Edit data user {{ $user->nama }}</p>
    </div>
    
    <form method="POST" action="{{ route('users.update', $user->id) }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')
        
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Username *</label>
            <input type="text" name="username" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('username', $user->username) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Password Baru</label>
            <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Kosongkan jika tidak ingin mengubah password">
            <div class="text-sm text-gray-500 mt-1">Minimal 3 karakter</div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Ulangi password baru">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Nama *</label>
            <input type="text" name="nama" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('nama', $user->nama) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">NIP</label>
            <input type="text" name="nip" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('nip', $user->nip) }}" placeholder="Opsional">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Bidang *</label>
            <select name="bidang" id="bidangSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <option value="">Pilih Bidang</option>
                <option value="Bidang Produksi Pertanian" {{ old('bidang', $user->bidang) == 'Bidang Produksi Pertanian' ? 'selected' : '' }}>Bidang Produksi Pertanian</option>
                <option value="Bidang Produksi Peternakan" {{ old('bidang', $user->bidang) == 'Bidang Produksi Peternakan' ? 'selected' : '' }}>Bidang Produksi Peternakan</option>
                <option value="Bidang Prasarana Pertanian" {{ old('bidang', $user->bidang) == 'Bidang Prasarana Pertanian' ? 'selected' : '' }}>Bidang Prasarana Pertanian</option>
                <option value="Bidang Agribisnis" {{ old('bidang', $user->bidang) == 'Bidang Agribisnis' ? 'selected' : '' }}>Bidang Agribisnis</option>
                <option value="Bidang Penyuluhan" {{ old('bidang', $user->bidang) == 'Bidang Penyuluhan' ? 'selected' : '' }}>Bidang Penyuluhan</option>
                <option value="Sekretariat" {{ old('bidang', $user->bidang) == 'Sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                <option value="lainnya">Lainnya (ketik sendiri)</option>
            </select>
            <input type="text" name="bidang_lainnya" id="bidangLainnya" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ !in_array($user->bidang, ['Bidang Produksi Pertanian', 'Bidang Produksi Peternakan', 'Bidang Prasarana Pertanian', 'Bidang Agribisnis', 'Bidang Penyuluhan', 'Sekretariat']) ? '' : 'hidden' }}" placeholder="Ketik bidang lainnya..." value="{{ !in_array($user->bidang, ['Bidang Produksi Pertanian', 'Bidang Produksi Peternakan', 'Bidang Prasarana Pertanian', 'Bidang Agribisnis', 'Bidang Penyuluhan', 'Sekretariat']) ? old('bidang_lainnya', $user->bidang) : '' }}">
        </div>
        
        <div class="flex space-x-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                💾 Simpan Perubahan
            </button>
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                ❌ Batal
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bidangSelect = document.getElementById('bidangSelect');
    const bidangLainnya = document.getElementById('bidangLainnya');
    
    bidangSelect.addEventListener('change', function() {
        if (this.value === 'lainnya') {
            bidangLainnya.classList.remove('hidden');
            bidangLainnya.required = true;
        } else {
            bidangLainnya.classList.add('hidden');
            bidangLainnya.required = false;
        }
    });
    
    // Handle form submission untuk bidang lainnya
    document.querySelector('form').addEventListener('submit', function(e) {
        if (bidangSelect.value === 'lainnya' && bidangLainnya.value) {
            // Set nilai bidang dari input lainnya
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'bidang';
            hiddenInput.value = bidangLainnya.value;
            this.appendChild(hiddenInput);
        }
    });
});
</script>
@endsection