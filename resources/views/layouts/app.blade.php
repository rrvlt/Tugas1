<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · VoltAcademic</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<a class="skip" href="#main">Lewati navigasi</a>
<header><div class="nav-wrap">
    <a class="brand" href="{{ route('campus.home') }}"><span class="brand-icon">V</span>VoltAcademic<span class="brand-tag"> / Explorer</span></a>
    <nav aria-label="Navigasi utama"><a href="{{ route('admin.dashboard') }}">Dashboard Akademik</a>
        @foreach(['campus.home' => 'Beranda', 'campus.programs' => 'Program Studi', 'campus.contact' => 'Kontak', 'explorer.architecture' => 'Arsitektur', 'explorer.lifecycle' => 'Lifecycle'] as $name => $label)
            <a href="{{ route($name) }}" @if(request()->routeIs($name)) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
        @if(app()->environment(['local', 'testing']))
            <a href="{{ route('explorer.environment') }}" @if(request()->routeIs('explorer.environment')) aria-current="page" @endif>Environment</a>
        @endif
    </nav>
</div></header>
<main id="main" class="container">@yield('content')</main>
<footer class="container"><span>VoltAcademic · Tugas Mandiri PPWL</span><span>Data kampus contoh untuk pembelajaran.</span></footer>
</body>
</html>
