<div class="modal fade" id="index-doctor" tabindex="-1" role="dialog" aria-hidden="true" aria-hidden="true">
    <div class="modal-dialog modal-xl bg-white" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                

                <h3 class="text-primary mb-3" style="font-weight: 600;">Mais solicitantes</h3>

                @if ($appointment->exams->isEmpty())
                    <div data-js="alert-more-doctor" class="alert alert-warning" style="display: block;">
                        Atenção! Para incluir mais solicitantes, é necessário primeiro <strong>selecionar os exames</strong>!
                    </div>
                @endif

                {{-- SOLICITANTE --}}
                <div data-js="search-more-doctor" style="display: {{ $appointment->exams->isEmpty() ? 'none' : 'block' }};">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="control-label">Solicitante <span class="text-danger">*</span></label>
                            <select class="form-control select2 sel-doctor" data-js="select-more-doctor"
                                onchange="changeMoreDoctor(this)"
                            >
                                <option selected disabled>Selecionar</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }} {{ $doctor->counsil_sigla }}: {{ $doctor->counsil_number }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- LISTA DE SOLICITANTES --}}
                <div data-js="container-more-doctors" class="mb-3">
                    @foreach ($more_doctors['names'] as $name => $moreDoctor)
                        <div data-js="div-more-doctor" data-doctor-id="{{ $moreDoctor['id'] }}" data-name="{{ $name }}  {{ $moreDoctor['class_council']['sigla'] }}: {{ $moreDoctor['class_council']['number'] }}">
                            <span title="Remover solicitante" doctor-number="{{ $loop->iteration }}" doctor-id="{{ $moreDoctor['id'] }}" onclick="removeDoctor(this)" class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 48 48" fill="#f46a6a" style="cursor: pointer;">
                                    <path d="M13.05 42q-1.2 0-2.1-.9-.9-.9-.9-2.1V10.5H8v-3h9.4V6h13.2v1.5H40v3h-2.05V39q0 1.2-.9 2.1-.9.9-2.1.9Zm5.3-7.3h3V14.75h-3Zm8.3 0h3V14.75h-3Z"></path>
                                </svg>
                            </span>
                            <strong>Solicitante {{ $loop->iteration }}</strong>: {{ $name }}  {{ $moreDoctor['class_council']['sigla'] }}: {{ $moreDoctor['class_council']['number'] }}
                        </div>
                    @endforeach
                </div>

                {{-- LISTA DE DE EXAMES --}}
                <table data-js="table-more-doctor" style="visibility: {{ $appointment->exams->isEmpty() ? 'hidden' : 'visible' }};" 
                    class="table table-bordered table-centered table-sm table-hover"
                >
                    <thead class="bg-light">
                        <tr data-js="thead-tr-more-doctor">
                            <th>Nº</th>
                            <th>Nome do exame</th>
                            @foreach ($more_doctors['names'] as $name => $moreDoctor)
                                <th data-th-doctor-number="{{ $loop->iteration }}" style="text-align: center;">
                                    Solicitante {{ $loop->iteration }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody data-js="tbody-more-doctor">
                        @foreach ($appointment->exams as $exam)
                            <tr data-js="tr-more-doctor-exam-{{ $exam->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $exam->name }}</td>
                                @foreach ($more_doctors['names'] as $name => $moreDoctor)
                                    <td class="text-center">
                                        <input type="checkbox" name="solicitations" 
                                        value="{{ $moreDoctor['id'] }}" data-check-doctor-id="{{ $moreDoctor['id'] }}" 
                                        data-check-exam-id="{{ $exam->id }}" onchange="changeCheckMoreDoctor()"
                                        @checked(in_array($exam->id, $moreDoctor['exam_id']))
                                    >
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>   
        </div>
        
    </div>
</div>
