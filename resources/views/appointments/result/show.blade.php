@extends('layouts.master-layouts')
@section('title') Ver resultado @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') RESULTADO DO EXAME @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') Resultado do exame @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <a href="{{ route('appointments.result.create', $appointment->id) }}"
               class="btn btn-primary text-white waves-effect waves-light mb-4"
            >
                <i class="mdi mdi-arrow-left font-size-16 align-middle mr-2"></i> 
                Voltar a lista de exames
            </a>
            {{-- <a href="{{ route('appointments.result.print', $appointment->id) }}" 
                class="btn btn-success text-white ml-2 mb-4 py-0" target="_blank"
            >
                <i class="mdi mdi-printer font-size-24 align-middle"></i>
            </a> --}}
            <a href="{{ route('appointments.result.pdf', $appointment->id) }}" 
                class="btn btn-success text-white ml-2 mb-4 py-0" target="_blank"
            >
                <i class="mdi mdi-printer font-size-24 align-middle"></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="card p-2">

                <div class="d-md-flex mt-3 border-top">

                    <div class="col-md-2">
                        <div class="text-center my-3">
                            <strong>Exames realizados</strong>   
                        </div>
                        <div class="list-group" id="list-tab" role="tablist">
                            @foreach ($exams as $exam)
                                <a class="list-group-item list-group-item-action text-center {{ $loop->first ? 'active' : '' }}" 
                                    id="list-{{ $exam->id }}-list" data-toggle="list" href="#list-{{ $exam->id }}"
                                    role="tab" aria-controls="{{ $exam->id }}"
                                >
                                    {{ $exam->abbreviation }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-10">

                        <header class="d-flex mb-2">
                            <div class="mr-3">
                                <img src="{{ asset('assets/images/brasao.png') }}" width="80px" alt="brasão">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <strong>PREFEITURA DE SÃO BENTO</strong>
                                <strong>SECRETARIA MUNICIPAL DE SAÚDE</strong>
                                <strong>LABORATÓRIO MUNICIPAL DE ANÁLISES CLÍNICAS DR. ALICIO ALEXANDRE DA SILVA</strong>
                                <strong>CNES: 6558321</strong>
                            </div>
                        </header>
                        <hr>

                        <div class="d-md-flex">
                            @php 
                                $patient = App\Models\Patient::firstWhere('user_id', $appointment->patient->id); 

                                $patientAgeYear = $patient->ageYear($appointment->appointment_date);
                                $patientAgeMonth = $patient->ageMonth($appointment->appointment_date);
                                $patientAgeDay = $patient->ageDay($appointment->appointment_date);
                            @endphp

                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <div>
                                        Paciente &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                                        <strong>{{ $appointment->patient->first_name }}</strong>
                                        @if($appointment->patient->patient->patient_social_name) ({{ $appointment->patient->patient->patient_social_name}}) @endif
                                    </div>
                                    <div>
                                        Idade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                                        {{ $patient->ageExtended($appointment->appointment_date) }}
                                    </div>
                                    <div>
                                        Convênio &nbsp;&nbsp;&nbsp;:&nbsp;
                                        {{ $appointment->company?->name }}
                                    </div>
                                    <div>
                                        Solicitante &nbsp;:&nbsp;
                                        {{ $appointment->doctor->first_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <div>
                                        Protocolo &nbsp;&nbsp;:&nbsp;
                                        {{ $appointment->id }}
                                    </div>
                                    <div>
                                        Sexo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                                        {{ $patient->gender_name }}
                                    </div>
                                    <div>
                                        Cadastro &nbsp;&nbsp;:&nbsp;
                                        {{ date('d/m/Y', strtotime($appointment->appointment_date)) }}
                                    </div>
                                    <div>
                                        Conferido &nbsp;:&nbsp;
                                        {{ date('d/m/Y', strtotime($appointment->checked_at)) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content" id="nav-tabContent">
                            @foreach ($exams as $exam)

                                @if ($exam->pivot->status == '1' && $exam->filters->isEmpty())
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                        id="list-{{ $exam->id }}" data-id="{{ $exam->id }}" data-name="{{ $exam->name }}"
                                        role="tabpanel" aria-labelledby="list-{{ $exam->id }}-list"
                                    >
                                        @php
                                            $content = App\Models\Exam\Model::find($exam->pivot->model_id)->exam_editor;
                                            $results = App\Models\Appointment\Result::with(['parameter'])
                                                ->where('appointment_id', $appointment->id)
                                                ->where('exam_id', $exam->id)
                                                ->get();

                                            foreach ($results as $result) {
                                                $input = "{$result->result}";

                                                $content = str_replace($result->parameter->parameter, $input, $content);
                                            }

                                            $biomedical = App\Models\User::find($exam->pivot->biomedical_id);
                                        @endphp

                                        <div style="background-color: #eff2f7;" class="my-3 p-2">
                                            <strong>{{ $exam->name }}</strong>
                                        </div>
                                        <div class="d-flex">
                                            <div class="content-result-exam">
                                                {!! $content !!}
                                            </div>
                                        </div>

                                        {{-- RESULTADOS ANTERIORES --}}
                                        @php
                                            $previousResults = [];

                                            foreach ($exam->parameters as $parameter) {
                                                if ($parameter->with_previous_result == '1') {
                                                    $previousResultsList = DB::select(
                                                        "SELECT 
                                                            appointments.checked_at,
                                                            results.result,
                                                            new_parameter.parameter

                                                        FROM results

                                                        INNER JOIN appointments
                                                        ON results.appointment_id = appointments.id

                                                        INNER JOIN new_parameter
                                                        ON results.parameter_id = new_parameter.id

                                                        INNER JOIN users
                                                        ON appointments.appointment_for = users.id

                                                        WHERE users.id = ? AND new_parameter.id = ?
                                                        ORDER BY appointments.checked_at DESC", [
                                                            $appointment->appointment_for,
                                                            $parameter->id,
                                                    ]);

                                                    $previousResults[] = json_decode(json_encode($previousResultsList), true);
                                                }
                                            }

                                            $previousResultsCollection = collect($previousResults)
                                                ->collapse()
                                                ->groupBy('checked_at')
                                                ->forget($appointment->checked_at);
                                        @endphp

                                        @if ($previousResultsCollection->isNotEmpty())
                                            <div class="mb-3">
                                                <div class="rounded p-2 mb-3" style="background-color: #e7e7e7; font-weight: bold; font-size: 15px;">
                                                    Resultados anteriores:
                                                </div>
                                                <div class="d-flex flex-wrap">
                                                    @foreach ($previousResultsCollection as $checkedAt => $items)
                                                        <div class="d-flex flex-column flex-wrap border-primary p-2 mb-3 mr-3" 
                                                            style="border: 2px solid #000"
                                                        >
                                                            <div class="border-bottom border-primary mb-2">
                                                                <span style="font-weight: 600;">Data:</span> 
                                                                <span>{{ date('d/m/Y', strtotime($checkedAt)) }}</span>
                                                            </div>
                                                            @foreach ($items as $item)
                                                                <div>{{ explode('##', $item['parameter'])[1] }} => {{ $item['result'] }}</div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex align-items-center justify-content-end my-2">
                                            <div>Conferido e liberado por: {{ $biomedical?->first_name }}</div>
                                        </div>
                                    </div>

                                @else
                                    
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                        id="list-{{ $exam->id }}" data-id="{{ $exam->id }}" data-name="{{ $exam->name }}"
                                        role="tabpanel" aria-labelledby="list-{{ $exam->id }}-list"
                                    >
                                        @php
                                            $content = App\Models\Exam\Model::find($exam->pivot->model_id)->exam_editor;

                                            foreach ($exam->filters as $filter) {

                                                // SEXO FEMININO (RECÉM-NASCIDO)
                                                if (
                                                    ($patientAgeYear <= 0) && ($patientAgeMonth <= 0) &&
                                                    ($patientAgeDay >= $filter->intial_age_day && $patientAgeDay <= $filter->final_age_day) &&
                                                    (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
                                                ) {
                                                    $content = $filter->exam_editor;
                                                    break;

                                                // SEXO MASCULINO (RECÉM-NASCIDO)
                                                } elseif (
                                                    (($patientAgeYear <= 0) && ($patientAgeMonth <= 0)) &&
                                                    (($patientAgeDay >= $filter->intial_age_day) && ($patientAgeDay <= $filter->final_age_day)) &&
                                                    (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
                                                ) {
                                                    $content = $filter->exam_editor;
                                                    break;
    
                                                // CRIANÇAS, JOVENS E ADULTOS - SEXO FEMININO
                                                } elseif (
                                                    ($patientAgeYear >= $filter->intial_age_year && $patientAgeYear <= $filter->final_age_year) &&
                                                    ( ((($patientAgeYear * 12) + $patientAgeMonth) >= (($filter->intial_age_year * 12) + $filter->intial_age_month)) && ((($patientAgeYear * 12) + $patientAgeMonth) <= (($filter->final_age_year * 12) + $filter->final_age_month)) ) &&
                                                    (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
                                                ) {
                                                    $content = $filter->exam_editor;
                                                    break;

                                                // CRIANÇAS, JOVENS E ADULTOS - SEXO MASCULINO
                                                } elseif (
                                                    ($patientAgeYear >= $filter->intial_age_year && $patientAgeYear <= $filter->final_age_year) &&
                                                    ( ((($patientAgeYear * 12) + $patientAgeMonth) >= (($filter->intial_age_year * 12) + $filter->intial_age_month)) && ((($patientAgeYear * 12) + $patientAgeMonth) <= (($filter->final_age_year * 12) + $filter->final_age_month)) ) &&
                                                    (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
                                                ) {
                                                    $content = $filter->exam_editor;
                                                    break;
                                                }

                                            }

                                            $results = App\Models\Appointment\Result::with(['parameter'])
                                                ->where('appointment_id', $appointment->id)
                                                ->where('exam_id', $exam->id)
                                                ->get();

                                            foreach ($results as $result) {
                                                $input = "{$result->result}";

                                                $content = str_replace($result->parameter->parameter, $input, $content);
                                            }

                                            $biomedical = App\Models\User::find($exam->pivot->biomedical_id);
                                        @endphp

                                        <div style="background-color: #eff2f7;" class="my-3 p-2">
                                            <strong>{{ $exam->name }}</strong>
                                        </div>
                                        <div class="d-flex">
                                            <div>{!! $content !!}</div>
                                        </div>

                                        {{-- RESULTADOS ANTERIORES --}}
                                        @php
                                            $previousResults = [];

                                            foreach ($exam->parameters as $parameter) {
                                                if ($parameter->with_previous_result == '1') {
                                                    $previousResultsList = DB::select(
                                                        "SELECT 
                                                            appointments.checked_at,
                                                            results.result,
                                                            new_parameter.parameter

                                                        FROM results

                                                        INNER JOIN appointments
                                                        ON results.appointment_id = appointments.id

                                                        INNER JOIN new_parameter
                                                        ON results.parameter_id = new_parameter.id

                                                        INNER JOIN users
                                                        ON appointments.appointment_for = users.id

                                                        WHERE users.id = ? AND new_parameter.id = ?
                                                        ORDER BY appointments.checked_at DESC", [
                                                            $appointment->appointment_for,
                                                            $parameter->id,
                                                    ]);

                                                    $previousResults[] = json_decode(json_encode($previousResultsList), true);
                                                }
                                            }

                                            $previousResultsCollection = collect($previousResults)
                                                ->collapse()
                                                ->groupBy('checked_at')
                                                ->forget($appointment->checked_at);
                                        @endphp

                                        @if ($previousResultsCollection->isNotEmpty())
                                            <div class="mb-3">
                                                <div class="rounded p-2 mb-3" style="background-color: #e7e7e7; font-weight: bold; font-size: 15px;">
                                                    Resultados anteriores:
                                                </div>
                                                <div class="d-flex flex-wrap">
                                                    @foreach ($previousResultsCollection as $checkedAt => $items)
                                                        <div class="d-flex flex-column flex-wrap border-primary p-2 mb-3 mr-3" 
                                                            style="border: 2px solid #000"
                                                        >
                                                            <div class="border-bottom border-primary mb-2">
                                                                <span style="font-weight: 600;">Data:</span> 
                                                                <span>{{ date('d/m/Y', strtotime($checkedAt)) }}</span>
                                                            </div>
                                                            @foreach ($items as $item)
                                                                <div>{{ explode('##', $item['parameter'])[1] }} => {{ $item['result'] }}</div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex align-items-center justify-content-end my-2">
                                            <div>Conferido e liberado por: {{ $biomedical?->first_name }}</div>
                                        </div>
                                    </div>
                                    
                                @endif

                            @endforeach
                            
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
