@props(['type'=>'info'])
@php
$variants = ['success'=>'success','warning'=>'warning','info'=>'info','error'=>'invalid'];
$variant = $variants[$type] ?? 'info';
$labels = ['success'=>'Berhasil','warning'=>'Perhatian','info'=>'Informasi','invalid'=>'Kesalahan'];
@endphp
<div {{ $attributes->merge(['class'=>'notice '.$variant, 'role'=> $type === 'error' ? 'alert' : 'status']) }}><strong>{{ $labels[$variant] }}.</strong> {{ $slot }}</div>
