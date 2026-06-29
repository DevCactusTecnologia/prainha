@extends('layouts.master-layouts')
@section('title') Lista de ocorrências por atendimento @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Lista de ocorrências por atendimento @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') Lista de ocorrências @endslot
    @endcomponent

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Procedimento <strong>salvo</strong> com sucesso!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        {{ session()->forget('success') }}
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <input type="hidden" data-js="base-url" value="{{ url('/') }}">
                <div class="card-body">

                    <div class="d-md-flex mb-3">
                        <div class="d-md-flex justify-content-between shadow-sm border border-1 p-3 mr-3" style="width: 18% ;border-radius: 10px">
                            <div class="font-weight-bold text-dark">
                                <div class="font-size-13 mb-2">Pendentes</div>
                                <div class="font-size-18">{{ $total->pendings }}</div>
                            </div>
                            <div>
                                <div class="font-size-13 invisible mb-0">.</div>
                                <div class="bg-primary font-size-15 px-2 py-1 rounded-lg text-white">
                                    <i class="mdi mdi-alert"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-md-flex justify-content-between shadow-sm border border-1 p-3 mr-3" style="width: 18% ;border-radius: 10px">
                            <div class="font-weight-bold text-dark">
                                <div class="font-size-13 mb-2">Parcial</div>
                                <div class="font-size-18">{{ $total->partials }}</div>
                            </div>
                            <div>
                                <div class="font-size-13 invisible mb-0">.</div>
                                <div class="bg-primary font-size-15 px-2 py-1 rounded-lg text-white">
                                    <i class="mdi mdi-forum"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-md-flex justify-content-between shadow-sm border border-1 p-3 mr-3" style="width: 18% ;border-radius: 10px">
                            <div class="font-weight-bold text-dark">
                                <div class="font-size-13 mb-2">Resolvido</div>
                                <div class="font-size-18">{{ $total->resolveds }}</div>
                            </div>
                            <div>
                                <div class="font-size-13 invisible mb-0">.</div>
                                <div class="bg-primary font-size-15 px-2 py-1 rounded-lg text-white">
                                    <i class="mdi mdi-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <table data-js="datatable" class="table table-hover table-centered table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="pb-0">
                                            <input type="text" class="form-control form-control-sm" 
                                                data-js="filter-patient" onkeyup="filterPatient(event)" 
                                                placeholder="Digite o nome do paciente"
                                            >
                                        </th>
                                        <th class="pb-0" style="width: 12%;">
                                            <input type="number" class="form-control form-control-sm text-center" 
                                                data-js="filter-protocol" onkeyup="filterProtocol(event)" placeholder="Protocolo"
                                            >
                                        </th>
                                        <th class="pb-0">Data da ocorrência</th>
                                        <th class="pb-0">Tipo de ocorrência</th>
                                        <th class="pb-0">Usuário</th>
                                        <th class="pb-0">
                                            <select class="form-control form-control-sm" data-js="filter-status" onchange="runFilter()">
                                                <option value="">Status</option>
                                                @foreach ($situations as $situation)
                                                    <option value="{{ $situation->value }}">{{ $situation->getName() }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th class="pb-0">Ação</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" data-js="datatable-url" value="{{ route('routine.occurrence.search') }}">
    <input type="hidden" data-js="datatable-lang-pt-br" value="{{ asset('assets/libs/datatables/lang/pt-BR.json') }}">
@endsection

@section('css-bottom')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}" />
@endsection

@section('script')
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/routine/occurrence.js') }}"></script>
@endsection
