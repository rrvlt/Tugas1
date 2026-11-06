<?php
namespace Tests\Feature;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
class DashboardMahasiswaTest extends TestCase
{
    use RefreshDatabase;
    private function student(string $nim, string $status): Mahasiswa {
        return Mahasiswa::create(['nim'=>$nim,'nama'=>'Nama '.$nim,'email'=>strtolower($nim).'@example.test',
            'program_studi'=>'Teknologi Informasi','angkatan'=>2026,'status'=>$status]);
    }
    public function test_empty_dashboard_and_empty_index(): void {
        $this->get(route('admin.dashboard'))->assertOk()->assertViewIs('dashboard')
            ->assertViewHas('stats', ['total'=>0,'aktif'=>0,'cuti'=>0,'nonaktif'=>0])
            ->assertSee('Belum ada data mahasiswa')->assertSee('Data mahasiswa belum tersedia');
        $this->get(route('admin.mahasiswa.index'))->assertOk()->assertViewIs('mahasiswa.index')->assertSee('Belum ada data mahasiswa');
        $this->get(route('admin.laporan'))->assertOk()->assertSee('Belum ada data mahasiswa');
    }
    public function test_counts_latest_five_badges_greeting_and_active_menu(): void {
        config(['academic.administrator'=>'Yoga']);
        foreach (['aktif','cuti','nonaktif','aktif','aktif','cuti'] as $i=>$status) $this->student('N'.$i,$status);
        $response = $this->get(route('admin.dashboard'))->assertOk()
            ->assertSee('Selamat datang, Yoga.')
            ->assertViewHas('stats', ['total'=>6,'aktif'=>3,'cuti'=>2,'nonaktif'=>1])
            ->assertViewHas('mahasiswaTerbaru', fn($items)=>$items->count()===5 && $items->first()->nim==='N5')
            ->assertSee('Nama N5')->assertDontSee('Nama N0')->assertSee('Aktif')->assertSee('Cuti')->assertSee('Nonaktif');
        $this->assertMatchesRegularExpression('/href="'.preg_quote(route('admin.dashboard'), '/').'"\s+aria-current="page"/', $response->getContent());
        $index=$this->get(route('admin.mahasiswa.index'))->assertOk();
        $this->assertMatchesRegularExpression('/href="'.preg_quote(route('admin.mahasiswa.index'), '/').'"\s+aria-current="page"/', $index->getContent());
    }
    public function test_status_filter_update_and_invalid_status(): void {
        $a=$this->student('A','aktif'); $this->student('B','cuti');
        $this->get(route('admin.mahasiswa.index', ['status'=>'cuti']))->assertOk()->assertSee('Nama B')->assertDontSee('Nama A');
        $data=$a->only(['nim','nama','email','program_studi','angkatan','status']);
        $this->put(route('admin.mahasiswa.update',$a), array_replace($data,['status'=>'nonaktif']))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('mahasiswas',['id'=>$a->id,'status'=>'nonaktif']);
        $this->get(route('admin.dashboard'))->assertViewHas('stats',['total'=>2,'aktif'=>0,'cuti'=>1,'nonaktif'=>1]);
        $this->put(route('admin.mahasiswa.update',$a), array_replace($data,['status'=>'invalid']))->assertSessionHasErrors('status');
        $this->post(route('admin.mahasiswa.store'), array_replace($data,['nim'=>'C','email'=>'c@example.test','status'=>'invalid']))->assertSessionHasErrors('status');
        $this->get(route('admin.mahasiswa.index',['status'=>'invalid']))->assertSessionHasErrors('status');
    }
    public function test_components_slots_variants_and_attribute_merging(): void {
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Data tertata, aktivitas terpantau.')
            ->assertSee('Perbarui status melalui menu Edit Mahasiswa.')
            ->assertSee('Lihat semua')->assertSee('latest-action')->assertSee('data-testid="view-all"', false);
        $this->get(route('admin.components'))->assertOk()->assertSee('Contoh pesan sukses.')->assertSee('Contoh pesan peringatan.')->assertSee('Contoh pesan informasi.')->assertSee('Contoh pesan kesalahan.')->assertSee('demo-extra-class')->assertSee('data-testid="custom-button"',false);
    }
    public function test_user_content_is_escaped_and_reports_are_accurate(): void {
        $student=$this->student('X','aktif');$student->update(['nama'=>'<script>alert(1)</script>']);
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;',false)->assertDontSee('<script>alert(1)</script>',false);
        $this->get(route('admin.laporan'))->assertOk()->assertViewHas('programs',fn($items)=>$items->count()===1 && (int)$items->first()->jumlah===1);
    }
}
