<?php
use App\Http\Controllers\CampusController;
use App\Http\Controllers\ExplorerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CampusController::class, 'home'])->name('campus.home');
Route::get('/program-studi', [CampusController::class, 'programs'])->name('campus.programs');
Route::get('/kontak', [CampusController::class, 'contact'])->name('campus.contact');
Route::get('/architecture', [ExplorerController::class, 'architecture'])->name('explorer.architecture');
Route::get('/request-lifecycle', [ExplorerController::class, 'lifecycle'])->name('explorer.lifecycle');
Route::get('/environment', [ExplorerController::class, 'environment'])->name('explorer.environment');
