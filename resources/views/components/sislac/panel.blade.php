{{-- SISLAC Panel — card branco com cabeçalho lavanda (espelha o Panel do appsislac).

   Uso:
     @include('components.sislac.panel', [
         'title'  => 'Pacientes',
         'hint'   => 'Base ativa e novos no período',
         'action' => ['label' => 'Ver todos', 'href' => route('patients.index')],
         'slot'   => '...HTML do corpo...'
     ])

   Como Blade não suporta slot real em @include, o conteúdo do painel é
   passado pela variável `$slot` (string com HTML) ou pelo método mais comum:
   abrir e fechar manualmente o painel com @include parciais.

   Para máxima clareza no dashboard, recomendamos abrir o painel inline:

     <div class="sl-panel sl-animate-fade-in-up">
         <div class="sl-panel__header">
             <div>
                 <h3 class="sl-panel__title">Pacientes</h3>
                 <p class="sl-panel__hint">Base ativa e novos no período</p>
             </div>
             <a class="sl-btn sl-btn--pill" href="...">Ver todos →</a>
         </div>
         <div class="sl-panel__body">...</div>
     </div>

   Mas se preferir usar este @include, passe `slot` com o HTML já renderizado.
--}}

@php
    $hint   = $hint   ?? null;
    $action = $action ?? null;
    $slot   = $slot   ?? '';
    $class  = $class  ?? '';
@endphp

<div class="sl-panel sl-animate-fade-in-up {{ $class }}">

    <div class="sl-panel__header">
        <div>
            <h3 class="sl-panel__title">{{ $title }}</h3>
            @if($hint)
                <p class="sl-panel__hint">{{ $hint }}</p>
            @endif
        </div>

        @if($action && !empty($action['label']))
            <a class="sl-btn sl-btn--pill" href="{{ $action['href'] ?? '#' }}">
                {{ $action['label'] }}
                <i class='bx bx-up-arrow-alt' style="transform: rotate(45deg); font-size: 12px;"></i>
            </a>
        @endif
    </div>

    <div class="sl-panel__body">
        {!! $slot !!}
    </div>

</div>
