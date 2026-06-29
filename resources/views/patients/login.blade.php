@extends('layouts.master-without-nav')
@section('title') {{ __("Login") }} @endsection
@section('css')

<style>
    .loginType{
        border:none;
    }
    .loginType:focus-visible{
        border:none;
        outline:none;
    }
</style>

@endsection
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
                                        <h5 class="text-primary">{{ __("Acesso ao laudo") }}</h5>
                                        <p>Faça login para continuar {{ config('app.name') }}.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ URL::asset('assets/images/profile-img.png') }}" alt=""
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <a href="{{ url('/') }}">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ URL::asset('assets/images/logo.png') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                <form class="form-horizontal" method="POST" action="{{ url('patient-login') }}" autocomplete="off">
                                    @csrf
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
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                        <label for="username">{{ __("Selecione um documento ") }} <span class="text-danger">*</span></label>
                                        <select class="loginType" name="loginType" required>
                                            <option value="patient_cpf">CPF</option>
                                            <option value="cns">CNS</option>
                                        </select>
                                        </div>
                                        <input name="loginName" type="text" id="loginName"
                                            class="form-control @error('loginName') is-invalid @enderror"
                                            @if (old('loginName')) value="{{ old('loginName') }}" @else value="" @endif id="loginName" placeholder="Digite o número do documento"
                                            autocomplete="off" autofocus required>
                                        @error('loginName')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
										
                                    </div>
                                    <div class="form-group">
                                        <label for="userpassword">{{ __("Senha") }} <span class="text-danger">*</span></label>
                                        <input type="password" name="password" id="pass"
                                            class="form-control  @error('password') is-invalid @enderror"
                                            id="userpassword" placeholder="Digite a senha" required autocomplete="off">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="remember"
                                            id="customControlInline">
                                        <label class="custom-control-label" for="customControlInline">{{ __("Lembre-se de mim") }}</label>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-primary btn-block waves-effect waves-light"
                                            type="submit">{{ __("Entrar") }}</button>
                                    </div>
                                </form>
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
    <script>
    </script>
@endsection
