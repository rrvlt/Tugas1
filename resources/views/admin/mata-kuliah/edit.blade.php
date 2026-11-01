@extends('layouts.admin')
@section('title', 'Edit Mata Kuliah')
@section('content')
<div class="page-head"><div><p class="eyebrow">MASTER DATA</p><h1>Edit Mata Kuliah</h1><p class="muted">Lengkapi data. Kolom bertanda * wajib diisi.</p></div></div>
<section class="card"><form method="POST" action="{{ route('admin.mata-kuliah.update', $item) }}">
@method('PUT')
@include('admin.mata-kuliah._form')
</form></section>
@endsection