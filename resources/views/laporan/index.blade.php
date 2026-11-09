@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
<div class="page-head"><div><p class="eyebrow">RINGKASAN AKADEMIK</p><h1>Laporan mahasiswa</h1><p class="muted">Rekap berdasarkan data saat halaman dibuka.</p></div><x-button variant="secondary" :href="route('admin.mahasiswa.index')">Lihat data</x-button></div>
@include('partials.statistics')
<x-panel><x-slot:header><h2>Mahasiswa per program studi</h2></x-slot:header><div class="table-wrap"><table><thead><tr><th>No.</th><th>Program studi</th><th>Jumlah mahasiswa</th></tr></thead><tbody>@forelse($programs as $program)<tr><td>{{ $loop->iteration }}</td><td>{{ $program->nama }}</td><td>{{ $program->mahasiswas_count }}</td></tr>@empty<tr><td colspan="3"><x-empty-state /></td></tr>@endforelse</tbody></table></div></x-panel>
@endsection
