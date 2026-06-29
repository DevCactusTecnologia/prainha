@extends('layouts.master-layouts')
@section('title') Atualizar paciente @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Atualizar dados do paciente @endslot
        @slot('li_1') <a href="{{ url('/') }}">Dashboard</a> @endslot
        @slot('li_2') <a href="{{ route('patients.index') }}">Pacientes</a> @endslot
        @slot('li_3') Atualizar dados do paciente @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <a href="{{ url('/') }}" class="btn btn-primary waves-effect mb-4">
                <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                Voltar ao Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('patients.update', $patient->id) }}" method="POST" 
                    enctype="multipart/form-data" class="card-body"
                >
                    @csrf
                    @method('PUT')

                    {{-- NOME COMPLETO, APELIDO E INATIVO --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Nome Completo<span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase @error('first_name') is-invalid @enderror" 
                                placeholder="Digite o nome completo sem abeviações" id="firstName"
                                name="first_name" value="{{ old('first_name', $patient->first_name) }}" required
                            >
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">Nome Social<span class="text-danger"></span></label>
                            <input type="text" class="form-control @error('patient_social_name') is-invalid @enderror" 
                                name="patient_social_name" id="patient_social_name" placeholder="Digite o nome social"
                                value="{{ old('patient_social_name', $patient_info->patient_social_name) }}" 
                            >
                            @error('patient_social_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">Inativo<span class="text-danger">*</span></label>
                            <select class="form-control" name="is_deleted" required>
                                <option value="1" @selected(old('is_deleted', $patient_info->is_deleted->value) == '1')>
                                    Sim
                                </option>
                                <option value="0" @selected(old('is_deleted', $patient_info->is_deleted->value) == '0')>
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

                    {{-- NOME DA MAE, CPF E CNS --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Nome da mãe</label>
                            <input type="text" class="form-control text-uppercase @error('mother_name') is-invalid @enderror"
                                name="mother_name" id="motherName"
                                value="{{ old('mother_name', $patient_info->mother_name) }}"
                            >
                            @error('cpf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">CPF</label>
                            <input type="text" class="form-control @error('patient_cpf') is-invalid @enderror" 
                                name="patient_cpf" id="patient_cpf" placeholder="Digite o nº do CPF"
                                value="{{ old('patient_cpf', $patient_info->patient_cpf) }}" 
                            >
                            @error('patient_cpf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">CNS</label>
                            <input type="text" class="form-control @error('cns') is-invalid @enderror"
                                placeholder="Digite o nº do Cartão SUS"
                                name="cns" id="cns" value="{{ old('cns', $patient_info->cns) }}" 
                            >
                            @error('cns')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- DATA DE NASCIMENTO, SEXO  --}}
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="control-label">Data de Nascimento<span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('dob') is-invalid @enderror" 
                                id="dob" name="dob" value="{{ old('dob', $patient_info->dob) }}" required 
                            >
                            @error('dob')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="form-label">Sexo Biológico<span class="text-danger">*</span></label>
                            <select class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                <option selected disabled>Selecione</option>
                                <option value="Male" @selected(old('gender', $patient_info->gender) == 'Male')>Masculino</option>
                                <option value="Female" @selected(old('gender', $patient_info->gender) == 'Female')>Feminino</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">E-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Digite o E-mail"
                                name="email" id="email" value="{{ old('email', $patient->email) }}"
                            >
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">Nº de Contato</label>
                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror" placeholder="Digite o nº de Contato" 
                                name="mobile" id="mobile"  value="{{ old('mobile', $patient->mobile) }}" 
                            >
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- ENDERECO --}}
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="form-label">Endereço Atual</label>
                            <textarea id="formmessage" name="address"
                                class="form-control @error('address') is-invalid @enderror" rows="3" 
                                placeholder="Digite o endereço"
                            >{{ old('address', $patient_info->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" onclick="loader(this)">
                                Atualizar dados
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
    <script src="{{ asset('assets/js/pages/patients/script.js') }}"></script>
@endsection
