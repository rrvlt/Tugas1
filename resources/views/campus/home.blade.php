@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<section class="hero"><div><p class="eyebrow">PORTAL INFORMASI KAMPUS</p><h1>{{ $campus['tagline'] }}</h1><p class="lead">{{ $campus['description'] }}</p><div class="actions"><a class="button" href="{{ route('campus.programs') }}">Jelajahi program studi <span aria-hidden="true">↗</span></a><a class="text-link" href="{{ route('campus.contact') }}">Hubungi kampus</a></div></div><aside class="hero-card"><span class="pill">CAMPUS PROFILE</span><div class="campus-mark" aria-hidden="true">KN</div><h2>{{ $campus['name'] }}</h2><p>Belajar teknologi.<br>Membangun masa depan.</p><small>Profil dan program merupakan data contoh.</small></aside></section>
<section><p class="eyebrow">MULAI DARI SINI</p><h2>Satu portal, informasi yang kamu butuhkan.</h2><div class="grid">@foreach($highlights as $highlight)<article class="card"><span class="number">0{{ $loop->iteration }}</span><h3>{{ $highlight }}</h3><p>Temukan melalui menu navigasi di bagian atas.</p></article>@endforeach</div></section>
<section class="feature"><div><p class="eyebrow">LARAVEL ARCHITECTURE EXPLORER</p><h2>Lihat cara aplikasi ini bekerja.</h2><p>Kenali struktur proyek dan ikuti perjalanan request sampai menjadi halaman.</p></div><a class="button secondary" href="{{ route('explorer.architecture') }}">Buka dashboard</a></section>
@endsection
