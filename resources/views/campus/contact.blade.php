@extends('layouts.app')
@section('title', 'Kontak')
@section('content')
<p class="eyebrow">LAYANAN AKADEMIK</p><h1>Kontak Kampus</h1><p class="lead">Informasi untuk menghubungi bagian akademik.</p><div class="grid two"><section class="card"><h2>Informasi kontak</h2><dl><dt>Email contoh</dt><dd>{{ $contact['email'] }}</dd><dt>Telepon</dt><dd>{{ $contact['phone'] }}</dd><dt>Alamat</dt><dd>{{ $contact['address'] }}</dd></dl></section><section class="card"><span class="pill">JAM LAYANAN CONTOH</span><h2>{{ $contact['hours'] }}</h2><p>Ganti data kontak pada CampusController dengan informasi kampus yang benar sebelum aplikasi digunakan.</p><p>Halaman ini menampilkan informasi saja dan tidak mengirim pesan.</p></section></div>
@endsection
