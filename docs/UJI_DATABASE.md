# Checklist praktikum Database Engineering

Gunakan data fiktif pada database lokal. `php verify.php` menjalankan route:list, kompilasi Blade dan seluruh tes. Tes memakai SQLite in-memory; tidak menghapus database aplikasi. Hasil saat kamu menjalankan tersimpan pada docs/evidence. Bukti lingkungan pembuatan paket berada di docs/validation-database.

| Skenario | Hasil yang harus terlihat |
|---|---|
|Upgrade dari tugas 4|Jumlah/ID/NIM mahasiswa tetap; nama prodi tampil dari master relasional|
|Tambah Program Studi dan Keahlian|Create/show/edit/index berjalan; kode/nama duplikat ditolak|
|Tambah mahasiswa + profil + prodi|Semua tersimpan; detail menampilkan profil dan nama prodi|
|Edit profil/prodi mahasiswa|Nilai berubah; tidak membuat profil kedua|
|Hapus profil saja|Mahasiswa dan KRS tetap ada|
|Simpan KRS periode A|MK dan total SKS tampil dengan tahun/semester yang dipilih|
|Tambah MK sama lagi pada periode A|Pesan validasi duplikat|
|Tambah MK sama pada periode B|Berhasil; periode A tetap ada|
|Ubah status MK pada periode B|Hanya status baris B berubah|
|Mode tambah KRS|Pilihan baru bertambah, pilihan lama tetap ada|
|Mode ganti KRS|Pilihan periode itu disamakan, periode lain tidak berubah|
|Ganti KRS dengan pilihan kosong|Semua hubungan periode terpilih hilang, master MK tetap ada|
|Lepas MK pada periode B|Baris B hilang, baris A tetap ada|
|Mata kuliah nonaktif|Tidak tersedia untuk pilihan baru; histori tetap tampil|
|Filter mahasiswa berdasarkan MK|Hanya mahasiswa yang memiliki relasi MK tersebut muncul (semua periode)|
|Laporan/master Prodi|Jumlah mahasiswa sesuai database (withCount)|
|Hapus prodi/MK/keahlian yang terpakai|Ditolak oleh aplikasi dan FK database|
|Hapus mahasiswa|Profil dan KRS miliknya terhapus; master prodi/MK tetap ada|
|Tambah Dosen dengan profil dan keahlian|Detail menampilkan prodi, profil, level, tahun mulai|
|Edit keahlian dosen|Pivot berubah sesuai centang, level dan tahun|
|Filter Dosen berdasarkan keahlian|Hanya dosen yang sesuai ditampilkan|
|Hapus Dosen|Profil dan pivot terhapus, master keahlian tetap ada|

## Screenshot untuk bukti

1. Master Program Studi beserta jumlah mahasiswa.
2. Form mahasiswa dengan select prodi, profil dan checkbox MK.
3. Detail mahasiswa yang menampilkan prodi, profil, KRS, total SKS dan status.
4. Dua periode KRS untuk mahasiswa yang sama, membuktikan histori tetap ada.
5. Pesan ketika menghapus master yang masih dipakai / KRS duplikat.
6. Form dan detail Dosen–Keahlian (level dan tahun mulai).
7. Hasil filter mahasiswa berdasarkan MK dan dosen berdasarkan keahlian.
8. Terminal `php artisan route:list --path=admin` dan `php verify.php`.

Simpan screenshot aktual ke docs/screenshots dan tautkan dari README jika dosen memerlukan bukti. Jangan screenshot .env. Tidak ada gambar hasil aplikasi yang direkayasa.
