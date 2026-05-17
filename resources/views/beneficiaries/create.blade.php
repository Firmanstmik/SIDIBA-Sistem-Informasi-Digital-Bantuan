@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">📍 Tambah Data Penerima</h3>
    <p class="text-gray-600">Input data penerima bantuan dengan peta interaktif</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
        <h5 class="font-semibold text-blue-800">📝 Form Tambah Penerima</h5>
    </div>
    
    <form method="POST" action="{{ route('beneficiaries.store') }}" class="p-6" id="beneficiaryForm">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Pribadi -->
            <div class="space-y-4">
                <h6 class="font-semibold text-gray-700 border-b pb-2">Data Pribadi</h6>
                
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">NIK *</label>
                    <input type="text" name="nik" id="nikInput" value="{{ old('nik') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <div id="nikStatus" class="mt-1 text-sm"></div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Alamat *</label>
                    <textarea name="alamat" class="w-full border border-gray-300 rounded-lg px-3 py-2" rows="3" required>{{ old('alamat') }}</textarea>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Nomor HP</label>
                    <input type="text" name="nomor_hape" value="{{ old('nomor_hape') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                           placeholder="Contoh: 081234567890">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Kelompok Tani</label>
                    <input type="text" name="kelompok_tani" value="{{ old('kelompok_tani') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                           placeholder="Nama kelompok tani">
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
                            <option value="{{ $bantuan->nama_bantuan }}" data-satuan="{{ $bantuan->satuan }}" {{ old('jenis_bantuan') == $bantuan->nama_bantuan ? 'selected' : '' }}>
                                {{ $bantuan->nama_bantuan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Tahun *</label>
                    <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                           min="2000" max="2030" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Kuantitas</label>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="kuantitas" value="{{ old('kuantitas', 1) }}" 
                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2" 
                               min="1" placeholder="Jumlah bantuan">
                        <span id="satuanText" class="text-gray-600 font-medium">-</span>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Status *</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="terdaftar" {{ old('status') == 'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                        <option value="diterima" {{ old('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ old('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Link Dokumen</label>
                    <input type="url" name="link" value="{{ old('link') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                           placeholder="https://example.com/dokumen">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Sumber Dana</label>
                    <select name="sumber_dana" id="sumberDanaSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Pilih Sumber Dana</option>
                        <option value="DBHCHT" {{ old('sumber_dana') == 'DBHCHT' ? 'selected' : '' }}>DBHCHT</option>
                        <option value="DAK NON FISIK" {{ old('sumber_dana') == 'DAK NON FISIK' ? 'selected' : '' }}>DAK NON FISIK</option>
                        <option value="DAK FISIK" {{ old('sumber_dana') == 'DAK FISIK' ? 'selected' : '' }}>DAK FISIK</option>
                        <option value="PAD" {{ old('sumber_dana') == 'PAD' ? 'selected' : '' }}>PAD</option>
                        <option value="LAINNYA" {{ old('sumber_dana') == 'LAINNYA' ? 'selected' : '' }}>LAINNYA</option>
                    </select>
                    <input type="text" name="sumber_dana_lainnya" id="sumberDanaLainnya" value="{{ old('sumber_dana_lainnya') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-2 hidden" 
                           placeholder="Masukkan sumber dana lainnya">
                </div>
            </div>

            <!-- Peta dan Koordinat -->
            <div class="md:col-span-2 space-y-4">
                <h6 class="font-semibold text-gray-700 border-b pb-2">📍 Pilih Lokasi di Peta</h6>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <button type="button" id="btnCurrentLocation" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            📍 Dapatkan Lokasi Saat Ini
                        </button>
                        <button type="button" id="btnResetMap" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            🔄 Reset Peta
                        </button>
                        <button type="button" id="btnUpdateFromInput" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            📍 Update dari Input Manual
                        </button>
                        <div class="text-sm text-gray-600 ml-auto">
                            <span class="font-medium">Tip:</span> Klik di peta untuk memilih lokasi atau input manual koordinat
                        </div>
                    </div>
                    
                    <!-- Peta Leaflet -->
                    <div id="map" class="w-full h-64 rounded-lg border border-gray-300"></div>
                    
                    <div class="mt-3 text-sm text-gray-600">
                        <span id="locationStatus">Silakan pilih lokasi di peta, gunakan tombol "Lokasi Saat Ini", atau input koordinat manual</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Latitude *</label>
                        <input type="text" name="latitude" id="latitudeInput" value="{{ old('latitude') }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="-8.589290" required>
                        <p class="text-xs text-gray-500 mt-1">Contoh: -8.589290</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Longitude *</label>
                        <input type="text" name="longitude" id="longitudeInput" value="{{ old('longitude') }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                               placeholder="116.128160" required>
                        <p class="text-xs text-gray-500 mt-1">Contoh: 116.128160</p>
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
        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('beneficiaries.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                Kembali
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition duration-200">
                Simpan Data
            </button>
        </div>
    </form>
</div>

<!-- Modal Overlay untuk Data NIK yang Sudah Ada -->
<div id="existingDataModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" id="modalBackdrop"></div>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform transition-all overflow-hidden rounded-2xl bg-white text-left shadow-2xl sm:my-8 sm:w-full sm:max-w-5xl w-full scale-95 opacity-0">
            <!-- Header dengan gradient hijau (tema pertanian) -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5 sm:px-8 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-full p-2">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">
                                NIK Sudah Terdaftar
                            </h3>
                            <p class="text-sm text-green-100 mt-1">
                                Data bantuan yang sudah diterima oleh NIK ini
                            </p>
                        </div>
                    </div>
                    <button type="button" id="btnCloseModal" class="rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 p-2 text-white transition-all duration-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="bg-gray-50 px-6 py-6 sm:px-8">
                <!-- Summary Statistics Card -->
                <div id="summaryCard" class="mb-6 bg-white rounded-xl shadow-md border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900" id="summaryName"></h4>
                                <p class="text-sm text-gray-500" id="summaryNik"></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-green-600" id="totalCount">0</div>
                            <div class="text-xs text-gray-500">Total Bantuan</div>
                        </div>
                    </div>
                    <div id="summaryStats" class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 pt-4 border-t border-gray-200">
                        <!-- Stats akan diisi oleh JavaScript -->
                    </div>
                </div>

                <!-- Timeline Header -->
                <div class="mb-4 flex items-center space-x-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h5 class="text-sm font-semibold text-gray-700">Riwayat Bantuan (Diurutkan dari Terbaru)</h5>
                </div>

                <!-- Data List dengan Timeline Accordion -->
                <div id="existingDataList" class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                    <!-- Data akan diisi oleh JavaScript -->
                </div>
            </div>

            <!-- Footer dengan Action Buttons -->
            <div class="bg-white px-6 py-4 sm:px-8 border-t border-gray-200 rounded-b-2xl">
                <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                    <button type="button" id="btnContinueNew" class="inline-flex justify-center items-center px-6 py-3 border-2 border-gray-300 rounded-lg text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Lanjutkan Input Baru
                    </button>
                    <button type="button" id="btnUseExistingData" class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-base font-medium text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Gunakan Data yang Sudah Ada
                    </button>
                </div>
            </div>
        </div>
    </div>
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
    
    /* Modal Animation */
    #existingDataModal {
        transition: opacity 0.2s ease-in-out;
    }
    
    #existingDataModal .transform {
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        transform: scale(0.95);
        opacity: 0;
    }
    
    #existingDataModal:not(.hidden) .transform {
        transform: scale(1);
        opacity: 1;
    }
    
    #modalBackdrop {
        transition: opacity 0.2s ease-in-out;
    }
    
    /* Custom Scrollbar untuk Data List */
    #existingDataList::-webkit-scrollbar {
        width: 8px;
    }
    
    #existingDataList::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    #existingDataList::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    #existingDataList::-webkit-scrollbar-thumb:hover {
        background: #555;
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

    // Initialize map
    const map = L.map('map').setView([defaultLat, defaultLng], zoomLevel);

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
    btnCurrentLocation.addEventListener('click', function() {
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
    btnResetMap.addEventListener('click', function() {
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

    // Update map from manual input
    btnUpdateFromInput.addEventListener('click', function() {
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

    // If editing and coordinates exist, add marker
    @if(isset($beneficiary) && $beneficiary->latitude && $beneficiary->longitude)
        addMarker({{ $beneficiary->latitude }}, {{ $beneficiary->longitude }});
    @endif

    // Form validation for coordinates
    document.getElementById('beneficiaryForm').addEventListener('submit', function(e) {
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
                sumberDanaLainnya.value = '';
            }
        });
    }

    // ========== NIK Validation & Duplicate Check ==========
    const nikInput = document.getElementById('nikInput');
    const nikStatus = document.getElementById('nikStatus');
    const existingDataModal = document.getElementById('existingDataModal');
    const existingDataList = document.getElementById('existingDataList');
    const totalDataText = document.getElementById('totalDataText');
    const btnUseExistingData = document.getElementById('btnUseExistingData');
    const btnContinueNew = document.getElementById('btnContinueNew');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const modalBackdrop = document.getElementById('modalBackdrop');
    
    let existingData = null;
    let checkNikTimeout = null;

    // Function to show modal with animation
    function showModal() {
        existingDataModal.classList.remove('hidden');
        // Trigger animation
        setTimeout(() => {
            const modalContent = existingDataModal.querySelector('.transform');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    // Function to hide modal with animation
    function hideModal() {
        const modalContent = existingDataModal.querySelector('.transform');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            existingDataModal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    // Function to check NIK
    function checkNik(nik) {
        if (!nik || nik.length < 10) {
            nikStatus.textContent = '';
            nikStatus.className = '';
            hideModal();
            return;
        }

        // Clear previous timeout
        if (checkNikTimeout) {
            clearTimeout(checkNikTimeout);
        }

        // Show loading
        nikStatus.textContent = '⏳ Memeriksa NIK...';
        nikStatus.className = 'text-sm text-blue-600';

        // Debounce: wait 500ms after user stops typing
        checkNikTimeout = setTimeout(() => {
            fetch(`{{ route('beneficiaries.checkNik') }}?nik=${encodeURIComponent(nik)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    existingData = data.data;
                    displayExistingData(data.data, data.total);
                    showModal();
                    nikStatus.textContent = `⚠️ NIK ini sudah terdaftar (${data.total} data ditemukan)`;
                    nikStatus.className = 'text-sm text-yellow-600 font-medium';
                    nikInput.classList.add('border-yellow-500', 'ring-2', 'ring-yellow-200');
                } else {
                    existingData = null;
                    hideModal();
                    nikStatus.textContent = '✅ NIK belum terdaftar';
                    nikStatus.className = 'text-sm text-green-600';
                    nikInput.classList.remove('border-yellow-500', 'ring-2', 'ring-yellow-200');
                }
            })
            .catch(error => {
                console.error('Error checking NIK:', error);
                nikStatus.textContent = '❌ Error memeriksa NIK';
                nikStatus.className = 'text-sm text-red-600';
            });
        }, 500);
    }

    // Function to display existing data with modern design
    function displayExistingData(data, total) {
        if (data.length === 0) return;
        
        const firstData = data[0];
        const nikValue = nikInput.value;
        
        // Update Summary Card
        document.getElementById('summaryName').textContent = firstData.nama;
        document.getElementById('summaryNik').textContent = `NIK: ${nikValue}`;
        document.getElementById('totalCount').textContent = total;
        
        // Calculate Statistics
        const stats = {
            diterima: data.filter(d => d.status === 'diterima').length,
            selesai: data.filter(d => d.status === 'selesai').length,
            terdaftar: data.filter(d => d.status === 'terdaftar').length,
            ditolak: data.filter(d => d.status === 'ditolak').length
        };
        
        // Update Summary Stats
        const summaryStatsHtml = `
            <div class="text-center">
                <div class="text-lg font-bold text-green-600">${stats.diterima}</div>
                <div class="text-xs text-gray-500">Diterima</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-bold text-blue-600">${stats.selesai}</div>
                <div class="text-xs text-gray-500">Selesai</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-bold text-yellow-600">${stats.terdaftar}</div>
                <div class="text-xs text-gray-500">Terdaftar</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-bold text-red-600">${stats.ditolak}</div>
                <div class="text-xs text-gray-500">Ditolak</div>
            </div>
        `;
        document.getElementById('summaryStats').innerHTML = summaryStatsHtml;
        
        // Generate Timeline Accordion
        let html = '';
        
        data.forEach((item, index) => {
            const statusConfig = {
                'terdaftar': { 
                    class: 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    icon: '⏳',
                    label: 'Terdaftar'
                },
                'diterima': { 
                    class: 'bg-green-100 text-green-800 border-green-200',
                    icon: '✅',
                    label: 'Diterima'
                },
                'ditolak': { 
                    class: 'bg-red-100 text-red-800 border-red-200',
                    icon: '❌',
                    label: 'Ditolak'
                },
                'selesai': { 
                    class: 'bg-blue-100 text-blue-800 border-blue-200',
                    icon: '✓',
                    label: 'Selesai'
                }
            };
            
            const status = statusConfig[item.status] || statusConfig['terdaftar'];
            const isLast = index === data.length - 1;
            const itemId = `beneficiary-${index}`;
            const isExpanded = index === 0; // First item expanded by default

            html += `
                <div class="relative">
                    <!-- Timeline Connector -->
                    ${!isLast ? `
                    <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-gray-300"></div>
                    ` : ''}
                    
                    <!-- Accordion Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden">
                        <!-- Compact Header (Always Visible) -->
                        <button type="button" 
                                class="w-full px-5 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors"
                                onclick="toggleBeneficiaryDetail('${itemId}')"
                                aria-expanded="${isExpanded}">
                            <div class="flex items-center space-x-4 flex-1">
                                <!-- Timeline Dot -->
                                <div class="flex-shrink-0 relative z-10">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg border-4 border-white">
                                        ${index + 1}
                                    </div>
                                </div>
                                
                                <!-- Main Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3 mb-1">
                                        <h4 class="text-base font-bold text-gray-900 truncate">${item.jenis_bantuan}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border ${status.class} flex-shrink-0">
                                            ${status.icon} ${status.label}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500 flex-wrap">
                                        <span class="flex items-center">
                                            <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Tahun ${item.tahun}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            Kuantitas: ${item.kuantitas || 1}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            ${item.created_at}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Expand/Collapse Icon -->
                            <div class="flex-shrink-0 ml-4">
                                <svg id="icon-${itemId}" class="h-5 w-5 text-gray-400 transform transition-transform duration-200 ${isExpanded ? 'rotate-180' : ''}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                        
                        <!-- Expandable Detail Content -->
                        <div id="detail-${itemId}" class="px-5 pb-5 ${isExpanded ? '' : 'hidden'}">
                            <div class="pl-16 border-t border-gray-100 pt-4">
                                <!-- Detail Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <!-- Kuantitas (Kolom 1, Baris 1) -->
                                    <div class="flex items-start space-x-2">
                                        <svg class="h-5 w-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500">Kuantitas</p>
                                            <p class="text-sm font-semibold text-gray-900">${item.kuantitas || 1}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Nomor HP (Kolom 2, Baris 1) - Dipindah ke sini -->
                                    ${item.nomor_hape ? `
                                    <div class="flex items-start space-x-2">
                                        <svg class="h-5 w-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500">Nomor HP</p>
                                            <p class="text-sm font-semibold text-gray-900">${item.nomor_hape}</p>
                                        </div>
                                    </div>
                                    ` : '<div></div>'}
                                    
                                    <!-- Kelompok Tani (Kolom 1, Baris 2) - Di bawah kuantitas -->
                                    ${item.kelompok_tani ? `
                                    <div class="flex items-start space-x-2">
                                        <svg class="h-5 w-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500">Kelompok Tani</p>
                                            <p class="text-sm font-semibold text-gray-900">${item.kelompok_tani}</p>
                                        </div>
                                    </div>
                                    ` : ''}
                                    
                                    <!-- Sumber Dana (Kolom 2, Baris 2) - Di bawah nomor HP -->
                                    ${item.sumber_dana ? `
                                    <div class="flex items-start space-x-2">
                                        <svg class="h-5 w-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500">Sumber Dana</p>
                                            <p class="text-sm font-semibold text-gray-900">${item.sumber_dana}</p>
                                        </div>
                                    </div>
                                    ` : ''}
                                </div>

                                <!-- Alamat (Full Width) -->
                                <div class="mb-4">
                                    <div class="flex items-start space-x-2">
                                        <svg class="h-5 w-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-gray-500 mb-1">Alamat</p>
                                            <p class="text-sm text-gray-900">${item.alamat}</p>
                                        </div>
                                    </div>
                                </div>

                                ${item.link ? `
                                <!-- Link Dokumen (Full Width, Di bawah alamat) -->
                                <div class="mb-4">
                                    <div class="flex items-start space-x-2">
                                        <svg class="h-5 w-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-gray-500 mb-1">Link Dokumen</p>
                                            <a href="${item.link}" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center space-x-2 text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                                <span class="truncate max-w-md">${item.link}</span>
                                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}

                                ${item.keterangan ? `
                                <!-- Keterangan Warning -->
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
                                    <div class="flex items-start space-x-2">
                                        <svg class="h-5 w-5 text-orange-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <div>
                                            <p class="text-xs font-medium text-orange-800 mb-1">Keterangan</p>
                                            <p class="text-sm text-orange-900">${item.keterangan}</p>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        existingDataList.innerHTML = html;
    }

    // Function to toggle accordion detail
    window.toggleBeneficiaryDetail = function(itemId) {
        const detailElement = document.getElementById(`detail-${itemId}`);
        const iconElement = document.getElementById(`icon-${itemId}`);
        const button = detailElement.previousElementSibling;
        const isExpanded = button.getAttribute('aria-expanded') === 'true';
        
        if (isExpanded) {
            detailElement.classList.add('hidden');
            iconElement.classList.remove('rotate-180');
            button.setAttribute('aria-expanded', 'false');
        } else {
            detailElement.classList.remove('hidden');
            iconElement.classList.add('rotate-180');
            button.setAttribute('aria-expanded', 'true');
        }
    };

    // Listen to NIK input
    nikInput.addEventListener('input', function(e) {
        const nik = e.target.value.trim();
        checkNik(nik);
    });

    // Close modal handlers
    btnCloseModal.addEventListener('click', hideModal);
    modalBackdrop.addEventListener('click', hideModal);
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !existingDataModal.classList.contains('hidden')) {
            hideModal();
        }
    });

    // Button: Use existing data (fill form with first existing data)
    btnUseExistingData.addEventListener('click', function() {
        if (existingData && existingData.length > 0) {
            const firstData = existingData[0];
            
            // Fill form with existing data
            const namaInput = document.querySelector('input[name="nama"]');
            const alamatInput = document.querySelector('textarea[name="alamat"]');
            const nomorHapeInput = document.querySelector('input[name="nomor_hape"]');
            const kelompokTaniInput = document.querySelector('input[name="kelompok_tani"]');
            const nikInputEl = document.querySelector('input[name="nik"]');
            
            namaInput.value = firstData.nama;
            alamatInput.value = firstData.alamat;
            if (firstData.nomor_hape) {
                nomorHapeInput.value = firstData.nomor_hape;
            }
            if (firstData.kelompok_tani) {
                kelompokTaniInput.value = firstData.kelompok_tani;
            }
            
            // Lock biodata fields (readonly)
            namaInput.setAttribute('readonly', 'readonly');
            namaInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            alamatInput.setAttribute('readonly', 'readonly');
            alamatInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            nomorHapeInput.setAttribute('readonly', 'readonly');
            nomorHapeInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            kelompokTaniInput.setAttribute('readonly', 'readonly');
            kelompokTaniInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            nikInputEl.setAttribute('readonly', 'readonly');
            nikInputEl.classList.add('bg-gray-100', 'cursor-not-allowed');
            
            // Hide modal
            hideModal();
            nikStatus.textContent = '✅ Menggunakan data yang sudah ada (biodata terkunci)';
            nikStatus.className = 'text-sm text-green-600';
            nikInput.classList.remove('border-yellow-500', 'ring-2', 'ring-yellow-200');
            nikInput.classList.add('border-green-500', 'ring-2', 'ring-green-200');
            
            // Focus on jenis bantuan field
            setTimeout(() => {
                document.querySelector('select[name="jenis_bantuan"]').focus();
            }, 300);
        }
    });

    // Button: Continue with new data
    btnContinueNew.addEventListener('click', function() {
        hideModal();
        nikStatus.textContent = '⚠️ NIK sudah terdaftar, tetapi Anda dapat melanjutkan input data baru';
        nikStatus.className = 'text-sm text-yellow-600';
    });
});
</script>
@endsection