<?php
namespace Tests\Feature;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class MahasiswaCrudTest extends TestCase
{
    use RefreshDatabase;
    private function data(): array { return ['nim'=>'422431053','nama'=>'Mahasiswa Contoh','email'=>'mahasiswa@example.test','program_studi'=>'Teknologi Informasi','angkatan'=>2026]; }
    public function test_all_pages_and_full_crud(): void {
        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.mahasiswa.index'))->assertOk();
        $this->get(route('admin.mahasiswa.create'))->assertOk()->assertSee('name="_token"', false);
        $this->post(route('admin.mahasiswa.store'), $this->data())->assertSessionHasNoErrors()->assertSessionHas('success');
        $item = Mahasiswa::firstOrFail();
        $this->assertDatabaseHas('mahasiswas', $this->data());
        $this->get(route('admin.mahasiswa.show', $item))->assertOk()->assertSee($item->nama);
        $this->get(route('admin.mahasiswa.edit', $item))->assertOk()->assertSee('value="PUT"', false);
        $this->put(route('admin.mahasiswa.update', $item), array_replace($this->data(), ['nama'=>'Nama Diperbarui']))
            ->assertRedirect(route('admin.mahasiswa.show', $item))->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertDatabaseHas('mahasiswas', ['id'=>$item->id,'nama'=>'Nama Diperbarui']);
        $this->delete(route('admin.mahasiswa.destroy', $item))->assertRedirect(route('admin.mahasiswa.index'))->assertSessionHas('success');
        $this->assertDatabaseMissing('mahasiswas', ['id'=>$item->id]);
        $this->get(route('admin.mahasiswa.show', $item))->assertNotFound();
    }
    public function test_required_and_invalid_values_are_rejected(): void {
        $this->post(route('admin.mahasiswa.store'), [])->assertSessionHasErrors(['nim', 'nama', 'email', 'program_studi', 'angkatan']);
        $bad = ['email'=>'invalid','angkatan'=>1999,'nim'=>'with space'];
        $this->post(route('admin.mahasiswa.store'), array_replace($this->data(), $bad))->assertSessionHasErrors(array_keys($bad));
        $this->assertDatabaseCount('mahasiswas', 0);
        $item = Mahasiswa::create($this->data());
        $this->put(route('admin.mahasiswa.update', $item), array_replace($this->data(), $bad))->assertSessionHasErrors(array_keys($bad));
        $this->assertDatabaseHas('mahasiswas', $this->data());
    }
    public function test_duplicate_values_rejected_and_unchanged_unique_values_allowed(): void {
        $first = Mahasiswa::create($this->data());
        $this->post(route('admin.mahasiswa.store'), $this->data())->assertSessionHasErrors(['nim', 'email']);
        $this->put(route('admin.mahasiswa.update', $first), $this->data())->assertSessionHasNoErrors();
        $second = Mahasiswa::create(array_replace($this->data(), ['nim'=>'99999','email'=>'other@example.test']));
        $this->put(route('admin.mahasiswa.update', $second), $this->data())->assertSessionHasErrors(['nim', 'email']);
        $this->assertDatabaseCount('mahasiswas', 2);
    }
    public function test_missing_models_return_404(): void {
        $this->get(route('admin.mahasiswa.show', 9999))->assertNotFound();
        $this->get(route('admin.mahasiswa.edit', 9999))->assertNotFound();
        $this->put(route('admin.mahasiswa.update', 9999), $this->data())->assertNotFound();
        $this->delete(route('admin.mahasiswa.destroy', 9999))->assertNotFound();
    }
    public function test_search_pagination_and_delete_confirmation(): void {
        $item = Mahasiswa::create($this->data());
        $this->get(route('admin.mahasiswa.index', ['q'=>$item->nim]))->assertOk()->assertSee($item->nama)->assertSee('return confirm(', false);
        $this->get(route('admin.mahasiswa.index', ['q'=>'NO_MATCH_987']))->assertOk()->assertDontSee($item->nama);
        for ($i=1; $i<=11; $i++) {
            Mahasiswa::create(array_replace($this->data(), ['nim'=>'N'.$i,'email'=>'s'.$i.'@example.test']));
        }
        $this->get(route('admin.mahasiswa.index'))->assertOk()->assertViewHas('items', fn($items)=>$items->count()===10 && $items->total()===12);
        $this->get(route('admin.mahasiswa.index', ['page'=>2]))->assertOk()->assertViewHas('items', fn($items)=>$items->count()===2);
    }
}
