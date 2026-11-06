@props(['status'=>'nonaktif'])
{{-- @if membedakan status, termasuk cuti sebagai status tersendiri. --}}
@if($status === 'aktif')
<span {{ $attributes->merge(['class'=>'badge badge-active']) }}>Aktif</span>
@elseif($status === 'cuti')
<span {{ $attributes->merge(['class'=>'badge badge-leave']) }}>Cuti</span>
@else
<span {{ $attributes->merge(['class'=>'badge badge-inactive']) }}>Nonaktif</span>
@endif
