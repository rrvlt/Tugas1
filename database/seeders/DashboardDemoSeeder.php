<?php
namespace Database\Seeders;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['aktif','cuti','nonaktif','aktif','aktif','cuti'] as $i=>$status) {
            $nim = 'T4DEMO'.str_pad((string)($i+1),3,'0',STR_PAD_LEFT);
            Mahasiswa::firstOrCreate(['nim'=>$nim], ['nama'=>'Mahasiswa Contoh '.($i+1),
                'email'=>strtolower($nim).'@example.test','program_studi'=>$i%2===0 ? 'Teknologi Informasi' : 'Sistem Informasi',
                'angkatan'=>2026,'status'=>$status]);
        }
    }
}
