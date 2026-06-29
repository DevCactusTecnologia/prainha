@extends('layouts.master-layouts')
@section('title') {{ __('Lista de Exames') }} @endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">
                    Lista de Exames
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">Lista de Exames</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3">
                    <a href="{{ route('exams.create') }}" class="btn btn-primary waves-effect waves-light mb-4">
                        <i class="bx bx-plus font-size-16 align-middle mr-2"></i> {{ __('Novo Exame') }}
                    </a>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-5 text-right">
                    @csrf
                    <input type="text" id="searchExam" class="form-control"
                        placeholder="Pesquisar pelo nome do exame ou abreviação"  
                    />
                    <input type="hidden" id="urlSearch" value="{{ route('exams.search.all') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="detail_box" style="overflow-x: hidden; overflow-y: scroll; height: 300px;">
                        <table class="w-100 table table-sm table-hover table-centered sislac_table table_form">
                            <thead class="bg-light">
                                <tr>                            
                                    <th style="width: 30%;">Nome do Exame</th>
                                    <th style="width: 10%;">Abreviação</th>
                                    <th style="width: 10%;">Categoria</th>
                                    <th style="width: 5%;">Prazo</th>
                                    <th style="width: 7%;">Destino</th>
                                    <th style="width: 9%;">G. Rótulos</th>                            
                                    <th style="width: 10%;">Qtd. Etiquetas</th>
                                    <th style="width: 5%;">Kit</th>                            
                                    <th style="width: 9%;">Status</th>                            
                                    <th style="width: 5%;">Ação</th>              
                                </tr>
                            </thead>
                            <tbody id="contentExam">
                                @foreach ($exams as $exam)
                                    <tr>
                                        <td>{{ $exam->name }}</td>
                                        <td>{{ $exam->abbreviation }}</td>
                                        <td>{{ $exam->category }}</td>
                                        <td>{{ $exam->deadline }}</td>
                                        <td>{{ $exam->destiny }}</td>
                                        <td>{{ $exam->label_group }}</td>
                                        <td>{{ $exam->quantity_label }}</td>
                                        <td>{{ $exam->exam_kit }}</td>
                                        <td>
                                            <span class="{{ $exam->is_active->getColor() }}">
                                                {{ $exam->is_active->getName() }}
                                            </span>
                                        <td>
                                            <a href="{{ route('exams.edit', $exam->id) }}" title="Atualizar Exame"
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
                                    <td colspan="10" >
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

                        <div class="row" id="paginate">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    {{ $exams->links() }}
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
    <script src="{{ asset('assets/js/pages/exams/search.js') }}"></script>
@endsection
