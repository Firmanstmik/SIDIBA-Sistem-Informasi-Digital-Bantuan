@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">✏️ Edit Data Penerima</h3>
    <p class="text-gray-600">Perbarui data penerima bantuan - {{ $beneficiary->nama }}</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-200">
        <h5 class="font-semibold text-yellow-800">📝 Edit Data Penerima - {{ $beneficiary->nama }}</h5>
        <p class="text-yellow-700 text-sm mt-1">NIK: {{ $beneficiary->nik }} | Terakhir update: {{ $beneficiary->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    
    <form method="POST" action="{{ route('beneficiaries.update', $beneficiary->id) }}" class="p-6" id="beneficiaryForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Pribadi -->
            <div class="space-y-4">
                <h6 class="font-semibold text-gray-700 border-b pb-2">Data Pribadi</h6>
                
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">NIK *</label>
                    <input type="text" name="nik" value="{{ old('nik', $beneficiary->nik) }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    @error('nik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama', $beneficiary->nama) }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Alamat *</label>
                    <textarea name="alamat" class="w-full border border-gray-300 rounded-lg px-3 py-2" rows="3" required>{{ old('alamat', $beneficiary->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Nomor HP</label>
                    <input type="text" name="nomor_hape" value="{{ old('nomor_hape', $beneficiary->nomor_hape) }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                           placeholder="Contoh: 081234567890">
                    @error('nomor_hape')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Kelompok Tani</label>
                    <input type="text" name="kelompok_tani" value="{{ old('kelompok_tani', $beneficiary->kelompok_tani) }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                           placeholder="Nama kelompok tani">
                    @error('kelompok_tani')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Data Bantuan -->
            <div class="space-y-4">
                <h6 class="font-semibold text-gray-700 border-b pb-2">Data Bantuan</h6>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Jenis Bantuan *</label>
                    <select name="jenis_bantuan" id="jenisBantuanSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Pilih Jenis Bantuan</option>
                        @foreach($bantuan_list as $bantuan)
                            <option value="{{ $bantuan->nama_bantuan }}" 
                                data-satuan="{{ $bantuan->satuan }}"
                                {{ old('jenis_bantuan', $beneficiary->jenis_bantuan) == $bantuan->nama_bantuan ? 'selected' : '' }}>
                                {{ $bantuan->nama_bantuan }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_bantuan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Tahun *</label>
                    <input type="number" name="tahun" value="{{ old('tahun', $beneficiary->tahun) }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                           min="2000" max="2030" required>
                    @error('tahun')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Kuantitas</label>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="kuantitas" value="{{ old('kuantitas', $beneficiary->kuantitas) }}" 
                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2" 
                               min="1" placeholder="Jumlah bantuan">
                        <span id="satuanText" class="text-gray-600 font-medium">-</span>
                    </div>
                    @error('kuantitas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Status *</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="terdaftar" {{ old('status', $beneficiary->status) == 'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                        <option value="diterima" {{ old('status', $beneficiary->status) == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ old('status', $beneficiary->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="selesai" {{ old('status', $beneficiary->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Link Dokumen</label>
                    <input type="url" name="link" value="{{ old('link', $beneficiary->link) }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                           placeholder="https://example.com/dokumen">
                    @error('link')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Sumber Dana</label>
                    @php
                        $sumberDanaValue = old('sumber_dana', $beneficiary->sumber_dana);
                        $isLainnya = !in_array($sumberDanaValue, ['DBHCHT', 'DAK NON FISIK', 'DAK FISIK', 'PAD', 'LAINNYA']);
                    @endphp
                    <select name="sumber_dana" id="sumberDanaSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Pilih Sumber Dana</option>
                        <option value="DBHCHT" {{ $sumberDanaValue == 'DBHCHT' ? 'selected' : '' }}>DBHCHT</option>
                        <option value="DAK NON FISIK" {{ $sumberDanaValue == 'DAK NON FISIK' ? 'selected' : '' }}>DAK NON FISIK</option>
                        <option value="DAK FISIK" {{ $sumberDanaValue == 'DAK FISIK' ? 'selected' : '' }}>DAK FISIK</option>
                        <option value="PAD" {{ $sumberDanaValue == 'PAD' ? 'selected' : '' }}>PAD</option>
                        <option value="LAINNYA" {{ $isLainnya && $sumberDanaValue ? 'selected' : '' }}>LAINNYA</option>
                    </select>
                    <input type="text" name="sumber_dana_lainnya" id="sumberDanaLainnya" 
                           value="{{ $isLainnya && $sumberDanaValue ? $sumberDanaValue : old('sumber_dana_lainnya') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-2 {{ $isLainnya && $sumberDanaValue ? '' : 'hidden' }}" 
                           placeholder="Masukkan sumber dana lainnya">
                    @error('sumber_dana')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Peta dan Koordinat -->
            <div class="md:col-span-2 space-y-4">
                <h6 class="font-semibold text-gray-700 border-b pb-2">
                    📍 Edit Lokasi di Peta 
                    @if($beneficiary->latitude && $beneficiary->longitude)
                        <span class="text-green-600 text-sm font-normal">(Lokasi saat ini sudah ada)</span>
                    @else
                        <span class="text-red-600 text-sm font-normal">(Belum ada lokasi)</span>
                    @endif
                </h6>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <button type="button" id="btnCurrentLocation" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            📍 Dapatkan Lokasi Saat Ini
                        </button>
                        <button type="button" id="btnResetMap" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            🔄 Reset Peta
                        </button>
                        <button type="button" id="btnUseOriginal" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            📍 Gunakan Lokasi Asli
                        </button>
                        <button type="button" id="btnUpdateFromInput" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            📍 Update dari Input Manual
                        </button>
                        <div class="text-sm text-gray-600 ml-auto">
                            <span class="font-medium">Tip:</span> Klik di peta atau input manual koordinat
                        </div>
                    </div>
                    
                    <!-- Peta Leaflet -->
                    <div id="map" class="w-full h-64 rounded-lg border border-gray-300" data-original-lat="{{ $beneficiary->latitude }}" data-original-lng="{{ $beneficiary->longitude }}"></div>
                    
                    <div class="mt-3 text-sm text-gray-600">
                        <span id="locationStatus">
                            @if($beneficiary->latitude && $beneficiary->longitude)
                                Lokasi saat ini: {{ $beneficiary->latitude }}, {{ $beneficiary->longitude }}
                            @else
                                Silakan pilih lokasi di peta, gunakan tombol "Lokasi Saat Ini", atau input koordinat manual
                            @endif
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Latitude *</label>
                        <input type="text" name="latitude" id="latitudeInput" value="{{ old('latitude', $beneficiary->latitude) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="-8.589290" required>
                        <p class="text-xs text-gray-500 mt-1">Contoh: -8.589290</p>
                        @error('latitude')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Longitude *</label>
                        <input type="text" name="longitude" id="longitudeInput" value="{{ old('longitude', $beneficiary->longitude) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="116.128160" required>
                        <p class="text-xs text-gray-500 mt-1">Contoh: 116.128160</p>
                        @error('longitude')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Alamat Terdeteksi</label>
                        <input type="text" id="addressInput" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" 
                               placeholder="Alamat akan muncul di sini" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                <strong>Data ID:</strong> {{ $beneficiary->id }} | 
                <strong>Dibuat:</strong> {{ $beneficiary->created_at->format('d/m/Y H:i') }} |
                <strong>Diupdate:</strong> {{ $beneficiary->updated_at->format('d/m/Y H:i') }}
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('beneficiaries.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    ❌ Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition duration-200">
                    💾 Perbarui Data
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { 
        height: 400px; 
        z-index: 1;
    }
    .leaflet-container {
        font-family: inherit;
    }
</style>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Default coordinates (Lombok Tengah)
    let defaultLat = -8.700000;
    let defaultLng = 116.300000;
    let zoomLevel = 10;

    // Original coordinates from database
    const mapElement = document.getElementById('map');
    if (!mapElement || typeof L === 'undefined') {
        return;
    }

    const originalLatRaw = mapElement.dataset.originalLat;
    const originalLngRaw = mapElement.dataset.originalLng;
    const originalLat = originalLatRaw !== undefined && originalLatRaw !== null && originalLatRaw !== '' ? parseFloat(originalLatRaw) : null;
    const originalLng = originalLngRaw !== undefined && originalLngRaw !== null && originalLngRaw !== '' ? parseFloat(originalLngRaw) : null;

    // Initialize map - use original coordinates if available, otherwise default
    const initialLat = originalLat !== null ? originalLat : defaultLat;
    const initialLng = originalLng !== null ? originalLng : defaultLng;
    const initialZoom = originalLat !== null && originalLng !== null ? 15 : zoomLevel;
    
    const map = L.map(mapElement).setView([initialLat, initialLng], initialZoom);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    let marker = null;
    const latitudeInput = document.getElementById('latitudeInput');
    const longitudeInput = document.getElementById('longitudeInput');
    const addressInput = document.getElementById('addressInput');
    const locationStatus = document.getElementById('locationStatus');
    const btnCurrentLocation = document.getElementById('btnCurrentLocation');
    const btnResetMap = document.getElementById('btnResetMap');
    const btnUseOriginal = document.getElementById('btnUseOriginal');
    const btnUpdateFromInput = document.getElementById('btnUpdateFromInput');

    // Function to add marker
    function addMarker(lat, lng) {
        // Remove existing marker
        if (marker) {
            map.removeLayer(marker);
        }

        // Add new marker
        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        // Update coordinates when marker is dragged
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
            reverseGeocode(position.lat, position.lng);
        });

        // Center map on marker
        map.setView([lat, lng], 15);
        
        // Update form inputs
        updateCoordinates(lat, lng);
        reverseGeocode(lat, lng);
    }

    // Function to update coordinate inputs
    function updateCoordinates(lat, lng) {
        latitudeInput.value = lat.toFixed(6);
        longitudeInput.value = lng.toFixed(6);
        locationStatus.textContent = `Lokasi dipilih: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        locationStatus.className = 'text-sm text-green-600';
    }

    // Function to reverse geocode coordinates to address
    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    addressInput.value = data.display_name;
                } else {
                    addressInput.value = 'Alamat tidak ditemukan';
                }
            })
            .catch(error => {
                console.error('Error reverse geocoding:', error);
                addressInput.value = 'Error mendapatkan alamat';
            });
    }

    // Function to validate coordinates
    function isValidCoordinate(lat, lng) {
        const latNum = parseFloat(lat);
        const lngNum = parseFloat(lng);
        return !isNaN(latNum) && !isNaN(lngNum) && 
               latNum >= -90 && latNum <= 90 && 
               lngNum >= -180 && lngNum <= 180;
    }

    // Click on map to add marker
    map.on('click', function(e) {
        addMarker(e.latlng.lat, e.latlng.lng);
    });

    // Get current location
    if (btnCurrentLocation) btnCurrentLocation.addEventListener('click', function() {
        locationStatus.textContent = 'Mendapatkan lokasi...';
        locationStatus.className = 'text-sm text-blue-600';
        
        if (!navigator.geolocation) {
            locationStatus.textContent = 'Geolocation tidak didukung browser ini';
            locationStatus.className = 'text-sm text-red-600';
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                addMarker(lat, lng);
                locationStatus.textContent = `Lokasi saat ini berhasil didapatkan`;
                locationStatus.className = 'text-sm text-green-600';
            },
            function(error) {
                let errorMessage = 'Gagal mendapatkan lokasi: ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'Izin lokasi ditolak';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'Informasi lokasi tidak tersedia';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'Permintaan lokasi timeout';
                        break;
                    default:
                        errorMessage += 'Error tidak diketahui';
                }
                locationStatus.textContent = errorMessage;
                locationStatus.className = 'text-sm text-red-600';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    });

    // Reset map
    if (btnResetMap) btnResetMap.addEventListener('click', function() {
        map.setView([defaultLat, defaultLng], zoomLevel);
        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }
        latitudeInput.value = '';
        longitudeInput.value = '';
        addressInput.value = '';
        locationStatus.textContent = 'Silakan pilih lokasi di peta, gunakan tombol "Lokasi Saat Ini", atau input koordinat manual';
        locationStatus.className = 'text-sm text-gray-600';
    });

    // Use original location
    if (btnUseOriginal) btnUseOriginal.addEventListener('click', function() {
        if (originalLat !== null && originalLng !== null) {
            addMarker(originalLat, originalLng);
            locationStatus.textContent = `Lokasi asli dipulihkan: ${originalLat}, ${originalLng}`;
            locationStatus.className = 'text-sm text-green-600';
        } else {
            locationStatus.textContent = 'Tidak ada lokasi asli yang tersimpan';
            locationStatus.className = 'text-sm text-red-600';
        }
    });

    // Update map from manual input
    if (btnUpdateFromInput) btnUpdateFromInput.addEventListener('click', function() {
        const lat = latitudeInput.value.trim();
        const lng = longitudeInput.value.trim();
        
        if (!lat || !lng) {
            locationStatus.textContent = 'Masukkan latitude dan longitude terlebih dahulu';
            locationStatus.className = 'text-sm text-red-600';
            return;
        }

        if (!isValidCoordinate(lat, lng)) {
            locationStatus.textContent = 'Format koordinat tidak valid. Pastikan latitude (-90 sampai 90) dan longitude (-180 sampai 180)';
            locationStatus.className = 'text-sm text-red-600';
            return;
        }

        const latNum = parseFloat(lat);
        const lngNum = parseFloat(lng);
        addMarker(latNum, lngNum);
        locationStatus.textContent = `Lokasi diperbarui dari input manual: ${latNum.toFixed(6)}, ${lngNum.toFixed(6)}`;
        locationStatus.className = 'text-sm text-green-600';
    });

    // If editing and coordinates exist, add marker initially
    if (originalLat !== null && originalLng !== null) {
        addMarker(originalLat, originalLng);
    }

    // Form validation for coordinates
    const beneficiaryForm = document.getElementById('beneficiaryForm');
    if (beneficiaryForm) beneficiaryForm.addEventListener('submit', function(e) {
        const lat = latitudeInput.value.trim();
        const lng = longitudeInput.value.trim();
        
        if (!lat || !lng) {
            e.preventDefault();
            alert('Silakan masukkan latitude dan longitude!');
            return false;
        }

        if (!isValidCoordinate(lat, lng)) {
            e.preventDefault();
            alert('Format koordinat tidak valid. Pastikan latitude (-90 sampai 90) dan longitude (-180 sampai 180)');
            return false;
        }
    });

    // ========== Sumber Dana Toggle ==========
    const sumberDanaSelect = document.getElementById('sumberDanaSelect');
    const sumberDanaLainnya = document.getElementById('sumberDanaLainnya');
    const jenisBantuanSelect = document.getElementById('jenisBantuanSelect');
    const satuanText = document.getElementById('satuanText');

    if (jenisBantuanSelect && satuanText) {
        function updateSatuan() {
            const selectedOption = jenisBantuanSelect.options[jenisBantuanSelect.selectedIndex];
            const satuan = selectedOption.getAttribute('data-satuan');
            satuanText.textContent = satuan || '-';
        }
        
        updateSatuan();
        
        jenisBantuanSelect.addEventListener('change', updateSatuan);
    }

    if (sumberDanaSelect && sumberDanaLainnya) {
        // Check initial value
        if (sumberDanaSelect.value === 'LAINNYA') {
            sumberDanaLainnya.classList.remove('hidden');
            sumberDanaLainnya.setAttribute('required', 'required');
        }

        // Toggle input manual saat dropdown berubah
        sumberDanaSelect.addEventListener('change', function() {
            if (this.value === 'LAINNYA') {
                sumberDanaLainnya.classList.remove('hidden');
                sumberDanaLainnya.setAttribute('required', 'required');
                sumberDanaLainnya.focus();
            } else {
                sumberDanaLainnya.classList.add('hidden');
                sumberDanaLainnya.removeAttribute('required');
                if (this.value !== '') {
                    sumberDanaLainnya.value = '';
                }
            }
        });
    }
});
</script>
@endsection
