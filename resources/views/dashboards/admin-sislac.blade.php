{{-- ============================================================================
   SISLAC Admin Dashboard — view principal
   ----------------------------------------------------------------------------
   Espelha pixel-a-pixel o print de referência (appsislac /dashboard).
   Não modifica o HomeController: usa as variáveis que ele já passa e
   computa as métricas adicionais via inline queries (com try/catch).

   Variáveis vindas do HomeController (já existentes):
     $user
     $total_appointment
     $total_exam_month_current
     $total_exams
     $today_appointment_total
     $today_appointment_exam_total
     $pending_appointment_total
     $pending_appointment_exam_total
     $occurrences
     $campaignCurrent

   Métricas computadas via queries inline (com fallback zero se schema diferir):
     - Receita do dia / mês, A receber, Ticket médio
     - Pacientes ativos / total / novos (30d) / atendidos (30d)
     - Top 5 exames (últimos 30 dias)
     - Taxa de cancelamento

   Para mover essas queries ao controller (recomendado para produção),
   ver MIGRATION_SISLAC.md → seção "Otimização: mover queries ao controller".
   ============================================================================ --}}

@extends('layouts.sislac.master')

@section('title', 'Visão geral')

@section('content')

@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Carbon;

    // ─── Helper: BRL sem dependências ──────────────────────────────
    $brl = fn($v) => 'R$ ' . number_format((float)$v, 2, ',', '.');

    // ─── Defaults seguros ───────────────────────────────────────────
    $metrics = [
        'receitaHoje'      => 0.0,
        'receitaMes'       => 0.0,
        'saidasMes'        => 0.0,
        'saldoMes'         => 0.0,
        'aReceber'         => 0.0,
        'ticketMedio'      => 0.0,
        'pacientesAtivos'  => 0,
        'pacientesTotal'   => 0,
        'pacientesNovos30' => 0,
        'pacientesAtend30' => 0,
        'topExames'        => [],
        'examesRealizados' => (int) ($total_exam_month_current ?? 0),
        'taxaCancelamento' => 0.0,
        'convenioTop'      => 'Particular',
        'solicitanteTop'   => 'SEM INFORMAÇÕES',
    ];

    // ─── Receita: hoje, mês, a receber, ticket médio ──────────────
    try {
        $hoje     = Carbon::now()->toDateString();
        $startMes = Carbon::now()->startOfMonth()->toDateString();
        $endMes   = Carbon::now()->endOfMonth()->toDateString();

        $metrics['receitaHoje'] = (float) DB::table('invoice_details')
            ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->whereDate('invoices.created_at', $hoje)
            ->where('invoices.payment_status', 'paid')
            ->where('invoices.is_deleted', 0)
            ->sum('invoice_details.amount');

        $metrics['receitaMes'] = (float) DB::table('invoice_details')
            ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startMes . ' 00:00:00', $endMes . ' 23:59:59'])
            ->where('invoices.payment_status', 'paid')
            ->where('invoices.is_deleted', 0)
            ->sum('invoice_details.amount');

        $metrics['aReceber'] = (float) DB::table('invoice_details')
            ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->where('invoices.payment_status', '!=', 'paid')
            ->where('invoices.is_deleted', 0)
            ->sum('invoice_details.amount');

        $metrics['saldoMes'] = $metrics['receitaMes'] - $metrics['saidasMes'];

        $countFaturas = (int) DB::table('invoices')->where('is_deleted', 0)->count();
        if ($countFaturas > 0) {
            $totalAmount = (float) DB::table('invoice_details')
                ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                ->where('invoices.is_deleted', 0)
                ->sum('invoice_details.amount');
            $metrics['ticketMedio'] = $totalAmount / $countFaturas;
        }
    } catch (\Throwable $e) { /* defaults zero */ }

    // ─── Pacientes ──────────────────────────────────────────────────
    try {
        $metrics['pacientesTotal']  = (int) DB::table('patients')->count();
        $metrics['pacientesAtivos'] = (int) DB::table('patients')->where('is_deleted', 0)->count();

        $cutoff30 = Carbon::now()->subDays(30)->toDateString() . ' 00:00:00';
        $metrics['pacientesNovos30'] = (int) DB::table('patients')
            ->where('created_at', '>=', $cutoff30)
            ->count();

        $metrics['pacientesAtend30'] = (int) DB::table('appointments')
            ->where('appointment_date', '>=', Carbon::now()->subDays(30)->toDateString())
            ->distinct('appointment_for')
            ->count('appointment_for');
    } catch (\Throwable $e) { /* defaults */ }

    // ─── Top 5 exames mais solicitados (últimos 30 dias) ────────────
    try {
        $cutoff30Date = Carbon::now()->subDays(30)->toDateString();
        $metrics['topExames'] = DB::table('appointment_exams')
            ->join('exams', 'appointment_exams.exam_id', '=', 'exams.id')
            ->whereDate('appointment_exams.created_at', '>=', $cutoff30Date)
            ->select('exams.name', DB::raw('COUNT(appointment_exams.id) as total'))
            ->groupBy('exams.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn($row) => ['name' => $row->name, 'total' => (int)$row->total])
            ->toArray();
    } catch (\Throwable $e) { /* lista vazia */ }

    // ─── Taxa de cancelamento ───────────────────────────────────────
    try {
        $totalAppt = (int) ($total_appointment ?? 0);
        if ($totalAppt > 0) {
            $cancelados = (int) DB::table('appointments')->where('status', 2)->count();
            $metrics['taxaCancelamento'] = ($cancelados / $totalAppt) * 100;
        }
    } catch (\Throwable $e) { /* 0% */ }

    // ─── Auxiliares ─────────────────────────────────────────────────
    $maxTopExame = !empty($metrics['topExames']) ? $metrics['topExames'][0]['total'] : 1;
    $primeiroNome = explode(' ', $user->first_name ?? 'Administrador')[0];
@endphp


{{-- ─── HEADER ────────────────────────────────────────────────────── --}}
<header class="sl-page-header">
    <div>
        <div class="sl-page-header__kicker">Visão Geral</div>
        <h1 class="sl-page-header__title">Olá, {{ $primeiroNome }}!</h1>
        <p class="sl-page-header__subtitle">Visão geral operacional, financeira e de produtividade.</p>
    </div>
    <div class="sl-page-header__actions">
        <a href="{{ url('/') }}" class="sl-btn sl-btn--dark">
            <i class='bx bx-crown'></i>
            Ver site
        </a>
    </div>
</header>


{{-- ─── HERO CARD ─────────────────────────────────────────────────── --}}
@php
    $heroActions = '
        <a href="' . e(route('appointments.index')) . '" class="sl-btn sl-btn--dark">
            Ver atendimentos
            <i class="bx bx-up-arrow-alt" style="transform: rotate(45deg);"></i>
        </a>
        <a href="' . e(url('invoice')) . '" class="sl-btn sl-btn--ghost-light">
            <i class="bx bx-receipt"></i> Orçamentos
            <span class="sl-btn__count">' . ((int)($pending_appointment_total ?? 0)) . '</span>
            <i class="bx bx-up-arrow-alt" style="transform: rotate(45deg);"></i>
        </a>
        <a href="' . e(route('appointments.schedule.index')) . '" class="sl-btn sl-btn--ghost-light">
            <i class="bx bx-globe"></i> Pedidos do site
            <i class="bx bx-up-arrow-alt" style="transform: rotate(45deg);"></i>
        </a>
    ';
@endphp
@include('components.sislac.hero-card', [
    'badge'    => 'HOJE',
    'title'    => 'Acompanhe seus atendimentos em tempo real',
    'subtitle' => '<strong>' . (int)($today_appointment_total ?? 0) . ' atendimentos hoje</strong> · <strong>' . (int)($today_appointment_exam_total ?? 0) . ' liberados.</strong>',
    'actions'  => $heroActions,
])


{{-- ─── 4 KPIs ───────────────────────────────────────────────────── --}}
<div class="sl-kpi-grid">

    @include('components.sislac.kpi-card', [
        'label'  => 'ATENDIMENTOS HOJE',
        'value'  => (int)($today_appointment_total ?? 0),
        'hint'   => (int)($today_appointment_exam_total ?? 0) . ' liberados',
        'href'   => route('appointments.index'),
        'visual' => 'ring',
    ])

    @include('components.sislac.kpi-card', [
        'label'  => 'RECEITA DO DIA',
        'value'  => $brl($metrics['receitaHoje']),
        'hint'   => 'Mês: ' . $brl($metrics['receitaMes']),
        'href'   => url('invoice'),
        'visual' => 'dots',
    ])

    @include('components.sislac.kpi-card', [
        'label'  => 'SALDO DO MÊS',
        'value'  => $brl($metrics['saldoMes']),
        'hint'   => 'Saídas: ' . $brl($metrics['saidasMes']),
        'href'   => url('invoice'),
        'visual' => 'spark',
    ])

    @include('components.sislac.kpi-card', [
        'label'  => 'A RECEBER',
        'value'  => $brl($metrics['aReceber']),
        'hint'   => 'Ticket médio: ' . $brl($metrics['ticketMedio']),
        'href'   => url('invoice'),
        'visual' => 'bars',
    ])

</div>


{{-- ─── FLUXO OPERACIONAL + PACIENTES ─────────────────────────────── --}}
<div class="sl-grid-2">

    <div class="sl-panel sl-animate-fade-in-up">
        <div class="sl-panel__header">
            <div>
                <h3 class="sl-panel__title">Fluxo operacional</h3>
                <p class="sl-panel__hint">Acompanha sua rotina laboratorial</p>
            </div>
        </div>
        <div class="sl-panel__body">
            <div class="sl-list">

                <a href="{{ route('routine.map.patient.index') }}" class="sl-list__item">
                    <div class="sl-list__left">
                        <span class="sl-list__marker sl-list__marker--dark"><i class='bx bx-test-tube'></i></span>
                        <span class="sl-list__label">Coletas realizadas</span>
                    </div>
                    <div class="sl-list__right">
                        <span class="sl-badge sl-badge--success">Concluído</span>
                        <span class="sl-list__value">0</span>
                        <i class='bx bx-up-arrow-alt sl-list__arrow' style="transform: rotate(45deg);"></i>
                    </div>
                </a>

                <a href="{{ route('routine.map.biomedical.index') }}" class="sl-list__item">
                    <div class="sl-list__left">
                        <span class="sl-list__marker sl-list__marker--accent"><i class='bx bx-microchip'></i></span>
                        <span class="sl-list__label">Análises em andamento</span>
                    </div>
                    <div class="sl-list__right">
                        <span class="sl-badge sl-badge--warning">Em processo</span>
                        <span class="sl-list__value">{{ (int)($pending_appointment_exam_total ?? 0) }}</span>
                        <i class='bx bx-up-arrow-alt sl-list__arrow' style="transform: rotate(45deg);"></i>
                    </div>
                </a>

                <a href="{{ route('appointments.index') }}" class="sl-list__item">
                    <div class="sl-list__left">
                        <span class="sl-list__marker"><i class='bx bx-file'></i></span>
                        <span class="sl-list__label">Resultados disponíveis</span>
                    </div>
                    <div class="sl-list__right">
                        <span class="sl-badge sl-badge--neutral">Aguardando</span>
                        <span class="sl-list__value">0</span>
                        <i class='bx bx-up-arrow-alt sl-list__arrow' style="transform: rotate(45deg);"></i>
                    </div>
                </a>

                <a href="{{ route('appointments.index') }}" class="sl-list__item">
                    <div class="sl-list__left">
                        <span class="sl-list__marker sl-list__marker--dark"><i class='bx bx-check-circle'></i></span>
                        <span class="sl-list__label">Liberados hoje</span>
                    </div>
                    <div class="sl-list__right">
                        <span class="sl-badge sl-badge--success">Concluído</span>
                        <span class="sl-list__value">{{ (int)($today_appointment_exam_total ?? 0) }}</span>
                        <i class='bx bx-up-arrow-alt sl-list__arrow' style="transform: rotate(45deg);"></i>
                    </div>
                </a>

            </div>
        </div>
    </div>

    <div class="sl-panel sl-animate-fade-in-up">
        <div class="sl-panel__header">
            <div>
                <h3 class="sl-panel__title">Pacientes</h3>
                <p class="sl-panel__hint">Base ativa e novos no período</p>
            </div>
            <a class="sl-btn sl-btn--pill" href="{{ route('patients.index') }}">
                Ver todos
                <i class='bx bx-up-arrow-alt' style="transform: rotate(45deg); font-size: 12px;"></i>
            </a>
        </div>
        <div class="sl-panel__body">
            <div class="sl-stat-block">

                <div class="sl-stat-block__group">
                    <div class="sl-stat-block__label">Ativos</div>
                    <div class="sl-stat-block__big">
                        <span class="sl-stat-block__big-value">{{ $metrics['pacientesAtivos'] }}</span>
                        <span class="sl-stat-block__big-hint">de {{ $metrics['pacientesTotal'] }}</span>
                    </div>
                </div>

                <div class="sl-stat-block__divider">
                    <div class="sl-stat-block__row">
                        <div class="sl-stat-block__row-left">
                            <i class='bx bx-user-plus'></i>
                            <span>Novos (30d)</span>
                        </div>
                        <span class="sl-stat-block__row-right sl-stat-block__row-right--positive">
                            +{{ $metrics['pacientesNovos30'] }}
                        </span>
                    </div>
                    <div class="sl-stat-block__row">
                        <div class="sl-stat-block__row-left">
                            <i class='bx bx-group'></i>
                            <span>Atendidos (30d)</span>
                        </div>
                        <span class="sl-stat-block__row-right sl-stat-block__row-right--neutral">
                            {{ $metrics['pacientesAtend30'] }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>


{{-- ─── EXAMES + PRODUTIVIDADE ────────────────────────────────────── --}}
<div class="sl-grid-2">

    <div class="sl-panel sl-animate-fade-in-up">
        <div class="sl-panel__header">
            <div>
                <h3 class="sl-panel__title">Exames mais solicitados</h3>
                <p class="sl-panel__hint">Últimos 30 dias</p>
            </div>
        </div>
        <div class="sl-panel__body">
            @if(empty($metrics['topExames']))
                <p style="padding: 24px; text-align: center; font-size: 13px; color: hsl(var(--sl-muted-foreground));">
                    Sem dados no período.
                </p>
            @else
                <ol class="sl-progress-list">
                    @foreach($metrics['topExames'] as $i => $exame)
                        @php $pct = $maxTopExame > 0 ? ($exame['total'] / $maxTopExame) * 100 : 0; @endphp
                        <li class="sl-progress-list__item">
                            <div class="sl-progress-list__row">
                                <div class="sl-progress-list__left">
                                    <span class="sl-progress-list__rank">{{ $i + 1 }}</span>
                                    <span class="sl-progress-list__name">{{ $exame['name'] }}</span>
                                </div>
                                <span class="sl-progress-list__value">{{ $exame['total'] }}</span>
                            </div>
                            <div class="sl-progress-bar">
                                <div class="sl-progress-bar__fill"
                                     style="width: {{ number_format($pct, 1) }}%; animation-delay: {{ $i * 60 }}ms;">
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>

    <div class="sl-panel sl-animate-fade-in-up">
        <div class="sl-panel__header">
            <div>
                <h3 class="sl-panel__title">Produtividade</h3>
                <p class="sl-panel__hint">Indicadores de 30 dias</p>
            </div>
        </div>
        <div class="sl-panel__body">
            <div class="sl-stat-block">

                <div class="sl-stat-block__group">
                    <div class="sl-stat-block__label">Exames realizados</div>
                    <div class="sl-stat-block__big">
                        <span class="sl-stat-block__big-value">{{ $metrics['examesRealizados'] }}</span>
                    </div>
                </div>

                <div class="sl-stat-block__divider">
                    <div class="sl-stat-block__row">
                        <span class="sl-stat-block__row-left">Cancelamento</span>
                        <span class="sl-stat-block__row-right {{ $metrics['taxaCancelamento'] > 5 ? 'sl-stat-block__row-right--danger' : '' }}">
                            {{ number_format($metrics['taxaCancelamento'], 1) }}%
                        </span>
                    </div>
                    <div class="sl-stat-block__row">
                        <span class="sl-stat-block__row-left">Convênio top</span>
                        <span class="sl-stat-block__row-right">{{ $metrics['convenioTop'] }}</span>
                    </div>
                    <div class="sl-stat-block__row">
                        <span class="sl-stat-block__row-left">Solicitante top</span>
                        <span class="sl-stat-block__row-right">{{ $metrics['solicitanteTop'] }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
