@extends('layouts.master-without-nav')

@section('title') {{ __("confirmar senha") }} @endsection

@section('body')
<body>
@endsection

@section('content')
    <div class="home-btn d-none d-sm-block">
        <a href="{{ url('/') }}" class="text-dark"><i class="fas fa-home h2"></i></a>
    </div>
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary">
                            <div class="text-primary text-center p-4">
                                <h5 class="text-white font-size-20 p-2">{{ __("confirmar senha") }}</h5>
                                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" height="24" alt="logo">
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="p-3">
                                <form class="form-horizontal mt-4" method="POST"
                                    action="{{ route('password.confirm') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="userpassword">{{ __("Senha") }}</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required id="userpassword" placeholder="{{ __("Digite a senha") }}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-12 text-right">
                                            <button class="btn btn-primary w-md waves-effect waves-light"
                                                type="submit">{{ __("Confirmar Senha") }}</button>
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Esqueceu sua senha?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>{{ __("Lembrou da senha ? ") }} <a href="{{ url('login') }}" class="font-weight-medium text-primary">{{ __("Faça login aqui") }}</a></p>
                        <p class="mb-0">© {{ date('Y') }} {{ config('app.name'); }}. Feito com <i class="mdi mdi-heart text-danger"></i> {{ __("by DevCactus") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
