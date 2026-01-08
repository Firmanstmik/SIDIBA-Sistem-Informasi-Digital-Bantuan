<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Monev - {{ $beneficiary->nama }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            color: #333;
            line-height: 1.6;
        }
        .header { 
            text-align: center; 
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 { 
            color: #2c3e50; 
            margin: 0;
            font-size: 24px;
        }
        .header h2 {
            color: #7f8c8d;
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        .info-section { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #4CAF50;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }
        .summary-box {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #4CAF50;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            text-align: center;
        }
        .summary-item {
            padding: 10px;
        }
        .summary-number {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }
        .summary-label {
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-align: left;
            padding: 12px 15px;
            font-weight: bold;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-selesai { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-ditolak { background: #f8d7da; color: #721c24; }
        .rekomendasi-lanjut { background: #d4edda; color: #155724; }
        .rekomendasi-perbaikan { background: #fff3cd; color: #856404; }
        .rekomendasi-stop { background: #f8d7da; color: #721c24; }
        .rekomendasi-lainnya { background: #d1ecf1; color: #0c5460; }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .print-date {
            text-align: right;
            margin-bottom: 20px;
            color: #666;
            font-size: 12px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="print-date">Dicetak pada: {{ date('d/m/Y H:i') }}</div>
    
    <div class="header">
        <h1>LAPORAN MONITORING & EVALUASI</h1>
        <h2>Penerima Bantuan Pertanian</h2>
    </div>

    <!-- Info Penerima -->
    <div class="info-section">
        <h3 style="margin: 0 0 15px 0; color: #2c3e50;">📋 Data Penerima Bantuan</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nama:</span> {{ $beneficiary->nama }}
            </div>
            <div class="info-item">
                <span class="info-label">NIK:</span> {{ $beneficiary->nik }}
            </div>
            <div class="info-item">
                <span class="info-label">Nomor HP:</span> {{ $beneficiary->nomor_hape ?: '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Bidang:</span> {{ $beneficiary->bidang }}
            </div>
            <div class="info-item">
                <span class="info-label">Jenis Bantuan:</span> {{ $beneficiary->jenis_bantuan }}
            </div>
            <div class="info-item">
                <span class="info-label">Tahun:</span> {{ $beneficiary->tahun }}
            </div>
            <div class="info-item">
                <span class="info-label">Kelompok Tani:</span> {{ $beneficiary->kelompok_tani ?: '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Alamat:</span> {{ $beneficiary->alamat }}
            </div>
        </div>
    </div>

    <!-- Ringkasan Monev -->
    <div class="summary-box">
        <h3 style="margin: 0 0 15px 0; color: #2c3e50;">📊 Ringkasan Monev</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ $beneficiary->monevs->count() }}</div>
                <div class="summary-label">Total Monev</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">
                    @if($beneficiary->monevs->first())
                        {{ \Carbon\Carbon::parse($beneficiary->monevs->first()->tanggal_monev)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </div>
                <div class="summary-label">Monev Terakhir</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $beneficiary->monevs->where('status', 'selesai')->count() }}</div>
                <div class="summary-label">Monev Selesai</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $beneficiary->monevs->where('rekomendasi', 'Lanjut')->count() }}</div>
                <div class="summary-label">Rekomendasi Lanjut</div>
            </div>
        </div>
    </div>

    <!-- Riwayat Monev -->
    @if($beneficiary->monevs->count() > 0)
        <h3 style="color: #2c3e50; margin-bottom: 15px;">📈 Riwayat Monitoring & Evaluasi</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Pelaksana</th>
                    <th>Hasil Evaluasi</th>
                    <th>Rekomendasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($beneficiary->monevs as $index => $monev)
                @php
                    $statusClass = 'status-' . $monev->status;
                    $rekomendasiClass = 'rekomendasi-' . strtolower(str_replace(' ', '-', $monev->rekomendasi ?: 'lainnya'));
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($monev->tanggal_monev)->format('d/m/Y') }}</td>
                    <td>{{ $monev->pelaksana }}</td>
                    <td>{{ Str::limit($monev->hasil_evaluasi, 100) }}</td>
                    <td><span class="status-badge {{ $rekomendasiClass }}">{{ $monev->rekomendasi ?: '-' }}</span></td>
                    <td><span class="status-badge {{ $statusClass }}">{{ ucfirst($monev->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3 style="color: #666; margin: 0;">Belum ada data monitoring & evaluasi</h3>
            <p style="color: #888;">Data monev akan muncul di sini setelah ditambahkan.</p>
        </div>
    @endif

    <!-- Statistik Rekomendasi -->
    @php
        $rekomendasiStats = $beneficiary->monevs->groupBy('rekomendasi')->map->count();
        $totalMonev = $beneficiary->monevs->count();
    @endphp
    
    @if($rekomendasiStats->count() > 0)
        <h3 style="color: #2c3e50; margin: 30px 0 15px 0;">📊 Statistik Rekomendasi</h3>
        <table>
            <thead>
                <tr>
                    <th>Rekomendasi</th>
                    <th>Jumlah</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekomendasiStats as $rekomendasi => $count)
                @php
                    $percentage = $totalMonev > 0 ? round(($count / $totalMonev) * 100, 1) : 0;
                @endphp
                <tr>
                    <td>{{ $rekomendasi ?: 'Tidak Ada' }}</td>
                    <td>{{ $count }}</td>
                    <td>{{ $percentage }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Monitoring & Evaluasi Bantuan Pertanian</p>
        <p>© {{ date('Y') }} Dinas Pertanian - All rights reserved</p>
    </div>
</body>
</html>