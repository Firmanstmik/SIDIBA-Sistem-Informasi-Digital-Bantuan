<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bantuan;
use App\Models\Beneficiary;

class DummyDataSeeder extends Seeder
{
    // Koordinat kecamatan di Lombok Tengah
    private $kecamatanCoords = [
        "Praya" => [-8.705, 116.271],
        "Pujut" => [-8.842, 116.333],
        "Jonggat" => [-8.640, 116.222],
        "Pringgarata" => [-8.610, 116.300],
        "Janapria" => [-8.673, 116.375],
        "Kopang" => [-8.585, 116.392],
        "Praya Barat" => [-8.786, 116.187],
        "Praya Timur" => [-8.780, 116.420],
    ];

    // Data bidang
    private $bidangs = [
        "Bidang Produksi Pertanian",
        "Bidang Produksi Peternakan", 
        "Bidang Prasarana Pertanian",
        "Bidang Agribisnis",
        "Bidang Penyuluhan",
        "Sekretariat"
    ];

    // Data jenis bantuan per bidang
    private $jenisBantuan = [
        "Bidang Produksi Pertanian" => [
            "Bibit Padi Unggul", "Bibit Jagung", "Pupuk Organik", "Pestisida", "Alat Tanam"
        ],
        "Bidang Produksi Peternakan" => [
            "Bibit Sapi Potong", "Pakan Ternak", "Kandang Modern", "Obat Hewan", "Bibit Ayam Kampung"
        ],
        "Bidang Prasarana Pertanian" => [
            "Pompa Air", "Jaringan Irigasi", "Traktor Mini", "Gudang Penyimpanan", "Alat Pengolahan Tanah"
        ],
        "Bidang Agribisnis" => [
            "Mesin Pengemas", "Alat Pengolahan Hasil", "Kios Pemasaran", "Modal Usaha", "Pelatihan Kewirausahaan"
        ],
        "Bidang Penyuluhan" => [
            "Paket Buku Pertanian", "Alat Peraga", "Paket Pelatihan", "Bibit Demonstrasi", "Alat Monitoring"
        ],
        "Sekretariat" => [
            "Kendaraan Dinas", "Komputer", "Alat Tulis Kantor", "Furniture", "Perlengkapan Meeting"
        ]
    ];

    // Data nama-nama Lombok
    private $namaLombok = [
        'Ahmad', 'Siti', 'Muhammad', 'Fatima', 'Ali', 'Nur', 'Hasan', 'Aisyah', 'Hussein', 'Mariam',
        'Abdul', 'Rahma', 'Omar', 'Zainab', 'Yusuf', 'Khadija', 'Ibrahim', 'Salma', 'Jamal', 'Nabila',
        'Rahmat', 'Sofia', 'Budi', 'Dewi', 'Joko', 'Rini', 'Surya', 'Maya', 'Dedi', 'Lina',
        'Fajar', 'Nina', 'Rizki', 'Diana', 'Hendra', 'Eka', 'Agus', 'Rita', 'Wayan', 'Sari',
        'Komang', 'Putu', 'Made', 'Ketut', 'Nyoman', 'Desak', 'Gede', 'Luh', 'Anom', 'Pande'
    ];

    public function run()
    {
        $this->command->info('Starting Dummy Data Seeder...');

        // 1. Create Users untuk setiap bidang (jika belum ada)
        $this->createUsers();

        // 2. Create Jenis Bantuan untuk setiap bidang (jika belum ada)
        $this->createBantuan();

        // 3. Create Penerima Bantuan (jika belum ada 50 data)
        $this->createBeneficiaries();

        $this->command->info('Dummy Data Seeder completed successfully!');
    }

