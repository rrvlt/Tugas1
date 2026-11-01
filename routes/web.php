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
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('mahasiswa', \App\Http\Controllers\Admin\MahasiswaController::class)
        ->parameters(['mahasiswa' => 'mahasiswa']);
    Route::resource('mata-kuliah', \App\Http\Controllers\Admin\MataKuliahController::class)
        ->parameters(['mata-kuliah' => 'mataKuliah']);
});
