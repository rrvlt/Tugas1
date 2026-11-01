<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\MahasiswaRequest;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class MahasiswaController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate(['q' => ['nullable','string','max:100']]);
        $q = trim((string) $request->input('q', ''));
        $items = Mahasiswa::query()->when($q !== '', function ($query) use ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('nim', 'like', '%'.$q.'%')->orWhere('nama', 'like', '%'.$q.'%');
            });
        })->orderByDesc('id')->paginate(10)->withQueryString();
        return view('admin.mahasiswa.index', compact('items','q'));
    }
    public function create(): View { return view('admin.mahasiswa.create', ['item' => new Mahasiswa]); }
    public function store(MahasiswaRequest $request): RedirectResponse
    {
        $item = Mahasiswa::create($request->validated());
        return redirect()->route('admin.mahasiswa.show', $item)->with('success', 'Mahasiswa berhasil ditambahkan.');
    }
    public function show(Mahasiswa $mahasiswa): View { return view('admin.mahasiswa.show', ['item' => $mahasiswa]); }
    public function edit(Mahasiswa $mahasiswa): View { return view('admin.mahasiswa.edit', ['item' => $mahasiswa]); }
    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa): RedirectResponse
    {
        $mahasiswa->update($request->validated());
        return redirect()->route('admin.mahasiswa.show', $mahasiswa)->with('success', 'Mahasiswa berhasil diperbarui.');
    }
    public function destroy(Mahasiswa $mahasiswa): RedirectResponse
    {
        $mahasiswa->delete();
        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
