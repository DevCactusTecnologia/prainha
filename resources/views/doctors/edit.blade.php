@extends('layouts.master-layouts')
@section('title'){{ __('Atualizar dados do médico') }}@endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')

    {{-- BREADCRUMB --}}
    @component('components.breadcrumb')
        @slot('title') Dashboard @endslot
        @slot('li_1') <a href="{{ route('doctors.index') }}">{{ __('Médicos') }}</a> @endslot
        @slot('li_2') Atualizar dados do médico @endslot
    @endcomponent

    {{-- VOLTAR PARA A DASHBOARD/PERFIL/LISTA DE MÉDICOS --}}
    <div class="row">
        <div class="col-12">
            @if ($doctor && $doctor_info)
                @if ($role == 'doctor')
                    <a href="{{ url('/') }}" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>{{ __('Voltar ao Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>{{ __('Voltar ao perfil') }}
                    </a>
                @endif
            @else
                <a href="{{ route('doctors.index') }}" class="btn btn-primary waves-effect mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>{{ __('Voltar à lista de médicos') }}
                </a>
            @endif
        </div>
    </div>

    {{-- FORMULÁRIO --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- NOME COMPLETO --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label">
                                    {{ __('Nome Completo ') }}<span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                    class="form-control text-uppercase @error('first_name') is-invalid @enderror" 
                                    name="first_name" id="firstname" placeholder="{{ __('Digite o primeiro nome') }}"
                                    value="{{ old('first_name', $doctor->first_name) }}" required
                                >
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="control-label">
                                    {{ __('Inativo ') }}<span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="is_deleted" required>
                                    <option value="1" @selected(old('is_deleted', $doctor_info->is_deleted->value) == '1')>
                                        Sim
                                    </option>
                                    <option value="0" @selected(old('is_deleted', $doctor_info->is_deleted->value) == '0')>
                                        Não
                                    </option>
                                </select>
                                @error('is_deleted')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- CPF E CNS --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label">{{ __('CPF ') }}<span class="text-danger"></span></label>
                                <input type="text" 
                                    class="form-control @error('doctor_cpf') is-invalid @enderror" 
                                    placeholder="{{ __('Digite o nº do CPF') }}" name="doctor_cpf" id="doctor_cpf" 
                                    value="{{ old('doctor_cpf', $doctor_info->doctor_cpf) }}"
                                >
                                @error('doctor_cpf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="control-label">{{ __('CNS ') }}<span class="text-danger"></span></label>
                                <input type="text" 
                                    class="form-control @error('doctor_cns') is-invalid @enderror" 
                                    name="doctor_cns" id="doctor_cns" placeholder="{{ __('Digite o nº do cartão SUS') }}"
                                    value="{{ old('doctor_cns', $doctor_info->doctor_cns) }}"
                                >
                                @error('doctor_cns')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>    
                        
                        {{-- CONSELHO DE CLASSE, ESTADO EMISSOR E Nº DE REGISTRO --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label">
                                    {{ __('Conselho de Classe ') }}<span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('class_council_id') is-invalid @enderror" 
                                    name="class_council_id" id="class_council" required
                                >
                                    <option value="">Selecione</option>
                                    @foreach ($classCouncils as $classCouncil)
                                        <option value="{{ $classCouncil->id }}"
                                            @selected(old('class_council_id', $doctor_info->class_council_id) == $classCouncil->id)
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

                            <div class="col-md-3 form-group">
                                <label class="control-label">
                                    {{ __('Estado Emissor ') }}<span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('issuing_state') is-invalid @enderror" 
                                    name="issuing_state_id" id="issuing_state" required
                                >
                                    <option value="">Selecione</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            @selected(old('issuing_state_id', $doctor_info->issuing_state_id) == $state->id)
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
                            <div class="col-md-3 form-group">
                                <label class="control-label">
                                    {{ __('Nº de Registro do Conselho ') }}<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('counsil_number') is-invalid @enderror" 
                                    placeholder="{{ __('Digite o nº do conselho') }}" 
                                    id="counsil_number" name="counsil_number" 
                                    value="{{ old('counsil_number', $doctor_info->counsil_number) }}" required
                                >
                                @error('counsil_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                               
                        {{-- EMAIL, SENHA DE ACESSO E Nº DE CONTATO --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label">{{ __('E-mail ') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" id="email" placeholder="{{ __('Digite o email') }}"
                                    value="{{ old('email', $doctor->email) }}" 
                                >
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3 form-group">
                                <label class="control-label">{{ __('Senha de acesso ') }}</label>
                                <input type="text" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" id="password" placeholder="{{ __('Digite a senha') }}"
                                    value="{{ old('password', $doctor->password) }}"
                                >
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="control-label">{{ __('Nº de Contato ') }}</label>
                                <input type="tel" class="form-control @error('mobile') is-invalid @enderror" 
                                    name="mobile" id="patientMobile" placeholder="{{ __('Digite o nº de contato') }}"
                                    value="{{ old('mobile', $doctor->mobile) }}" 
                                >
                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                                
                        {{-- AVATAR --}}
                        <div class="row">         
                            <div class="col-md-6 form-group">
                                <label class="control-label">{{ __('Foto do Perfil ') }}</label>
                                <img class="@error('profile_photo') is-invalid @enderror"
                                    src="@if ($doctor && $doctor_info && $doctor->profile_photo != 'noImage.png' && $doctor && $doctor_info && $doctor->profile_photo != '') {{ URL::asset('storage/images/users/' . $doctor->profile_photo) }}  @else {{ URL::asset('assets/images/users/noImage.png') }} @endif" 
                                    id="profile_display" onclick="triggerClick()"
                                    data-toggle="tooltip" data-placement="top"
                                    title="Clique para enviar a foto do perfil" 
                                />
                                <input type="file"
                                    class="form-control @error('profile_photo') is-invalid @enderror"
                                    name="profile_photo" id="profile_photo" style="display:none;"
                                    onchange="displayProfile(this)">
                                @error('profile_photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Atualizar detalhes') }}
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
    <script src="{{ asset('assets/js/pages/doctors/create.js') }}"></script>
@endsection
