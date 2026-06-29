@extends('layouts.master-layouts')
@section('title') {{ __('Novo Setor') }} @endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    <div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        Criar Setor
                    </h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a href="{{ route('categories.index') }}" class="btn btn-primary waves-effect waves-light mb-4">
                    <i class=" font-size-16 align-middle mr-2"></i> {{ __('Voltar a Lista de Setores') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body row">
                <div class="col-lg-12">

                    <form action="{{ route('categories.store') }}" name="doctorform" method="POST">
                        @csrf

                        <div class="d-md-flex col-md-12">
                            <h4>Adicionar novo Setor</h4>
                        </div>

                        <div class="d-md-flex">
                            <div class="col-md-5 mb-2">
                                <label class="form-label">Nome<span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" name="name" 
                                   value="{{ old('name') }}" max="191" required
                                >
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Abreviação<span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" name="abbreviation" 
                                    value="{{ old('abbreviation') }}" required
                                >
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label invisible">.</label>
                                <button type="submit" class="btn btn-primary form-control">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            
    </div>
@endsection