    private function createUsers()
    {
        $this->command->info('Creating users...');

        $users = [
            // Admin
            [
                'username' => 'admin',
                'password' => 'admin123',
                'role' => 'admin',
                'nama' => 'Administrator System',
                'nip' => '198001012000121001',
                'bidang' => 'Administrator'
            ]
        ];

        // User untuk setiap bidang
        $userData = [
            "Bidang Produksi Pertanian" => [
                'username' => 'user_produksi',
                'nama' => 'Ahmad Yudi, SP',
                'nip' => '198002022000122002'
            ],
            "Bidang Produksi Peternakan" => [
                'username' => 'user_peternakan', 
                'nama' => 'Siti Fatima, S.Pt',
                'nip' => '198003032000123003'
            ],
            "Bidang Prasarana Pertanian" => [
                'username' => 'user_prasarana',
                'nama' => 'Muhammad Ali, ST',
                'nip' => '198004042000124004'
            ],
            "Bidang Agribisnis" => [
                'username' => 'user_agribisnis',
                'nama' => 'Nur Hasanah, SE',
                'nip' => '198005052000125005'
            ],
            "Bidang Penyuluhan" => [
                'username' => 'user_penyuluhan',
                'nama' => 'Hussein Rahman, SP',
                'nip' => '198006062000126006'
            ],
            "Sekretariat" => [
                'username' => 'user_sekretariat',
                'nama' => 'Aisyah Mardiah, S.Sos',
                'nip' => '198007072000127007'
            ]
        ];

        foreach ($userData as $bidang => $data) {
            $users[] = [
                'username' => $data['username'],
                'password' => 'user123',
                'role' => 'user',
                'nama' => $data['nama'],
                'nip' => $data['nip'],
                'bidang' => $bidang
            ];
        }

        foreach ($users as $user) {
            // Cek apakah user sudah ada
            $existingUser = User::where('username', $user['username'])->first();
            
            if (!$existingUser) {
                User::create($user);
                $this->command->info("User {$user['username']} created!");
            } else {
                $this->command->info("User {$user['username']} already exists, skipping...");
            }
        }

        $this->command->info('Users creation completed!');
    }

    private function createBantuan()
    {
        $this->command->info('Creating jenis bantuan...');

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($this->jenisBantuan as $bidang => $bantuans) {
            foreach ($bantuans as $bantuan) {
                $satuan = $this->getSatuan($bantuan);
                
                // Cek apakah bantuan sudah ada
                $existingBantuan = Bantuan::where('nama_bantuan', $bantuan)
                                         ->where('bidang', $bidang)
                                         ->first();
                
                if (!$existingBantuan) {
                    Bantuan::create([
                        'nama_bantuan' => $bantuan,
                        'bidang' => $bidang,
                        'satuan' => $satuan
                    ]);
                    $createdCount++;
                    $this->command->info("Bantuan {$bantuan} created for {$bidang}");
                } else {
                    $skippedCount++;
                }
            }
        }

        $this->command->info("Jenis bantuan created: {$createdCount}, skipped: {$skippedCount}");
    }

    private function getSatuan($bantuan)
    {
        $satuanMap = [
            'Bibit Padi Unggul' => 'kg',
            'Bibit Jagung' => 'kg',
            'Pupuk Organik' => 'karung',
            'Pestisida' => 'liter',
            'Alat Tanam' => 'unit',
            'Bibit Sapi Potong' => 'ekor',
            'Pakan Ternak' => 'karung',
            'Kandang Modern' => 'unit',
            'Obat Hewan' => 'pack',
            'Bibit Ayam Kampung' => 'ekor',
            'Pompa Air' => 'unit',
            'Jaringan Irigasi' => 'meter',
            'Traktor Mini' => 'unit',
            'Gudang Penyimpanan' => 'unit',
            'Alat Pengolahan Tanah' => 'unit',
            'Mesin Pengemas' => 'unit',
            'Alat Pengolahan Hasil' => 'unit',
            'Kios Pemasaran' => 'unit',
            'Modal Usaha' => 'paket',
            'Pelatihan Kewirausahaan' => 'paket',
            'Paket Buku Pertanian' => 'paket',
            'Alat Peraga' => 'unit',
            'Paket Pelatihan' => 'paket',
            'Bibit Demonstrasi' => 'paket',
            'Alat Monitoring' => 'unit',
            'Kendaraan Dinas' => 'unit',
            'Komputer' => 'unit',
            'Alat Tulis Kantor' => 'paket',
            'Furniture' => 'unit',
            'Perlengkapan Meeting' => 'paket'
        ];

        return $satuanMap[$bantuan] ?? 'unit';
    }

