<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\View\View;
class DashboardController extends Controller {
    public function index(): View {
        return view('admin.dashboard', ['mahasiswaCount'=>Mahasiswa::count(), 'mataKuliahCount'=>MataKuliah::count(), 'sksTotal'=>MataKuliah::sum('sks')]);
    }
}
