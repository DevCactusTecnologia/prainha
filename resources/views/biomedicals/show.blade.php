@extends('layouts.master-layouts')
@section('title') Perfil do Analista @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Perfil do Analista  @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') <a href="{{ route('biomedicals.index') }}">Analistas</a> @endslot
        @slot('li_3') Perfil do Analista @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-4">
            <div class="card overflow-hidden">
                <div class="bg-soft-primary">
                    <div class="row">
                        <div class="col-7">
                            <div class="text-primary p-3">
                                <h5 class="text-primary">Informações do Analista</h5>
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
                            <div class="avatar-md profile-user-wid mb-4">
                                <img src="@if ($biomedical->profile_photo != ''){{ URL::asset('storage/images/users/' . $biomedical->profile_photo) }}@else{{ URL::asset('assets/images/users/noImage.png') }}@endif" alt="{{ $biomedical->fisrt_name }}"
                                    class="img-thumbnail rounded-circle">
                            </div>
                            <h5 class="font-size-15">{{ $biomedical->first_name }}</h5>
                            <p class="text-muted mb-0 text-truncate"> {{ $biomedical->title }} </p>
                        </div>
                        <div class="col-sm-7">
                            <div class="pt-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="font-size-12">{{ __('Último Login:') }}</h5>
                                        @if ($biomedical->last_login)
                                            <p class="text-muted mb-0">
                                                {{ date('d/m/Y H:i:s', strtotime($biomedical->last_login)) }}
                                            </p>
                                        @else
                                            <p class="bg-light mb-0 p-2 text-muted">
                                                O profissional ainda não acessou o sistema.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if ($role == 'admin')
                                    <div class="mt-4">
                                        <a href="{{ route('biomedicals.edit', $biomedical->id) }}"
                                            class="btn btn-primary waves-effect waves-light btn-sm"
                                        >
                                            {{ __('Editar Perfil') }} <i class="mdi mdi-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- INFORMAÇÕES PESSOAIS DO ANALISTA --}}
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">{{ __('Informações pessoais') }}</h4>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">{{ __('Nome:') }}</th>
                                    <td>{{ $biomedical->first_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Contato:') }}</th>
                                    <td> {{ $biomedical->mobile }} </td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('E-mail:') }}</th>
                                    <td> {{ $biomedical->email }} </td>
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
                                    <p class="text-muted font-weight-medium">{{ __('Exames pendentes') }}</p>
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
                                    <p class="text-muted font-weight-medium">{{ __('Total') }}</p>
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
                                <span class="d-none d-sm-block">{{ __('Lista de atendimentos') }}</span>
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
                            <table class="table table-bordered table-sm table-centered table-hover dt-responsive nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th>{{ __('Nº') }}</th>
                                        <th>{{ __('Nome do Médico') }}</th>
                                        <th>{{ __('Nome do Paciente') }}</th>
                                        <th>{{ __('Data') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $currentpage = $appointments->currentPage();
                                    @endphp
                                    @foreach ($appointments as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 + $limit * ($currentpage - 1) }}</td>
                                            <td>{{ $item->doctor->first_name }}</td>
                                            <td> {{ $item->patient->first_name }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->appointment_date)) }}</td>
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

                        <div class="tab-pane" id="Invoices" role="tabpanel">
                            <table class="table table-bordered dt-responsive nowrap "
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nº') }}</th>
                                        <th>{{ __('Data') }}</th>
                                        <th>{{ __('Nome do Paciente') }}</th>
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
                                            <td>{{ $loop->index + 1 + $per_page * ($currentpage - 1) }}</td>
                                            <td>{{ date('d-m-Y') }}</td>
                                            <td>{{ $item->user->first_name . ' ' . $item->user->last_name }}</td>
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
