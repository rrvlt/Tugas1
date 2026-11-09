<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{ProgramStudi,Mahasiswa,MataKuliah,Keahlian,Dosen};
use App\Services\KrsService;
class RelationshipDemoSeeder extends Seeder {
    public function run(): void {
        $p=ProgramStudi::firstOrCreate(['nama'=>'Teknologi Informasi'],['kode'=>'DEMO-TI','aktif'=>true]);
        $mk=MataKuliah::firstOrCreate(['kode'=>'REL-WEB'],['nama'=>'Relasi Database Laravel','sks'=>3,'semester'=>3,'aktif'=>true]);
        $m=Mahasiswa::firstOrCreate(['nim'=>'REL001'],['nama'=>'Mahasiswa Relasi Demo','email'=>'rel001@example.test','program_studi_id'=>$p->id,'angkatan'=>2026,'status'=>'aktif']);
        $m->profil()->firstOrCreate([],['telepon'=>'0800000000','alamat'=>'Alamat fiktif']);
        $y=(int)date('Y'); app(KrsService::class)->save($m,['tahun_akademik'=>$y.'/'.($y+1),'semester_krs'=>'ganjil','mata_kuliah_ids'=>[$mk->id],'krs_mode'=>'add']);
        $k=Keahlian::firstOrCreate(['kode'=>'REL-LARAVEL'],['nama'=>'Laravel Eloquent']);
        $d=Dosen::firstOrCreate(['nidn'=>'REL-D001'],['nama'=>'Dosen Relasi Demo','email'=>'dosen.rel@example.test','program_studi_id'=>$p->id]);
        $d->profil()->firstOrCreate([],['telepon'=>'0800000001','alamat'=>'Alamat fiktif dosen']);
        if(!$d->keahlians()->where('keahlians.id',$k->id)->exists()) $d->keahlians()->attach($k->id,['level'=>'ahli','tahun_mulai'=>2020]);
    }
}
