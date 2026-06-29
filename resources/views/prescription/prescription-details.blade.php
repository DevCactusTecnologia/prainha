@extends('layouts.master-layouts')
@section('title') {{ __('Criar prescrição') }} @endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
@endsection
@section('body')

    <body data-topbar="dark" data-layout="horizontal">
    @endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title')Criar prescrição @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Prescriçãon @endslot
            @slot('li_3') Criar prescrição @endslot
        @endcomponent
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <a href="{{ url('prescription') }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                        <i
                            class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>{{ __('Voltar à lista de prescrição') }}
                    </button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <blockquote>{{ __('Detalhes da prescrição') }}</blockquote>
                        <form class="outer-repeater" action="{{ route('prescription.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="control-label">{{ __('Paciente ') }}<span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 sel_patient @error('patient_id') is-invalid @enderror"
                                        name="patient_id" id="patient">
                                        <option disabled selected>{{ __('Selecionar Paciente') }}</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}" @if (old('patient_id') == $patient->id) selected @endif>
                                                {{ $patient->first_name }} {{ $patient->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="control-label">{{ __('Data do atendimento ') }}<span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 sel_appointment @error('appointment_id') is-invalid @enderror"
                                        name="appointment_id" id="appointment">
                                        <option disabled selected>{{ __('Selecionar data') }}</option>
                                    </select>
                                    @error('appointment_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="created_by" value="{{ $user->id }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="control-label">{{ __('Sintomas ') }}<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('symptoms') is-invalid @enderror" name="symptoms"
                                        id="symptoms" placeholder="{{ __('Adicione os Sintomas') }}"
                                        rows="3">@if (old('symptoms')){{ old('symptoms') }}@endif</textarea>
                                    @error('symptoms')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="control-label">{{ __('Diagnóstico ') }}<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('diagnosis') is-invalid @enderror" name="diagnosis"
                                        id="diagnosis" placeholder="{{ __('Adicione o Diagnóstico') }}"
                                        rows="3">@if (old('diagnosis')){{ old('diagnosis') }}@endif</textarea>
                                    @error('diagnosis')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <blockquote>{{ __('Medicamentos e Exames') }}</blockquote>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='repeater mb-4'>
                                        <div data-repeater-list="medicines" class="form-group">
                                            <label>{{ __('Medicamentos ') }}<span class="text-danger">*</span></label>
                                            <div data-repeater-item class="mb-3 row">
                                                <div class="col-md-5 col-6">
                                                    <input type="text" name="medicine" class="form-control"
                                                        placeholder="{{ __('Nome do Medicamento') }}" />
                                                </div>
                                                <div class="col-md-5 col-6">
                                                    <textarea type="text" name="notes" class="form-control"
                                                        placeholder="{{ __('Observações...') }}"></textarea>
                                                </div>
                                                <div class="col-md-2 col-4">
                                                    <input data-repeater-delete type="button"
                                                        class="fcbtn btn btn-outline btn-danger btn-1d btn-sm inner"
                                                        value="X" />
                                                </div>
                                            </div>
                                        </div>
                                        <input data-repeater-create type="button" class="btn btn-primary"
                                            value="Adicionar Medicamento" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='repeater mb-4'>
                                        <div data-repeater-list="test_reports" class="form-group">
                                            <label>{{ __('Solicitar Exame ') }}</label>
                                            <div data-repeater-item class="mb-3 row">
                                                <div class="col-md-5 col-6">
                                                    <input type="text" name="test_report" class="form-control"
                                                        placeholder="{{ __('Nome do Exame') }}" />
                                                </div>
                                                <div class="col-md-5 col-6">
                                                    <textarea type="text" name="notes" class="form-control"
                                                        placeholder="{{ __('Observações...') }}"></textarea>
                                                </div>
                                                <div class="col-md-2 col-4">
                                                    <input data-repeater-delete type="button"
                                                        class="fcbtn btn btn-outline btn-danger btn-1d btn-sm inner"
                                                        value="X" />
                                                </div>
                                            </div>
                                        </div>
                                        <input data-repeater-create type="button" class="btn btn-primary"
                                            value="Adicionar Exame" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Criar prescrição') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
    @section('script')
        <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
        <!-- form mask -->
        <script src="{{ URL::asset('assets/libs/jquery-repeater/jquery-repeater.min.js') }}"></script>
        <!-- form init -->
        <script src="{{ URL::asset('assets/js/pages/form-repeater.int.js') }}"></script>
        <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js') }}"></script>
        <script src="{{ URL::asset('assets/js/pages/notification.init.js') }}"></script>
        <script>
            $('.sel_patient').on('change', function(e) {
                e.preventDefault();
                var patientId = $(this).val();
                var token = $("input[name='_token']").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('patient_by_appointment') }}",
                    data: {
                        patient_id: patientId,
                        _token: token,
                    },
                    success: function(res) {
                        $('.sel_appointment').html('');
                        $('.sel_appointment').html(res.options);
                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
            });
        </script>
    @endsection
