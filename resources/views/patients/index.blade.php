@extends('layouts.master-layouts')
@section('title') {{ __('Lista de Pacientes') }} @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Lista de Pacientes @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') Pacientes @endslot
    @endcomponent

    {{-- ALERT --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session()->get('success') !!}
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

                    <div class="row">
                        <div class="col-lg-3">
                            <a href="{{ route('patients.create') }}" class="btn btn-primary waves-effect mb-4">
                                <i class="bx bx-plus font-size-16 align-middle mr-2"></i> {{ __('Novo Paciente') }}
                            </a>
                        </div>
                        <div class="col-lg-4"></div>
                        <div class="col-lg-5 text-right">
                            @csrf
                            
                            <input class="form-control" id="searchPatient" type="text" name="search_name" 
                                placeholder="Pesquisar paciente pelo nome, CPF ou CNS"  
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12" style="overflow-x: hidden; overflow-y: scroll; height: 650px;">
                            <table class="table table-hover table-centered table-bordered table-sm dt-responsive nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 2%;">{{ __('Nº') }}</th>
                                        <th style="width: 50%;">{{ __('Nome') }}</th>
                                        <th style="width: 13%;">{{ __('CPF') }}</th>
                                        <th style="width: 20%;">{{ __('CNS') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th style="width: 12%;">{{ __('Opções') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="contentPatient">
                                    @php
                                        $currentpage = $patients->currentPage();
                                    @endphp
                                    @foreach ($patients as $item)
                                        <tr>
                                            <td>
                                                {{ $loop->index + 1 + $limit * ($currentpage - 1) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('patients.show', $item->id) }}">
                                                    {{ $item->first_name }} {{ $item->last_name }}
                                                </a>
                                            </td>
                                            <td>{{ $item->patient->cpf_masked }}</td>
                                            <td>{{ $item->patient->cns_masked }}</td>
                                            <td>
                                                <span class="{{ $item->patient->is_deleted?->getColor() }}">
                                                    {{ $item->patient->is_deleted?->getName() }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('patients.show', $item->id) }}" title="Visualizar Perfil"
                                                    class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                >
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('patients.edit', $item->id) }}" title="Editar Perfil"
                                                    class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                >
                                                    <i class="mdi mdi-lead-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tbody id="loader" style="display: none;">
                                    <tr>
                                        <td colspan="5" >
                                            <div class="d-flex justify-content-center align-items-center text-primary" 
                                                style="font-size: 20px; height: 200px;"
                                            >
                                                <span class="spinner-border spinner-border-sm mr-2" 
                                                    role="status" aria-hidden="true">
                                                </span> Carregando...
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 text-center mt-3" id="paginate">
                            <div class="d-flex justify-content-end">
                                {{ $patients->links() }}
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/pages/patients/search.js') }}"></script>
@endsection
