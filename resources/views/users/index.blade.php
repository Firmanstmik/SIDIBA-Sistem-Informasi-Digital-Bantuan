@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">👥 Kelola User</h3>
    <p class="text-gray-600">Management user sistem SIDIBA</p>
</div>

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Form Tambah User -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
        <h5 class="font-semibold text-blue-800">➕ Tambah User Baru</h5>
    </div>
    <form method="POST" action="{{ route('users.store') }}" class="p-6" id="userForm">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Username *</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Username" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Password *</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Password" required>
                <div class="text-xs text-gray-500 mt-1">Min. 3 karakter</div>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Ulangi password" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Nama *</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nama Lengkap" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">NIP</label>
                <input type="text" name="nip" value="{{ old('nip') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="NIP (opsional)">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Bidang *</label>
                <select name="bidang" id="bidangSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">Pilih Bidang</option>
                    <option value="Bidang Produksi Pertanian" {{ old('bidang') == 'Bidang Produksi Pertanian' ? 'selected' : '' }}>Bidang Produksi Pertanian</option>
                    <option value="Bidang Produksi Peternakan" {{ old('bidang') == 'Bidang Produksi Peternakan' ? 'selected' : '' }}>Bidang Produksi Peternakan</option>
                    <option value="Bidang Prasarana Pertanian" {{ old('bidang') == 'Bidang Prasarana Pertanian' ? 'selected' : '' }}>Bidang Prasarana Pertanian</option>
                    <option value="Bidang Agribisnis" {{ old('bidang') == 'Bidang Agribisnis' ? 'selected' : '' }}>Bidang Agribisnis</option>
                    <option value="Bidang Penyuluhan" {{ old('bidang') == 'Bidang Penyuluhan' ? 'selected' : '' }}>Bidang Penyuluhan</option>
                    <option value="Sekretariat" {{ old('bidang') == 'Sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                    <option value="lainnya">Lainnya (ketik sendiri)</option>
                </select>
                <input type="text" name="bidang_lainnya" id="bidangLainnya" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hidden" placeholder="Ketik bidang lainnya..." value="{{ old('bidang_lainnya') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    ➕ Tambah
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Daftar User -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-green-600 text-white px-6 py-4">
        <h6 class="font-medium">📋 Daftar User</h6>
    </div>

    @if($users->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Username</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">NIP</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Bidang</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $user->username }}</div>
                        <div class="text-xs text-gray-500">Role: {{ $user->role }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $user->nama }}</td>
                    <td class="px-4 py-3">{{ $user->nip ?: '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $user->bidang }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex space-x-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-yellow-600 hover:text-yellow-800 transition duration-200" title="Edit">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user {{ $user->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition duration-200" title="Hapus">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <div class="text-4xl mb-4">👥</div>
        <h4 class="text-xl font-semibold text-gray-700 mb-2">Belum ada user</h4>
        <p class="text-gray-600">Tambahkan user pertama menggunakan form di atas.</p>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bidangSelect = document.getElementById('bidangSelect');
    const bidangLainnya = document.getElementById('bidangLainnya');
    const userForm = document.getElementById('userForm');

    // Toggle input lainnya
    bidangSelect.addEventListener('change', function() {
        if (this.value === 'lainnya') {
            bidangLainnya.classList.remove('hidden');
            bidangLainnya.required = true;
        } else {
            bidangLainnya.classList.add('hidden');
            bidangLainnya.required = false;
            bidangLainnya.value = '';
        }
    });

    // Handle form submission
    userForm.addEventListener('submit', function(e) {
        if (bidangSelect.value === 'lainnya' && !bidangLainnya.value.trim()) {
            e.preventDefault();
            alert('Silakan ketik bidang lainnya');
            bidangLainnya.focus();
            return false;
        }
        
        // Jika pilihan "lainnya" dipilih, gunakan nilai dari input lainnya
        if (bidangSelect.value === 'lainnya') {
            // Buat input hidden untuk menyimpan nilai bidang yang dipilih
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'bidang';
            hiddenInput.value = bidangLainnya.value;
            userForm.appendChild(hiddenInput);
            
            // Nonaktifkan select dan input lainnya agar tidak ikut ter-submit
            bidangSelect.disabled = true;
            bidangLainnya.disabled = true;
        }

        // Validasi password match
        const password = document.querySelector('input[name="password"]').value;
        const passwordConfirmation = document.querySelector('input[name="password_confirmation"]').value;
        
        if (password !== passwordConfirmation) {
            e.preventDefault();
            alert('Password dan Konfirmasi Password tidak cocok!');
            return false;
        }
    });

    // Check if bidang lainnya should be shown on page load (from validation errors)
    @if(old('bidang') && !in_array(old('bidang'), ['Bidang Produksi Pertanian', 'Bidang Produksi Peternakan', 'Bidang Prasarana Pertanian', 'Bidang Agribisnis', 'Bidang Penyuluhan', 'Sekretariat']))
        bidangSelect.value = 'lainnya';
        bidangLainnya.classList.remove('hidden');
        bidangLainnya.value = '{{ old('bidang') }}';
        bidangLainnya.required = true;
    @endif
});
</script>

<style>
.hidden {
    display: none;
}
</style>
@endsection