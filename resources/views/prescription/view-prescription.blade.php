@extends('layouts.master-layouts')
@section('title') {{ __('Detalhes da prescrição') }} @endsection
@section('body')

    <body data-topbar="dark" data-layout="horizontal">
    @endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title') Detalhes da prescrição @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Lista de Detalhes da prescrição @endslot
            @slot('li_3') Detalhes da prescrição @endslot
        @endcomponent
        <!-- end page title -->
        <div class="row d-print-none">
            <div class="col-12">
                <a href="{{ url('prescription') }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                        <i
                            class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>{{ __('Volar a lista de prescrição') }}
                    </button>
                </a>
                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light mb-4">
                    <i class="fa fa-print"></i>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-title">
                            <h4 class="float-right font-size-16">Prescription #{{ $prescription->id }}</h4>
                            <div class="mb-4">
                                <img src="{{ URL::asset('assets/images/logo-dark.png') }}" alt="logo" height="20" />
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-5">
                                <address>
                                    <strong>{{ __('Dr(a)') }}</strong><br>
                                    {{ $user_details->appointment->doctor->first_name . ' ' . $user_details->appointment->doctor->last_name }}<br>
                                    <i class="mdi mdi-phone"></i> {{ $user_details->appointment->doctor->mobile }}<br>
                                    <i class="mdi mdi-email"></i> {{ $user_details->appointment->doctor->email }}<br>
                                </address>
                            </div>
                            <div class="col-4">
                                <address>
                                    <strong>{{ __('Paciente') }}</strong><br>
                                    {{ $user_details->patient->first_name . ' ' . $user_details->patient->last_name }}<br>
                                    <i class="mdi mdi-phone"></i> {{ $user_details->patient->mobile }}<br>
                                    <i class="mdi mdi-email"></i> {{ $user_details->patient->email }}<br>
                                </address>
                            </div>
                            <div class="col-3">
                                <address>
                                    <strong>{{ __('Data da prescrição: ') }}</strong>{{ $prescription->created_at }}<br>
                                    <strong>{{ __('Data do Atendimento: ') }}</strong>{{ $prescription->appointment->appointment_date . ' ' . $prescription->appointment->timeSlot->form . ' ' . $prescription->appointment->timeSlot->to }}<br>
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5 mt-3 text-center">
                                <address>
                                    <strong>{{ __('Sintomas:') }}</strong><br>
                                    {{ $prescription->symptoms }}
                                </address>
                            </div>
                            <div class="col-5 mt-3 text-center">
                                <address>
                                    <strong>{{ __('Diagnóstico:') }}</strong><br>
                                    {{ $prescription->diagnosis }}
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="py-2 mt-3">
                                    <h3 class="font-size-15 font-weight-bold">{{ __('Medicamentos') }}</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 70px;">{{ __('Nº') }}</th>
                                                <th>{{ __('Nome') }}</th>
                                                <th>{{ __('Observações') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($medicines as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->notes }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="py-2 mt-3">
                                    <h3 class="font-size-15 font-weight-bold">{{ __('Exames') }}</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 70px;">{{ __('Nº') }}</th>
                                                <th>{{ __('Nome') }}</th>
                                                <th>{{ __('Observações') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($test_reports as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td> {{ $item->name }} </td>
                                                    <td> {{ $item->notes }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
