# Route master data

Definisi berada di `routes/web.php`. Dua `Route::resource` dalam group `prefix('admin')->name('admin.')` masing-masing menghasilkan 7 route. Parameter dipetakan eksplisit: mahasiswa menjadi `{mahasiswa}`, mata-kuliah menjadi `{mataKuliah}`. Controller type-hint model yang cocok, sehingga ID tidak ditemukan menjadi HTTP 404.

Tabel berikut adalah **pemetaan yang diharapkan dari source**, bukan salinan output runtime. Hasil asli disimpan oleh `php verify.php` pada `docs/evidence/route-admin.txt`.

| Method | URI | Named route | Controller action |
|---|---|---|---|
|GET|/admin|admin.dashboard|DashboardController@index|
|GET|/admin/mahasiswa|admin.mahasiswa.index|MahasiswaController@index|
|GET|/admin/mahasiswa/create|admin.mahasiswa.create|MahasiswaController@create|
|POST|/admin/mahasiswa|admin.mahasiswa.store|MahasiswaController@store|
|GET|/admin/mahasiswa/{mahasiswa}|admin.mahasiswa.show|MahasiswaController@show|
|GET|/admin/mahasiswa/{mahasiswa}/edit|admin.mahasiswa.edit|MahasiswaController@edit|
|PUT/PATCH|/admin/mahasiswa/{mahasiswa}|admin.mahasiswa.update|MahasiswaController@update|
|DELETE|/admin/mahasiswa/{mahasiswa}|admin.mahasiswa.destroy|MahasiswaController@destroy|
|GET|/admin/mata-kuliah|admin.mata-kuliah.index|MataKuliahController@index|
|GET|/admin/mata-kuliah/create|admin.mata-kuliah.create|MataKuliahController@create|
|POST|/admin/mata-kuliah|admin.mata-kuliah.store|MataKuliahController@store|
|GET|/admin/mata-kuliah/{mataKuliah}|admin.mata-kuliah.show|MataKuliahController@show|
|GET|/admin/mata-kuliah/{mataKuliah}/edit|admin.mata-kuliah.edit|MataKuliahController@edit|
|PUT/PATCH|/admin/mata-kuliah/{mataKuliah}|admin.mata-kuliah.update|MataKuliahController@update|
|DELETE|/admin/mata-kuliah/{mataKuliah}|admin.mata-kuliah.destroy|MataKuliahController@destroy|

GET route juga menerima HEAD. Total route admin yang diharapkan: 15. Root `/` mengarah ke `/admin`. Beranda tugas 1 menjadi `/campus` dengan named route `campus.home`; URL lain tetap.

Blade memakai `route('admin.mahasiswa.store')`, `route('admin.mahasiswa.update', $item)`, dan padanannya untuk Mata Kuliah. Semua form mutasi memakai `@csrf`; update memakai `@method('PUT')`, delete memakai `@method('DELETE')`.

## Pemetaan ketentuan tugas

| Ketentuan | Implementasi |
|---|---|
|Route::resource, named routes, prefix|routes/web.php|
|Route model binding|Parameter resource dan signature show/edit/update/destroy pada controller|
|Migration & Eloquent|database/migrations/2026_10_30_* dan app/Models|
|Validasi create/update|app/Http/Requests/MahasiswaRequest.php dan MataKuliahRequest.php|
|Blade index/create/show/edit|resources/views/admin/mahasiswa dan mata-kuliah|
|Flash message|with('success', ...) pada controller; layout admin menampilkan session|
|Konfirmasi delete|Form dengan confirm(), CSRF, dan DELETE method|
|Dokumentasi route:list|php verify.php → docs/evidence/route-list.txt|
|Tes CRUD|tests/Feature/MahasiswaCrudTest.php dan MataKuliahCrudTest.php|

Referensi: https://laravel.com/docs/12.x/controllers#resource-controllers dan https://laravel.com/docs/12.x/validation
