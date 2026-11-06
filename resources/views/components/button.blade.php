@props(['variant'=>'primary', 'href'=>null])
@php($classes = 'btn '.($variant === 'secondary' ? 'secondary' : 'primary'))
@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class'=>$classes]) }}>{{ $slot }}</a>
@else
<button {{ $attributes->merge(['type'=>'button','class'=>$classes]) }}>{{ $slot }}</button>
@endif
