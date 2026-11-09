@extends('layouts.app')
@section('title',$label)
@section('content')
<h1>{{ $item->exists ? 'Edit' : 'Tambah' }} {{ $label }}</h1><x-panel><form method="POST" action="{{ $item->exists ? route('admin.'.$resource.'.update',$item) : route('admin.'.$resource.'.store') }}">@csrf @if($item->exists) @method('PUT') @endif<div class="form-grid">
<div class="field"><label for="kode">Kode *</label><input id="kode" name="kode" type="text" value="{{ old('kode', $item->kode) }}"></div>
<div class="field"><label for="nama">Nama *</label><input id="nama" name="nama" type="text" value="{{ old('nama', $item->nama) }}"></div>
@if($hasActive)<div><input type="hidden" name="aktif" value="0"><label><input type="checkbox" name="aktif" value="1" @checked(old('aktif',$item->aktif ?? true))> Aktif</label></div>@endif
</div><div class="actions"><x-button type="submit">Simpan</x-button><x-button variant="secondary" :href="route('admin.'.$resource.'.index')">Batal</x-button></div></form></x-panel>
@endsection
