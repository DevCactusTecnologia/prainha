@extends('layouts.master-layouts')
@section('title') {{ __('Lista de Setores') }} @endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    <div>
        
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                      {{ __('Lista de Setores') }}
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">
                                {{ __('Lista de Setores') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <a href="{{ route('categories.create') }}">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i class="bx bx-plus font-size-16 align-middle mr-2"></i> {{ __('Novo Setor') }}
                            </button>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="detail_box">
                            <table class="table table-sm table-centered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="20%">Nome</th>
                                        <th width="20%">Abreviação</th>
                                        <th width="10%">Status</th>
                                        <th width="50%">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->abbreviation }}</td>
                                            <td>
                                                <span class="{{ $category->is_active->getColor() }}">
                                                    {{ $category->is_active->getName() }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('categories.edit', $category->id) }}" title="Atualizar setor"
                                                    class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0"
                                                >
                                                    <i class="mdi mdi-lead-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 text-center mt-3">
                        <div class="d-flex justify-content-start">
                            {{ $category->count() }} registros encontrados
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
