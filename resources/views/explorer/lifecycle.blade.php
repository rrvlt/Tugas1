@extends('layouts.app')
@section('title', 'Request Lifecycle')
@section('content')
<p class="eyebrow">DARI REQUEST KE RESPONSE</p><h1>Request Lifecycle</h1><p class="lead">Perjalanan <code>GET /program-studi</code> pada aplikasi CampusInfo.</p><ol class="timeline">@foreach($steps as [$title, $description])<li><span class="step-number">{{ $loop->iteration }}</span><div><h2>{{ $title }}</h2><p>{{ $description }}</p></div></li>@endforeach</ol><p class="note">Alur disederhanakan untuk pembelajaran. Middleware dapat menghentikan request lebih awal, misalnya saat akses tidak diizinkan.</p>
@endsection
