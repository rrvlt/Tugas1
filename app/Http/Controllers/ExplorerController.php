<?php
namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ExplorerController extends Controller
{
    public function architecture(): View
    {
        $folders = [
            ['app/Models/', 'Eloquent model dan atribut master data.', 'Mahasiswa.php dan MataKuliah.php'],
            ['app/Http/Requests/', 'Validasi input create dan update.', 'MahasiswaRequest.php dan MataKuliahRequest.php'],
            ['app/', 'Kode aplikasi, termasuk controller.', 'app/Http/Controllers/CampusController.php'],
            ['bootstrap/', 'Membentuk aplikasi dan konfigurasi routing serta middleware.', 'bootstrap/app.php'],
            ['config/', 'Konfigurasi aplikasi yang diakses melalui config().', 'config/app.php'],
            ['routes/', 'Pemetaan URL ke controller dan penamaan route.', 'routes/web.php'],
            ['resources/', 'Template Blade dan aset sumber.', 'resources/views/campus/programs.blade.php'],
            ['public/', 'Document root web server dan aset publik.', 'public/index.php dan public/css/app.css'],
            ['storage/', 'Log, cache, session file, serta hasil kompilasi view.', 'storage/framework/views/'],
            ['database/', 'Migration dan seeder untuk master data Mahasiswa dan Mata Kuliah.', 'database/migrations/'],
            ['tests/', 'Pengujian otomatis aplikasi.', 'tests/Feature/VoltAcademicTest.php'],
            ['vendor/', 'Dependensi yang dipasang Composer; jangan diedit langsung.', 'vendor/autoload.php'],
            ['.env', 'Konfigurasi lokal termasuk secret; tidak dipublikasikan.', '.env.example menjadi contoh konfigurasi.'],
            ['composer.json', 'Daftar dependensi, autoload, dan script Composer.', 'laravel/framework dikunci pada major 12.']
        ];
        return view('explorer.architecture', compact('folders'));
    }

    public function lifecycle(): View
    {
        $steps = [
            ['Browser', 'Pengguna membuka GET /program-studi.'],
            ['public/index.php', 'Front controller memuat autoloader Composer dan bootstrap/app.php.'],
            ['Bootstrap aplikasi', 'HTTP kernel menyiapkan konfigurasi, penanganan error, dan service providers.'],
            ['Middleware global', 'Request melewati pemeriksaan dan pemrosesan middleware global.'],
            ['Router dan middleware web', 'Router mencocokkan GET /program-studi; middleware web memproses cookie dan session.'],
            ['CampusController', 'Method programs() menyusun array $programs lalu memanggil view().'],
            ['Blade dan response', 'campus/programs.blade.php merender data menjadi HTML; response kembali melewati middleware.'],
            ['Browser menerima HTML', 'Response dikirim ke browser dan halaman ditampilkan; pekerjaan terminable middleware dapat berjalan setelah pengiriman.']
        ];
        return view('explorer.lifecycle', compact('steps'));
    }

    public function environment(): View
    {
        // Hanya tersedia pada local/testing. Jangan expose metadata runtime ke publik.
        abort_unless(app()->environment(['local', 'testing']), 404);
        // Allowlist eksplisit: jangan gunakan phpinfo(), $_ENV, atau config()->all().
        $environment = [
            'Nama aplikasi' => config('app.name'),
            'Environment' => app()->environment(),
            'Versi Laravel' => app()->version(),
            'Versi PHP' => PHP_VERSION,
            'Zona waktu' => config('app.timezone'),
            'Bahasa' => config('app.locale'),
            'Mode debug' => config('app.debug') ? 'Aktif' : 'Nonaktif',
            'Session driver' => config('session.driver'),
            'Cache store' => config('cache.default'),
        ];
        return view('explorer.environment', compact('environment'));
    }
}
