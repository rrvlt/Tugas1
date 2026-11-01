<?php
namespace Tests\Feature;

use Tests\TestCase;

class VoltAcademicTest extends TestCase
{
    public function test_all_six_pages_render(): void
    {
        foreach (['campus.home', 'campus.programs', 'campus.contact', 'explorer.architecture', 'explorer.lifecycle', 'explorer.environment'] as $name) {
            $this->get(route($name))->assertOk();
        }
    }

    public function test_program_data_reaches_blade(): void
    {
        $this->get(route('campus.programs'))->assertOk()
            ->assertViewIs('campus.programs')->assertViewHas('programs', fn ($data) => count($data) === 3)
            ->assertSee('Teknologi Informasi')->assertSee('Sistem Informasi')->assertSee('Manajemen Informatika');
    }

    public function test_environment_does_not_expose_secret_values(): void
    {
        config(['app.key' => 'base64:'.base64_encode(str_repeat('X', 32)),
            'database.connections.mysql.password' => 'SECRET_DATABASE_SENTINEL',
            'services.example.token' => 'SECRET_API_SENTINEL']);
        $response = $this->get(route('explorer.environment'))->assertOk();
        $response->assertDontSee(config('app.key'))->assertDontSee('SECRET_DATABASE_SENTINEL')->assertDontSee('SECRET_API_SENTINEL');
    }

    public function test_environment_is_hidden_in_production(): void
    {
        $this->app->instance('env', 'production');
        $this->get(route('explorer.environment'))->assertNotFound();
        $this->get(route('campus.home'))->assertOk()->assertDontSee('>Environment</a>', false);
    }
}
