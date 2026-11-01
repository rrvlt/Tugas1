<?php
namespace Tests\Feature;
use App\Models\MataKuliah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class MataKuliahCrudTest extends TestCase
{
    use RefreshDatabase;
    private function data(): array { return ['kode'=>'TI201','nama'=>'Pemrograman Web Lanjut','sks'=>3,'semester'=>3,'deskripsi'=>'Praktik Laravel']; }
    public function test_all_pages_and_full_crud(): void {
        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.mata-kuliah.index'))->assertOk();
        $this->get(route('admin.mata-kuliah.create'))->assertOk()->assertSee('name="_token"', false);
        $this->post(route('admin.mata-kuliah.store'), $this->data())->assertSessionHasNoErrors()->assertSessionHas('success');
        $item = MataKuliah::firstOrFail();
        $this->assertDatabaseHas('mata_kuliahs', $this->data());
        $this->get(route('admin.mata-kuliah.show', $item))->assertOk()->assertSee($item->nama);
        $this->get(route('admin.mata-kuliah.edit', $item))->assertOk()->assertSee('value="PUT"', false);
        $this->put(route('admin.mata-kuliah.update', $item), array_replace($this->data(), ['nama'=>'Nama Diperbarui']))
            ->assertRedirect(route('admin.mata-kuliah.show', $item))->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertDatabaseHas('mata_kuliahs', ['id'=>$item->id,'nama'=>'Nama Diperbarui']);
        $this->delete(route('admin.mata-kuliah.destroy', $item))->assertRedirect(route('admin.mata-kuliah.index'))->assertSessionHas('success');
        $this->assertDatabaseMissing('mata_kuliahs', ['id'=>$item->id]);
        $this->get(route('admin.mata-kuliah.show', $item))->assertNotFound();
    }
    public function test_required_and_invalid_values_are_rejected(): void {
        $this->post(route('admin.mata-kuliah.store'), [])->assertSessionHasErrors(['kode', 'nama', 'sks', 'semester']);
        $bad = ['sks'=>0,'semester'=>15,'kode'=>'with space'];
        $this->post(route('admin.mata-kuliah.store'), array_replace($this->data(), $bad))->assertSessionHasErrors(array_keys($bad));
        $this->assertDatabaseCount('mata_kuliahs', 0);
        $item = MataKuliah::create($this->data());
        $this->put(route('admin.mata-kuliah.update', $item), array_replace($this->data(), $bad))->assertSessionHasErrors(array_keys($bad));
        $this->assertDatabaseHas('mata_kuliahs', $this->data());
    }
    public function test_duplicate_values_rejected_and_unchanged_unique_values_allowed(): void {
        $first = MataKuliah::create($this->data());
        $this->post(route('admin.mata-kuliah.store'), $this->data())->assertSessionHasErrors(['kode']);
        $this->put(route('admin.mata-kuliah.update', $first), $this->data())->assertSessionHasNoErrors();
        $second = MataKuliah::create(array_replace($this->data(), ['kode'=>'OTHER']));
        $this->put(route('admin.mata-kuliah.update', $second), $this->data())->assertSessionHasErrors(['kode']);
        $this->assertDatabaseCount('mata_kuliahs', 2);
    }
    public function test_missing_models_return_404(): void {
        $this->get(route('admin.mata-kuliah.show', 9999))->assertNotFound();
        $this->get(route('admin.mata-kuliah.edit', 9999))->assertNotFound();
        $this->put(route('admin.mata-kuliah.update', 9999), $this->data())->assertNotFound();
        $this->delete(route('admin.mata-kuliah.destroy', 9999))->assertNotFound();
    }
    public function test_search_pagination_and_delete_confirmation(): void {
        $item = MataKuliah::create($this->data());
        $this->get(route('admin.mata-kuliah.index', ['q'=>$item->kode]))->assertOk()->assertSee($item->nama)->assertSee('return confirm(', false);
        $this->get(route('admin.mata-kuliah.index', ['q'=>'NO_MATCH_987']))->assertOk()->assertDontSee($item->nama);
        for ($i=1; $i<=11; $i++) {
            MataKuliah::create(array_replace($this->data(), ['kode'=>'K'.$i]));
        }
        $this->get(route('admin.mata-kuliah.index'))->assertOk()->assertViewHas('items', fn($items)=>$items->count()===10 && $items->total()===12);
        $this->get(route('admin.mata-kuliah.index', ['page'=>2]))->assertOk()->assertViewHas('items', fn($items)=>$items->count()===2);
    }
}
