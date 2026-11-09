<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ProgramStudiController extends Controller {
    private function context(): array { return ['resource'=>'program-studi','label'=>'Program Studi','hasActive'=>true]; }
    private function data(Request $r, ?ProgramStudi $item=null): array { return $r->validate([
        'kode'=>['required','string','max:20',Rule::unique('program_studis','kode')->ignore($item)],
        'nama'=>['required','string','max:100',Rule::unique('program_studis','nama')->ignore($item)], 'aktif'=>['required','boolean']
    ]); }
    public function index() { return view('admin.master.index',$this->context()+['items'=>ProgramStudi::query()->withCount(['mahasiswas','dosens'])->orderBy('nama')->paginate(15)]); }
    public function create() { return view('admin.master.form',$this->context()+['item'=>new ProgramStudi]); }
    public function store(Request $r) { $item=ProgramStudi::create($this->data($r)); return redirect()->route('admin.program-studi.show',$item)->with('success','Program Studi ditambahkan.'); }
    public function show(ProgramStudi $programStudi) { return view('admin.master.show',$this->context()+['item'=>$programStudi->loadCount(["mahasiswas","dosens"])]); }
    public function edit(ProgramStudi $programStudi) { return view('admin.master.form',$this->context()+['item'=>$programStudi]); }
    public function update(Request $r,ProgramStudi $programStudi) { $programStudi->update($this->data($r,$programStudi)); return redirect()->route('admin.program-studi.show',$programStudi)->with('success','Program Studi diperbarui.'); }
    public function destroy(ProgramStudi $programStudi) {
        $item=$programStudi; if($item->mahasiswas()->exists() || $item->dosens()->exists()) return back()->withErrors(['delete'=>'Program Studi masih dipakai dan tidak dapat dihapus.']);
        try { $item->delete(); } catch(\Illuminate\Database\QueryException $e) {
            if(str_starts_with((string)$e->getCode(),'23')) return back()->withErrors(['delete'=>'Data masih direferensikan.']); throw $e;
        }
        return redirect()->route('admin.program-studi.index')->with('success','Program Studi dihapus.');
    }
}
