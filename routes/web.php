<?php
use App\Http\Controllers\CampusController;
use App\Http\Controllers\ExplorerController;
use Illuminate\Support\Facades\Route;

Route::get('/campus', [CampusController::class, 'home'])->name('campus.home');
Route::redirect('/', '/admin');
Route::get('/program-studi', [CampusController::class, 'programs'])->name('campus.programs');
Route::get('/kontak', [CampusController::class, 'contact'])->name('campus.contact');
Route::get('/architecture', [ExplorerController::class, 'architecture'])->name('explorer.architecture');
Route::get('/request-lifecycle', [ExplorerController::class, 'lifecycle'])->name('explorer.lifecycle');
Route::get('/environment', [ExplorerController::class, 'environment'])->name('explorer.environment');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('program-studi', \App\Http\Controllers\Admin\ProgramStudiController::class)->parameters(['program-studi'=>'programStudi']);
    Route::resource('keahlian', \App\Http\Controllers\Admin\KeahlianController::class)->parameters(['keahlian'=>'keahlian']);
    Route::resource('dosen', \App\Http\Controllers\Admin\DosenController::class)->parameters(['dosen'=>'dosen']);
    Route::delete('dosen/{dosen}/profil',[\App\Http\Controllers\Admin\DosenController::class,'deleteProfile'])->name('dosen.profil.destroy');
    Route::delete('mahasiswa/{mahasiswa}/profil',[\App\Http\Controllers\Admin\MahasiswaController::class,'deleteProfile'])->name('mahasiswa.profil.destroy');
    Route::post('mahasiswa/{mahasiswa}/krs',[\App\Http\Controllers\Admin\MahasiswaController::class,'attach'])->name('mahasiswa.krs.store');
    Route::match(['PATCH','DELETE'],'mahasiswa/{mahasiswa}/krs/{mataKuliah}',[\App\Http\Controllers\Admin\MahasiswaController::class,'pivot'])->name('mahasiswa.krs.pivot');

    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [\App\Http\Controllers\Admin\DashboardController::class, 'laporan'])->name('laporan');
    Route::get('/komponen', [\App\Http\Controllers\Admin\DashboardController::class, 'components'])->name('components');
    Route::resource('mahasiswa', \App\Http\Controllers\Admin\MahasiswaController::class)
        ->parameters(['mahasiswa' => 'mahasiswa']);
    Route::resource('mata-kuliah', \App\Http\Controllers\Admin\MataKuliahController::class)
        ->parameters(['mata-kuliah' => 'mataKuliah']);
});
