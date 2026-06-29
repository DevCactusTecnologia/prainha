@extends('layouts.master-layouts')
@section('title') {{ __('Atualizar recepcionista') }} @endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    
    @component('components.breadcrumb')
        @slot('title') Atualizar recepcionista @endslot
        @slot('li_1') <a href="{{ url('/') }}">{{ __('Dashboard') }}</a> @endslot
        @slot('li_2') <a href="{{ route('receptionists.index') }}">{{ __('Recepcionistas') }}</a> @endslot
        @slot('li_3') Atualizar recepcionista @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            @if ($receptionist)
                @if ($role == 'receptionist' || $role == 'biomedical')
                    <a href="{{ url('/') }}" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                        {{ __('Voltar ao Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('receptionists.show', $receptionist->id) }}" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                        {{ __('Voltar para o perfil do recepcionista') }}
                    </a>
                @endif
            @else
                <a href="{{ route('receptionists.index') }}" class="btn btn-primary waves-effect mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                    {{ __('Voltar à lista de recepcionistas') }}
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('receptionists.update', $receptionist->id) }}" method="POST" 
                    enctype="multipart/form-data" class="card-body"
                >
                    @csrf
                    @method('PUT')

                    {{-- NOME COMPLETO --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                {{ __('Nome completo ') }}<span class="text-danger">*</span>
                            </label>
                            <input type="text" name="first_name" id="firstname"
                                class="form-control text-uppercase @error('first_name') is-invalid @enderror" 
                                placeholder="{{ __('Digite o nome sem abreviações') }}"
                                value="{{ old('first_name', $receptionist->first_name) }}" required
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
                                <option value="1" @selected(old('is_deleted', $receptionist_info->is_deleted->value) == '1')>
                                    Sim
                                </option>
                                <option value="0" @selected(old('is_deleted', $receptionist_info->is_deleted->value) == '0')>
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
                                name="cpf" id="cpf" value="{{ old('cpf', $receptionist_info->cpf) }}" 
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
                                name="cns" id="cns" value="{{ old('cns', $receptionist_info->cns) }}" 
                                placeholder="{{ __('Digite o nº do cartão SUS') }}"
                            >
                            @error('cns')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror   
                        </div>
                    </div>
                    
                    {{-- EMAIL, SENHA DE ACESSO E Nº DE CONTATO --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('E-mail ') }}<span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" id="email" placeholder="{{ __('Digite o email') }}"
                                value="{{ old('email', $receptionist->email) }}" required
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
                                name="password" id="password" value="{{ old('password', $receptionist->password) }}" 
                                placeholder="{{ __('Digite a senha') }}"
                            >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">
                                {{ __('Nº de Contato ') }}<span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror" 
                                name="mobile" id="mobile" value="{{ old('mobile', $receptionist->mobile) }}" 
                                placeholder="{{ __('Digite o nº de contato') }}" required
                            >
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- FOTO DE PERFIL --}}
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('Foto do perfil') }}</label>
                            <img class="@error('profile_photo') is-invalid @enderror" src="@if ($receptionist && $receptionist->profile_photo != null){{ URL::asset('storage/images/users/' . $receptionist->profile_photo) }}@else{{ URL::asset('assets/images/users/noImage.png') }}@endif" 
                                id="profile_display" onclick="triggerClick()" 
                                data-toggle="tooltip" data-placement="top" title="Clique para enviar a foto do perfil" 
                            />
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                                name="profile_photo" id="profile_photo" 
                                style="display:none;" onchange="displayProfile(this)"
                            >
                            @error('profile_photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group"></div>
                        <div class="col-md-6 form-group"></div>
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
    <script src="{{ asset('assets/js/pages/receptionists/script.js') }}"></script>
@endsection
