@extends('layouts.admin')
@section('title', 'Edit Mahasiswa')
@section('content')
<div class="page-head"><div><p class="eyebrow">MASTER DATA</p><h1>Edit Mahasiswa</h1><p class="muted">Lengkapi data. Kolom bertanda * wajib diisi.</p></div></div>
<section class="card"><form method="POST" action="{{ route('admin.mahasiswa.update', $item) }}">
@method('PUT')
@include('admin.mahasiswa._form')
</form></section>
@endsection