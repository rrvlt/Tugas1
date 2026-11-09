@extends('layouts.app')
@section('title','Dosen')
@section('content')
<div class="page-head"><h1>Data Dosen</h1><x-button :href="route('admin.dosen.create')">+ Tambah dosen</x-button></div>
<x-panel><form method="GET" class="search"><label for="skill">Keahlian</label><select id="skill" name="keahlian_id"><option value="">Semua keahlian</option>@foreach($keahlians as $k)<option value="{{ $k->id }}" @selected(request('keahlian_id')==$k->id)>{{ $k->nama }}</option>@endforeach</select><x-button type="submit">Filter</x-button></form><div class="table-wrap"><table><thead><tr><th>NIDN</th><th>Nama</th><th>Program studi</th><th>Keahlian</th><th>Aksi</th></tr></thead><tbody>@forelse($items as $item)<tr><td>{{ $item->nidn }}</td><td>{{ $item->nama }}</td><td>{{ $item->programStudi?->nama }}</td><td>{{ $item->keahlians->pluck('nama')->join(', ') ?: '—' }}</td><td><a href="{{ route('admin.dosen.show',$item) }}">Detail</a> · <a href="{{ route('admin.dosen.edit',$item) }}">Edit</a></td></tr>@empty<tr><td colspan="5">Belum ada data yang sesuai.</td></tr>@endforelse</tbody></table></div>{{ $items->links() }}</x-panel>
@endsection
