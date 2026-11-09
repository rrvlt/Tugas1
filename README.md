# VoltAcademic · Database Engineering dengan Laravel

Laravel 12 + Eloquent + Blade + Vite. Lanjutan VoltAcademic tugas 4 dengan Program Studi, Profil Mahasiswa, KRS per periode, serta Dosen–Keahlian.

## Instalasi setelah clone baru

PHP >=8.2, Composer, SQLite extension, Node >=22.12 dan npm.

```powershell
git clone --branch database-engineering https://github.com/rrvlt/Tugas1.git
cd Tugas1
composer install
Copy-Item .env.example .env
New-Item database/database.sqlite -ItemType File
php artisan key:generate
php artisan migrate
npm.cmd ci
npm.cmd run build
php verify.php
php artisan serve
```

Clone berlaku setelah branch di-push. Copy .env, buat database dan generate key hanya untuk instalasi baru. Jangan menimpa konfigurasi/database atau mengganti APP_KEY existing. Untuk pengembangan, jalankan `npm.cmd run dev` pada terminal kedua. Buka http://127.0.0.1:8000/admin.

## Database dan demo

Migration menormalisasi prodi teks dari tugas 4 menjadi FK dan membuat tabel relasi. Tidak ada migrate:fresh. Untuk data contoh opsional: `php artisan db:seed --class=RelationshipDemoSeeder`.

Mulai dari master Program Studi dan Mata Kuliah, kemudian tambah mahasiswa/profil/KRS. Untuk tugas mandiri, isi master Keahlian lalu Dosen. Detail Mahasiswa memfilter KRS per periode; saat mengubah periode, klik Edit dari detail periode yang benar agar checkbox sesuai. Mode ganti hanya mengubah periode terpilih; mode tambah memakai syncWithoutDetaching. Status disetujui masih latihan pivot, belum persetujuan berbasis akun.

## Dokumentasi dan pengujian

- [Relasi, constraint, delete rules, transaction dan pemetaan modul](docs/DATABASE_ENGINEERING.md)
- [Checklist praktikum dan screenshot](docs/UJI_DATABASE.md)
- [Skema SQLite referensi](docs/schema-sqlite.sql)
- [Bukti pengujian paket](docs/validation-database/tests.txt)

`php verify.php` membuat hasil aktual pada docs/evidence. Tes otomatis menggunakan SQLite in-memory. Aset dibangun terpisah dengan npm run build. Dokumen tugas 4 dan bukti lamanya dipertahankan sebagai riwayat; bukti versi Database Engineering ada pada validation-database.

## Pengumpulan

```powershell
git status
git branch --show-current
git add .
git diff --cached --stat
git commit -m "ADD DATABASE ENGINEERING dengan laravel"
git push -u origin database-engineering
```

Gunakan branch database-engineering. Jangan commit .env, database, vendor, node_modules atau backup. Setelah berhasil push, kumpulkan https://github.com/rrvlt/Tugas1/tree/database-engineering di Edlink jika repository tetap sama. Pengumpulan tidak dilakukan otomatis oleh aplikasi.

## Batasan

Belum memiliki autentikasi/otorisasi dan ditujukan untuk praktikum lokal. Pengujian dilakukan pada Linux/PHP 8.3.6/Laravel 12.70.2/SQLite. Installer Windows dan database MySQL belum diuji di lingkungan pembuatan.
