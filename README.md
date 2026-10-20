# 🌍 Sistem Pendukung Keputusan (SPK) SAW & GIS Pemetaan Lahan Pertanian

Aplikasi berbasis web yang mengintegrasikan **Sistem Informasi Geografis (SIG / GIS)** berbasis peta satelit interaktif dengan **Sistem Pendukung Keputusan (SPK)** menggunakan metode **Simple Additive Weighting (SAW)** untuk menentukan rekomendasi pupuk terbaik bagi lahan pertanian.

---

## 🚀 1. Spesifikasi Aplikasi

* **Framework Backend:** Laravel 13 (PHP 8.3+)
* **Database Management System (DBMS):** MySQL / MariaDB
* **Frontend Template:** Clean Minimalist Dashboard UI (Bootstrap & Themify Icons)
* **GIS & Mapping Library:** 
  * **Leaflet.js** (Render peta satelit interaktif)
  * **Leaflet-Geoman** (Tool menggambar dan memodifikasi poligon lahan)
* **Metode Keputusan:** *Simple Additive Weighting (SAW)* dengan normalisasi matriks, perkalian bobot kriteria (*benefit* / *cost*), dan perankingan skor preferensi ($V_i$).

---

## 👥 2. Alur Aplikasi & Hak Akses (Role-Based Access)

Aplikasi ini membagi hak akses menjadi **3 entitas utama** sesuai dengan alur sistem:

### 1. 🛡️ Admin (Aktor Utama / Pengelola Penuh)
* **Autentikasi:** Wajib login ke sistem.
* **Fitur & Alur Kerja:**
  * Mengelola **Master Data Kriteria** (menentukan bobot persen dan atribut *benefit* atau *cost*).
  * Mengelola **Master Data Alternatif** (data jenis pupuk).
  * Mengelola **Master Data Pemilik Lahan** (nama, alamat, dan nomor HP).
  * Mengakses **Dashboard GIS Pemetaan**: Menggunakan *tools* Leaflet-Geoman untuk menggambar batas poligon lahan di atas peta satelit, memilih pemilik, lalu menyimpannya ke database.
  * Memberikan **Penilaian Skala (1–5)** pada setiap kriteria untuk setiap alternatif pupuk pada suatu lahan.
  * Menjalankan **Perhitungan SAW** untuk menghasilkan perangkingan pupuk terbaik secara otomatis.

### 2. 👔 Pimpinan (Pengambil Keputusan)
* **Autentikasi:** Wajib login dengan akun ber-role *pimpinan*.
* **Fitur & Alur Kerja:**
  * Mengakses **Dashboard Utama** untuk memantau statistik total lahan, pemilik, dan kriteria.
  * Membuka menu **Laporan SAW** untuk melihat daftar lahan yang sudah dianalisis.
  * Melakukan **Cetak Dokumen / Print Laporan Resmi** yang berisi rincian lengkap 6 tahapan perhitungan SAW per lahan beserta tanda tangan pengesahan.
  * *Batasan:* Bersifat *Read-Only* pada peta (tidak dapat menggambar, menambah, atau menghapus data master).

### 3. 👨‍🌾 Masyarakat / Publik
* **Autentikasi:** **Tanpa Login (Publik)**.
* **Fitur & Alur Kerja:**
  * Mengakses **Landing Page Utama** (`/`) yang menampilkan informasi ringkas, statistik wilayah, informasi pupuk, serta **Peta Interaktif Publik**.
  * Dapat mengklik poligon lahan berwarna di atas peta untuk melihat *pop-up* informasi **Nama Pemilik**, **Luas Lahan**, dan **Rekomendasi Pupuk Terbaik** hasil analisis sistem.

---

## 🛠️ 3. Cara Instalasi Aplikasi (Lokal / Laragon)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal:

### 1. Clone Repository
* ** Buka terminal (Git Bash / Command Prompt) di direktori *web server* kamu (contoh: `C:\laragon\www`), lalu jalankan:
* git clone [https://github.com/Syamra/GIS-Pemetaan-Lahan-dan-SPK-Penentuan-Pupuk-Terbaik-Menggunakan-Metode-SAW-Berbasis-WEB-Laravel.git](https://github.com/Syamra/GIS-Pemetaan-Lahan-dan-SPK-Penentuan-Pupuk-Terbaik-Menggunakan-Metode-SAW-Berbasis-WEB-Laravel.git) spk-saw-gis
* cd spk-saw-gis

* ** 2. Install Dependensi PHP & JavaScript

composer install
npm install
npm run build

* ** 3. Konfigurasi Environment (.env)
* Salin file konfigurasi bawaan:
* copy .env.example .env
* Buka file .env menggunakan text editor, lalu sesuaikan konfigurasi database kamu:
* Cuplikan kode
* DB_CONNECTION=mysql
* DB_HOST=127.0.0.1
* DB_PORT=3306
* DB_DATABASE=spk_saw_gis
* DB_USERNAME=root
* DB_PASSWORD=

* ** 4. Generate Application Key
* php artisan key:generate

* ** 5. Jalankan Migrasi & Seeder Database
* Pastikan database spk_saw_gis sudah dibuat di phpMyAdmin / HeidiSQL, lalu jalankan:
* php artisan migrate --seed

* ** 6. Jalankan Server Lokal
* php artisan serve
* Aplikasi sekarang dapat diakses melalui browser di alamat: http://127.0.0.1:8000 atau http://localhost/spk-saw-gis/public/
