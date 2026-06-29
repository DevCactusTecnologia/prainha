@extends('layouts.master-layouts')
@section('title') {{ __('Editar setor') }} @endsection

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
                        {{ __('Editar Setor') }}
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">
                                {{ __('Editar Setor') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>  
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('categories.index') }}" class="btn btn-primary waves-effect waves-light mb-4">
                    <i class=" font-size-16 align-middle mr-2"></i> {{ __('Voltar a Lista de Setores') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                            
                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="d-md-flex col-md-12">
                                <h4>Editar Setor</h4>
                            </div>
    
                            <div class="d-md-flex">
                                <div class="col-md-5 mb-2">
                                    <label class="form-label">Nome<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control text-uppercase" name="name" 
                                        value="{{ old('name', $category->name) }}" max="191" required
                                    >
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Abreviação<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control text-uppercase" name="abbreviation" 
                                        value="{{ old('abbreviation', $category->abbreviation) }}" required
                                    >
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Status<span class="text-danger">*</span></label>
                                    <select class="form-control" name="is_active" required>
                                        @foreach ($actives as $status)
                                            <option value="{{ $status->value }}"
                                                @selected(old('is_active', $category->is_active->value) == $status->value)
                                            >
                                                {{ $status->getName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label invisible">.</label>
                                    <button type="submit" class="btn btn-primary form-control">Editar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