    private function createBeneficiaries()
    {
        $this->command->info('Creating beneficiaries...');

        $existingCount = Beneficiary::count();
        
        if ($existingCount >= 50) {
            $this->command->info("Already have {$existingCount} beneficiaries, skipping creation...");
            return;
        }

        $needed = 50 - $existingCount;
        $this->command->info("Creating {$needed} new beneficiaries...");

        $statuses = ['terdaftar', 'diterima', 'ditolak', 'selesai'];
        $tahun = [2022, 2023, 2024];
        
        $beneficiaries = [];

        for ($i = 1; $i <= $needed; $i++) {
            // Pilih bidang random
            $bidang = $this->bidangs[array_rand($this->bidangs)];
            
            // Pilih jenis bantuan dari bidang tersebut
            $jenisBantuan = $this->jenisBantuan[$bidang][array_rand($this->jenisBantuan[$bidang])];
            
            // Pilih kecamatan random
            $kecamatan = array_rand($this->kecamatanCoords);
            $coords = $this->kecamatanCoords[$kecamatan];
            
            // Tambahkan random offset untuk koordinat (agar tidak sama persis)
            $latitude = $coords[0] + (rand(-50, 50) / 1000);
            $longitude = $coords[1] + (rand(-50, 50) / 1000);
            
            $nama = $this->namaLombok[array_rand($this->namaLombok)] . ' ' . $this->namaLombok[array_rand($this->namaLombok)];
            
            $beneficiaries[] = [
                'nik' => '5271' . sprintf('%04d', rand(1, 1231)) . rand(100000, 999999),
                'nama' => $nama,
                'alamat' => 'Desa ' . $this->getRandomDesa($kecamatan) . ', Kec. ' . $kecamatan,
                'nomor_hape' => '08' . rand(12, 15) . rand(10000000, 99999999),
                'kelompok_tani' => rand(0, 1) ? 'Kelompok Tani ' . $this->getRandomKelompokTani() : null,
                'bidang' => $bidang,
                'jenis_bantuan' => $jenisBantuan,
                'tahun' => $tahun[array_rand($tahun)],
                'kuantitas' => rand(1, 20),
                'status' => $statuses[array_rand($statuses)],
                'link' => rand(0, 1) ? 'https://example.com/doc' . ($existingCount + $i) : null,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'keterangan' => rand(0, 1) ? 'Penerima bantuan ' . $jenisBantuan . ' tahun ' . $tahun[array_rand($tahun)] : null,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert semua data
        foreach (array_chunk($beneficiaries, 10) as $chunk) {
            Beneficiary::insert($chunk);
        }

        $this->command->info("{$needed} new beneficiaries created successfully!");
        $this->command->info("Total beneficiaries now: " . Beneficiary::count());
    }

    private function getRandomDesa($kecamatan)
    {
        $desaMap = [
            "Praya" => ["Mujur", "Praya", "Bunut", "Gapuk", "Ganti"],
            "Pujut" => ["Sengkol", "Mertak", "Sukadana", "Pengengat", "Teruwai"],
            "Jonggat" => ["Mantang", "Puyung", "Barejulat", "Kembang Kerang", "Jenggik"],
            "Pringgarata" => ["Pringgarata", "Bagu", "Beber", "Dasan Tereng", "Murbaya"],
            "Janapria" => ["Janapria", "Loangmaka", "Saba", "Jango", "Pendem"],
            "Kopang" => ["Kopang", "Mujur", "Semayan", "Lendang Ara", "Bungtiang"],
            "Praya Barat" => ["Batuyang", "Bonder", "Mekar Damai", "Pengadang", "Murbaya"],
            "Praya Timur" => ["Gerantung", "Jagong", "Kutaraja", "Aikmel", "Sukarara"]
        ];

        return $desaMap[$kecamatan][array_rand($desaMap[$kecamatan])];
    }

    private function getRandomKelompokTani()
    {
        $kelompok = [
            "Maju Jaya", "Tani Sejahtera", "Sumber Rejeki", "Harapan Baru", "Mekar Sari",
            "Sumber Makmur", "Tani Makmur", "Bersama Maju", "Tani Jaya", "Sumber Hasil"
        ];

        return $kelompok[array_rand($kelompok)];
    }
}