<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>@yield('title') · VoltAcademic</title><link rel="stylesheet" href="{{ asset('css/admin.css') }}"></head>
<body><a class="skip" href="#main">Lewati navigasi</a><aside class="sidebar"><a class="brand" href="{{ route('admin.dashboard') }}"><span class="logo">V</span>VoltAcademic</a><p class="sidebar-label">ACADEMIC WORKSPACE</p><nav aria-label="Navigasi admin">
@foreach(['admin.dashboard'=>'Dashboard','admin.mahasiswa.index'=>'Mahasiswa','admin.mata-kuliah.index'=>'Mata Kuliah'] as $name=>$label)
<a href="{{ route($name) }}" @if(request()->routeIs(str_replace('.index', '.*', $name))) aria-current="page" @endif>{{ $label }}</a>
@endforeach
</nav><div class="sidebar-bottom"><a href="{{ route('campus.home') }}">Campus & Laravel Explorer ↗</a><p>PPWL · Mini Project Academic</p></div></aside>
<div class="workspace"><header class="topbar"><span>Pengelolaan akademik</span><span class="pill">Workspace lokal</span></header><main id="main">
@if(session('success'))<div class="notice success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="notice invalid" role="alert"><strong>Data belum tersimpan.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('content')</main><footer>VoltAcademic · Master Data Akademik</footer></div></body></html>
