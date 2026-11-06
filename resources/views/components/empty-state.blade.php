@props(['title'=>'Belum ada data mahasiswa','description'=>'Tambahkan mahasiswa pertama untuk mulai mengelola data akademik.'])
<div {{ $attributes->merge(['class'=>'empty-state']) }}><span class="empty-icon" aria-hidden="true">◎</span><h3>{{ $title }}</h3><p>{{ $description }}</p>{{ $slot }}</div>
