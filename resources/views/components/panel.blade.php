<section {{ $attributes->merge(['class'=>'card panel']) }}>
@if(isset($header) || isset($actions))
<div class="panel-header"><div>{{ $header ?? '' }}</div>@isset($actions)<div class="actions">{{ $actions }}</div>@endisset</div>
@endif
<div class="panel-content">{{ $slot }}</div>
</section>
