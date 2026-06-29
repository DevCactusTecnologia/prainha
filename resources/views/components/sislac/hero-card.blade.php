{{--
    SISLAC Hero Card
    --------------------------------------------------------------------------
    Card destaque com gradiente roxo (estilo appsislac).

    Uso:
        @include('components.sislac.hero-card', [
            'badge'    => 'HOJE',
            'title'    => 'Acompanhe seus atendimentos em tempo real',
            'subtitle' => '2 atendimentos hoje · 3 liberados.',
            'actions'  => '<a href="..." class="sl-btn sl-btn--dark">Ver atendimentos →</a>'
        ])

    Variáveis:
        $badge    : texto pequeno em uppercase (opcional)
        $title    : título principal
        $subtitle : texto descritivo abaixo do título (opcional)
        $actions  : HTML dos botões (opcional, pode ser string OU view rendered)
--}}

<section class="sl-hero sl-animate-fade-in-up" role="region" aria-label="Destaque do dia">
    <span class="sl-hero__decoration" aria-hidden="true"></span>
    <span class="sl-hero__decoration sl-hero__decoration--bottom" aria-hidden="true"></span>

    {{-- Decoração SVG floral (substitui a imagem heroFlower.webp do appsislac) --}}
    <svg class="sl-hero__flower" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <defs>
            <radialGradient id="slHeroFlower" cx="50%" cy="50%" r="50%">
                <stop offset="0%" stop-color="white" stop-opacity="0.45"/>
                <stop offset="60%" stop-color="white" stop-opacity="0.15"/>
                <stop offset="100%" stop-color="white" stop-opacity="0"/>
            </radialGradient>
        </defs>
        <g transform="translate(200,200)">
            @for($i = 0; $i < 8; $i++)
                <ellipse
                    cx="0" cy="-90" rx="55" ry="130"
                    fill="url(#slHeroFlower)"
                    transform="rotate({{ $i * 45 }})"
                />
            @endfor
            <circle cx="0" cy="0" r="40" fill="white" fill-opacity="0.35"/>
        </g>
    </svg>

    <div class="sl-hero__content">
        @if(!empty($badge))
            <div class="sl-hero__badge">
                <i class='bx bx-calendar'></i>
                {{ $badge }}
            </div>
        @endif

        <h2 class="sl-hero__title">{{ $title ?? '' }}</h2>

        @if(!empty($subtitle))
            <p class="sl-hero__subtitle">{!! $subtitle !!}</p>
        @endif

        @if(!empty($actions))
            <div class="sl-hero__actions">{!! $actions !!}</div>
        @endif
    </div>
</section>
