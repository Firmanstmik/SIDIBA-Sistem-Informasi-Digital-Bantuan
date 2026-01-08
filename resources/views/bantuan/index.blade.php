@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">🎁 Kelola Jenis Bantuan</h3>
    <p class="text-gray-600">Management jenis-jenis bantuan yang tersedia</p>
</div>

<!-- Form Tambah Bantuan -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
        <h5 class="font-semibold text-blue-800">➕ Tambah Jenis Bantuan Baru</h5>
    </div>
    <form method="POST" action="{{ route('bantuan.store') }}" class="p-6" id="bantuanForm">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Nama Bantuan *</label>
                <input type="text" name="nama_bantuan" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Contoh: Bibit Padi Unggul" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Bidang *</label>
                <select name="bidang" id="bidangSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">Pilih Bidang</option>
                    <option value="Bidang Produksi Pertanian">Bidang Produksi Pertanian</option>
                    <option value="Bidang Produksi Peternakan">Bidang Produksi Peternakan</option>
                    <option value="Bidang Prasarana Pertanian">Bidang Prasarana Pertanian</option>
                    <option value="Bidang Agribisnis">Bidang Agribisnis</option>
                    <option value="Bidang Penyuluhan">Bidang Penyuluhan</option>
                    <option value="Sekretariat">Sekretariat</option>
                    <option value="lainnya">Lainnya (ketik sendiri)</option>
                </select>
                <input type="text" name="bidang_lainnya" id="bidangLainnya" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-2 hidden" placeholder="Ketik bidang lainnya...">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Satuan *</label>
                <input type="text" name="satuan" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Contoh: kg, liter, ekor" required>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    Tambah
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Daftar Jenis Bantuan -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-green-600 text-white px-6 py-4">
        <h6 class="font-medium">📋 Daftar Jenis Bantuan</h6>
    </div>

    @if($data->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Nama Bantuan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Bidang</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Satuan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($data as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $item->nama_bantuan }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $item->bidang }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $item->satuan }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex space-x-2">
                            <a href="{{ route('bantuan.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                ✏️
                            </a>
                            <form action="{{ route('bantuan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus jenis bantuan {{ $item->nama_bantuan }}?')">
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
    @else
    <div class="text-center py-8">
        <div class="text-4xl mb-4">📭</div>
        <h4 class="text-xl font-semibold text-gray-700 mb-2">Belum ada jenis bantuan</h4>
        <p class="text-gray-600">Tambahkan jenis bantuan pertama menggunakan form di atas.</p>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bidangSelect = document.getElementById('bidangSelect');
    const bidangLainnya = document.getElementById('bidangLainnya');
    const bantuanForm = document.getElementById('bantuanForm');

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
    bantuanForm.addEventListener('submit', function(e) {
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
            bantuanForm.appendChild(hiddenInput);
            
            // Nonaktifkan select dan input lainnya agar tidak ikut ter-submit
            bidangSelect.disabled = true;
            bidangLainnya.disabled = true;
        }
    });
});
</script>

<style>
.hidden {
    display: none;
}
</style>
@endsection