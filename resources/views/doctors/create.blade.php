@extends('layouts.master-layouts')
@section('title') Novo Médico @endsection
@section('body') <body data-topbar="dark" data-layout="horizontal"> @endsection

@section('content')

    {{-- BREADCRUMB --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ __('Novo Médico') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('doctors.index') }}">{{ __('Médicos') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Novo Médico') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    {{-- VOLTAR PARA A LISTAGEM DOS MÉDICOS --}}
    <div class="row">
        <div class="col-12">
            <a href="{{ route('doctors.index') }}" class="btn btn-primary waves-effect mb-4">
                <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>{{ __('Voltar à lista de médicos') }}
            </a>
        </div>
    </div>

    {{-- FORMULÁRIO --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">

                                {{-- NOME COMPLETO --}}
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">
                                            {{ __('Nome Completo') }}<span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                            class="form-control text-uppercase @error('first_name') is-invalid @enderror" 
                                            placeholder="{{ __('Digite o nome sem abreviações') }}" autofocus 
                                            name="first_name" id="firstname" value="{{ old('first_name') }}" required
                                        >
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- CPF E CNS --}}
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">
                                            {{ __('CPF ') }}<span class="text-danger"></span>
                                        </label>
                                        <input type="text" placeholder="{{ __('Digite o CPF') }}" 
                                            class="form-control @error('doctor_cpf') is-invalid @enderror" 
                                            name="doctor_cpf" id="doctor_cpf" value="{{ old('doctor_cpf') }}"
                                        >
                                        @error('doctor_cpf')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">{{ __('CNS ') }}
                                            <span class="text-danger"></span>
                                        </label>
                                        <input type="text" placeholder="{{ __('Digite o cartão SUS') }}"
                                            class="form-control @error('doctor_cns') is-invalid @enderror" 
                                            name="doctor_cns" id="doctor_cns" value="{{ old('doctor_cns') }}"
                                        >
                                        @error('doctor_cns')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- CONSELHO DE CLASSE, UF DE REGISTRO E NÚMERO --}}
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">
                                            {{ __('Conselho de Classe') }}<span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control @error('class_council_id') is-invalid @enderror" 
                                            name="class_council_id" id="class_council_id" required
                                        >
                                            <option value="">Selecione</option>
                                            @foreach ($classCouncils as $classCouncil)
                                                <option value="{{ $classCouncil->id }}"
                                                    @selected(old('class_council_id') == $classCouncil->id)
                                                >
                                                    {{ $classCouncil->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('class_council_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">
                                                    {{ __('Estado Emissor') }}<span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control @error('issuing_state_id') is-invalid @enderror" 
                                                    name="issuing_state_id" id="issuing_state" required
                                                >
                                                    <option value="">Selecione</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->id }}"
                                                            @selected(old('issuing_state_id') == $state->id)
                                                        >
                                                            {{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('issuing_state_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">
                                                    {{ __('Número de Registro do Conselho') }}<span class="text-danger">*</span>
                                                </label>
                                                <input type="text" placeholder="{{ __('Digite o número do conselho') }}" 
                                                    class="form-control @error('counsil_number') is-invalid @enderror" 
                                                    name="counsil_number" id="counsil_number" 
                                                    value="{{ old('counsil_number') }}" required
                                                >
                                                @error('counsil_number')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- EMAIL, SENHA DE ACESSO E NÚMERO DE CONTATO --}}
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">
                                            {{ __('E-mail ') }}<span class="text-danger"></span>
                                        </label>
                                        <input type="email" placeholder="{{ __('Digite o e-mail') }}"
                                            class="form-control @error('email') is-invalid @enderror" 
                                            name="email" id="email" value="{{ old('email') }}"
                                        >
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">
                                                    {{ __('Senha de Acesso ') }}<span class="text-danger"></span>
                                                </label>
                                                <input type="text" placeholder="{{ __('Digite a senha') }}"
                                                    class="form-control @error('password') is-invalid @enderror" 
                                                    name="password" id="password" value="{{ old('password') }}"
                                                >
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">
                                                    {{ __('Nº de Contato ') }}<span class="text-danger"></span>
                                                </label>
                                                <input type="tel" placeholder="{{ __('Digite o número') }}"
                                                    class="form-control @error('mobile') is-invalid @enderror" 
                                                    name="mobile" id="patientMobile" value="{{ old('mobile') }}"
                                                >
                                                @error('mobile')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- AVATAR --}}
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label class="control-label">
                                                {{ __('Foto de perfil') }}<span class="text-danger"></span>
                                            </label>
                                            <img class="@error('profile_photo') is-invalid @enderror"
                                                src="{{ asset('assets/images/users/noImage.png') }}" 
                                                id="profile_display" onclick="triggerClick()"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Clique para enviar a foto do perfil" 
                                            />
                                            <input type="file" id="profile_photo" name="new_profile_photo"
                                                class="form-control @error('profile_photo') is-invalid @enderror"
                                                style="display:none;" onchange="displayProfile(this)"
                                            >
                                            @error('profile_photo')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" onclick="loader(this)">
                                    Adicionar novo médico
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('script')
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/doctors/create.js') }}?version=1"></script>
@endsection
