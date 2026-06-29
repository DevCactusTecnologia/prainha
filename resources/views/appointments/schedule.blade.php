@extends('layouts.master-layouts')
@section('title') {{ __('Agendar Atendimento') }} @endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/fullcalendar/fullcalendar.min.css') }}">
@endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
        
    @component('components.breadcrumb')
        @slot('title') Agendar Atendimento @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') Agendar Atendimento @endslot
    @endcomponent
    
    <div class="row">
        <div class="col-12">
            <a href="{{ route('appointments.create') }}"
                class="btn btn-primary text-white waves-effect mb-4">
                <i class="bx bx-plus font-size-16 align-middle mr-2"></i> {{ __('Novo Atendimento') }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        {{ __('Lista de Atendimentos') }} | 
                        <label id="selected_date">{{ date('d M, Y') }}</label>
                    </h4>
                    <div id="appointment_list">
                        <table class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Nº') }}</th>
                                    @if ($role == 'patient')
                                        <th>{{ __('Nome do Médico') }}</th>
                                        <th>{{ __('Nº do Médico') }}</th>
                                    @elseif ($role == 'doctor')
                                        <th>{{ __('Nome do Paciente') }}</th>
                                        <th>{{ __('Nº do Paciente') }}</th>
                                    @else
                                        <th>{{ __('Nome do Paciente') }}</th>
                                        <th>{{ __('Nome do Médico') }}</th>
                                        <th>{{ __('Nº do Paciente') }}</th>
                                    @endif
                                    <th>{{ __('Data') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @if ($role == 'receptionist' || $role == 'biomedical')
                                    @foreach ($appointments as $appointment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $appointment->patient->first_name }}
                                            </td>
                                            <td>
                                                {{ $appointment->doctor->first_name }}
                                            </td>
                                            <td>
                                                {{ $appointment->patient->id }}
                                            </td>
                                            <td>
                                                {{ date('d/m/Y', strtotime($appointment->appointment_date)) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @elseif ($role == 'doctor')
                                    @foreach ($appointments as $appointment)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>
                                                {{ $appointment->patient->first_name . ' ' . $appointment->patient->last_name }}
                                            </td>
                                            <td>
                                                {{ $appointment->patient->mobile }}
                                            </td>
                                            <td>
                                                {{ $appointment->date }}
                                            </td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @elseif ($role == 'patient')
                                    @foreach ($appointments as $appointment)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>
                                                {{ $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name }}
                                            </td>
                                            <td>
                                                {{ $appointment->doctor->mobile }}
                                            </td>
                                            <td>
                                                {{ $appointment->date }}
                                            </td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else 
                                    @foreach ($appointments as $appointment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $appointment->patient->first_name }}
                                            </td>
                                            <td>
                                                {{ $appointment->doctor->first_name }}
                                            </td>
                                            <td>
                                                {{ $appointment->patient->id }}
                                            </td>
                                            <td>
                                                {{ date('d/m/Y', strtotime($appointment->appointment_date)) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div id="new_list" style="display : none"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- LIBS -->
    <script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/fullcalendar/fullcalendar.min.js') }}"></script>
    
    <!-- Get App url in Javascript file -->
    <script type="text/javascript">
        var aplist_url = "{{ url('appointments/schedules/list') }}";
    </script>

    <!-- Init js-->
    <script src="{{ URL::asset('assets/js/pages/calendar-init.js') }}"></script>
@endsection
