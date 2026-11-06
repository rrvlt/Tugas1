<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}"><title>@yield('title', 'Dashboard') · VoltAcademic</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head><body><a class="skip" href="#main">Lewati navigasi</a>
@include('partials.sidebar')
<div class="workspace">@include('partials.navbar')
<main id="main">
@if(session('success'))<x-alert type="success">{{ session('success') }}</x-alert>@endif
@if($errors->any())<x-alert type="error"><strong>Data belum tersimpan.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></x-alert>@endif
@yield('content')
</main><footer>VoltAcademic · Sistem informasi akademik <span>PPWL / Tugas 4</span></footer></div>
</body></html>
