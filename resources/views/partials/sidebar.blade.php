<aside class="sidebar"><a class="brand" href="{{ route('admin.dashboard') }}"><span class="logo" aria-hidden="true">V</span>VoltAcademic</a><p class="sidebar-label">MENU UTAMA</p><nav aria-label="Navigasi akademik">
@foreach([
 ['route'=>'admin.dashboard','match'=>'admin.dashboard','label'=>'Dashboard','icon'=>'◈'],
 ['route'=>'admin.mahasiswa.index','match'=>'admin.mahasiswa.*','label'=>'Data Mahasiswa','icon'=>'◎'],
 ['route'=>'admin.mata-kuliah.index','match'=>'admin.mata-kuliah.*','label'=>'Mata Kuliah','icon'=>'▤'],
 ['route'=>'admin.program-studi.index','match'=>'admin.program-studi.*','label'=>'Program Studi','icon'=>'▧'],
 ['route'=>'admin.dosen.index','match'=>'admin.dosen.*','label'=>'Dosen','icon'=>'◇'],
 ['route'=>'admin.keahlian.index','match'=>'admin.keahlian.*','label'=>'Keahlian','icon'=>'✧'],
 ['route'=>'admin.laporan','match'=>'admin.laporan','label'=>'Laporan','icon'=>'▥'],
 ['route'=>'admin.components','match'=>'admin.components','label'=>'Komponen UI','icon'=>'◇'],
] as $menu)
<a href="{{ route($menu['route']) }}" @if(request()->routeIs($menu['match'])) aria-current="page" @endif><span aria-hidden="true">{{ $menu['icon'] }}</span> {{ $menu['label'] }}</a>
@endforeach
</nav><div class="sidebar-bottom"><div class="sidebar-note">Satu tempat untuk<br><strong>data akademik yang rapi.</strong></div><a href="{{ route('campus.home') }}">Campus & Laravel Explorer ↗</a><p>VoltAcademic · Workspace lokal</p></div></aside>
