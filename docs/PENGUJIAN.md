# Pengujian VoltAcademic

## Bukti otomatis

Jalankan `php verify.php` di root aplikasi. File `docs/evidence/route-list.txt`, `route-admin.txt`, `tests.txt`, dan `status.json` akan berisi hasil aktual dengan waktu dan exit code. Exit code 0 berarti perintah berhasil. Kegagalan tidak boleh disebut lulus. Tes memakai RefreshDatabase dan SQLite :memory: sehingga tidak menghapus data aplikasi.

Dua kelas CRUD mencakup: index/create/show/edit, tambah, update tanpa mengganti nilai unik, update nama, delete, flash message, required, format/rentang invalid, duplikat pada create/update, missing ID 404, pencarian dan pagination. Empat tes Explorer lama dipertahankan. CSRF tidak dijalankan oleh HTTP test Laravel; token dan method spoofing diperiksa markup-nya. Interaksi dialog browser diperiksa manual.

## Skenario browser, lakukan untuk kedua resource

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
|1|Buka /admin dan index masing-masing modul|Dashboard, jumlah data, tabel/empty state terlihat|
|2|Klik Tambah, kirim kosong|Tidak tersimpan; validasi browser atau pesan required muncul|
|3|Isi data valid lalu simpan|Redirect ke detail, flash sukses, data persisten setelah refresh|
|4|Tambah NIM/email atau kode yang sudah dipakai|Pesan duplikat, input sebelumnya tetap tampil|
|5|Email salah; angkatan <2000; SKS 0/7; semester 0/15|Ditolak tanpa menambah data|
|6|Buka Detail dan Edit|Nilai sesuai data tersimpan|
|7|Edit nama saja tanpa mengganti NIM/email/kode|Berhasil, tidak terkena duplikat milik sendiri|
|8|Edit menjadi NIM/email/kode milik record lain|Ditolak dan data lama tidak berubah|
|9|Cari NIM/kode/nama dan istilah yang tidak ada|Hasil relevan atau empty state; Reset kembali semua data|
|10|Buat >10 record lalu buka halaman berikutnya|Pagination bekerja, filter pencarian tetap dibawa|
|11|Klik Hapus lalu Cancel|Data tetap ada|
|12|Klik Hapus lalu OK|Data dihapus, kembali index, flash sukses, jumlah dashboard berkurang|
|13|Buka URL ID yang telah dihapus|404|
|14|Buka di layar sempit|Navigasi dan form dapat digunakan, tabel bisa digeser|

Gunakan data fiktif, misalnya NIM TEST001, email test001@example.test, kode TEST-WEB.

## Screenshot yang dapat disertakan di repository

Dashboard; index/create/show/edit Mahasiswa dan Mata Kuliah; validasi duplikat; flash message; dialog hapus; terminal route:list; terminal tests. Simpan gambar yang benar-benar kamu ambil pada `docs/screenshots/`, lalu tautkan dari README. Jangan screenshot isi .env.
