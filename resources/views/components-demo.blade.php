@extends('layouts.app')
@section('title', 'Komponen UI')
@section('content')
<div class="page-head"><div><p class="eyebrow">BLADE COMPONENTS</p><h1>Komponen antarmuka</h1><p class="muted">Contoh variasi komponen untuk pemeriksaan tugas.</p></div></div>
<x-panel><x-slot:header><h2>Empat variasi alert</h2></x-slot:header><x-alert type="success">Contoh pesan sukses.</x-alert><x-alert type="warning">Contoh pesan peringatan.</x-alert><x-alert type="info">Contoh pesan informasi.</x-alert><x-alert type="error">Contoh pesan kesalahan.</x-alert></x-panel>
<x-panel><x-slot:header><h2>Status dan tombol</h2></x-slot:header><div class="actions"><x-badge status="aktif" /><x-badge status="cuti" /><x-badge status="nonaktif" /><x-button :href="route('admin.dashboard')" class="demo-extra-class" data-testid="custom-button">Tombol utama</x-button><x-button variant="secondary" :href="route('admin.mahasiswa.index')">Tombol sekunder</x-button></div></x-panel>
<x-panel><x-slot:header><h2>Contoh @@if untuk status biner</h2></x-slot:header><p>Latihan kondisi aktif dan nonaktif dari bagian A tugas; status cuti tetap ditampilkan tersendiri pada data aplikasi.</p>@foreach([true, false] as $isActive)<p>@if($isActive) Aktif @else Nonaktif @endif</p>@endforeach</x-panel>
<x-panel><x-slot:header><h2>Contoh empty state</h2></x-slot:header><x-empty-state title="Belum ada data" description="Ini contoh komponen, bukan kondisi database saat ini." /></x-panel>
@endsection
