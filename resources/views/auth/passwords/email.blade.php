@extends('layouts.master-without-nav')

@section('title') {{ __("Esqueci minha senha") }} @endsection

@section('body')
<body>
@endsection

@section('content')
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-soft-primary">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">{{ __("Solicitação") }}</h5>
                                        <p>Redefina sua senha {{ config('app.name'); }}.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ URL::asset('images/profile-img.png') }}" alt="Sislac"
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <a href="{{ url('/') }}">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ URL::asset('images/logo.png') }}" alt="Sislac"
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                <h4>{{ __("Olá,") }} {{ $user->first_name }} {{ $user->last_name }} </h4>
                                <p>
                                    <a href="{{ url('reset-password/' . $user->id . '/' . $token) }}">{{ __("Clique aqui") }}</a> para redefinir {{ config('app.name'); }} senha de conta</p>
                                <p> {{ __("Se o pedido de redefinição de senha não for feito por você, altere imediatamente sua senha para proteger sua conta.") }}</p>
                                <p>{{ __("Obrigado,") }}</p>
                                <p>{{ config('app.name'); }}.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>© {{ date('Y') }} {{ config('app.name'); }}. Feito com <i class="mdi mdi-heart text-danger"></i> {{ __("by DevCactus") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
