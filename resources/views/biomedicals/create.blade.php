@extends('layouts.master-layouts')
@section('title') Adicionar novo analista @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
     
    @component('components.breadcrumb')
        @slot('title') Adicionar novo analista  @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') <a href="{{ route('biomedicals.index') }}">Analista</a> @endslot
        @slot('li_3') Adicionar novo analista @endslot
    @endcomponent
        
    <div class="row">
        <div class="col-12">
            <a href="{{ route('biomedicals.index') }}" class="btn btn-primary waves-effect waves-light mb-4">
                <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>Voltar à Lista de Analistas
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('biomedicals.store') }}" method="POST" enctype="multipart/form-data" class="card-body">
                    @csrf

                    {{-- NOME COMPLETO E TIPO DE PROFISSIONAL --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                {{ __('Nome completo ') }}<span class="text-danger">*</span>
                            </label>
                            <input type="text" name="first_name" placeholder="{{ __('Digite o nome sem abreviações') }}"
                                class="form-control text-uppercase @error('first_name') is-invalid @enderror" 
                                id="firstname" value="{{ old('first_name') }}" maxlength="28" required
                            >
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                {{ __('Tipo de profissional ') }}<span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="professional_type_id" required>
                                <option value="">Selecione</option>
                                @foreach ($professionals as $professional)
                                    <option value="{{ $professional->id }}"
                                        @selected(old('professional_type_id') == $professional->id)
                                    >
                                        {{ $professional->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('professional_type_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- CPF E CNS --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('CPF') }}</label>
                            <input type="text" class="form-control @error('cpf') is-invalid @enderror"
                                name="cpf" id="cpf" value="{{ old('cpf') }}" placeholder="{{ __('Digite o nº do CPF') }}"
                            >
                            @error('cpf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('CNS') }}</label>
                            <input type="text" class="form-control @error('cns') is-invalid @enderror"
                                name="cns" id="cns" value="{{ old('cns') }}" 
                                placeholder="{{ __('Digite o nº do cartão SUS') }}"
                            >
                            @error('cns')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- CONSELHO DE CLASSE, ESTADO EMISSOR E NÚMERO DE REGISTRO --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                {{ __('Conselho de Classe') }}<span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('class_council_id') is-invalid @enderror" 
                                name="class_council_id" required
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
                        <div class="col-md-3 form-group">
                            <label class="control-label">
                                {{ __('Estado Emissor') }}<span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('issuing_state_id') is-invalid @enderror" 
                                name="issuing_state_id" required
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
                        <div class="col-md-3 form-group">
                            <label class="control-label">
                                {{ __('Número de Registro do Conselho') }}<span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('counsil_number') is-invalid @enderror" 
                                name="counsil_number" id="counsil_number" placeholder="{{ __('Digite o número do conselho') }}"
                                value="{{ old('counsil_number') }}" required
                            >
                            @error('counsil_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- EMAIL, SENHA DE ACESSO E TELEFONE PARA CONTATO --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('E-mail ') }}<span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" id="email" value="{{ old('email') }}"
                                placeholder="{{ __('Digite o e-mail') }}" required
                            >
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('Senha de acesso') }}</label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror"
                                name="password" id="password" value="{{ old('password') }}"
                                placeholder="{{ __('Digite a senha') }}"
                            >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('Nº de Contato') }}<span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror"
                                name="mobile" id="mobile" value="{{ old('mobile') }}"
                                placeholder="{{ __('DIgite o nº do contato') }}" required
                            >
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- FOTO DO PERFIL E ASSINATURA --}}
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-3 form-group mb-0">
                            <label class="control-label">{{ __('Foto do Perfil ') }}</label>
                            <img class="@error('new_profile_photo') is-invalid @enderror"
                                src="{{ asset('assets/images/users/noImage.png') }}" 
                                id="profile_display" title="Clique para enviar a foto do perfil" 
                                data-toggle="tooltip" data-placement="top" onclick="triggerClick()"
                            />
                            <input type="file" class="form-control @error('new_profile_photo') is-invalid @enderror"
                                name="new_profile_photo" id="new_profile_photo" style="display:none;"
                                onchange="displayProfile(this)"
                            >
                            @error('new_profile_photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group mb-0">
                            <label class="control-label">{{ __('Assinatura') }}</label>
                            <img class="@error('new_signature') is-invalid @enderror" 
                                src="{{ asset('assets/images/users/noImage.png') }}" 
                                id="signature_display" onclick="triggerClickSignature()" data-toggle="tooltip" 
                                data-placement="top" title="Clique para enviar a foto do perfil" 
                            />
                            <input type="file" class="form-control @error('new_signature') is-invalid @enderror" 
                                name="new_signature" id="new_signature" style="display:none;" onchange="displayProfileSignature(this)"
                            >
                            @error('new_signature')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                Adicionar novo analista
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
@endsection

@section('script')
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/biomedicals/script.js') }}"></script>
@endsection
