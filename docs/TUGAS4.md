# Tugas Mandiri 4 · Dashboard Mahasiswa

Implementasi menggunakan database Eloquent dari tugas sebelumnya. Data contoh tersedia melalui DashboardDemoSeeder; data ditampilkan oleh controller, bukan ditulis langsung pada view. Nama administrator berasal dari config/academic.php (ACADEMIC_ADMIN_NAME di .env), bukan identitas login.

## Pemetaan seluruh 21 langkah

| Langkah | Ketentuan | Implementasi |
|---|---|---|
|1|Data mahasiswa dan mahasiswa/index.blade.php|resources/views/mahasiswa/index.blade.php; MahasiswaController@index; DashboardDemoSeeder|
|2|Sapaan dengan variabel|DashboardController@index mengirim administrator; dashboard.blade.php memakai `{{ $administrator }}`|
|3|Status dengan @if|components/badge.blade.php memakai @if/@elseif/@else; komponen UI menunjukkan contoh boolean Aktif/Nonaktif|
|4|@forelse, $loop->iteration, empty state|mahasiswa/index.blade.php; nomor melanjutkan offset pagination|
|5|Layout header, sidebar, konten, footer; @yield|layouts/app.blade.php|
|6|Dashboard @extends dan @section|resources/views/dashboard.blade.php|
|7|CSS sesuai Tailwind dan @vite|Tailwind 4 via @import dan @tailwindcss/vite; resources/css/app.css; resources/js/app.js; vite.config.js; layouts/app.blade.php|
|8|Navbar partial|partials/navbar.blade.php: nama aplikasi, judul halaman, dan administrator|
|9|Sidebar Dashboard, Data Mahasiswa, Laporan|partials/sidebar.blade.php; tambahan Mata Kuliah dan Komponen UI|
|10|@include partial|layouts/app.blade.php|
|11|Named route dan active route|admin.dashboard; request()->routeIs pada sidebar, aria-current=page|
|12|x-card|components/card.blade.php; dipakai pada partial statistics; putih, border, rounded, shadow|
|13|x-alert empat jenis|components/alert.blade.php; success/warning/info/error; contoh di /admin/komponen|
|14|x-badge tiga status|components/badge.blade.php; aktif hijau, cuti kuning, nonaktif abu-abu|
|15|x-button dan $attributes|components/button.blade.php; primary/secondary; merge class dan atribut; contoh tambahan class + data-testid|
|16|x-panel default dan named slot|components/panel.blade.php; $slot, header, actions|
|17|Dua panel markup berbeda|dashboard.blade.php: panel penjelasan dan panel tabel dengan tombol aksi|
|18|Statistik dari controller|DashboardController::statistics; total/aktif/cuti/nonaktif dari database|
|19|Minimal 3 kartu|4 x-card melalui partial statistics|
|20|Mahasiswa terbaru|Controller mengambil 5 record ID terbaru; tabel nomor, nama, NIM, prodi, status; @forelse dan x-badge|
|21|x-empty-state|components/empty-state.blade.php, dipakai pada tabel dashboard, index, dan laporan|

Langkah 3 mencontohkan status dua kondisi; langkah 14/18 membutuhkan tiga status. Data aplikasi mempertahankan cuti sebagai status tersendiri. Contoh dua kondisi terpisah tersedia di halaman Komponen UI agar cuti tidak salah dilabeli Nonaktif.

## Data dan migration

`2026_11_01_000001_add_status_to_mahasiswas_table.php` menambah kolom string status dengan default aktif dan index. Default ini mengisi mahasiswa lama. MahasiswaRequest mewajibkan status aktif/cuti/nonaktif saat create/update. Model memasukkan status pada fillable. Tugas 2 tetap memakai migration aslinya; tidak ada reset data.

## Alur singkat

GET /admin → route admin.dashboard → DashboardController@index → query statistik + lima mahasiswa → dashboard.blade.php → layouts/app → partial + components → response HTML. Vite memuat CSS/JS melalui manifest hasil build atau dev server.

## Skenario uji browser dan screenshot

| No | Tindakan | Hasil yang diperiksa / screenshot |
|---|---|---|
|1|Jalankan php artisan serve dan npm run dev|Kedua terminal aktif; dashboard memperoleh CSS|
|2|Buka /admin|Sapaan, 4 kartu, 2 panel, tabel terbaru, sidebar Dashboard aktif|
|3|Seed data contoh bila diperlukan|Badge Aktif, Cuti, Nonaktif terlihat; jumlah sesuai data|
|4|Buka Data Mahasiswa|Menu aktif berpindah; daftar dan filter status tampil|
|5|Edit satu status aktif menjadi cuti|Flash sukses; dashboard aktif berkurang satu dan cuti bertambah satu|
|6|Cari teks yang tidak ada|Empty state muncul pada tabel tanpa menghapus database|
|7|Buka dashboard pada database uji kosong|Angka 0, alert informasi dan x-empty-state|
|8|Buka Komponen UI|Empat variasi alert, tiga badge, dua tombol, contoh boolean|
|9|Buka Laporan|Ringkasan dan jumlah per prodi sesuai database|
|10|Kecilkan layar|Sidebar/form/tabel tetap dapat digunakan; tabel horizontal scroll|
|11|Buka source layout|Screenshot @vite, @include navbar/sidebar, @yield|
|12|Buka source dashboard dan panel|Screenshot @extends, @section, x-slot:header/actions, $slot|
|13|Buka source card/alert/badge/button|Screenshot @props, @if, $attributes->merge|
|14|Buka source tabel|Screenshot @forelse, $loop->iteration, @empty, x-empty-state|
|15|Jalankan php verify.php|Output route:list, view:cache dan tests tersimpan ke docs/evidence|

Simpan screenshot aktual di docs/screenshots jika diperlukan. Jangan mengambil screenshot .env. Tidak ada screenshot runtime fiktif dalam paket.

## Tes otomatis

DashboardMahasiswaTest memeriksa kondisi kosong, statistik tiga status, lima record terbaru, greeting, menu aktif, filter status, update status, penolakan status invalid, komponen/slot/atribut, HTML escaping, dan rekap prodi. Tes CRUD tugas sebelumnya disertakan. Tes HTTP memakai withoutVite supaya tidak memerlukan dev server; build aset diperiksa terpisah dengan npm run build. Database tes memakai SQLite :memory:.

## Referensi teknis

- https://laravel.com/docs/12.x/blade
- https://laravel.com/docs/12.x/vite
- https://tailwindcss.com/docs/installation/using-vite
