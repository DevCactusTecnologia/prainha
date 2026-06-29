@extends('layouts.master-layouts')
@section('title') Mapa por analista @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Mapa por analista @endslot
        @slot('li_1') Rotina @endslot
        @slot('li_2') Mapa por analista @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card p-3 mb-3">
                <form action="{{ route('routine.map.biomedical.search') }}" id="formMapBiomedical" method="POST">
                    @csrf
                    <div class="d-md-flex">
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Data do atendimento</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="col-md-2 mb-4">
                            <label class="form-label invisible">.</label>
                            <button type="submit" id="searchExamByBiomedical" class="btn btn-primary form-control">
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
                                <th>Analista</th>
                                <th>Exames a serem analidados</th>
                                <th>Data do atendimento</th>
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
    <script src="{{ asset('assets/js/pages/routine/biomedical.js') }}"></script>
@endsection
