<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Keahlian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class KeahlianController extends Controller {
    private function context(): array { return ['resource'=>'keahlian','label'=>'Keahlian','hasActive'=>false]; }
    private function data(Request $r, ?Keahlian $item=null): array { return $r->validate([
        'kode'=>['required','string','max:20',Rule::unique('keahlians','kode')->ignore($item)],
        'nama'=>['required','string','max:100',Rule::unique('keahlians','nama')->ignore($item)]
    ]); }
    public function index() { return view('admin.master.index',$this->context()+['items'=>Keahlian::query()->withCount('dosens')->orderBy('nama')->paginate(15)]); }
    public function create() { return view('admin.master.form',$this->context()+['item'=>new Keahlian]); }
    public function store(Request $r) { $item=Keahlian::create($this->data($r)); return redirect()->route('admin.keahlian.show',$item)->with('success','Keahlian ditambahkan.'); }
    public function show(Keahlian $keahlian) { return view('admin.master.show',$this->context()+['item'=>$keahlian->loadCount("dosens")]); }
    public function edit(Keahlian $keahlian) { return view('admin.master.form',$this->context()+['item'=>$keahlian]); }
    public function update(Request $r,Keahlian $keahlian) { $keahlian->update($this->data($r,$keahlian)); return redirect()->route('admin.keahlian.show',$keahlian)->with('success','Keahlian diperbarui.'); }
    public function destroy(Keahlian $keahlian) {
        $item=$keahlian; if($item->dosens()->exists()) return back()->withErrors(['delete'=>'Keahlian masih dipakai dan tidak dapat dihapus.']);
        try { $item->delete(); } catch(\Illuminate\Database\QueryException $e) {
            if(str_starts_with((string)$e->getCode(),'23')) return back()->withErrors(['delete'=>'Data masih direferensikan.']); throw $e;
        }
        return redirect()->route('admin.keahlian.index')->with('success','Keahlian dihapus.');
    }
}
