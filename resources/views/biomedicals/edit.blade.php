@extends('layouts.master-layouts')
@section('title') Atualizar Anslista @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Atualizar analista  @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') <a href="{{ route('biomedicals.index') }}">Analistas</a> @endslot
        @slot('li_3') Atualizar analista @endslot
    @endcomponent
    
    <div class="row">
        <div class="col-12">
            @if ($biomedical)
                @if ($role == 'biomedical')
                    <a href="{{ url('/') }}" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>Voltar ao Dashboard
                    </a>
                @else
                    <a href="{{ route('biomedicals.show', $biomedical->id) }}" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>Voltar para Perfil do Analista
                    </a>
                @endif
            @else
                <a href="{{ route('biomedicals.index') }}" class="btn btn-primary waves-effect mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>Voltar à Lista de Analistas
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('biomedicals.update', $biomedical->id) }}" method="POST" 
                    class="card-body" enctype="multipart/form-data"
                >
                    @csrf
                    @method('PUT')

                    {{-- NOME COMPLETO E TIPO DE PROFISSIONAL --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                {{ __('Nome completo ') }}<span class="text-danger">*</span>
                            </label>
                            <input type="text" name="first_name" placeholder="{{ __('Digite o nome sem abreviações') }}"
                                class="form-control text-uppercase @error('first_name') is-invalid @enderror" 
                                id="firstname" value="{{ old('first_name', $biomedical->first_name) }}"
                                maxlength="28" required
                            >
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">
                                {{ __('Tipo de profissional ') }}<span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="professional_type_id" required>
                                <option value="">Selecione</option>
                                @foreach ($professionals as $professional)
                                    <option value="{{ $professional->id }}"
                                        @selected(old('professional_type_id', $biomedical_info->professional_type_id) == $professional->id)
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
                        <div class="col-md-3 form-group">
                            <label class="control-label">
                                {{ __('Inativo ') }}<span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="is_deleted" required>
                                <option value="1" @selected(old('is_deleted', $biomedical_info->is_deleted->value) == '1')>
                                    Sim
                                </option>
                                <option value="0" @selected(old('is_deleted', $biomedical_info->is_deleted->value) == '0')>
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
                            <label class="control-label">{{ __('CPF') }}</label>
                            <input type="text" class="form-control @error('cpf') is-invalid @enderror"
                                name="cpf" id="cpf" value="{{ old('cpf', $biomedical_info->cpf) }}" 
                                placeholder="{{ __('Digite o nº do CPF') }}"
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
                                name="cns" id="cns" value="{{ old('cns', $biomedical_info->cns) }}" 
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
                                        @selected(old('class_council_id', $biomedical_info->class_council_id) == $classCouncil->id)
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
                                        @selected(old('issuing_state_id', $biomedical_info->issuing_state_id) == $state->id)
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
                                value="{{ old('counsil_number', $biomedical_info->counsil_number) }}" required
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
                                name="email" id="email" value="{{ old('email', $biomedical->email) }}"
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
                                name="password" id="password" value="{{ old('password', $biomedical->password) }}"
                                placeholder="{{ __('Digite a senha') }}"
                            >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('Nº de Contato') }}</label>
                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror"
                                name="mobile" id="mobile" value="{{ old('mobile', $biomedical->mobile) }}"
                                placeholder="{{ __('DIgite o nº do contato') }}"
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
                            <img class="@error('profile_photo') is-invalid @enderror"
                                src="@if ($biomedical && $biomedical->profile_photo != null){{ URL::asset('storage/images/users/' . $biomedical->profile_photo) }}@else{{ URL::asset('assets/images/users/noImage.png') }}@endif" 
                                data-toggle="tooltip" data-placement="top" title="Clique para enviar a foto do perfil"
                                id="profile_display" onclick="triggerClick()"
                            />
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                                name="profile_photo" id="profile_photo" style="display:none;"
                                onchange="displayProfile(this)"
                            >
                            @error('profile_photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group mb-0">
                            <label class="control-label">{{ __('Assinatura ') }}</label>
                            <img class="@error('signature') is-invalid @enderror" 
                                src="@if ($biomedical && $biomedical_info->signature){{ URL::asset('storage/images/users/signature/' . $biomedical_info->signature) }}@else{{ URL::asset('assets/images/users/noImage.png') }}@endif" 
                                id="signature_display" onclick="triggerClickSignature()" data-toggle="tooltip" 
                                data-placement="top" title="Clique para enviar a foto do perfil" 
                            />
                            <input type="file" class="form-control @error('signature') is-invalid @enderror" 
                                name="signature" id="signature" style="display:none;" 
                                onchange="displayProfileSignature(this)"
                            >
                            @error('signature')
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
        
@endsection

@section('script')
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/biomedicals/script.js') }}"></script>
@endsection
