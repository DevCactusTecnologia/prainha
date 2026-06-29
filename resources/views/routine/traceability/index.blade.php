@extends('layouts.master-layouts')
@section('title') Rastreabilidade @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Rastreabilidade @endslot
        @slot('li_1') Rotina @endslot
        @slot('li_2') Rastreabilidade @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card p-3 mb-3">
                <input type="hidden" data-js="url-historic" value="{{ route('routine.traceability.historic') }}">
                <form action="{{ route('routine.traceability.search') }}" data-js="formTraceability" method="POST">
                    @csrf

                    {{-- PESQUISAR POR PROTOCOLO --}}
                    <div class="d-md-flex bg-light p-2 mb-3">
                        <div class="col-md-2">
                            <label class="form-label" style="font-weight: 600;">Protocolo</label>
                            <input type="text" class="form-control" data-js="protocol">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label invisible">.</label>
                            <button type="submit" id="searchTraceability" class="btn btn-primary waves-effect form-control">
                                <i class="fa fa-search"></i>
                                <span class="ml-2">Pesquisar</span>
                            </button>
                        </div>
                    </div>

                    {{-- DADOS DO USUARIO --}}
                    <div data-js="container-header"></div>

                    {{-- DADOS DOS EXAMES --}}
                    <div data-js="container-table" style="display: none;">
                        <table class="table table-sm table-centered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>EXAME</th>
                                    <th>SOLICITANTE</th>
                                    <th>ANALISTA RESPONSÁVEL</th>
                                    <th class="text-center">DATA DO CADASTRO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody data-js="content-table"></tbody>
                        </table>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div data-js="container-modal"></div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/pages/routine/traceability.js') }}"></script>
@endsection
