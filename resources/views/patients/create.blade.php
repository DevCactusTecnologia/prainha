@extends('layouts.master-layouts')
@section('title') Adicionar novo paciente @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Adicionar novo paciente @endslot
        @slot('li_1') <a href="{{ url('/') }}"> Dashboard </a> @endslot
        @slot('li_2') <a href="{{ route('patients.index') }}"> Pacientes </a> @endslot
        @slot('li_3') Adicionar novo paciente @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <a href="{{ route('patients.index') }}" class="btn btn-primary waves-effect mb-4">
                <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                Voltar à lista de pacientes
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data" class="card-body">
                    @csrf

                    {{-- NOME COMPLETO E APELIDO --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label"> Nome Completo<span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase @error('first_name') is-invalid @enderror" 
                                placeholder="Digite o nome completo sem abeviações" name="first_name" id="firstName" 
                                value="{{ old('first_name') }}" required
                            >
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">Nome Social<span class="text-danger"></span></label>
                            <input type="text" class="form-control @error('patient_social_name') is-invalid @enderror" 
                                name="patient_social_name" id="patient_social_name" 
                                value="{{ old('patient_social_name') }}" placeholder="Digite o nome social"
                            >
                            @error('patient_social_name')
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
                                name="mother_name" id="motherName" value="{{ old('mother_name') }}" 
                            >
                            @error('mother_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">CPF</label>
                            <input type="text" class="form-control @error('patient_cpf') is-invalid @enderror" 
                                name="patient_cpf" id="patient_cpf" placeholder="Digite o nº do CPF"
                                value="{{ old('patient_cpf') }}" 
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
                                placeholder="Digite o nº do Cartão SUS" name="cns" id="cns" value="{{ old('cns') }}" 
                            >
                            @error('cns')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- DATA DE NASCIMENTO, SEXO, EMAIL E NUMERO DE CONTATO --}}
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="control-label">Data de Nascimento<span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('dob') is-invalid @enderror" 
                                id="dob" name="dob" value="{{ old('dob') }}" required 
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
                                <option value="">Selecione</option>
                                <option value="Male" @selected(old('gender') == 'Male')>Masculino</option>
                                <option value="Female" @selected(old('gender') == 'Female')>Feminino</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">E-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                placeholder="Digite o E-mail" name="email" id="email" value="{{ old('email') }}"
                            >
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">Nº de Contato</label>
                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror"
                                placeholder="Digite o nº de Contato" 
                                name="mobile" id="mobile"  value="{{ old('mobile') }}" 
                            >
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- ENDEREÇO --}}
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="form-label">Endereço Atual</label>
                            <textarea id="formmessage" name="address"
                                class="form-control @error('address') is-invalid @enderror" rows="3" 
                                placeholder="Digite o endereço"
                            >{{ old('address') }}</textarea>
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
                                Adicionar novo paciente
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
