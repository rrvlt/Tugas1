@extends('layouts.app')
@section('title', 'Environment')
@section('content')
<p class="eyebrow">LOCAL DEVELOPMENT</p><h1>Environment</h1><p class="lead">Informasi runtime yang dipilih secara khusus untuk pembelajaran.</p><div class="table-wrap"><table><caption>Konfigurasi nonrahasia</caption><thead><tr><th>Informasi</th><th>Nilai</th></tr></thead><tbody>@foreach($environment as $label => $value)<tr><td>{{ $label }}</td><td><code>{{ $value }}</code></td></tr>@endforeach</tbody></table></div><p class="note">APP_KEY, password database, token API, dan isi .env tidak ditampilkan. Halaman ini mengembalikan 404 di luar local/testing.</p>
@endsection
