<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\MahasiswaRequest;
use App\Models\{Mahasiswa,ProgramStudi,MataKuliah};
use App\Services\KrsService;
use App\Rules\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MahasiswaController extends Controller {
    private function period(Request $r): array {
        $r->validate(['tahun_akademik'=>['sometimes','required',new TahunAkademik], 'semester_krs'=>['sometimes','required','in:ganjil,genap']]);
        $year=(int)date('Y');
        return ['tahun_akademik'=>$r->input('tahun_akademik', $year.'/'.($year+1)), 'semester_krs'=>$r->input('semester_krs','ganjil')];
    }
    public function index(Request $request) {
        $request->validate(['q'=>['nullable','string','max:100'],'status'=>['nullable','in:aktif,cuti,nonaktif'],
            'program_studi_id'=>['nullable','integer','exists:program_studis,id'],'mata_kuliah_id'=>['nullable','integer','exists:mata_kuliahs,id']]);
        $q=trim((string)$request->input('q','')); $status=(string)$request->input('status','');
        $items=Mahasiswa::with('programStudi','profil')->withCount('mataKuliahs')
            ->when($q!=='',fn($b)=>$b->where(fn($b)=>$b->where('nim','like','%'.$q.'%')->orWhere('nama','like','%'.$q.'%')))
            ->when($status!=='',fn($b)=>$b->where('status',$status))
            ->when($request->program_studi_id,fn($b,$id)=>$b->where('program_studi_id',$id))
            ->when($request->mata_kuliah_id,fn($b,$id)=>$b->whereHas('mataKuliahs',fn($mk)=>$mk->where('mata_kuliahs.id',$id)))
            ->latest('id')->paginate(10)->withQueryString();
        return view('mahasiswa.index',compact('items','q','status')+['programStudis'=>ProgramStudi::orderBy('nama')->get(),'mataKuliahs'=>MataKuliah::orderBy('nama')->get()]);
    }
    private function form(Request $r, Mahasiswa $item) {
        $period=$this->period($r); $item->load('profil','programStudi');
        $selected=$item->exists ? $item->krsPeriode($period['tahun_akademik'],$period['semester_krs'])->pluck('mata_kuliahs.id')->all() : [];
        return compact('item','period','selected')+['programStudis'=>ProgramStudi::orderBy('nama')->get(),
            'mataKuliahs'=>MataKuliah::where('aktif',true)->orWhereIn('id',$selected)->orderBy('nama')->get()];
    }
    public function create(Request $r) { return view('admin.mahasiswa.create',$this->form($r,new Mahasiswa)); }
    public function edit(Request $r,Mahasiswa $mahasiswa) { return view('admin.mahasiswa.edit',$this->form($r,$mahasiswa)); }
    private function persist(MahasiswaRequest $r,Mahasiswa $item,KrsService $krs): Mahasiswa {
        $data=$r->validated();
        return DB::transaction(function() use($data,$item,$krs) {
            if($item->exists) Mahasiswa::whereKey($item->id)->lockForUpdate()->firstOrFail();
            $item->fill(collect($data)->only(['nim','nama','email','angkatan','status','program_studi_id'])->all())->save();
            if(array_key_exists('profil',$data)) $item->profil()->updateOrCreate([], $data['profil']);
            if($data['krs_enabled'] ?? false) $krs->save($item,$data);
            return $item;
        });
    }
    public function store(MahasiswaRequest $r,KrsService $krs) {
        $item=$this->persist($r,new Mahasiswa,$krs);
        return redirect()->route('admin.mahasiswa.show',$item)->with('success','Mahasiswa dan relasinya berhasil ditambahkan.');
    }
    public function update(MahasiswaRequest $r,Mahasiswa $mahasiswa,KrsService $krs) {
        $this->persist($r,$mahasiswa,$krs);
        return redirect()->route('admin.mahasiswa.show',$mahasiswa)->with('success','Mahasiswa dan relasinya berhasil diperbarui.');
    }
    public function show(Request $r,Mahasiswa $mahasiswa) {
        $period=$this->period($r);
        $mahasiswa->load(['programStudi','profil','mataKuliahs'=>fn($q)=>$q->wherePivot('tahun_akademik',$period['tahun_akademik'])->wherePivot('semester',$period['semester_krs'])->orderBy('nama')]);
        return view('admin.mahasiswa.show',['item'=>$mahasiswa,'period'=>$period,'mataKuliahs'=>MataKuliah::where('aktif',true)->orderBy('nama')->get()]);
    }
    public function destroy(Mahasiswa $mahasiswa) {
        $mahasiswa->delete(); return redirect()->route('admin.mahasiswa.index')->with('success','Mahasiswa, profil dan KRS berhasil dihapus.');
    }
    public function deleteProfile(Mahasiswa $mahasiswa) {
        $mahasiswa->profil()->delete(); return back()->with('success','Profil dihapus. Data mahasiswa tetap tersimpan.');
    }
    public function attach(Request $r,Mahasiswa $mahasiswa,KrsService $service) {
        $data=$r->validate(['tahun_akademik'=>['required',new TahunAkademik],'semester_krs'=>['required','in:ganjil,genap'],'mata_kuliah_id'=>['required','integer','exists:mata_kuliahs,id']]);
        $service->attach($mahasiswa,$data); return back()->with('success','Mata kuliah ditambahkan ke KRS.');
    }
    public function pivot(Request $r,Mahasiswa $mahasiswa,MataKuliah $mataKuliah,KrsService $service) {
        $data=$r->validate(['tahun_akademik'=>['required',new TahunAkademik],'semester_krs'=>['required','in:ganjil,genap'], 'status'=>[$r->isMethod('DELETE')?'nullable':'required','in:draft,disetujui']]);
        $service->change($mahasiswa,$mataKuliah->id,$data,$r->isMethod('DELETE'));
        return back()->with('success','KRS periode terpilih berhasil diperbarui.');
    }
}
