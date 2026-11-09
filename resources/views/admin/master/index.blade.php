@extends('layouts.app')
@section('title',$label)
@section('content')
<div class="page-head"><h1>{{ $label }}</h1><x-button :href="route('admin.'.$resource.'.create')">+ Tambah {{ $label }}</x-button></div>
<x-panel><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Nama</th>@if($hasActive)<th>Aktif</th><th>Mahasiswa</th>@endif<th>Dosen</th><th>Aksi</th></tr></thead><tbody>@forelse($items as $item)<tr><td>{{ $item->kode }}</td><td>{{ $item->nama }}</td>@if($hasActive)<td>{{ $item->aktif ? 'Ya' : 'Tidak' }}</td><td>{{ $item->mahasiswas_count }}</td>@endif<td>{{ $item->dosens_count }}</td><td><a href="{{ route('admin.'.$resource.'.show',$item) }}">Detail</a> · <a href="{{ route('admin.'.$resource.'.edit',$item) }}">Edit</a></td></tr>@empty<tr><td colspan="6">Belum ada data.</td></tr>@endforelse</tbody></table></div><div class="pagination">{{ $items->links() }}</div></x-panel>
@endsection
