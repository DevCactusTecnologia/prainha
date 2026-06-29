@extends('layouts.master-layouts')
@section('title') {{ __('Perfil do médico') }} @endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')

    {{-- BREADCRUMB --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">
                    {{ __('Perfil do médico') }}
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('doctors.index') }}">{{ __('Médicos') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Perfil do médico') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card overflow-hidden">
                <div class="bg-soft-primary">
                    <div class="row">
                        <div class="col-7">
                            <div class="text-primary p-3">
                                <h5 class="text-primary">{{ __('Informações do médico') }}</h5>
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="{{ URL::asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="avatar-md profile-user-wid mb-3">
                                <img src="@if ($doctor->profile_photo != ''){{ URL::asset('storage/images/users/' . $doctor->profile_photo) }}@else{{ URL::asset('assets/images/users/noImage.png') }}@endif" alt="{{ $doctor->first_name }}"
                                    class="img-thumbnail rounded-circle">
                            </div>
                            <h5 class="font-size-15">{{ @$doctor->first_name }}</h5>
                            <p class="text-muted mb-0 text-truncate"> {{ @$doctor_info->title }} </p>
                        </div>
                        <div class="col-sm-7">
                            <div class="pt-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="font-size-12">{{ __('Último Login :') }}</h5>
                                        @if ($doctor->last_login)
                                            <p class="text-muted mb-0">
                                                {{ date('d/m/Y H:i:s', strtotime($doctor->last_login)) }}
                                            </p>
                                        @else
                                            <p class="bg-light mb-0 p-2 text-muted">
                                                O profissional ainda não acessou o sistema.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if ($role == 'doctor' || $role == 'admin')
                                    <div class="mt-4">
                                        <a href="{{ route('doctors.edit', $doctor->id) }}"
                                            class="btn btn-primary waves-effect waves-light btn-sm"
                                        >
                                            {{ __('Editar Perfil') }} <i class="mdi mdi-arrow-right ml-1"></i>
                                        </a>
                                        {{-- <a href="{{ url('time-edit/' . $doctor->id) }}"
                                            class="btn btn-primary waves-effect waves-light btn-sm">{{ __('Editar Horário Disponível') }}
                                            <i class="mdi mdi-arrow-right ml-1"></i></a> --}}
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">{{ __('Informações pessoais') }}</h4>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">{{ __('Nome:') }}</th>
                                    <td>{{ @$doctor->first_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Conselho:') }}</th>
                                    <td> {{ @$doctor_info->council->name }} </td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Estado:') }}</th>
                                    <td> {{ @$doctor_info->state->name }} </td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Nº de Conselho:') }}</th>
                                    <td> {{ @$doctor_info->counsil_number }} </td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Nº de Contato:') }}</th>
                                    <td> {{ $doctor->mobile ?: '-' }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <div class="col-xl-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium">{{ __('Atendimentos') }}</p>
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
                                    <p class="text-muted font-weight-medium">{{ __('Atendimentos Pendentes') }}</p>
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
                                    <p class="text-muted font-weight-medium">{{ __('Total Atendimentos') }}</p>
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
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#AppointmentList" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">{{ __('Lista de Atendimentos') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#PrescriptionList" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                <span class="d-none d-sm-block">{{ __('Lista de Prescrições') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Invoices" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block">{{ __('Faturas') }}</span>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" id="AppointmentList" role="tabpanel">
                            <table class="table table-bordered table-sm table-centered table-hover dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;"
                            >
                                <thead class="bg-light">
                                    <tr>
                                        <th>{{ __('Nº') }}</th>
                                        <th>{{ __('Nome do Paciente') }}</th>
                                        <th>{{ __('Nº de Contato') }}</th>
                                        <th>{{ __('E-mail') }}</th>
                                        <th>{{ __('Data') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $currentpage = $appointments->currentPage();
                                    @endphp
                                    @foreach ($appointments as $appointment)
                                        <tr>
                                            <td>{{ $loop->index + 1 + $limit * ($currentpage - 1) }}</td>
                                            <td>{{ $appointment->patient->first_name }}</td>
                                            <td>{{ $appointment->patient->mobile }}</td>
                                            <td>{{ $appointment->patient->email }}</td>
                                            <td>{{ date('d/m/Y', strtotime($appointment->appointment_date)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12 text-center mt-3">
                                <div class="d-flex justify-content-start">
                                    Mostrando {{ $appointments->firstItem() }} de {{ $appointments->lastItem() }} de
                                    {{ $appointments->total() }} entradas
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $appointments->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="PrescriptionList" role="tabpanel">
                            <table class="table table-bordered dt-responsive nowrap "
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nº') }}</th>
                                        <th>{{ __('Nome do Paciente') }}</th>
                                        <th>{{ __('Data') }}</th>
                                        <th>{{ __('Opções') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $currentpage = $prescriptions->currentPage();
                                    @endphp
                                    @foreach ($prescriptions as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 + $limit * ($currentpage - 1) }}</td>
                                            <td>{{ $item->patient->first_name }} {{ $item->patient->last_name }}
                                            </td>
                                            <td>{{ date('d-m-Y') }}</td>
                                            <td>
                                                <a href="{{ url('prescription/' . $item->id) }}">
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        {{ __('Visualizar') }}
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12 text-center mt-3">
                                <div class="d-flex justify-content-start">
                                    Mostrando {{ $prescriptions->firstItem() }} de {{ $prescriptions->lastItem() }}
                                    de {{ $prescriptions->total() }} entradas
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $prescriptions->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="Invoices" role="tabpanel">
                            <table class="table table-bordered dt-responsive nowrap "
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nº') }}</th>
                                        <th>{{ __('Nome do Paciente') }}</th>
                                        <th>{{ __('Data') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Opções') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @php
                                        $currentpage = $invoices->currentPage();
                                    @endphp
                                    @foreach ($invoices as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 + $limit * ($currentpage - 1) }}</td>
                                            <td>{{ $item->user->first_name }} {{ $item->user->last_name }}</td>
                                            <td>{{ date('d-m-Y') }}</td>
                                            <td>{{ $item->payment_status }}</td>
                                            <td>
                                                <a href="{{ url('invoice/' . $item->id) }}">
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        {{ __('Visualizar') }}
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                            <div class="col-md-12 text-center mt-3">
                                Mostrando 0 entradas
                                {{-- <div class="d-flex justify-content-start">
                                    Mostrando {{ $invoices->firstItem() }} de {{ $invoices->lastItem() }} de
                                    {{ $invoices->total() }} entradas
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $invoices->links() }}
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
