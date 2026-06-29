{{-- SISLAC Sidebar (vertical, sticky, branca com ícones)
    Espelha o AppSidebar.tsx do appsislac, adaptado para Sentinel + rotas do prainha.
    Estado: por padrão recolhida (só ícones) — toggle via JS no master.

    Variáveis disponíveis:
      $sidebarRole : slug do role (admin / doctor / receptionist / biomedical)
      $sidebarUser : usuário atual (Sentinel)
      $sidebarInitials : 2 letras para o avatar
--}}

@php
    // Helper local: marca o item ativo conforme a rota atual.
    $current = Route::currentRouteName() ?? '';
    $currentUrl = url()->current();

    $isActive = function (...$candidates) use ($current, $currentUrl) {
        foreach ($candidates as $c) {
            if ($current === $c) return true;
            if (str_starts_with($current, $c . '.')) return true;
            if (str_ends_with($currentUrl, '/' . $c)) return true;
        }
        return false;
    };

    $isHome = $current === '' || $current === 'home' || $currentUrl === url('/');
@endphp

<aside class="sl-sidebar" id="slSidebar" aria-label="Navegação principal">

    {{-- ─── Brand (topo) ─────────────────────────────────────────── --}}
    <div class="sl-sidebar__brand">
        <a href="{{ url('/') }}" class="sl-sidebar__logo" title="{{ config('app.name', 'SISLAC') }}">
            <i class='bx bx-building-house'></i>
            <span class="sl-sidebar__logo-dot" aria-hidden="true"></span>
        </a>
        <span class="sl-sidebar__brand-name">{{ config('app.name', 'SISLAC') }}</span>
        <button type="button" class="sl-sidebar__toggle" id="slSidebarToggle" aria-label="Recolher menu">
            <i class='bx bx-chevrons-left'></i>
        </button>
    </div>

    {{-- ─── Navegação ─────────────────────────────────────────────── --}}
    <nav class="sl-sidebar__nav">

        {{-- Dashboard — todos os roles --}}
        <a href="{{ url('/') }}"
           class="sl-sidebar__item {{ $isHome ? 'sl-sidebar__item--active' : '' }}"
           title="Dashboard">
            <i class='bx bx-grid-alt'></i>
            <span class="sl-sidebar__item-label">Dashboard</span>
        </a>

        @if(in_array($sidebarRole, ['admin', 'doctor', 'receptionist', 'biomedical']))

            {{-- Atendimentos --}}
            <a href="{{ route('appointments.index') }}"
               class="sl-sidebar__item {{ $isActive('appointments.index', 'appointments.create', 'appointments.edit') ? 'sl-sidebar__item--active' : '' }}"
               title="Atendimentos">
                <i class='bx bx-clipboard'></i>
                <span class="sl-sidebar__item-label">Atendimentos</span>
            </a>

            {{-- Agenda --}}
            <a href="{{ route('appointments.schedule.index') }}"
               class="sl-sidebar__item {{ $isActive('appointments.schedule.index') ? 'sl-sidebar__item--active' : '' }}"
               title="Agenda">
                <i class='bx bx-calendar'></i>
                <span class="sl-sidebar__item-label">Agenda</span>
            </a>

            {{-- Rotina (Mapa por paciente) --}}
            <a href="{{ route('routine.map.patient.index') }}"
               class="sl-sidebar__item {{ $isActive('routine.map.patient.index', 'routine.map.biomedical.index') ? 'sl-sidebar__item--active' : '' }}"
               title="Mapas / Rotina">
                <i class='bx bx-test-tube'></i>
                <span class="sl-sidebar__item-label">Rotina</span>
            </a>

            {{-- Rastreabilidade --}}
            <a href="{{ route('routine.traceability.index') }}"
               class="sl-sidebar__item {{ $isActive('routine.traceability.index') ? 'sl-sidebar__item--active' : '' }}"
               title="Rastreabilidade">
                <i class='bx bx-file-find'></i>
                <span class="sl-sidebar__item-label">Rastreabilidade</span>
            </a>

            {{-- Produção (relatórios) --}}
            <a href="{{ route('routine.production.by.biomedical.index') }}"
               class="sl-sidebar__item {{ $isActive('routine.production.by.biomedical.index', 'routine.production.by.unity.index') ? 'sl-sidebar__item--active' : '' }}"
               title="Produção">
                <i class='bx bx-bar-chart-alt-2'></i>
                <span class="sl-sidebar__item-label">Produção</span>
            </a>

            {{-- Pacientes --}}
            <a href="{{ route('patients.index') }}"
               class="sl-sidebar__item {{ $isActive('patients.index', 'patients.create', 'patients.edit') ? 'sl-sidebar__item--active' : '' }}"
               title="Pacientes">
                <i class='bx bx-user'></i>
                <span class="sl-sidebar__item-label">Pacientes</span>
            </a>

            {{-- Médicos / Especialistas --}}
            <a href="{{ route('doctors.index') }}"
               class="sl-sidebar__item {{ $isActive('doctors.index', 'doctors.create', 'doctors.edit') ? 'sl-sidebar__item--active' : '' }}"
               title="Médicos">
                <i class='bx bx-user-voice'></i>
                <span class="sl-sidebar__item-label">Médicos</span>
            </a>

        @endif

        @if($sidebarRole === 'admin')

            {{-- Faturas --}}
            <a href="{{ url('invoice') }}"
               class="sl-sidebar__item {{ $isActive('invoice', 'invoice.create') || str_contains($currentUrl, '/invoice') ? 'sl-sidebar__item--active' : '' }}"
               title="Faturas">
                <i class='bx bx-wallet'></i>
                <span class="sl-sidebar__item-label">Faturas</span>
            </a>

            {{-- Soroteca (mapa por analista) - placeholder com mesma rota --}}
            <a href="{{ route('routine.map.biomedical.index') }}"
               class="sl-sidebar__item"
               title="Soroteca">
                <i class='bx bx-water'></i>
                <span class="sl-sidebar__item-label">Soroteca</span>
            </a>

            {{-- Equipe (recepcionistas + analistas) --}}
            <a href="{{ route('receptionists.index') }}"
               class="sl-sidebar__item {{ $isActive('receptionists.index', 'biomedicals.index') ? 'sl-sidebar__item--active' : '' }}"
               title="Equipe">
                <i class='bx bx-group'></i>
                <span class="sl-sidebar__item-label">Equipe</span>
            </a>

            {{-- Cadastros (Exames + Categorias) --}}
            <a href="{{ route('exams.index') }}"
               class="sl-sidebar__item {{ $isActive('exams.index', 'categories.index') ? 'sl-sidebar__item--active' : '' }}"
               title="Cadastros">
                <i class='bx bx-book-open'></i>
                <span class="sl-sidebar__item-label">Cadastros</span>
            </a>

        @endif

        @if($sidebarRole === 'patient')
            <a href="{{ url('appointments/patient-appointment') }}"
               class="sl-sidebar__item"
               title="Meus atendimentos">
                <i class='bx bx-list-plus'></i>
                <span class="sl-sidebar__item-label">Atendimentos</span>
            </a>
        @endif

    </nav>

    {{-- ─── Footer (avatar + logout) ─────────────────────────────── --}}
    <div class="sl-sidebar__footer">
        <a href="{{ url('profile-view') }}" class="sl-sidebar__avatar" title="{{ $sidebarUser->first_name ?? 'Perfil' }}">
            @if(!empty($sidebarUser->profile_photo))
                <img
                    src="{{ asset('storage/images/users/' . $sidebarUser->profile_photo) }}"
                    alt="{{ $sidebarUser->first_name }}"
                    style="width:100%;height:100%;border-radius:inherit;object-fit:cover;"
                >
            @else
                {{ $sidebarInitials ?? 'U' }}
            @endif
        </a>
        <div class="sl-sidebar__user-info">
            <div class="sl-sidebar__user-name">{{ $sidebarUser->first_name ?? '' }}</div>
            <div class="sl-sidebar__user-role">
                @switch($sidebarRole)
                    @case('admin') Administrador @break
                    @case('doctor') Médico @break
                    @case('receptionist') Recepcionista @break
                    @case('biomedical') Analista @break
                    @case('patient') Paciente @break
                    @default Usuário
                @endswitch
            </div>
        </div>
    </div>

</aside>

{{-- Logout form (POST) — invocado pelo botão do header --}}
<form id="slLogoutForm" action="{{ url('logout') }}" method="POST" style="display:none;">
    @csrf
</form>
