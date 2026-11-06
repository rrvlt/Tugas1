# VoltAcademic · Dashboard Mahasiswa

Laravel 12, Blade, Tailwind CSS 4 dan Vite. Melanjutkan CRUD Mahasiswa/Mata Kuliah dengan statistik status, komponen UI dan laporan mahasiswa.

## Menjalankan setelah clone baru

PHP >=8.2 + Composer, Node.js >=22.12 + npm. Dari PowerShell:

```powershell
git clone --branch tugas-4 https://github.com/rrvlt/Tugas1.git
cd Tugas1
composer install
Copy-Item .env.example .env
New-Item database/database.sqlite -ItemType File
php artisan key:generate
php artisan migrate
npm ci
npm run build
php verify.php
php artisan serve
```

Perintah Copy-Item, New-Item dan key:generate hanya untuk clone baru; jangan menimpa .env/database atau mengganti APP_KEY aplikasi existing. Repository/branch harus sudah di-push sebelum perintah clone bisa digunakan.

Untuk mode pengembangan sesuai tugas, buka terminal kedua di folder aplikasi dan jalankan `npm run dev`. Buka http://127.0.0.1:8000/admin. Laravel dan Vite harus tetap berjalan. Alternatif hasil build cukup menjalankan Laravel setelah npm run build.

## Data dan nama pengguna

Atur `ACADEMIC_ADMIN_NAME="Yoga"` di .env lalu jalankan `php artisan config:clear`. Nama ini untuk tampilan, belum ada login.

Opsional: `php artisan db:seed --class=DashboardDemoSeeder` menambahkan enam mahasiswa fiktif dengan status aktif/cuti/nonaktif. Tidak mengganti record existing. Mahasiswa sebelum tugas 4 mendapat status default aktif saat migration; perbarui sesuai kondisi.

## Halaman

| URL | Fungsi |
|---|---|
|/admin|Sapaan, 4 statistik, 5 mahasiswa terbaru, panel dan empty state|
|/admin/mahasiswa|CRUD, pencarian, filter status dan pagination|
|/admin/mata-kuliah|CRUD mata kuliah|
|/admin/laporan|Rekap status dan program studi|
|/admin/komponen|Variasi alert/badge/button dan contoh kondisi Blade|
|/campus|Halaman Campus Explorer tugas awal|

## Bukti tugas

- [Pemetaan 21 langkah dan checklist screenshot](docs/TUGAS4.md)
- [Route master data](docs/ROUTES.md)
- [Pengujian CRUD](docs/PENGUJIAN.md)

`php verify.php` menyimpan route:list, kompilasi Blade dan hasil tes aktual ke docs/evidence. Tes memakai SQLite :memory: terpisah. Bukti dari lingkungan pembuatan paket berada di docs/validation-build; verifikasi ulang di laptop sebelum mengumpulkan. Jalankan `npm run build` untuk memeriksa aset.

## Push

Di folder ini, pastikan branch tugas-4 lalu:

```powershell
git status
git add .
git diff --cached --stat
git commit -m "Tugas 4: dashboard mahasiswa Blade components dan Vite"
git push -u origin tugas-4
```

Jangan commit .env, database, backup, vendor, node_modules atau public/hot. Commit kedua lockfile dependency. Link pengumpulan sesudah push: https://github.com/rrvlt/Tugas1/tree/tugas-4.

## Batasan

Workspace lokal tanpa autentikasi/otorisasi admin. Nama administrator bukan login. Installer Windows perlu diverifikasi di laptop; tes paket dilakukan pada Linux/PHP 8.3/SQLite. Penggunaan database selain SQLite belum diuji.
