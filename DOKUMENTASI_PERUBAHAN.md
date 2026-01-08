# 📋 Dokumentasi Perubahan Aplikasi SIDIBA

**Tanggal:** 13 Desember 2025  
**Versi:** 1.0  
**Aplikasi:** Sistem Informasi Data Bantuan Pertanian (SIDIBA)

---

## 📑 Daftar Isi

1. [Ringkasan Perubahan](#ringkasan-perubahan)
2. [Detail Perubahan per Fitur](#detail-perubahan-per-fitur)
3. [File yang Diubah](#file-yang-diubah)
4. [Struktur Database](#struktur-database)
5. [Cara Kerja Fitur](#cara-kerja-fitur)

---

## 🎯 Ringkasan Perubahan

### Fitur Utama yang Ditambahkan:
1. ✅ **Validasi NIK Real-time dengan Modal Overlay** - Mencegah duplikasi data
2. ✅ **Kolom Sumber Dana** - Tracking sumber dana bantuan
3. ✅ **Fitur Pencarian by NIK** - Di halaman Beneficiaries dan Monev
4. ✅ **Perbaikan Metadata File Blade** - Menghapus metadata yang tidak perlu

---

## 📝 Detail Perubahan per Fitur

### 1. Validasi NIK Real-time dengan Modal Overlay

#### **Deskripsi:**
Fitur untuk mengecek NIK secara real-time saat user mengetik di form tambah data. Jika NIK sudah terdaftar, akan muncul modal overlay yang menampilkan semua data bantuan yang sudah diterima oleh NIK tersebut.

#### **File yang Diubah:**

**a. Controller: `app/Http/Controllers/BeneficiaryController.php`**
- **Method Baru:** `checkNik(Request $request)`
  - Fungsi: Mengecek apakah NIK sudah terdaftar di database
  - Return: JSON response dengan data semua bantuan yang sudah diterima
  - Lokasi: Baris 79-118

**b. Routes: `routes/web.php`**
- **Route Baru:** `GET /beneficiaries/check-nik`
  - Route name: `beneficiaries.checkNik`
  - Lokasi: Baris 28

**c. View: `resources/views/beneficiaries/create.blade.php`**
- **Modal Overlay:** Ditambahkan modal modern dengan desain profesional
  - Header dengan gradient hijau (tema pertanian)
  - Summary card dengan statistik
  - Timeline accordion untuk multiple data
  - Lokasi: Baris 178-240

- **JavaScript:**
  - Function `checkNik()` - Validasi real-time dengan debounce 500ms
  - Function `displayExistingData()` - Menampilkan data dalam format timeline
  - Function `showModal()` / `hideModal()` - Animasi modal
  - Function `toggleBeneficiaryDetail()` - Accordion expand/collapse
  - Lokasi: Baris 531-818

#### **Fitur Modal:**
- ✅ Summary statistics (Total, Diterima, Selesai, Terdaftar, Ditolak)
- ✅ Timeline accordion dengan visual connector
- ✅ Compact header (selalu terlihat)
- ✅ Expandable detail (klik untuk expand)
- ✅ Menampilkan: Kuantitas, Nomor HP, Kelompok Tani, Sumber Dana, Alamat, Link Dokumen
- ✅ Tombol aksi: "Gunakan Data yang Sudah Ada" dan "Lanjutkan Input Baru"

---

### 2. Kolom Sumber Dana

#### **Deskripsi:**
Menambahkan field "Sumber Dana" untuk tracking sumber dana bantuan dengan opsi: DBHCHT, DAK NON FISIK, DAK FISIK, PAD, dan LAINNYA (dengan input manual).

#### **File yang Diubah:**

**a. Database Migration: `database/migrations/2025_12_13_171035_add_sumber_dana_to_beneficiaries_table.php`**
- **Kolom Baru:** `sumber_dana` (string, nullable)
- **Posisi:** Setelah kolom `link`
- **Method `up()`:** Menambahkan kolom
- **Method `down()`:** Menghapus kolom (rollback)

**b. Model: `app/Models/Beneficiary.php`**
- **Field Baru:** `'sumber_dana'` ditambahkan ke `$fillable`
- Lokasi: Baris 24

**c. Controller: `app/Http/Controllers/BeneficiaryController.php`**
- **Method `store()`:**
  - Validasi: `'sumber_dana'` dan `'sumber_dana_lainnya'`
  - Logika: Jika "LAINNYA" dipilih, gunakan nilai dari input manual
  - Lokasi: Baris 133-137, 160

- **Method `update()`:**
  - Validasi dan logika yang sama dengan `store()`
  - Lokasi: Baris 199-203, 206

- **Method `checkNik()`:**
  - Return field `sumber_dana` di response JSON
  - Lokasi: Baris 107

**d. View Create: `resources/views/beneficiaries/create.blade.php`**
- **Field Dropdown:** Sumber Dana dengan 5 opsi
- **Input Manual:** Muncul otomatis saat pilih "LAINNYA"
- **JavaScript:** Toggle input manual
- Lokasi: Baris 103-115, 927-945

**e. View Edit: `resources/views/beneficiaries/edit.blade.php`**
- **Field Dropdown:** Sama dengan create
- **Logika PHP:** Deteksi otomatis jika data adalah "LAINNYA"
- **JavaScript:** Toggle input manual
- Lokasi: Baris 133-150, 488-506

**f. View Index: `resources/views/beneficiaries/index.blade.php`**
- **Kolom Baru:** "Sumber Dana" di tabel
- **Badge:** Warna ungu untuk menampilkan sumber dana
- Lokasi: Baris 109, 137-142

**g. Modal Timeline: `resources/views/beneficiaries/create.blade.php`**
- **Field Baru:** Sumber Dana ditampilkan di detail section
- **Posisi:** Di bawah Kelompok Tani (kolom 2, baris 2)
- Lokasi: Baris 808-820

---

### 3. Fitur Pencarian by NIK

#### **Deskripsi:**
Menambahkan field pencarian NIK di halaman Beneficiaries dan Monev untuk memudahkan pencarian data.

#### **File yang Diubah:**

**a. Controller Beneficiaries: `app/Http/Controllers/BeneficiaryController.php`**
- **Method `index()`:**
  - Parameter baru: `$search_nik`
  - Query filter: `where('nik', 'like', '%' . $search_nik . '%')`
  - Pass ke view: `'search_nik'`
  - Lokasi: Baris 16, 33-36, 63

**b. View Beneficiaries: `resources/views/beneficiaries/index.blade.php`**
- **Field Pencarian:** Input text "Cari NIK"
- **Tombol Cari:** Hijau dengan icon search
- **Layout:** Grid 5 kolom (admin) / 4 kolom (user)
- Lokasi: Baris 48-54, 73-88

**c. Controller Monev: `app/Http/Controllers/MonevController.php`**
- **Method `index()`:**
  - Parameter baru: `$search_nik`
  - Query filter: `where('nik', 'like', '%' . $search_nik . '%')`
  - Pass ke view: `'search_nik'`
  - Lokasi: Baris 15, 30-33, 37

**d. View Monev: `resources/views/monev/index.blade.php`**
- **Field Pencarian:** Input text "Cari NIK"
- **Kolom NIK:** Ditambahkan di tabel
- **Tombol Cari:** Hijau dengan icon search
- Lokasi: Baris 17-22, 61, 77

---

### 4. Perbaikan Metadata File Blade

#### **Deskripsi:**
Menghapus metadata yang tidak perlu (`[file name]`, `[file content begin]`, `[file content end]`) dari file Blade yang menyebabkan error di browser.

#### **File yang Diperbaiki:**

**a. `resources/views/beneficiaries/create.blade.php`**
- Menghapus metadata di baris 1-2 dan baris terakhir
- Status: ✅ Sudah diperbaiki

**b. `resources/views/beneficiaries/index.blade.php`**
- Menghapus metadata di baris 1-2 dan baris terakhir
- Status: ✅ Sudah diperbaiki

**c. `resources/views/beneficiaries/edit.blade.php`**
- Menghapus metadata di baris 1-2 dan baris terakhir
- Status: ✅ Sudah diperbaiki

---

## 📁 File yang Diubah

### Database
1. ✅ `database/migrations/2025_12_13_171035_add_sumber_dana_to_beneficiaries_table.php` (BARU)

### Models
2. ✅ `app/Models/Beneficiary.php`

### Controllers
3. ✅ `app/Http/Controllers/BeneficiaryController.php`
4. ✅ `app/Http/Controllers/MonevController.php`

### Routes
5. ✅ `routes/web.php`

### Views
6. ✅ `resources/views/beneficiaries/create.blade.php`
7. ✅ `resources/views/beneficiaries/edit.blade.php`
8. ✅ `resources/views/beneficiaries/index.blade.php`
9. ✅ `resources/views/monev/index.blade.php`

---

## 🗄️ Struktur Database

### Tabel: `beneficiaries`

#### Kolom Baru:
- **`sumber_dana`** (string, nullable)
  - Tipe: VARCHAR
  - Nullable: Yes
  - Posisi: Setelah kolom `link`
  - Default: NULL

#### Kolom yang Sudah Ada (Relevan):
- `nik` - Untuk pencarian
- `nama` - Data penerima
- `alamat` - Alamat lengkap
- `nomor_hape` - Nomor HP
- `kelompok_tani` - Kelompok tani
- `jenis_bantuan` - Jenis bantuan
- `tahun` - Tahun bantuan
- `kuantitas` - Jumlah bantuan
- `status` - Status bantuan
- `link` - Link dokumen
- `sumber_dana` - **BARU**
- `keterangan` - Keterangan
- `latitude` - Koordinat latitude
- `longitude` - Koordinat longitude

---

## ⚙️ Cara Kerja Fitur

### 1. Validasi NIK Real-time

#### Flow:
```
User mengetik NIK
    ↓
JavaScript debounce (500ms)
    ↓
AJAX Request ke /beneficiaries/check-nik
    ↓
Controller checkNik() query database
    ↓
Return JSON response
    ↓
JavaScript displayExistingData()
    ↓
Modal muncul dengan animasi
    ↓
User pilih aksi:
  - Gunakan Data yang Sudah Ada (auto-fill form)
  - Lanjutkan Input Baru
```

#### Teknologi:
- **Frontend:** JavaScript (Vanilla), AJAX, Tailwind CSS
- **Backend:** Laravel Controller, Eloquent ORM
- **API:** RESTful JSON Response

### 2. Sumber Dana

#### Flow:
```
User pilih dropdown "Sumber Dana"
    ↓
Jika pilih "LAINNYA"
    ↓
Input manual muncul (JavaScript toggle)
    ↓
User input sumber dana manual
    ↓
Form submit
    ↓
Controller validasi
    ↓
Jika "LAINNYA" + ada input manual
    ↓
Simpan nilai dari input manual
    ↓
Jika pilih opsi lain
    ↓
Simpan nilai dari dropdown
```

#### Validasi:
- `sumber_dana`: nullable, string, max:255
- `sumber_dana_lainnya`: nullable, string, max:255
- Logika: Jika "LAINNYA" dipilih, gunakan `sumber_dana_lainnya`

### 3. Pencarian NIK

#### Flow:
```
User input NIK di field pencarian
    ↓
Klik tombol "Cari" atau Enter
    ↓
Form submit GET request
    ↓
Controller terima parameter search_nik
    ↓
Query: where('nik', 'like', '%search_nik%')
    ↓
Return filtered data
    ↓
View menampilkan hasil
```

#### Query:
```php
if ($search_nik) {
    $query->where('nik', 'like', '%' . $search_nik . '%');
}
```

---

## 🎨 Desain UI/UX

### Modal Overlay
- **Backdrop:** Blur effect dengan opacity 75%
- **Animasi:** Scale dan fade transition
- **Header:** Gradient hijau (from-green-600 to-green-700)
- **Summary Card:** Statistik dengan icon dan badge
- **Timeline:** Visual connector dengan numbered badge
- **Accordion:** Smooth expand/collapse animation

### Badge Colors
- **Sumber Dana:** Purple (bg-purple-100, text-purple-800)
- **Status Terdaftar:** Yellow
- **Status Diterima:** Green
- **Status Ditolak:** Red
- **Status Selesai:** Blue

---

## 🔧 Teknologi yang Digunakan

### Backend
- **Framework:** Laravel 9.x
- **Database:** MySQL/MariaDB
- **ORM:** Eloquent
- **Validation:** Laravel Request Validation

### Frontend
- **CSS Framework:** Tailwind CSS
- **JavaScript:** Vanilla JavaScript (ES6+)
- **Maps:** Leaflet.js
- **Icons:** SVG (Heroicons style)

### API
- **Format:** JSON
- **Method:** GET (untuk check NIK)
- **Response:** RESTful

---

## 📊 Statistik Perubahan

### File yang Diubah: 9 file
### File Baru: 1 file (migration)
### Method Baru: 1 method (checkNik)
### Route Baru: 1 route
### Kolom Database Baru: 1 kolom
### Fitur Baru: 3 fitur utama

---

## 🧪 Testing Checklist

### Validasi NIK
- [ ] Input NIK yang belum terdaftar → Modal tidak muncul
- [ ] Input NIK yang sudah terdaftar → Modal muncul dengan data
- [ ] Multiple data → Timeline accordion berfungsi
- [ ] Klik "Gunakan Data yang Sudah Ada" → Form terisi otomatis
- [ ] Klik "Lanjutkan Input Baru" → Form tetap kosong
- [ ] Close modal (X, backdrop, Escape) → Modal tertutup

### Sumber Dana
- [ ] Pilih opsi dari dropdown → Tersimpan dengan benar
- [ ] Pilih "LAINNYA" → Input manual muncul
- [ ] Input manual → Tersimpan dengan benar
- [ ] Edit data dengan "LAINNYA" → Input manual muncul otomatis
- [ ] Kolom muncul di tabel index

### Pencarian NIK
- [ ] Pencarian di Beneficiaries → Hasil sesuai
- [ ] Pencarian di Monev → Hasil sesuai
- [ ] Partial match → Berfungsi (contoh: cari "520" menemukan "5201238978310")
- [ ] Reset filter → Semua filter terhapus

---

## 📚 Penjelasan untuk Dosen Pembimbing

### 1. Validasi NIK Real-time
**Tujuan:** Mencegah duplikasi data dan memberikan informasi lengkap tentang riwayat bantuan penerima.

**Implementasi:**
- Menggunakan AJAX untuk komunikasi real-time tanpa reload halaman
- Debounce 500ms untuk mengurangi beban server
- Modal overlay dengan desain profesional menggunakan Tailwind CSS
- Timeline accordion untuk menampilkan multiple data secara rapi

**Keuntungan:**
- User experience lebih baik (tidak perlu submit form dulu untuk tahu duplikasi)
- Data lebih akurat (user bisa lihat riwayat lengkap)
- Mencegah human error (duplikasi data)

### 2. Kolom Sumber Dana
**Tujuan:** Tracking sumber dana bantuan untuk akuntabilitas dan pelaporan.

**Implementasi:**
- Database migration untuk menambahkan kolom baru
- Dropdown dengan opsi standar + input manual untuk fleksibilitas
- JavaScript untuk toggle input manual secara dinamis
- Validasi di controller untuk memastikan data valid

**Keuntungan:**
- Data lebih lengkap untuk pelaporan
- Fleksibel (bisa input manual jika tidak ada di opsi)
- Konsisten di semua form (create, edit, index)

### 3. Pencarian NIK
**Tujuan:** Memudahkan pencarian data penerima bantuan berdasarkan NIK.

**Implementasi:**
- Query LIKE untuk partial match
- Filter terintegrasi dengan filter lain (tahun, jenis, bidang)
- Konsisten di halaman Beneficiaries dan Monev

**Keuntungan:**
- Efisiensi waktu (tidak perlu scroll manual)
- User-friendly (partial match lebih fleksibel)
- Konsisten di semua halaman

---

## 🔍 Detail Teknis

### API Endpoint Baru

**Route:** `GET /beneficiaries/check-nik?nik={nik}`

**Request:**
```
GET /beneficiaries/check-nik?nik=5201238978310
```

**Response (NIK ditemukan):**
```json
{
    "exists": true,
    "total": 3,
    "data": [
        {
            "id": 1,
            "nama": "Firman Maulana",
            "alamat": "BERTONG KDJBAKJBKDA",
            "nomor_hape": "081236893055",
            "kelompok_tani": "MABRU BANGET",
            "jenis_bantuan": "Bibit Padi Unggul",
            "tahun": 2025,
            "kuantitas": 100,
            "status": "ditolak",
            "link": "https://drive.google.com/...",
            "sumber_dana": "DAK NON FISIK",
            "keterangan": null,
            "created_at": "12/12/2025 14:23"
        }
    ]
}
```

**Response (NIK tidak ditemukan):**
```json
{
    "exists": false
}
```

### JavaScript Functions

**1. `checkNik(nik)`**
- Validasi input (min 10 karakter)
- Debounce 500ms
- AJAX request ke API
- Update UI berdasarkan response

**2. `displayExistingData(data, total)`**
- Generate summary statistics
- Generate timeline accordion HTML
- Format data dengan icon dan badge

**3. `toggleBeneficiaryDetail(itemId)`**
- Toggle accordion expand/collapse
- Rotate icon
- Smooth animation

**4. `showModal()` / `hideModal()`**
- Animate modal appearance
- Prevent body scroll
- Handle backdrop click

---

## 📝 Catatan Penting

1. **Migration sudah dijalankan** - Kolom `sumber_dana` sudah ada di database
2. **Modal menggunakan z-index 50** - Pastikan tidak tertutup elemen lain
3. **Debounce 500ms** - Balance antara responsivitas dan performa
4. **Partial match search** - Menggunakan LIKE untuk fleksibilitas
5. **Responsive design** - Grid layout menyesuaikan screen size

---

## 🚀 Cara Menjalankan

1. Pastikan migration sudah dijalankan:
   ```bash
   php artisan migrate
   ```

2. Clear cache jika perlu:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

3. Akses aplikasi:
   - Beneficiaries: http://localhost:8000/beneficiaries
   - Monev: http://localhost:8000/monev

---

## 📞 Support

Jika ada pertanyaan atau masalah, silakan cek:
1. Log file: `storage/logs/laravel.log`
2. Browser console untuk JavaScript errors
3. Network tab untuk API requests

---

**Dokumentasi ini dibuat untuk keperluan konsultasi dengan dosen pembimbing.**
