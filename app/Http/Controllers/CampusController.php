<?php
namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class CampusController extends Controller
{
    public function home(): View
    {
        $campus = ['name' => 'Kampus Nusantara', 'tagline' => 'Kenali kampus, temukan arah belajarmu.',
            'description' => 'VoltAcademic membantu calon mahasiswa mengenal program studi dan menemukan informasi kontak kampus dalam satu tempat.'];
        $highlights = ['Informasi program studi', 'Kontak layanan akademik', 'Eksplorasi arsitektur Laravel'];
        return view('campus.home', compact('campus', 'highlights'));
    }

    public function programs(): View
    {
        $programs = [
            ['code' => 'TI', 'name' => 'Teknologi Informasi', 'degree' => 'S1', 'duration' => '8 semester',
                'description' => 'Mempelajari pengembangan aplikasi, jaringan komputer, dan keamanan informasi.',
                'courses' => ['Pemrograman Web', 'Jaringan Komputer', 'Keamanan Informasi']],
            ['code' => 'SI', 'name' => 'Sistem Informasi', 'degree' => 'S1', 'duration' => '8 semester',
                'description' => 'Menghubungkan kebutuhan bisnis dengan sistem informasi yang tepat.',
                'courses' => ['Analisis Sistem', 'Basis Data', 'Proses Bisnis']],
            ['code' => 'MI', 'name' => 'Manajemen Informatika', 'degree' => 'D3', 'duration' => '6 semester',
                'description' => 'Mengembangkan keterampilan praktis untuk membangun dan mengelola aplikasi.',
                'courses' => ['Pemrograman Dasar', 'Aplikasi Bisnis', 'Manajemen Proyek']],
        ];
        return view('campus.programs', compact('programs'));
    }

    public function contact(): View
    {
        $contact = ['email' => 'akademik@kampus.example', 'phone' => 'Nomor telepon belum diisi',
            'address' => 'Alamat kampus belum diisi', 'hours' => 'Senin–Jumat, 08.00–16.00 WIB'];
        return view('campus.contact', compact('contact'));
    }
}
