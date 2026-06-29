@extends('layouts.master-layouts')
@section('title') Criar Atendimento @endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Criar Atendimento @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') <a href="{{ route('appointments.index') }}">Atendimentos</a> @endslot
        @slot('li_3') Criar @endslot
    @endcomponent

    @if (session()->has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Atendimento <strong>registrado</strong> com sucesso! Deseja imprimir o comprovante?
            <a href="{{ route('appointments.print', session()->get('appointment_id')) }}" target="_blank"
                class="btn btn-pill btn-success ml-2 rounded rounded-pill text-white py-0 px-3"
            >
                <i class="mdi mdi-printer font-size-22 align-middle"></i>
                Imprimir
            </a>
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
                <form action="{{ route('appointments.store') }}" method="POST" class="card-body" enctype="multipart/form-data">
                    @csrf

                    {{-- PACIENTE --}}
                    <div class="row">
                        <div class="col-md-11 form-group">
                            <input type="hidden" id="urlSearchPatient" value="{{ route('appointment.patient.search') }}">
                            <label class="control-label">Paciente <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('appointment_for') is-invalid @enderror"
                                name="appointment_for" id="searchPatient" 
                            >
                            </select>
                            @error('appointment_for')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-1 form-group">
                            <label class="control-label invisible">.</label>
                            <button type="button" class="btn btn-primary form-control"
                                title="Adicionar novo paciente" data-toggle="modal" data-target="#create-patient"
                            >
                                <i class="bx bx-plus font-size-16 align-middle"></i>
                            </button>
                        </div>
                    </div>

                    {{-- SOLICITANTE --}}
                    <div class="row">
                        <div class="col-md-5 form-group">
                            <label class="control-label">Solicitante <span class="text-danger">*</span></label>
                            <select class="form-control select2 sel-doctor @error('appointment_with') is-invalid @enderror"
                                name="appointment_with" id="doctor"
                            >
                                <option selected disabled>Selecionar</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" @selected(old('appointment_with') == $doctor->id)>
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
                        <div class="col-md-1 form-group">
                            <label class="control-label invisible">.</label>
                            <button type="button" class="btn btn-primary form-control"
                                title="Adicionar novo solicitante" data-toggle="modal" data-target="#create-doctor"
                            >
                                <i class="bx bx-plus font-size-16 align-middle"></i>
                            </button>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label">Convênio<span class="text-danger">*</span></label>
                            <select class="form-control" name="company_id" required>
                                <option value="">Selecione</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ $companies->count() === 1 ? 'selected' : '' }}
                                    >
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- DATA DO AGENDAMENTO DO ATENDIMENTO --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                Data do atendimento <span class="text-danger" style="margin-right: 8px;">*</span>
                            </label>
                            <div class="input-group datepickerdiv">
                                <input type="text"
                                    class="form-control appointment-date @error('appointment_date') is-invalid @enderror"
                                    name="appointment_date" id="datepicker" data-provide="datepicker"
                                    data-date-format="dd/mm/yyyy" value="{{ old('appointment_date', date('d/m/Y')) }}"
                                    data-date-autoclose="true" autocomplete="off"
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
                                    <option value="{{ $unity->id }}">{{ $unity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- DADOS DOS EXAMES --}}
                    <div class="row">
                        <div class="col-md-12 table-resposive">
                            <h6 class="text-primary fw-bold">Exames</h6>
                            <table class="table table-bordered table-centered table-sm table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nº</th>
                                        <th>EXAME</th>
                                        <th>DESCRIÇÃO</th>
                                        <th>ANALISTA</th>
                                        <th>DATA DA COLETA</th>
                                    </tr>
                                </thead>
                                <tbody id="examContent">
                                    @if (old('exam_ids'))
                                        @foreach (old('exam_ids') as $index => $exam)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ old('exam_names')[$index] }}</td>
                                                <td class="d-flex align-items-center">
                                                    <div title="Remover exame" onclick="removeExam(this)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 48 48"
                                                            fill="var(--danger)" style="cursor: pointer;" 
                                                        >
                                                            <path d="M13.05 42q-1.2 0-2.1-.9-.9-.9-.9-2.1V10.5H8v-3h9.4V6h13.2v1.5H40v3h-2.05V39q0 1.2-.9 2.1-.9.9-2.1.9Zm5.3-7.3h3V14.75h-3Zm8.3 0h3V14.75h-3Z"/>
                                                        </svg>
                                                    </div>
                                                    <span style="margin-left: 5px;">{{ old('exam_abbreviations')[$index] }}</span>
                                                </td>
                                                <td>
                                                    <select class="form-control form-select" name="exam_biomedicals[]">
                                                        <option value="">Selecione</option>
                                                        @foreach ($biomedicals as $biomedical)
                                                            <option value="{{ $biomedical->id }}" style="font-size: 17px;"
                                                                {{ old('exam_biomedicals')[$index] == $biomedical->id ? 'selected' : '' }}
                                                            >
                                                                {{ $biomedical->first_name }} {{ $biomedical->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control" name="exam_collected_at[]" 
                                                        value="{{ old('exam_collected_at')[$index] }}"
                                                    >
                                                </td>

                                                <input type="hidden" name="exam_ids[]" value="{{ $exam }}">
                                                <input type="hidden" name="exam_abbreviations[]" value="{{ old('exam_abbreviations')[$index] }}">
                                                <input type="hidden" name="exam_names[]" value="{{ old('exam_names')[$index] }}">
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td style="width: 15%">
                                            <input type="hidden" id="urlSearchExamAbbreviation" 
                                                value="{{ route('exams.search.abbreviation') }}"
                                            >
                                            <select class="form-control" id="searchExamAbbreviation" 
                                                onchange="changeExamAbbreviation()"
                                            >
                                            </select>
                                        </td>
                                        <td style="width: 35%">
                                            <input type="hidden" id="urlSearchExamName" value="{{ route('exams.search.name') }}" >
                                            <select class="form-control" id="searchExamName" 
                                                onchange="changeExamName()"
                                            >
                                            </select>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                    </div>

                    {{-- DADOS COMPLEMENTARES E OBSERVACOES --}}
                    <div class="d-md-flex pl-md-0">
                        <div class="col-md-7 pl-md-0">
                            <div class="d-md-flex pl-md-0">
                                <div class="col-md-4 form-group pl-md-0">
                                    <label class="control-label">Prioridade</label>
                                    <select class="form-control" name="priority_id">
                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority->value }}" @selected($loop->first)>{{ $priority->getName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Data de entrega</label>
                                    <input type="date" class="form-control" name="delivery_date" 
                                        value="{{ old('delivery_date', $deliveredAt) }}"
                                    />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Jejum</label>
                                    <select name="fast" class="form-control">
                                        <option value="yes" {{ old('fast', 'yes') == 'yes' ? 'selected' : '' }}>Sim</option>
                                        <option value="no" {{ old('fast') == 'no' ? 'selected' : '' }}>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-md-flex pl-md-0">
                                <div class="col-md-4 form-group pl-md-0" id="dum_group">
                                    <label class="control-label" title="Data da Última Menstruação">DUM</label>
                                    <input type="date" class="form-control" name="dum"  
                                        value="{{ old('dum') }}"
                                    />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Nº da Guia</label>
                                    <input type="text" class="form-control bg-light" value="Automático" 
                                        title="O número da guia será gerado automaticamente conforme a unidade de atendimento"
                                        style="cursor: help;" readonly
                                    >
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Documentos</label>
                                    <div class="form-control bg-light" title="Realizar o carregamento de documentos" style="cursor: pointer;"
                                        data-toggle="modal" data-target="#add-document"
                                    >
                                        <i class="bx bxs-cloud-upload font-size-24"></i>
                                    </div>
                                    @include('appointments.modal.document.create')
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 pr-0">
                            <label class="control-label">Observações, doenças e medicamentos</label>
                            <textarea class="form-control" name="observation" rows="5">{{ old('observation') }}</textarea>
                        </div>
                    </div>

                    {{-- BOTAO DE VOLTAR E SALVAR --}}
                    <div class="d-md-flex justify-content-md-between text-center">
                        <a href="{{ route('appointments.index') }}" class="btn font-weight-medium text-primary rounded-lg">
                            <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                            Voltar
                        </a>
                        <button type="submit" id="createAttendance" 
                            class="btn btn-primary font-weight-medium px-4 rounded-lg"
                        >
                            Salvar Atendimento
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    @include('appointments.modal.patient.create')
    @include('appointments.modal.doctor.create')

@endsection

@section('script')
    {{-- LIBS --}}
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>

    {{-- PAGES --}}
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/appointments/search.js') }}"></script>
    <script src="{{ asset('assets/js/pages/appointments/people.js') }}"></script>
@endsection
