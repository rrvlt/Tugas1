<?php
namespace Tests\Feature;
use App\Models\{Mahasiswa,MataKuliah,ProgramStudi,ProfilMahasiswa,Dosen,Keahlian};
use App\Services\KrsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
class DatabaseEngineeringTest extends TestCase {
    use RefreshDatabase;
    private function data(): array {
        $p=ProgramStudi::firstOrCreate(['kode'=>'TI'],['nama'=>'Teknologi Informasi','aktif'=>true]);
        return ['nim'=>'M001','nama'=>'Mahasiswa Satu','email'=>'m@example.test','angkatan'=>2026,'status'=>'aktif','program_studi_id'=>$p->id];
    }
    private function course(string $code='MK1',bool $active=true): MataKuliah {
        return MataKuliah::create(['kode'=>$code,'nama'=>'Mata Kuliah '.$code,'sks'=>3,'semester'=>1,'aktif'=>$active]);
    }
    private function period(string $year='2026/2027'): array { return ['tahun_akademik'=>$year,'semester_krs'=>'ganjil']; }
    public function test_integrated_student_create_show_edit_and_filter(): void {
        $mk=$this->course();
        $this->post(route('admin.mahasiswa.store'),$this->data()+['profil'=>['telepon'=>'081234','alamat'=>'Alamat Contoh'],
            'krs_enabled'=>1,'mata_kuliah_ids'=>[$mk->id]]+$this->period())->assertSessionHasNoErrors()->assertRedirect();
        $m=Mahasiswa::firstOrFail();
        $this->assertSame('081234',$m->profil->telepon);
        $this->assertSame('Teknologi Informasi',$m->programStudi->nama);
        $this->assertDatabaseHas('krs',['mahasiswa_id'=>$m->id,'mata_kuliah_id'=>$mk->id,'semester'=>'ganjil']);
        $this->get(route('admin.mahasiswa.show',['mahasiswa'=>$m]+$this->period()))->assertOk()->assertSee('Alamat Contoh')->assertSee('MK1');
        $this->get(route('admin.mahasiswa.edit',['mahasiswa'=>$m]+$this->period()))->assertOk()->assertSee('checked',false);
        $this->get(route('admin.mahasiswa.index',['mata_kuliah_id'=>$mk->id]))->assertOk()->assertSee('Mahasiswa Satu');
        $other=$this->course('MK2');
        $this->get(route('admin.mahasiswa.index',['mata_kuliah_id'=>$other->id]))->assertOk()->assertDontSee('Mahasiswa Satu');
        $this->put(route('admin.mahasiswa.update',$m),$this->data()+['profil'=>['telepon'=>'08999'],'krs_enabled'=>1,'mata_kuliah_ids'=>[$other->id]]+$this->period())->assertSessionHasNoErrors();
        $this->assertSame('08999',$m->fresh()->profil->telepon);
        $this->assertDatabaseMissing('krs',['mahasiswa_id'=>$m->id,'mata_kuliah_id'=>$mk->id]);
    }
    public function test_period_scoped_sync_status_detach_and_duplicate_attach(): void {
        $m=Mahasiswa::create($this->data()); $a=$this->course();$b=$this->course('MK2');$svc=app(KrsService::class);
        $svc->save($m,$this->period()+['mata_kuliah_ids'=>[$a->id]]);
        $svc->save($m,$this->period('2027/2028')+['mata_kuliah_ids'=>[$a->id]]);
        $this->patch(route('admin.mahasiswa.krs.pivot',[$m,$a]),$this->period('2027/2028')+['status'=>'disetujui'])->assertSessionHasNoErrors();
        $svc->save($m,$this->period('2027/2028')+['mata_kuliah_ids'=>[$a->id,$b->id],'krs_mode'=>'add']);
        $this->assertDatabaseHas('krs',['mahasiswa_id'=>$m->id,'mata_kuliah_id'=>$a->id,'tahun_akademik'=>'2027/2028','status'=>'disetujui']);
        $this->post(route('admin.mahasiswa.krs.store',$m),$this->period()+['mata_kuliah_id'=>$a->id])->assertSessionHasErrors('mata_kuliah_id');
        $this->delete(route('admin.mahasiswa.krs.pivot',[$m,$a]),$this->period('2027/2028'))->assertRedirect();
        $this->assertDatabaseHas('krs',['mahasiswa_id'=>$m->id,'mata_kuliah_id'=>$a->id,'tahun_akademik'=>'2026/2027']);
        $this->assertDatabaseMissing('krs',['mahasiswa_id'=>$m->id,'mata_kuliah_id'=>$a->id,'tahun_akademik'=>'2027/2028']);
        $svc->save($m,$this->period('2027/2028')+['mata_kuliah_ids'=>[]]);
        $this->assertDatabaseCount('krs',1);
        $this->post(route('admin.mahasiswa.krs.store',$m),$this->period('2028/2029')+['mata_kuliah_id'=>$b->id])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('krs',2);
    }
    public function test_transaction_rolls_back_student_profile_when_course_is_inactive(): void {
        $inactive=$this->course('OFF',false);
        $this->post(route('admin.mahasiswa.store'),$this->data()+['profil'=>['telepon'=>'0812'],'krs_enabled'=>1,'mata_kuliah_ids'=>[$inactive->id]]+$this->period())->assertSessionHasErrors('mata_kuliah_ids');
        $this->assertDatabaseCount('mahasiswas',0);$this->assertDatabaseCount('profil_mahasiswas',0);$this->assertDatabaseCount('krs',0);
        $m=Mahasiswa::create($this->data());$m->profil()->create(['telepon'=>'old']);
        $payload=array_replace($this->data(),['nama'=>'Uncommitted','profil'=>['telepon'=>'new'],'krs_enabled'=>1,'mata_kuliah_ids'=>[$inactive->id]]+$this->period());
        $this->put(route('admin.mahasiswa.update',$m),$payload)->assertSessionHasErrors('mata_kuliah_ids');
        $this->assertSame('Mahasiswa Satu',$m->fresh()->nama);$this->assertSame('old',$m->fresh()->profil->telepon);
    }
    public function test_constraints_cascades_and_restrict_rules(): void {
        $m=Mahasiswa::create($this->data());$mk=$this->course();$m->profil()->create(['telepon'=>'1']);
        app(KrsService::class)->attach($m,$this->period()+['mata_kuliah_id'=>$mk->id]);
        $this->delete(route('admin.program-studi.destroy',$m->program_studi_id))->assertSessionHasErrors('delete');
        $this->delete(route('admin.mata-kuliah.destroy',$mk))->assertSessionHasErrors('delete');
        $this->delete(route('admin.mahasiswa.destroy',$m))->assertRedirect();
        $this->assertDatabaseCount('profil_mahasiswas',0);$this->assertDatabaseCount('krs',0);
        $this->assertDatabaseCount('mata_kuliahs',1);$this->assertDatabaseCount('program_studis',1);
        $this->delete(route('admin.mata-kuliah.destroy',$mk))->assertSessionHasNoErrors();
    }
    public function test_unique_profile_is_enforced_by_database(): void {
        $m=Mahasiswa::create($this->data());$m->profil()->create(['telepon'=>'1']);
        $this->expectException(QueryException::class);$m->profil()->create(['telepon'=>'2']);
    }
    public function test_composite_unique_is_enforced_by_database(): void {
        $m=Mahasiswa::create($this->data());$mk=$this->course();
        $pivot=['tahun_akademik'=>'2026/2027','semester'=>'ganjil','status'=>'draft'];
        $m->mataKuliahs()->attach($mk,$pivot);
        $this->expectException(QueryException::class);$m->mataKuliahs()->attach($mk,$pivot);
    }
    public function test_invalid_foreign_keys_year_and_duplicate_selection(): void {
        $this->post(route('admin.mahasiswa.store'),array_replace($this->data(),['program_studi_id'=>999]))->assertSessionHasErrors('program_studi_id');
        $m=Mahasiswa::create($this->data());$mk=$this->course();
        $this->post(route('admin.mahasiswa.krs.store',$m),['tahun_akademik'=>'2026/2029','semester_krs'=>'ganjil','mata_kuliah_id'=>$mk->id])->assertSessionHasErrors('tahun_akademik');
        $this->put(route('admin.mahasiswa.update',$m),$this->data()+$this->period()+['krs_enabled'=>1,'mata_kuliah_ids'=>[$mk->id,$mk->id]])->assertSessionHasErrors('mata_kuliah_ids.0');
        $this->delete(route('admin.mahasiswa.krs.pivot',[$m,$mk]),$this->period())->assertNotFound();
        $this->delete(route('admin.mahasiswa.profil.destroy',$m))->assertRedirect();
        $this->assertDatabaseHas('mahasiswas',['id'=>$m->id]);
    }
    public function test_master_crud_and_dosen_relationships(): void {
        $this->post(route('admin.program-studi.store'),['kode'=>'SI','nama'=>'Sistem Informasi','aktif'=>1])->assertSessionHasNoErrors();
        $p=ProgramStudi::firstOrFail();
        foreach(['index','create','show','edit'] as $action) $this->get(route('admin.program-studi.'.$action,in_array($action,['show','edit'])?$p:[]))->assertOk();
        $this->put(route('admin.program-studi.update',$p),['kode'=>'SI','nama'=>'SI Baru','aktif'=>1])->assertSessionHasNoErrors();
        $this->post(route('admin.keahlian.store'),['kode'=>'WEB','nama'=>'Web Laravel'])->assertSessionHasNoErrors();
        $skill=Keahlian::firstOrFail();
        foreach(['index','create','show','edit'] as $action) $this->get(route('admin.keahlian.'.$action,in_array($action,['show','edit'])?$skill:[]))->assertOk();
        $data=['nidn'=>'12345','nama'=>'Dosen Demo','email'=>'dosen@example.test','program_studi_id'=>$p->id,'profil'=>['telepon'=>'08123'],
            'keahlian_ids'=>[$skill->id],'levels'=>[$skill->id=>'ahli'],'tahun_mulai'=>[$skill->id=>2020]];
        $this->post(route('admin.dosen.store'),$data)->assertSessionHasNoErrors();$d=Dosen::firstOrFail();
        $this->get(route('admin.dosen.show',$d))->assertOk()->assertSee('Web Laravel')->assertSee('2020')->assertSee('08123');
        $this->get(route('admin.dosen.edit',$d))->assertOk();$this->get(route('admin.dosen.create'))->assertOk();
        $this->get(route('admin.dosen.index',['keahlian_id'=>$skill->id]))->assertOk()->assertSee('Dosen Demo');
        $this->delete(route('admin.keahlian.destroy',$skill))->assertSessionHasErrors('delete');
        $this->delete(route('admin.program-studi.destroy',$p))->assertSessionHasErrors('delete');
        $data['levels'][$skill->id]='menengah';$this->put(route('admin.dosen.update',$d),$data)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('dosen_keahlian',['dosen_id'=>$d->id,'level'=>'menengah']);
        $data['keahlian_ids']=[];$this->put(route('admin.dosen.update',$d),$data)->assertSessionHasNoErrors();$this->assertDatabaseCount('dosen_keahlian',0);
        $this->delete(route('admin.dosen.profil.destroy',$d))->assertSessionHasNoErrors();$this->assertDatabaseCount('profil_dosens',0);
        $this->delete(route('admin.dosen.destroy',$d))->assertRedirect();
        $this->delete(route('admin.keahlian.destroy',$skill))->assertSessionHasNoErrors();
        $this->delete(route('admin.program-studi.destroy',$p))->assertSessionHasNoErrors();
    }

