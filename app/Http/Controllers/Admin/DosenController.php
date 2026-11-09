<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Dosen,ProgramStudi,Keahlian};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class DosenController extends Controller {
    public function index(Request $r) {
        $r->validate(['keahlian_id'=>['nullable','integer','exists:keahlians,id']]);
        return view('admin.dosen.index',['items'=>Dosen::with('programStudi','profil','keahlians')->when($r->keahlian_id,
            fn($q,$id)=>$q->whereHas('keahlians',fn($k)=>$k->where('keahlians.id',$id)))->latest('id')->paginate(10)->withQueryString(), 'keahlians'=>Keahlian::orderBy('nama')->get()]);
    }
    private function form(Dosen $item) {
        return ['item'=>$item->load('profil','programStudi','keahlians'),'programStudis'=>ProgramStudi::orderBy('nama')->get(),'keahlians'=>Keahlian::orderBy('nama')->get()];
    }
    public function create() { return view('admin.dosen.form',$this->form(new Dosen)); }
    public function edit(Dosen $dosen) { return view('admin.dosen.form',$this->form($dosen)); }
    public function show(Dosen $dosen) { return view('admin.dosen.show',['item'=>$dosen->load('profil','programStudi','keahlians')]); }
    private function persist(Request $r,Dosen $item): Dosen {
        $data=$r->validate(['nidn'=>['required','string','max:20',Rule::unique('dosens','nidn')->ignore($item)],
            'nama'=>['required','string','max:100'],'email'=>['required','email','max:150',Rule::unique('dosens','email')->ignore($item)],
            'program_studi_id'=>['required','integer','exists:program_studis,id'],
            'profil'=>['sometimes','array:telepon,alamat'],'profil.telepon'=>['nullable','string','max:20'],'profil.alamat'=>['nullable','string','max:2000'],
            'keahlian_ids'=>['sometimes','array'],'keahlian_ids.*'=>['integer','distinct','exists:keahlians,id'],
            'levels'=>['sometimes','array'],'levels.*'=>['required','in:pemula,menengah,ahli'],
            'tahun_mulai'=>['sometimes','array'],'tahun_mulai.*'=>['required','integer','between:1950,'.date('Y')]]);
        $pivot=[];
        foreach($data['keahlian_ids'] ?? [] as $id) {
            if(!isset($data['levels'][$id],$data['tahun_mulai'][$id])) throw \Illuminate\Validation\ValidationException::withMessages(['keahlian_ids'=>'Level dan tahun mulai wajib diisi untuk setiap keahlian terpilih.']);
            $pivot[$id]=['level'=>$data['levels'][$id],'tahun_mulai'=>$data['tahun_mulai'][$id]];
        }
        return DB::transaction(function() use($item,$data,$pivot) {
            if($item->exists) Dosen::whereKey($item->id)->lockForUpdate()->firstOrFail();
            $item->fill(collect($data)->only(['nidn','nama','email','program_studi_id'])->all())->save();
            if(isset($data['profil'])) $item->profil()->updateOrCreate([],$data['profil']);
            $item->keahlians()->sync($pivot); return $item;
        });
    }
    public function store(Request $r) { $item=$this->persist($r,new Dosen);return redirect()->route('admin.dosen.show',$item)->with('success','Dosen dan relasi ditambahkan.'); }
    public function update(Request $r,Dosen $dosen) { $this->persist($r,$dosen); return redirect()->route('admin.dosen.show',$dosen)->with('success','Dosen dan relasi diperbarui.'); }
    public function destroy(Dosen $dosen) { $dosen->delete();return redirect()->route('admin.dosen.index')->with('success','Dosen, profil dan hubungan keahlian dihapus.'); }
    public function deleteProfile(Dosen $dosen) { $dosen->profil()->delete();return back()->with('success','Profil dosen dihapus.'); }
}
