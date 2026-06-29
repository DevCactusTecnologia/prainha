@extends('layouts.master-layouts')
@section('title') {{ __('Impressão geral') }} @endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('title') Impressão geral @endslot
        @slot('li_1') Rotina @endslot
        @slot('li_2') Impressão geral @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <form class="card p-3 mb-3">
                <input type="hidden" data-js="base-url" value="{{ url('/') }}">
                <input type="hidden" name="url_current" url="{{ route('routine.appointment.by.day.search') }}">
                @csrf

                <div class="d-md-flex">
                    <div class="col-md-3 mb-4">
                        <label class="form-label">Data do atendimento</label>
                        <input type="date" id="date" class="form-control">
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label">Unidade de atendimento</label>
                        <select class="form-control" id="unity">
                            <option value="">Selecione</option>
                            @foreach ($unitys as $unity)
                                <option value="{{ $unity->id }}">{{ $unity->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-4">
                        <label class="form-label invisible">.</label>
                        <button id="search" class="btn btn-primary form-control">
                            <i class="fa fa-search"></i>
                            <span class="ml-2">Buscar</span>
                        </button>
                    </div>
                </div>

                <div id="result" class="pl-2"></div>
            </form>

        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/pages/routine/print-by-day.js') }}"></script>
@endsection