    public function test_foreign_key_restrict_is_enforced_even_without_controller(): void {
        $m=Mahasiswa::create($this->data());
        try { DB::table('program_studis')->where('id',$m->program_studi_id)->delete(); $this->fail('FK must reject parent deletion'); }
        catch(QueryException $e) { $this->assertDatabaseHas('program_studis',['id'=>$m->program_studi_id]); }
        $mk=$this->course();app(KrsService::class)->attach($m,$this->period()+['mata_kuliah_id'=>$mk->id]);
        try { DB::table('mata_kuliahs')->where('id',$mk->id)->delete(); $this->fail('FK must reject course deletion'); }
        catch(QueryException $e) { $this->assertDatabaseHas('mata_kuliahs',['id'=>$mk->id]); }
    }
    public function test_dosen_cascade_preserves_skill_master(): void {
        $p=ProgramStudi::create(['kode'=>'D','nama'=>'Prodi D','aktif'=>true]);
        $d=Dosen::create(['nidn'=>'D1','nama'=>'Dosen','email'=>'d1@example.test','program_studi_id'=>$p->id]);
        $k=Keahlian::create(['kode'=>'K','nama'=>'Skill']);$d->profil()->create(['telepon'=>'1']);
        $d->keahlians()->attach($k->id,['level'=>'ahli','tahun_mulai'=>2020]);
        $this->delete(route('admin.dosen.destroy',$d))->assertRedirect();
        $this->assertDatabaseCount('profil_dosens',0);$this->assertDatabaseCount('dosen_keahlian',0);
        $this->assertDatabaseHas('keahlians',['id'=>$k->id]);
    }
}
