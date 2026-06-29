{{-- SISLAC KPI Card — espelha o componente HeroKpi do appsislac.

   Uso:
     @include('components.sislac.kpi-card', [
         'label'   => 'Atendimentos hoje',
         'value'   => '2',
         'hint'    => '3 liberados',
         'href'    => route('appointments.index'),  // opcional
         'visual'  => 'ring',                       // ring | dots | spark | bars | null
     ])

   Variáveis:
     label   (string)  — rótulo em caixa alta no topo
     value   (string)  — número/valor grande no centro-baixo
     hint    (string?) — texto pequeno abaixo do valor (opcional)
     href    (string?) — se preenchido, vira <a>; senão é <div>
     visual  (string?) — tipo de decoração: ring | dots | spark | bars
--}}

@php
    $href    = $href    ?? null;
    $hint    = $hint    ?? null;
    $visual  = $visual  ?? null;
    $tag     = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    class="sl-kpi sl-animate-fade-in-up"
>
    <div class="sl-kpi__label">{{ $label }}</div>

    <div class="sl-kpi__value">{{ $value }}</div>

    @if($hint)
        <div class="sl-kpi__hint">{{ $hint }}</div>
    @endif

    @if($visual === 'ring')
        <div class="sl-kpi__visual">
            <div class="sl-kpi__ring"></div>
        </div>
    @elseif($visual === 'dots')
        <div class="sl-kpi__visual">
            <div class="sl-kpi__dots">
                @for($i = 0; $i < 16; $i++) <span></span> @endfor
            </div>
        </div>
    @elseif($visual === 'spark')
        <div class="sl-kpi__visual">
            <svg class="sl-kpi__spark" viewBox="0 0 128 64" preserveAspectRatio="none">
                <path class="fill" d="M 0 46 L 10 34 L 20 27 L 30 32 L 40 42 L 50 38 L 60 24 L 70 16 L 80 23 L 90 29 L 100 14 L 110 7 L 120 11 L 128 64 L 0 64 Z"/>
                <path class="line" d="M 0 46 L 10 34 L 20 27 L 30 32 L 40 42 L 50 38 L 60 24 L 70 16 L 80 23 L 90 29 L 100 14 L 110 7 L 120 11"/>
            </svg>
        </div>
    @elseif($visual === 'bars')
        <div class="sl-kpi__visual">
            <div class="sl-kpi__bars">
                <span style="height:42%; animation-delay:0ms;"></span>
                <span style="height:64%; animation-delay:40ms;"></span>
                <span style="height:36%; animation-delay:80ms;"></span>
                <span style="height:50%; animation-delay:120ms;"></span>
                <span style="height:78%; animation-delay:160ms;"></span>
                <span style="height:34%; animation-delay:200ms;"></span>
                <span style="height:96%; animation-delay:240ms;"></span>
                <span style="height:44%; animation-delay:280ms;"></span>
            </div>
        </div>
    @endif
</{{ $tag }}>
