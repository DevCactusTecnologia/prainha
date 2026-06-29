@extends('layouts.master-layouts')
@section('title') Perfil do Paciente @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Perfil do Paciente  @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') <a href="{{ route('patients.index') }}">Pacientes</a> @endslot
        @slot('li_3') Perfil do Paciente @endslot
    @endcomponent
    
    <div class="row">
        <div class="col-xl-4">
            <div class="card overflow-hidden">
                <div class="bg-soft-primary">
                    <div class="row">
                        <div class="col-7">
                            <div class="text-primary p-3">
                                <h5 class="text-primary">Informações do paciente</h5>
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="avatar-md profile-user-wid mb-4">
                                <img src="@if ($patient->profile_photo != null){{ asset('storage/images/users/' . $patient->profile_photo) }}@else{{ asset('assets/images/users/noImage.png') }}@endif" alt="{{ $patient->first_name }}" class="img-thumbnail rounded-circle">
                            </div>
                            <h5 class="font-size-15">{{ $patient->first_name }}</h5>
                        </div>
                        <div class="col-sm-7">
                            <div class="pt-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="font-size-12">Último Login :</h5>
                                        @if ($patient->last_login)
                                            <p class="text-muted mb-0">
                                                {{ date('d/m/Y H:i:s', strtotime($patient->last_login)) }}
                                            </p>
                                        @else
                                            <p class="bg-light mb-0 p-2 text-muted">
                                                O paciente ainda não acessou o sistema.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary waves-effect btn-sm">
                                        Editar Perfil<i class="mdi mdi-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informações pessoais</h4>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">Nome</th>
                                    <td>{{ @$patient->first_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Sexo:</th>
                                    <td>{{ @$patient_info->gender_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Idade:</th>
                                    <td>{{ @$patient_info->age }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">CPF:</th>
                                    <td>{{ @$patient_info->cpf_masked }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">CNS:</th>
                                    <td>{{ @$patient_info->cns_masked }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Endereço:</th>
                                    <td>{{ $patient_info->address ?: '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-xl-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium">Atendimentos</p>
                                    <h4 class="mb-0">{{ number_format($data['total_appointment']) }}</h4>
                                </div>
                                <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-check-circle font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium">Exames pendentes</p>
                                    <h4 class="mb-0">{{ number_format($data['pending_bill']) }}</h4>
                                </div>
                                <div class="avatar-sm align-self-center mini-stat-icon rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-hourglass font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium">Total</p>
                                    <h4 class="mb-0">{{ number_format($data['revenue'], 2) }}</h4>
                                </div>
                                <div class="avatar-sm align-self-center mini-stat-icon rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-package font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Lista de Atendimentos</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nº</th>
                                    <th>Nome do Médico</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('page_limit'))
                                    @php
                                        $per_page = session()->get('page_limit');
                                    @endphp
                                @else
                                    @php
                                        $per_page = Config::get('app.page_limit');
                                    @endphp
                                @endif
                                @php
                                    $currentpage = $appointments->currentPage();
                                @endphp
                                @foreach ($appointments as $item)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $item->doctor->first_name }} {{ $item->doctor->last_name }}</td>
                                        <td>{{ date('d/m/Y', strtotime($item->appointment_date)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
@endsection
