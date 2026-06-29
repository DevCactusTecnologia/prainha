<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link pr-0" href="{{ url('/') }}" title="Home">
                            <i class="bx bx-home-circle mr-2"></i>
                        </a>
                    </li>
                    @if ($role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('doctors.index') }}">
                                <i class="bx bx-user-circle mr-2"></i> Médicos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex" href="{{ route('patients.index') }}">
                                <i class="bx bxs-user-detail font-size-18 mr-2"></i><span> Pacientes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('receptionists.index') }}">
                                <i class="bx bx-user-circle mr-2"></i> Recepcionistas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('biomedicals.index') }}">
                                <i class="bx bx-user-circle mr-2"></i> Analistas
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-list-plus mr-2'></i>Atendimentos <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                                <a class="dropdown-item" href="{{ route('appointments.index') }}">
                                    Lista de Atendimentos
                                </a>
                                <a class="dropdown-item" href="{{ route('appointments.create') }}">
                                    Novo Atendimento
                                </a>
                                <a class="dropdown-item" href="{{ route('appointments.schedule.index') }}">
                                    Ver Agenda
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-receipt mr-2'></i>Rotina<div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                                <a class="dropdown-item" href="{{ route('routine.map.patient.index') }}">
                                    <i class='bx bx-notepad mr-2'></i>Mapa por paciente
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.map.biomedical.index') }}">
                                    <i class='bx bx-notepad mr-2'></i>Mapa por analista
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.appointment.by.day.index') }}">
                                    <i class='bx bx-printer mr-2'></i>Impressão Geral
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.production.by.biomedical.index') }}">
                                    <i class='mdi mdi-microscope mr-2'></i>Produção do analista
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.production.by.unity.index') }}">
                                    <i class='mdi mdi-microscope mr-2'></i>Produção por unidade
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.traceability.index') }}">
                                    <i class='bx bx-file-find mr-2'></i>Rastreabilidade
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.tag.index') }}">
                                    <i class='bx bx-printer mr-2'></i>Impressão de etiquetas
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.occurrence.index') }}">
                                    <i class='bx bx-error mr-2'></i>Ocorrências
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            >
                                <i class='bx bx-list-plus mr-2'></i>Exames <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                                <a class="dropdown-item" href="{{ route('exams.index') }}">
                                    Lista de Exames
                                </a>
                                <a class="dropdown-item" href="{{ route('exams.create') }}">
                                    Novo Exame
                                </a>
                                <a class="dropdown-item" href="{{ route('categories.index') }}">
                                    Setores
                                </a>
                            </div>
                        </li>
                    @elseif ($role == 'doctor')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointments.create') }}">
                                <i class="bx bx-calendar-plus mr-2"></i>{{ __('Atendimento') }}
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            >
                                <i class="bx bx-user-circle mr-2"></i>
                                {{ __('Pacientes') }} <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                                <a href="{{ route('patients.index') }}" class="dropdown-item">
                                    {{ __('Lista de Pacientes') }}
                                </a>
                                <a href="{{ route('patients.create') }}" class="dropdown-item">
                                    {{ __('Novo Paciente') }}
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-notepad mr-2"></i>{{ __('Prescrição') }}<div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                                <a href="{{ url('prescription') }}" class="dropdown-item">
                                    {{ __('Lista de Prescrição') }}
                                </a>
                                <a href="{{ route('prescription.create') }}" class="dropdown-item">
                                    {{ __('Criar Prescrição') }}
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-receipt mr-2"></i>{{ __('Faturas') }} <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                                <a href="{{ url('invoice') }}" class="dropdown-item">{{ __('Lista de faturas') }}</a>
                                <a href="{{ route('invoice.create') }}" class="dropdown-item">{{ __('Nova fatura') }}</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointments.index') }}">
                                <i class='bx bx-list-plus mr-2'></i>{{ __('Lista de Atendimentos') }}
                            </a>
                        </li>
                    @elseif ($role == 'receptionist')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('doctors.index') }}">
                                <i class="bx bx-user-circle mr-2"></i> Médicos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex" href="{{ route('patients.index') }}">
                                <i class="bx bxs-user-detail font-size-18 mr-2"></i><span> Pacientes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointments.index') }}">
                                <i class="bx bx-list-check mr-2"></i> Lista de Atendimentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointments.create') }}">
                                <i class="bx bx-calendar-plus mr-2"></i> Novo Atendimento
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            >
                                <i class='bx bx-receipt mr-2'></i> Rotina <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                                <a class="dropdown-item" href="{{ route('routine.map.patient.index') }}">
                                    <i class='bx bx-notepad mr-2'></i> Mapa por paciente
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.map.biomedical.index') }}">
                                    <i class='bx bx-notepad mr-2'></i> Mapa por analista
                                </a>
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.appointment.by.day.index') }}">
                                    <i class='bx bx-printer mr-2'></i> Impressão Geral
                                </a>
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.production.by.biomedical.index') }}">
                                    <i class='mdi mdi-microscope mr-2'></i>Produção do analista
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.production.by.unity.index') }}">
                                    <i class='mdi mdi-microscope mr-2'></i>Produção por unidade
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.traceability.index') }}">
                                    <i class='bx bx-file-find mr-2'></i>Rastreabilidade
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.tag.index') }}">
                                    <i class='bx bx-printer mr-2'></i>Impressão de etiquetas
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.occurrence.index') }}">
                                    <i class='bx bx-error mr-2'></i>Ocorrências
                                </a>
                            </div>
                        </li>
                    @elseif ($role == 'biomedical')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('doctors.index') }}">
                                <i class="bx bx-user-circle mr-2"></i> Médicos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex" href="{{ route('patients.index') }}">
                                <i class="bx bxs-user-detail font-size-18 mr-2"></i><span> Pacientes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointments.index') }}">
                                <i class="bx bx-list-check mr-2"></i> Lista de Atendimentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointments.create') }}">
                                <i class="bx bx-calendar-plus mr-2"></i> Novo Atendimento
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            >
                                <i class='bx bx-receipt mr-2'></i> Rotina <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                                <a class="dropdown-item" href="{{ route('routine.map.patient.index') }}">
                                    <i class='bx bx-notepad mr-2'></i> Mapa por paciente
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.map.biomedical.index') }}">
                                    <i class='bx bx-notepad mr-2'></i> Mapa por analista
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.production.by.biomedical.index') }}">
                                    <i class='mdi mdi-microscope mr-2'></i> Produção do analista
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.traceability.index') }}">
                                    <i class='bx bx-file-find mr-2'></i>Rastreabilidade
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.appointment.by.day.index') }}">
                                    <i class='bx bx-printer mr-2'></i>Impressão Geral
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.tag.index') }}">
                                    <i class='bx bx-printer mr-2'></i>Impressão de etiquetas
                                </a>
                                <a class="dropdown-item" href="{{ route('routine.occurrence.index') }}">
                                    <i class='bx bx-error mr-2'></i>Ocorrências
                                </a>
                            </div>
                        </li>
                    @elseif ($role == 'patient')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('appointments/patient-appointment') }}">
                                <i class='bx bx-list-plus mr-2'></i> Lista de Atendimentos
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
