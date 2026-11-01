@extends('layouts.app')
@section('title', 'Program Studi')
@section('content')
<p class="eyebrow">PILIH ARAH BELAJARMU</p><h1>Program Studi</h1><p class="lead">{{ count($programs) }} pilihan program dengan fokus pembelajaran yang berbeda.</p><div class="grid">@foreach($programs as $program)<article class="card program"><span class="program-code">{{ $program['code'] }}</span><p class="meta">{{ $program['degree'] }} · {{ $program['duration'] }}</p><h2>{{ $program['name'] }}</h2><p>{{ $program['description'] }}</p><h3>Contoh mata kuliah</h3><ul>@foreach($program['courses'] as $course)<li>{{ $course }}</li>@endforeach</ul></article>@endforeach</div><p class="note">Data di halaman ini dikirim sebagai array dari CampusController::programs() ke Blade.</p>
@endsection
