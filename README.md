# VoltAcademic · Mini Project Academic

Laravel 12 · PPWL Tugas 2. Pengembangan CampusInfo dengan master data Mahasiswa dan Mata Kuliah.

## Menjalankan setelah clone

Persyaratan: PHP >=8.2, Composer dan extension pdo_sqlite, mbstring, openssl, fileinfo, curl, dom, xml. Tidak perlu Node/npm. Jalankan melalui PowerShell:

```powershell
git clone --branch tugas-2 https://github.com/rrvlt/Tugas1.git
cd Tugas1
composer install
Copy-Item .env.example .env
New-Item database/database.sqlite -ItemType File
php artisan key:generate
php artisan migrate
php verify.php
php artisan serve
```

Perintah menyalin `.env`, membuat database, dan `key:generate` hanya untuk clone baru. Jangan menimpa konfigurasi/database proyek existing. Jika SQLite sudah ada, lewati New-Item. Buka http://127.0.0.1:8000/admin.

## Data contoh opsional

```powershell
php artisan db:seed --class=AcademicSeeder
```

Tidak perlu menjalankan DatabaseSeeder bawaan. Data demo menggunakan identitas fiktif.

## Verifikasi dan dokumentasi

Jalankan `php verify.php` di terminal kedua. Hasil aktual route:list dan test tersimpan dalam `docs/evidence/`. Tes menggunakan database SQLite in-memory, terpisah dari data aplikasi.

- [Pemetaan route dan ketentuan](docs/ROUTES.md)
- [Skenario pengujian dan screenshot](docs/PENGUJIAN.md)

Setelah semua verifikasi lulus:

```powershell
git status
git branch --show-current
git add .
git diff --cached --stat
git commit -m "Tugas 2: VoltAcademic master data akademik"
git push -u origin tugas-2
```

Pastikan branch aktif tugas-2. Jangan commit .env, vendor, database SQLite atau backup. Link pengumpulan setelah push: https://github.com/rrvlt/Tugas1/tree/tugas-2.

## Cakupan aplikasi

CRUD dengan Eloquent dan migration, Route::resource + model binding, named route di `/admin`, validasi create/update, index/create/show/edit, flash message, konfirmasi delete, pencarian, pagination, dashboard, serta halaman tugas 1 di `/campus` dan URL Explorer lama.

Versi ini adalah fondasi aplikasi lokal, **belum memiliki login dan otorisasi admin**. Prefix `/admin` bukan kontrol akses. Sebelum memakai data mahasiswa nyata di server publik, tambahkan autentikasi, policy/role, audit trail dan proses backup. Database constraint unik tersedia, sedangkan race-condition insert unik bersamaan belum diberi pesan custom.

## Status pengujian paket

Source dan konsistensi struktur diperiksa statis. PHP/Composer tidak tersedia pada lingkungan pembuatan, sehingga tes Laravel, render Blade, dan route:list **belum dieksekusi di sana**. Tidak ada hasil PASS atau screenshot runtime buatan. Hasil aktual akan dibuat oleh `php verify.php` di laptop kamu. Jangan mengumpulkan sebelum semua tes dan skenario manual lulus.
