@extends('layouts.master-layouts')
@section('title') Editar Atendimento @endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Editar Atendimento @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') Editar Atendimento @endslot
    @endcomponent

    @if (session()->has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Atendimento <strong>alterado</strong> com sucesso!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{ session()->forget('status') }}
    @endif

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
        <div class="col-xl-12">
            <div class="card">
                <input type="hidden" data-js="baseUrl" value="{{ url('/') }}">
                
                <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" class="card-body">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="access_key" value="{{ $appointment->access_key }}">
                    
                    {{-- PACIENTE --}}
                    @if ($role != 'patient')
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input type="hidden" id="urlSearchPatient" value="{{ route('appointment.patient.search') }}">
                                <label class="control-label">Paciente <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('appointment_for') is-invalid @enderror"
                                    name="appointment_for" id="searchPatient"
                                >
                                    <option value="{{ $appointment->patient->id }}" selected>
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->patient->patient_social_name }}
                                    </option>
                                </select>
                                @error('appointment_for')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="appointment_for" value="{{ $user->id }}">
                    @endif

                    {{-- SOLICITANTE --}}
                    @if ($role != 'doctor')
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label">
                                    Solicitante <span class="text-danger">*</span>
                                </label>
                                <select class="form-control select2 sel-doctor @error('appointment_with') is-invalid @enderror"
                                    name="appointment_with" id="doctor"
                                >
                                    <option selected disabled>Selecionar</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}"
                                            {{ old('appointment_with', $appointment->doctor->id) == $doctor->id ? 'selected' : '' }}
                                        >
                                            {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('appointment_with')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="control-label">Convênio<span class="text-danger">*</span></label>
                                <select class="form-control" name="company_id" required>
                                    <option value="">Selecione</option>
                                    @if ($companies->count() === 1)
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" @selected($companies->count() === 1)>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                @selected(old('company_id', $appointment->company_id) == $company->id)
                                            >
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="appointment_with" value="{{ $user->id }}" id="doctor">
                    @endif

                    {{-- DATA DO AGENDAMENTO DO ATENDIMENTO --}}
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label class="control-label">Nº do protocolo</label>
                            <input type="text" class="form-control bg-light" value="{{ $appointment->id }}" readonly>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="control-label">
                                Data do atendimento <span class="text-danger" style="margin-right: 8px;">*</span>
                            </label>
                            <div class="input-group datepickerdiv">
                                <input type="text"
                                    class="form-control appointment-date @error('appointment_date') is-invalid @enderror"
                                    name="appointment_date" id="datepicker" data-provide="datepicker"
                                    value="{{ old('appointment_date', date('d/m/Y', strtotime($appointment->appointment_date))) }}"
                                    data-date-autoclose="true" autocomplete="off" data-date-format="dd/mm/yyyy"
                                >
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                Unidade de atendimento<span class="text-danger" style="margin-right: 8px;">*</span>
                            </label>
                            <select class="form-control" name="unity_id" required>
                                <option value="">Selecione</option>
                                @foreach ($unitys as $unity)
                                    <option value="{{ $unity->id }}"
                                        @selected(old('unity_id', $appointment->unity_id) == $unity->id)
                                    >
                                        {{ $unity->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- DADOS DOS EXAMES --}}
                    <div class="row">
                        <div class="col-md-12 table-resposive">
                            <h5 class="text-primary fw-bold">Exames</h5>
                            <table class="table table-bordered table-centered table-sm table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nº</th>
                                        <th>CÓD</th>
                                        <th>DESCRIÇÃO</th>
                                        <th>ANALISTA</th>
                                        <th style="width: 8%">COLETADO EM</th>
                                        <th class="text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody id="examContent">
                                    @foreach ($appointment->exams as $exam)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $exam->abbreviation }}</td>
                                            <td><span style="margin-left: 5px;">{{ $exam->name }}</span></td>
                                            <td>
                                                <select class="form-control form-select" name="exam_biomedicals[]">
                                                    <option value="">Selecione</option>
                                                    @foreach ($biomedicals as $biomedical)
                                                        <option value="{{ $biomedical->id }}" style="font-size: 17px;"
                                                            {{ $exam->pivot->biomedical_id == $biomedical->id ? 'selected' : '' }}
                                                        >
                                                            {{ $biomedical->first_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" name="exam_collected_at[]" 
                                                    value="{{ $exam->pivot->collected_at }}" required
                                                >
                                            </td>
                                            <td class="text-center">
                                                @if ($exam->pivot->status == '0')
                                                    <span style="color: #efc681;" title="Pendente">
                                                        <i class="mdi mdi-information-outline font-size-22 align-middle"></i>
                                                    </span>
                                                @endif

                                                @if ($exam->pivot->status == '1')
                                                    <span style="color: #33c38e;" title="Finalizado">
                                                        <i class="mdi mdi-checkbox-marked-circle font-size-22 align-middle"></i>
                                                    </span>
                                                @endif

                                                @if ($exam->pivot->status == '2' && $exam->pivot->re_test == '1')
                                                    <div style="display: flex; justify-content: center;">
                                                        <div title="Reteste: {{ $exam->pivot->observation ?: 'NÃO INFORMADO' }}"
                                                            class="d-flex justify-content-center align-items-center rounded-circle"
                                                            style="background-color: #ff7e24; color: #fff; width: 19px; height: 19px; cursor: default;"
                                                        >
                                                            R
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if ($exam->pivot->status == '2' && $exam->pivot->re_test == '0')
                                                    <span title="Cancelado: {{ $exam->pivot->observation ?: 'NÃO INFORMADO' }}"
                                                        style="color: #ff0000;"
                                                    >
                                                        <i class="mdi mdi-cancel font-size-22 align-middle"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            
                                            <input type="hidden" name="exam_ids[]" value="{{ $exam->pivot->exam_id }}">
                                            <input type="hidden" name="exam_abbreviations[]" value="{{ $exam->abbreviation }}">
                                            <input type="hidden" name="exam_names[]" value="{{ $exam->name }}">
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td style="width: 9%">
                                            <input type="hidden" id="urlSearchExamAbbreviation" 
                                                value="{{ route('exams.search.abbreviation') }}"
                                            >
                                            <select class="form-control" id="searchExamAbbreviation" 
                                                onchange="changeExamAbbreviationEdit()"
                                            >
                                            </select>
                                        </td>
                                        <td style="width: 40%">
                                            <input type="hidden" id="urlSearchExamName" value="{{ route('exams.search.name') }}" >
                                            <select class="form-control" id="searchExamName" 
                                                onchange="changeExamNameEdit()"
                                            >
                                            </select>
                                        </td>
                                        <td></td>
                                        <td style="width: 9%"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                    </div>

                    {{-- DADOS COMPLEMENTARES E OBSERVACOES --}}
                    <div class="d-md-flex">
                        <div class="col-md-7 pl-md-0">
                            <div class="d-md-flex">
                                <div class="col-md-4 pl-md-0 form-group">
                                    <label class="control-label">Prioridade</label>
                                    <select class="form-control" name="priority_id">
                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority->value }}" 
                                                @selected(old('priority_id', $appointment->priority_id?->value) == $priority->value)
                                            >
                                                {{ $priority->getName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Data de entrega</label>
                                    <input type="date" class="form-control" name="delivery_date" 
                                        value="{{ old('delivery_date', $appointment->delivery_date) }}"
                                    />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Jejum</label>
                                    <select name="fast" class="form-control">
                                        <option value="yes" @selected(old('fast', $appointment->fast) == 'yes')>Sim</option>
                                        <option value="no" @selected(old('fast', $appointment->fast) == 'no')>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-md-flex">
                                <div class="col-md-4 pl-md-0 form-group" id="dum_group">
                                    <label class="control-label" title="Data da Última Menstruação">DUM</label>
                                    <input type="date" class="form-control" name="dum" 
                                        value="{{ old('dum', $appointment->dum) }}"
                                    />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Nº da Guia</label>
                                    <input type="number" class="form-control" name="guide_number"
                                        value="{{ $appointment->guide_number }}" required
                                    >
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Documentos</label>
                                    @if ($appointment->documents->isNotEmpty())
                                        <div class="form-control bg-light d-flex align-items-center" title="Visualizar documentos carregados" 
                                            style="cursor: pointer;" data-toggle="modal" data-target="#show-document"
                                        >
                                            <i class="bx bxs-file font-size-24 mr-2"></i> {{ $appointment->documents->count() }}
                                        </div>
                                        @include('appointments.modal.document.show')
                                    @else 
                                        <input type="text" class="form-control bg-light"
                                            value="Não carregados" readonly
                                        > 
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 pr-md-0">
                            <div class="form-group">
                                <label class="control-label">Observações, doenças e medicamentos</label>
                                <textarea class="form-control" name="observation" rows="5">{{ old('observation', $appointment->observation) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- BOTAO DE VOLTAR E EDITAR --}}
                    <div class="d-md-flex justify-content-md-between text-center">
                        <a href="{{ route('appointments.index') }}" class="btn font-weight-medium text-primary rounded-lg">
                            <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                            Voltar
                        </a>
                        <button type="submit" id="createAttendance" 
                            class="btn btn-primary font-weight-medium waves-effect px-4 rounded-lg"
                        >
                            Editar Atendimento
                        </button>
                    </div>
                    
                </form>
                
            </div>
        </div>
    </div>

@endsection

@section('script')
    {{-- LIBS --}}
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    {{-- PAGES --}}
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/appointments/search.js') }}"></script>
@endsection
