# SIDIBA (Sistem Informasi Digital Bantuan)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Leaflet](https://img.shields.io/badge/Leaflet-199900?style=for-the-badge&logo=Leaflet&logoColor=white)

## Deskripsi Singkat
**SIDIBA** adalah aplikasi berbasis web yang dibangun dengan Laravel untuk memfasilitasi pengelolaan, pemetaan, dan monitoring evaluasi (Monev) penyaluran bantuan kepada penerima manfaat (khususnya kelompok tani). Aplikasi ini memungkinkan pelacakan distribusi bantuan secara transparan, berbasis lokasi (GIS), dan terintegrasi dengan laporan monitoring berkala.

## Fitur Utama
*   **Manajemen Data Penerima:** CRUD lengkap data penerima bantuan (NIK, Nama, Kelompok Tani, Jenis Bantuan).
*   **Peta Sebaran (GIS):** Visualisasi lokasi penerima bantuan menggunakan peta interaktif (Leaflet.js) dengan fitur clustering.
*   **Monitoring & Evaluasi (Monev):** Modul pencatatan hasil kunjungan lapangan, upload dokumentasi, dan rekomendasi tindak lanjut.
*   **Laporan & Ekspor:** Fitur ekspor data ke format Excel/CSV dan pembuatan laporan hasil monitoring.
*   **Manajemen Master Data:** Pengelolaan jenis bantuan, bidang, dan hak akses pengguna.

## Teknologi yang Digunakan
*   **Backend:** Laravel 9 (PHP)
*   **Frontend:** Blade Templates, Tailwind CSS
*   **Database:** MySQL
*   **Maps:** Leaflet JS & MarkerCluster
*   **Libraries:** Maatwebsite Excel (Import/Export), Sanctum (API Auth)

## Cara Instalasi

1.  **Clone Repository**
    ```bash
    git clone https://github.com/Firmanstmik/SIDIBA-Sistem-Informasi-Digital-Bantuan-.git
    cd SIDIBA-Sistem-Informasi-Digital-Bantuan-
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**
    Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Migration & Seeding**
    ```bash
    php artisan migrate --seed
    ```

5.  **Jalankan Aplikasi**
    ```bash
    npm run dev
    php artisan serve
    ```

## Lisensi
Aplikasi ini bersifat open-source dan dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
