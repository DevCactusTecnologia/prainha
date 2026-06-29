@extends('layouts.master-without-nav')
@section('title') Login @endsection
@section('body') <body class="bg-white"> @endsection
<style> .btn:focus { outline: none; } </style>

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center"> 
            <div class="col-lg-6 p-md-5 p-1" style="height: 100vh;">
                <div class="container col-xl-9 col-lg-12 col-md-12 d-flex flex-column justify-content-between" style="height: 100vh">
                    <div class="p-5 py-lg-2">
                        <h3>Bem vindo de volta 👋</h3>
                        <p>Hoje é um dia novo. É seu dia. Favor entrar para começar a gerenciar seu trabalho.</p>

                        <form method="POST" action="{{ url('login') }}">
                            @csrf

                            {{--ALERTAS  --}}
                            @if ($msg = Session::get('error'))
                                <div class="alert alert-danger">
                                    <span> {{ $msg }} </span>
                                </div>
                            @endif
                            @if ($msg = Session::get('success'))
                                <div class="alert alert-success">
                                    <span> {{ $msg }} </span>
                                </div>
                            @endif

                            {{-- USUARIO E SENHA --}}
                            <div class="form-group">
                                <label for="username">Usuário</label>
                                <input name="email" type="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    @if (old('email')) value="{{ old('email') }}" @else value="" @endif
                                    id="username" placeholder="Digite seu usuário" autocomplete="email"
                                    style="border-radius:10px;" autofocus
                                >
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="userpassword">Senha</label>
                                <input type="password" name="password" id="pass"
                                    class="form-control @error('password') is-invalid @enderror" id="userpassword"
                                    placeholder="Digite sua senha" style="border-radius:10px;"
                                    @if (old('password')) value="{{ old('password') }}" @else value="" @endif
                                >
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-block waves-effect"
                                    style="border-radius:10px;background-color:#172D3A;color:white"
                                >
                                    Entrar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="text-center"><p>© {{ date('Y') }} SISLAC TODOS OS DIREITOS RESERVADOS</p></div>
                </div>
            </div>
            <div class="col-lg-6 d-lg-block d-none p-2">
                <div class="container" style="height:100vh">
                    <img src={{ asset('assets/images/login.webp') }} alt="Login" class="w-100" style='border-radius:14px;object-fit:cover;height:104vh'>
                </div>
            </div>
        </div>
    </div>
@endsection
