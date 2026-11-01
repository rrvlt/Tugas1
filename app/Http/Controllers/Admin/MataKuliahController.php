<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\MataKuliahRequest;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class MataKuliahController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate(['q' => ['nullable','string','max:100']]);
        $q = trim((string) $request->input('q', ''));
        $items = MataKuliah::query()->when($q !== '', function ($query) use ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('kode', 'like', '%'.$q.'%')->orWhere('nama', 'like', '%'.$q.'%');
            });
        })->orderByDesc('id')->paginate(10)->withQueryString();
        return view('admin.mata-kuliah.index', compact('items','q'));
    }
    public function create(): View { return view('admin.mata-kuliah.create', ['item' => new MataKuliah]); }
    public function store(MataKuliahRequest $request): RedirectResponse
    {
        $item = MataKuliah::create($request->validated());
        return redirect()->route('admin.mata-kuliah.show', $item)->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }
    public function show(MataKuliah $mataKuliah): View { return view('admin.mata-kuliah.show', ['item' => $mataKuliah]); }
    public function edit(MataKuliah $mataKuliah): View { return view('admin.mata-kuliah.edit', ['item' => $mataKuliah]); }
    public function update(MataKuliahRequest $request, MataKuliah $mataKuliah): RedirectResponse
    {
        $mataKuliah->update($request->validated());
        return redirect()->route('admin.mata-kuliah.show', $mataKuliah)->with('success', 'Mata Kuliah berhasil diperbarui.');
    }
    public function destroy(MataKuliah $mataKuliah): RedirectResponse
    {
        $mataKuliah->delete();
        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}
