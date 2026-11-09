<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
class AcademicSeeder extends Seeder {
    public function run(): void {
        Mahasiswa::firstOrCreate(['nim'=>'DEMO001'], ['nama'=>'Mahasiswa Demo','email'=>'demo001@example.test','program_studi_id'=>\App\Models\ProgramStudi::firstOrCreate(['nama'=>'Teknologi Informasi'],['kode'=>'DEMO-TI','aktif'=>true])->id,'angkatan'=>2026]);
        MataKuliah::firstOrCreate(['kode'=>'DEMO-WEB'], ['nama'=>'Pemrograman Web Lanjut','sks'=>3,'semester'=>3,'deskripsi'=>'Data contoh untuk pengujian lokal.']);
    }
}
