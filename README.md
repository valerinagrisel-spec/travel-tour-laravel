# Travel Tour — Kelompok 7

Aplikasi berbasis web untuk pengelolaan layanan perjalanan wisata (*Travel Tour*) yang dikembangkan menggunakan Laravel dan MySQL sebagai bagian dari tugas praktikum Rekayasa Sistem Informasi.

## 1. Teknologi yang Digunakan

- PHP dan Laravel
- MySQL
- Composer
- HTML, CSS, JavaScript, dan Blade
- XAMPP (untuk MySQL)
- Ubuntu WSL atau terminal dengan PHP yang kompatibel
- Git dan GitHub

## 2. Persiapan Sebelum Menjalankan Project

Pastikan perangkat sudah memiliki:

1. PHP versi yang sesuai dengan `composer.json` (disarankan PHP 8.3 untuk lingkungan pengembangan kelompok).
2. Composer.
3. MySQL melalui XAMPP atau instalasi MySQL lainnya.
4. Git.
5. Node.js dan npm jika diperlukan untuk membangun aset frontend.

## 3. Clone Repository

Buka terminal, kemudian jalankan:

```bash
git clone https://github.com/valerinagrisel-spec/travel-tour-laravel.git
```

Masuk ke folder project:

```bash
cd travel-tour-laravel
```

Jika project diletakkan di folder XAMPP, lokasi yang dapat digunakan adalah:

```text
C:\xampp\htdocs\travel-tour-laravel
```

Untuk pengguna Ubuntu WSL:

```bash
cd /mnt/c/xampp/htdocs/travel-tour-laravel
```

## 4. Instalasi Dependency Laravel

Jalankan perintah:

```bash
composer install
```

Perintah tersebut akan memasang seluruh dependency PHP yang diperlukan oleh aplikasi.

## 5. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

**Ubuntu WSL / Linux:**

```bash
cp .env.example .env
```

**Windows PowerShell:**

```powershell
Copy-Item .env.example .env
```

Kemudian generate application key:

```bash
php artisan key:generate
```

## 6. Membuat Database MySQL

1. Jalankan XAMPP.
2. Aktifkan layanan MySQL.
3. Buka `http://localhost/phpmyadmin`.
4. Buat database baru dengan nama:

```text
travel_tour
```

Tidak perlu membuat tabel secara manual karena tabel akan dibuat menggunakan Laravel Migration.

## 7. Konfigurasi Koneksi Database

Buka file `.env`, kemudian sesuaikan konfigurasi berikut:

```dotenv
APP_NAME="Travel Tour"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travel_tour
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:**

- Pengaturan di atas merupakan contoh untuk MySQL XAMPP dengan konfigurasi lokal standar.
- Jika MySQL menggunakan password atau port berbeda, sesuaikan nilainya.
- Pengguna WSL perlu memastikan MySQL XAMPP dapat diakses dari WSL. Jika koneksi `127.0.0.1` gagal, periksa konfigurasi jaringan dan host MySQL.
- Jangan mengunggah file `.env` ke GitHub.

## 8. Menjalankan Migration

Setelah database dibuat dan konfigurasi `.env` selesai, jalankan:

```bash
php artisan migrate
```

Perintah tersebut membuat tabel sesuai migration yang tersedia pada project.

Jika terjadi kesalahan, periksa apakah MySQL sudah aktif dan konfigurasi database sudah sesuai.

## 9. Menyiapkan Frontend

Jika diperlukan oleh konfigurasi Vite pada project, jalankan:

```bash
npm install
npm run build
```

Perintah tersebut digunakan untuk memasang dependency frontend dan membangun aset CSS/JavaScript.

## 10. Menyiapkan Storage

Jalankan:

```bash
php artisan storage:link
```

Perintah ini membuat tautan storage publik untuk file yang disimpan melalui Laravel, apabila fitur terkait menggunakannya.

## 11. Menjalankan Aplikasi

Bersihkan cache konfigurasi terlebih dahulu:

```bash
php artisan optimize:clear
```

Kemudian jalankan server Laravel:

```bash
php artisan serve
```

Buka browser dan akses:

```text
http://127.0.0.1:8000
```

Pastikan terminal server tetap aktif selama aplikasi digunakan.

## 12. Fitur dan Role Pengguna

Aplikasi Travel Tour memiliki beberapa role pengguna:

| Role | Fitur Utama |
|---|---|
| Admin | Mengelola paket wisata, driver, kendaraan, dan penugasan perjalanan |
| Staff | Memverifikasi pembayaran dan pemesanan, memeriksa pelanggan, serta mengelola laporan transaksi |
| Customer | Melihat paket wisata, melakukan pemesanan, mengunggah bukti pembayaran, dan memeriksa status pesanan |
| Driver | Melihat jadwal tour dan detail penugasan |
| Manager | Meninjau laporan transaksi serta mengekspor laporan |

Akses masing-masing halaman mengikuti role akun yang digunakan.

Untuk pengujian awal, buat akun melalui halaman registrasi apabila fitur tersebut tersedia. Akun dengan role khusus seperti Admin, Staff, Driver, dan Manager perlu disiapkan sesuai mekanisme pengelolaan pengguna pada project.

## 13. Melakukan Update Project

Apabila terdapat perubahan kode terbaru di GitHub, masuk ke folder project lalu jalankan:

```bash
git pull origin main
```

Jika terdapat perubahan dependency atau migration, jalankan kembali perintah yang relevan:

```bash
composer install
php artisan migrate
php artisan optimize:clear
```

Jika terdapat perubahan frontend, jalankan kembali:

```bash
npm install
npm run build
```

Sebelum melakukan `git pull`, pastikan perubahan lokal sudah disimpan atau di-commit agar tidak terjadi konflik.

## 14. Troubleshooting

**A. Error koneksi database**

Periksa layanan MySQL, nama database, username, password, port, dan host yang digunakan.

**B. Error `No application encryption key has been specified`**

Jalankan:

```bash
php artisan key:generate
```

**C. Error `Class not found` atau masalah dependency**

Jalankan:

```bash
composer install
composer dump-autoload
```

**D. Perubahan route atau tampilan tidak muncul**

Jalankan:

```bash
php artisan optimize:clear
```

**E. Menampilkan daftar route aplikasi**

Jalankan:

```bash
php artisan route:list
```

**F. Gambar dari storage tidak muncul**

Pastikan file tersedia dan jalankan:

```bash
php artisan storage:link
```

## 15. Catatan Pengembangan

- Gunakan database lokal masing-masing.
- File `.env` tidak boleh diunggah ke GitHub.
- Jangan memasukkan password, token, atau data pribadi pengguna ke repository.
- Perubahan fitur sebaiknya mengikuti Use Case Diagram, Activity Diagram, Sequence Diagram, Class Diagram, dan Physical Data Model pada dokumen praktikum kelompok.
- Sebelum melakukan perubahan pada fitur utama, koordinasikan dengan anggota kelompok untuk menghindari konflik kode.

---

**Kelompok 7 — Travel Tour**

Repository: https://github.com/valerinagrisel-spec/travel-tour-laravel
