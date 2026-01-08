<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Bantuan - SIDIBA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css" />
    <style>
        #map { 
            height: 600px; 
            z-index: 1;
        }
        .leaflet-container {
            font-family: inherit;
        }
        .info-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            min-width: 280px;
            max-width: 350px;
        }
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .google-maps-btn {
            background: #4285f4;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background 0.3s;
        }
        .google-maps-btn:hover {
            background: #3367d6;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-green-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left mb-4 md:mb-0">
                    <h1 class="text-2xl font-bold">🗺️ Peta Sebaran Bantuan</h1>
                    <p class="text-green-100">Sistem Informasi Bantuan Dinas Pertanian</p>
                </div>
                <nav class="flex space-x-2">
                    <a href="{{ route('home') }}" class="bg-green-500 hover:bg-green-700 px-3 py-2 rounded-lg font-medium transition duration-200">
                        📊 Data Penerima
                    </a>
                    <a href="{{ route('public.map') }}" class="bg-green-700 px-3 py-2 rounded-lg font-medium">
                        🗺️ Peta Bantuan
                    </a>
                    <a href="/login" class="bg-white text-green-600 hover:bg-gray-100 px-3 py-2 rounded-lg font-medium transition duration-200">
                        🔐 Login
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Filter Section -->
    <section class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4">
            <form method="GET" action="{{ route('public.map') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Tahun</label>
                    <select name="tahun" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Semua Tahun</option>
                        @foreach($tahun_list as $tahun_item)
                            <option value="{{ $tahun_item }}" {{ $tahun == $tahun_item ? 'selected' : '' }}>
                                {{ $tahun_item }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Jenis Bantuan</label>
                    <select name="jenis" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Semua Jenis</option>
                        @foreach($jenis_list as $jenis_item)
                            <option value="{{ $jenis_item }}" {{ $jenis == $jenis_item ? 'selected' : '' }}>
                                {{ $jenis_item }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg font-medium text-sm">
                        🔍 Filter Peta
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-blue-50 py-3">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-4 text-sm">
                <div class="bg-white px-3 py-2 rounded-lg shadow-sm">
                    <span class="font-medium">Total Titik: </span>
                    <span id="totalPoints" class="loading-spinner"></span>
                </div>
                <div class="bg-white px-3 py-2 rounded-lg shadow-sm">
                    <span class="font-medium">Cluster: </span>
                    <span id="clusterCount">0</span>
                </div>
                <div class="bg-white px-3 py-2 rounded-lg shadow-sm">
                    <span class="font-medium">Status: </span>
                    <span id="mapStatus">Memuat peta...</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-green-600 text-white px-6 py-3">
                <h3 class="text-lg font-semibold">🗺️ Peta Sebaran Penerima Bantuan</h3>
                <p class="text-green-100 text-sm">Klik marker atau cluster untuk melihat detail penerima bantuan</p>
            </div>
            
            <div id="map"></div>
            
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span>Penerima Bantuan</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                        <span>Cluster (10-50 titik)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span>Cluster (50+ titik)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} Dinas Pertanian. Sistem Informasi Bantuan Dinas Pertanian.
            </p>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map
            const map = L.map('map').setView([-8.700000, 116.300000], 10);
            let markers = L.markerClusterGroup({
                chunkedLoading: true,
                chunkInterval: 100,
                maxClusterRadius: 50,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: true,
                zoomToBoundsOnClick: true
            });

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            // Update status
            document.getElementById('mapStatus').textContent = 'Memuat data...';

            // Fetch beneficiaries data
            fetch('/api/beneficiaries?{{ request()->getQueryString() }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(beneficiaries => {
                    // Filter hanya yang punya koordinat
                    const validBeneficiaries = beneficiaries.filter(b => 
                        b.latitude && b.longitude && 
                        b.latitude != 0 && b.longitude != 0
                    );

                    document.getElementById('totalPoints').textContent = validBeneficiaries.length;
                    document.getElementById('mapStatus').textContent = 'Data berhasil dimuat';

                    // Add markers to cluster group
                    validBeneficiaries.forEach(beneficiary => {
                        const bantuan = beneficiary;
                        
                        // Create custom icon berdasarkan jenis bantuan
                        const getIconColor = (jenisBantuan) => {
                            const colors = {
                                'Bibit Padi': 'blue',
                                'Bibit Jagung': 'green', 
                                'Pupuk': 'orange',
                                'Alat Pertanian': 'red',
                                'default': 'blue'
                            };
                            return colors[jenisBantuan] || colors['default'];
                        };

                        const customIcon = L.divIcon({
                            className: 'custom-marker',
                            html: `<div style="background: ${getIconColor(bantuan.jenis_bantuan)}; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">📍</div>`,
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });

                        // Create marker
                        const marker = L.marker([bantuan.latitude, bantuan.longitude], {
                            icon: customIcon,
                            title: bantuan.nama
                        });

                        // Popup content - TANPA NOMOR HP, DENGAN TOMBOL GOOGLE MAPS
                        const popupContent = `
                            <div class="info-box">
                                <h4 class="font-bold text-lg text-gray-800 mb-2 border-b pb-1">${bantuan.nama}</h4>
                                <div class="space-y-2 text-sm">
                                    <div><strong>📦 Bantuan:</strong> ${bantuan.jenis_bantuan}</div>
                                    <div><strong>📊 Jumlah:</strong> ${bantuan.kuantitas} ${bantuan.satuan}</div>
                                    <div><strong>📅 Tahun:</strong> ${bantuan.tahun}</div>
                                    <div><strong>📍 Alamat:</strong> ${bantuan.alamat}</div>
                                    ${bantuan.kelompok_tani ? `<div><strong>👥 Kelompok Tani:</strong> ${bantuan.kelompok_tani}</div>` : ''}
                                </div>
                                <button class="google-maps-btn" onclick="window.open('https://www.google.com/maps?q=${bantuan.latitude},${bantuan.longitude}', '_blank')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/>
                                    </svg>
                                    Buka di Google Maps
                                </button>
                            </div>
                        `;

                        marker.bindPopup(popupContent);
                        markers.addLayer(marker);
                    });

                    // Add cluster group to map
                    map.addLayer(markers);

                    // Fit map to show all markers
                    if (validBeneficiaries.length > 0) {
                        map.fitBounds(markers.getBounds().pad(0.1));
                    }

                    // Update cluster count
                    setTimeout(() => {
                        const clusterCount = document.querySelectorAll('.marker-cluster').length;
                        document.getElementById('clusterCount').textContent = clusterCount;
                    }, 1000);

                })
                .catch(error => {
                    console.error('Error loading beneficiaries:', error);
                    document.getElementById('mapStatus').textContent = 'Error memuat data';
                    document.getElementById('mapStatus').className = 'text-red-600';
                });

            // Loading indicator untuk tile layer
            map.on('loading', function() {
                document.getElementById('mapStatus').textContent = 'Memuat peta...';
            });

            map.on('load', function() {
                document.getElementById('mapStatus').textContent = 'Peta siap';
            });
        });
    </script>
</body>
</html>