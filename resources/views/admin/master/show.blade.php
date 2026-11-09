@extends('layouts.app')
@section('title',$label)
@section('content')
<h1>{{ $item->nama }}</h1><x-panel><p>Kode: {{ $item->kode }}</p>@if($hasActive)<p>Status: {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}</p><p>Jumlah mahasiswa: {{ $item->mahasiswas_count }}</p>@endif<p>Jumlah dosen: {{ $item->dosens_count }}</p><div class="actions"><x-button :href="route('admin.'.$resource.'.edit',$item)">Edit</x-button><x-button variant="secondary" :href="route('admin.'.$resource.'.index')">Kembali</x-button><form method="POST" action="{{ route('admin.'.$resource.'.destroy',$item) }}" onsubmit="return confirm('Hapus master ini? Master yang masih dipakai akan ditolak.')">@csrf @method('DELETE')<button class="btn danger">Hapus</button></form></div></x-panel>
@endsection
