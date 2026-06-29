{{-- ============================================================================
   SISLAC Master Layout
   ----------------------------------------------------------------------------
   Layout-base para páginas migradas para o novo design system.
   Páginas o usam com:

       @extends('layouts.sislac.master')
       @section('title', 'Visão geral')
       @section('content')
           ... seu HTML aqui ...
       @endsection

   Sections disponíveis:
       title    : título da aba do browser
       css      : CSS adicional no <head>
       content  : conteúdo principal da página
       script   : JS adicional antes do </body>
   ============================================================================ --}}

@php
    use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

    $sidebarUser = Sentinel::getUser();
    $sidebarRole = ($sidebarUser && $sidebarUser->roles->isNotEmpty())
        ? $sidebarUser->roles[0]->slug
        : 'guest';

    // Iniciais para o avatar (ex.: "André Silva" -> "AS")
    $sidebarInitials = 'U';
    if ($sidebarUser) {
        $firstInitial = mb_strtoupper(mb_substr($sidebarUser->first_name ?? '', 0, 1));
        $lastInitial  = mb_strtoupper(mb_substr($sidebarUser->last_name  ?? '', 0, 1));
        $sidebarInitials = ($firstInitial . $lastInitial) ?: 'U';
    }
@endphp

<!doctype html>
<html lang="pt-BR">
<head>
    @include('layouts.sislac.head')
</head>
<body class="sl-shell">

    <div class="sl-layout">

        {{-- Sidebar (vertical, sticky) --}}
        @include('layouts.sislac.sidebar', [
            'sidebarUser'     => $sidebarUser,
            'sidebarRole'     => $sidebarRole,
            'sidebarInitials' => $sidebarInitials,
        ])

        {{-- Main content --}}
        <main class="sl-main">
            <div class="sl-main__inner">
                @yield('content')
            </div>
            @include('layouts.sislac.footer')
        </main>

    </div>

    {{-- Scripts mínimos: toggle da sidebar + logout helper --}}
    <script>
        (function () {
            'use strict';

            // ─── Sidebar toggle (recolhida ↔ expandida) ─────────────────
            var sidebar = document.getElementById('slSidebar');
            var toggleBtn = document.getElementById('slSidebarToggle');
            if (sidebar && toggleBtn) {
                var STORAGE_KEY = 'sl_sidebar_expanded';

                // Restaurar estado anterior (default = recolhida, como no appsislac)
                try {
                    if (localStorage.getItem(STORAGE_KEY) === '1') {
                        sidebar.classList.add('sl-sidebar--expanded');
                    }
                } catch (e) { /* localStorage indisponível — ignora */ }

                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('sl-sidebar--expanded');
                    try {
                        localStorage.setItem(
                            STORAGE_KEY,
                            sidebar.classList.contains('sl-sidebar--expanded') ? '1' : '0'
                        );
                    } catch (e) { /* idem */ }
                });
            }

            // ─── Logout via form POST (preserva CSRF) ───────────────────
            window.slLogout = function () {
                var form = document.getElementById('slLogoutForm');
                if (form) form.submit();
            };
        })();
    </script>

    @yield('script')
</body>
</html>
