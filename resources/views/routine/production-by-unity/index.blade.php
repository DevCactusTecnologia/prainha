@extends('layouts.master-layouts')
@section('title') {{ __('Produção por unidade') }} @endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('title') Produção por unidade @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') Produção por unidade @endslot
    @endcomponent

    <div class="row"> 

        <div class="col-xl-12">

            <input type="hidden" data-js="baseUrl" value="{{ url('/') }}">
            <div data-js="alert" class="alert alert-warning" style="display: none"></div>

            <div class="card p-3 mb-3">
                <form action="{{ route('routine.production.by.unity.search.all') }}" 
                    data-js="formProductionByUnity" method="POST"
                >
                    @csrf
                    <div class="d-md-flex">
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Data inicial</label>
                            <input type="date" class="form-control" data-js="dateStart">
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Data final</label>
                            <input type="date" class="form-control" data-js="dateEnd">
                        </div>
                        <div class="col-md-2 mb-4">
                            <label class="form-label invisible">.</label>
                            <button type="submit" data-js="searchProductionByBiomedical" 
                                class="btn btn-primary form-control"
                            >
                                <i class="fa fa-search"></i>
                                <span class="ml-2">Buscar</span>
                            </button>
                        </div>
                    </div>
                </form>

                <div data-js="tableContent" style="display: none;">
                    <table class="table table-responsive table-sm table-centered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Nº</th>
                                <th>Unidade de atendimento</th>
                                <th>Total de exames analisados</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody data-js="content"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/pages/routine/production-by-unity.js') }}"></script>
@endsection
