@props(['title' => null, 'value' => null, 'icon' => '◈'])
<section {{ $attributes->merge(['class'=>'card rounded-2xl border border-slate-200 bg-white shadow-sm']) }}>
@if($title)<div class="stat-heading"><h2>{{ $title }}</h2><span class="stat-icon" aria-hidden="true">{{ $icon }}</span></div>@endif
@if(!is_null($value))<strong class="stat-number">{{ $value }}</strong>@endif
{{ $slot }}
</section>
