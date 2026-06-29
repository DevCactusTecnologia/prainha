@extends('layouts.master-layouts')
@section('title') {{ __('Mapa individual') }} @endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('title') Mapa individual @endslot
        @slot('li_1') Rotina @endslot
        @slot('li_2') Mapa por paciente @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card p-3 mb-3">
                <form action="{{ route('routine.map.patient.search') }}" id="formMapPatient" method="POST">
                    @csrf

                    <div class="d-md-flex">
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Data do atendimento</label>
                            <input type="date" id="date" class="form-control">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Nome do paciente</label>
                            <input type="text" id="patient" class="form-control">
                        </div>
                        <div class="col-md-2 mb-4">
                            <label class="form-label invisible">.</label>
                            <button type="submit" id="searchPatient" class="btn btn-primary form-control">
                                <i class="fa fa-search"></i>
                                <span class="ml-2">Buscar</span>
                            </button>
                        </div>
                    </div>
                </form>

                <div id="contentTable" style="display: none;">
                    <table class="table table-sm table-centered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Protocolo</th>
                                <th>Paciente</th>
                                <th>CPF</th>
                                <th class="text-center">Data do atendimento</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="contentMap"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/pages/routine/patient.js') }}"></script>
@endsection
