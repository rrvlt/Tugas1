@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-head"><div><p class="eyebrow">ACADEMIC OVERVIEW</p><h1>Selamat datang, {{ $administrator }}.</h1><p class="muted">Pantau status mahasiswa dan kelola data akademik dari sini.</p></div><x-button :href="route('admin.mahasiswa.create')">+ Tambah mahasiswa</x-button></div>
@include('partials.statistics')
@if($stats['total'] > 0)
<x-alert type="success">Data mahasiswa berhasil dimuat. Statistik mengikuti data tersimpan.</x-alert>
@else
<x-alert type="info">Data mahasiswa belum tersedia. Mulai dengan menambahkan data baru.</x-alert>
@endif
<x-panel class="welcome-panel">
<x-slot:header><p class="eyebrow">RUANG KERJA AKADEMIK</p><h2>Data tertata, aktivitas terpantau.</h2><p class="muted">Satu ringkasan untuk melihat kondisi mahasiswa saat ini.</p></x-slot:header>
<p>Perbarui status melalui menu Edit Mahasiswa. Status baru langsung tercermin pada kartu statistik dan laporan.</p>
</x-panel>
<x-panel>
<x-slot:header><h2>Mahasiswa terbaru</h2><p class="muted">Lima mahasiswa yang paling baru ditambahkan.</p></x-slot:header>
<x-slot:actions><x-button variant="secondary" :href="route('admin.mahasiswa.index')" class="latest-action" data-testid="view-all">Lihat semua →</x-button></x-slot:actions>
<div class="table-wrap"><table><thead><tr><th>No.</th><th>Nama</th><th>NIM</th><th>Program studi</th><th>Status</th></tr></thead><tbody>
@forelse($mahasiswaTerbaru as $mahasiswa)
<tr><td>{{ $loop->iteration }}</td><td><a href="{{ route('admin.mahasiswa.show', $mahasiswa) }}">{{ $mahasiswa->nama }}</a></td><td>{{ $mahasiswa->nim }}</td><td>{{ $mahasiswa->programStudi?->nama }}</td><td><x-badge :status="$mahasiswa->status" /></td></tr>
@empty
<tr><td colspan="5"><x-empty-state><x-button :href="route('admin.mahasiswa.create')">+ Tambah mahasiswa</x-button></x-empty-state></td></tr>
@endforelse
</tbody></table></div>
</x-panel>
@endsection
