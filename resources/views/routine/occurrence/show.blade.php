@extends('layouts.master-layouts')
@section('title') Lista de ocorrências por atendimento @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Lista de ocorrências por atendimento @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') <a href="{{ route('routine.occurrence.index') }}">Ocorrências</a> @endslot
        @slot('li_3') Atendimento @endslot
    @endcomponent

    <div class="row bg-white" style="border-radius: 10px; border: 2px #e9ecef solid;">
        <div class="bg-light w-100 my-3 p-3">
            <div class="d-md-flex">
                <div class="col-md-2">
                    <div class="font-weight-bold">Nº do protocolo</div>
                    <div class="font-size-16 font-weight-bold mt-2 ml-2">
                        {{ $occurrence->appointment_id }}
                    </div>
                </div>
                <div class="col-md-5 pl-0">
                    <div class="invisible">.</div>
                    <div>Paciente &nbsp;  :&nbsp;<strong>{{ $occurrence->appointment->patient?->first_name }}</strong></div>
                    <div>Idade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;{{ $occurrence->appointment->patient?->patient?->ageExtended() }}</div>
                </div>
                <div class="col-md-2 pl-0">
                    <div class="invisible">.</div>
                    <div>Sexo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;{{ $occurrence->appointment->patient?->patient?->gender_name }}</div>
                    <div>Cadastro &nbsp;:&nbsp;{{ $occurrence->appointment->registered_at_formatted }}</div>
                </div>
            </div>
        </div>

        <div class="w-100">
            <table class="table table-sm table-hover table-centered table-borderless dt-responsive nowrap">
                <thead style="background-color: #f8f9fa">
                    <th class="pl-4">Nº</th>
                    <th>EXAME</th>
                    <th>DATA DA OCORRÊNCIA</th>
                    <th>TIPO</th>
                    <th>MOTIVO DA OCORRÊNCIA</th>
                    <th>IDENTIFICADO POR</th>
                    <th class="pr-4">CRIADO POR</th>
                </thead>
                <tbody>
                    @foreach ($exams as $exam)
                        <tr>
                            <td class="pl-4">{{ $loop->iteration }}</td>
                            <td class="text-primary font-weight-bold">{{ $exam->name }}</td>
                            <td>
                                {{ $exam->pivot->updated_at ? date('d/m/Y H:i:s', strtotime($exam->pivot->updated_at)) : '-' }}
                            </td>
                            <td class="font-weight-bold text-{{ $exam->pivot->re_test == '1' ? 'warning' : 'danger' }}">
                                {{ $exam->pivot->re_test == '1' ? 'RETESTE' : 'CANCELADO' }}
                            </td>
                            <td>{{ $exam->pivot->observation }}</td>
                            <td class="text-uppercase">
                                {{ App\Models\User::find($exam->pivot->biomedical_id)?->first_name ?: 'Não informado' }}
                            </td>
                            <td class="pr-4 text-uppercase">
                                {{ App\Models\User::find($exam->pivot->user_id)?->first_name }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="w-100 mb-3" style="border-top: 2px solid #e6e9ed;"></div>
        <div class="text-white font-weight-bold w-100 px-2 py-1 mb-2" style="background-color: #aab5c8">RESOLUÇÃO</div>

        <input type="hidden" data-js="user_name" value="{{ explode(' ', $user->first_name)[0] }}">
        <input type="hidden" data-js="user_id" value="{{ $user->id }}">

        <form action="{{ route('routine.occurrence.update', $occurrence->id) }}" method="POST" class="w-100">
            @csrf
            @method('PUT')

            <div data-js="container-procediment">
                @foreach ($occurrence->procediments as $procediment)
                    <div class="d-md-flex">
                        <div class="col-md-2 mb-3">
                            <label>Data do Registro <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="registered_ats[]" 
                                value="{{ $procediment->registered_at?->format('Y-m-d') }}" required
                            >
                        </div>
                        <div class="col-md-7 mb-3">
                            <label>Relato/Procedimento</label>
                            <input type="text" class="form-control" name="procediments[]" placeholder="Digite o texto aqui" 
                               value="{{ $procediment->procediment }}" required
                            >
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Usuário</label>
                            <input type="text" class="border-0 form-control pl-0" 
                                value="{{ explode(' ', $procediment->user->first_name)[0] }}" readonly
                            >
                            <input type="hidden" name="user_ids[]" value="{{ $procediment->user->id }}" readonly>
                        </div>
                        <div class="col-md-1 mb-3">
                            <div class="invisible">Ação</div>
                            <button type="button" class="btn btn-danger rounded-circle btn-sm" 
                                onclick="this.parentElement.parentElement.remove()"
                                title="Excluir relato/procedimento"
                            >
                                <i class="bx bx-trash align-middle"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="w-100 d-flex align-items-center px-2 mb-3">
                <button type="button" class="btn btn-primary rounded-circle btn-sm" onclick="addProcediment()"
                    style="font-size: 8px;"
                >
                    <i class="bx bx-plus align-middle"></i>
                </button>
                <span class="text-primary ml-1">Adicionar <strong>Relato/Procedimento</strong></span>
            </div>

            <div class="w-100 px-2 mb-3">
                <select class="form-control" name="solution_id" required>
                    <option value="">Selecione uma solução</option>
                    @foreach ($resolutions as $resolution)
                        <option value="{{ $resolution->value }}" 
                            @selected($occurrence->solution_id?->value == $resolution->value)
                        >
                            {{ $resolution->getName() }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <div class="d-flex justify-content-end mb-5 px-2 w-100">
                <button class="btn btn-primary font-weight-bold px-5 rounded-lg text-right" onclick="loader(this)">
                    Salvar
                </button>
            </div>
        </form>
        
    </div>
@endsection

@section('script')
    <script>
        const containerProcediment = document.querySelector('[data-js="container-procediment"]');
        const userName = document.querySelector('[data-js="user_name"]');
        const userId = document.querySelector('[data-js="user_id"]');

        function addProcediment() {
            const procediment = document.createElement('div');
            procediment.classList.add('d-md-flex');

            procediment.innerHTML = (
                `<div class="col-md-2 mb-3">
                    <label>Data do Registro <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="registered_ats[]" required>
                </div>
                <div class="col-md-7 mb-3">
                    <label>Relato/Procedimento</label>
                    <input type="text" class="form-control" name="procediments[]" placeholder="Digite o texto aqui" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label>Usuário</label>
                    <input type="text" class="border-0 form-control pl-0" value="${userName.value}" readonly>
                    <input type="hidden" name="user_ids[]" value="${userId.value}" readonly>
                </div>
                <div class="col-md-1 mb-3">
                    <div class="invisible">Ação</div>
                    <button type="button" class="btn btn-danger rounded-circle btn-sm" 
                        onclick="this.parentElement.parentElement.remove()"
                        title="Excluir relato/procedimento"
                    >
                        <i class="bx bx-trash align-middle"></i>
                    </button>
                </div>`
            );

            containerProcediment.appendChild(procediment);
        }

        function loader(button) {
            setTimeout(() => {
                button.innerHTML = (
                    `<span class="spinner-border spinner-border-sm mr-2" 
                        role="status" aria-hidden="true">
                    </span>Aguarde...`
                );
                button.disabled = true;
            }, 20);

            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = 'Salvar';
            }, 7000);
        }
    </script>
@endsection
