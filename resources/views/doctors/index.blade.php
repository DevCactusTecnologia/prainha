@extends('layouts.master-layouts')
@section('title') Lista de Médicos @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Lista de Médicos @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') Médicos @endslot
    @endcomponent

    {{-- ALERT --}}
    @if (session()->has('success'))
        <div class="alert alert-success text-dark alert-dismissible fade show" role="alert">
            {!! session()->get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{ session()->forget('success') }}
    @endif

    <div class="row">
        <input type="hidden" data-js="base-url" value="{{ url('/') }}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @csrf
                    
                    <div class="row">
                        <div class="col-lg-3">
                            <a href="{{ route('doctors.create') }}" class="btn btn-primary waves-effect mb-4">
                                <i class="bx bx-plus font-size-16 align-middle mr-2"></i> Novo Médico
                            </a>
                        </div>
                        <div class="col-lg-4"></div>
                        <div class="col-lg-5 text-right">
                            @csrf
                            
                            <input type="search" class="form-control" id="searchDoctor" name="search_name" 
                                placeholder="Pesquisar médico pelo nome, CPF ou CNS"  
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12" style="overflow-x: hidden; overflow-y: scroll; height: 650px;">
                            <table class="table table-sm table-hover table-centered table-bordered dt-responsive nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 2%;">Nº</th>
                                        <th>Nome</th>
                                        <th style="width: 30%;">Conselho de Classe</th>
                                        <th style="width: 10%;">Estado Emissor</th>
                                        <th style="width: 12%;">Nº do Conselho</th>
                                        <th>Status</th> 
                                        <th style="width: 10%;">Opções</th>
                                    </tr>
                                </thead>
                                <tbody id="contentDoctor">
                                    @php
                                        $currentPage = $doctors->currentPage();
                                        $limit = App\Helpers\Pagination::getLimit();
                                    @endphp

                                    @foreach ($doctors as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 + $limit * ($currentPage - 1) }}</td>
                                            <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                            <td>{{ $item->doctor?->council?->name }}</td>
                                            <td>{{ $item->doctor?->state->name }}</td>
                                            <td>{{ $item->doctor?->counsil_number }}</td>
                                            <td>
                                                <span class="{{ $item->doctor?->is_deleted?->getColor() }}">
                                                    {{ $item->doctor?->is_deleted?->getName() }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('doctors.show', $item->id) }}" title="Visualizar Perfil"
                                                    class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                >
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('doctors.edit', $item->id) }}" title="Editar Perfil"
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

                            <div class="col-md-12 text-center mt-3" id="paginate">
                                <div class="d-flex justify-content-end">
                                    {{ $doctors->links() }}
                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/pages/doctors/index.js') }}"></script>
@endsection
