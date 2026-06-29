{{-- SISLAC Theme — Head partial --}}
{{-- Carrega apenas o necessário para o novo design system; NÃO inclui Bootstrap. --}}

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Sistema para Laboratórios, Clínicas e Hospitais">
<meta name="author" content="Sislac">

<title>@yield('title', 'Visão geral') | {{ config('app.name', 'SISLAC') }}</title>

<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

{{-- Google Fonts — Inter (mesma fonte do appsislac) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
    rel="stylesheet"
>

{{-- BoxIcons (já existente no projeto) --}}
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

{{-- SISLAC Design System (compilado de resources/sass/sislac.scss) --}}
<link href="{{ asset('css/sislac.css') }}" rel="stylesheet" type="text/css">

{{-- Slot para páginas adicionarem CSS extra --}}
@yield('css')
