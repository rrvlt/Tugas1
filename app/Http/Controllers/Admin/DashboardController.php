<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\View\View;
class DashboardController extends Controller
{
    private function statistics(): array
    {
        $counts = Mahasiswa::query()->selectRaw('status, COUNT(*) AS jumlah')->groupBy('status')->pluck('jumlah','status');
        return ['total' => (int) $counts->sum(), 'aktif'=>(int)($counts['aktif'] ?? 0),
            'cuti'=>(int)($counts['cuti'] ?? 0), 'nonaktif'=>(int)($counts['nonaktif'] ?? 0)];
    }
    public function index(): View
    {
        return view('dashboard', [
            'administrator' => config('academic.administrator'),
            'stats' => $this->statistics(),
            'mahasiswaTerbaru' => Mahasiswa::with('programStudi')->latest('id')->limit(5)->get(),
        ]);
    }
    public function laporan(): View
    {
        return view('laporan.index', ['stats'=>$this->statistics(),
            'programs'=>\App\Models\ProgramStudi::withCount('mahasiswas')->orderBy('nama')->get()]);
    }
    public function components(): View { return view('components-demo'); }
}
