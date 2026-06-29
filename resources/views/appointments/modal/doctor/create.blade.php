<div class="modal fade" id="create-doctor" tabindex="-1" role="dialog" aria-hidden="true" aria-hidden="true">
    <div class="modal-dialog modal-lg bg-white" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                <form action="{{ route('doctors.appointment.store') }}" method="POST" >
                    @csrf

                    <h3 class="text-primary mb-3" style="font-weight: bold;">Novo solicitante</h3>
                    
                    {{-- NOME COMPLETO, CPF E CNS --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Nome Completo<span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" name="first_name" id="doctorFirstName"
                                placeholder="Digite o nome completo sem abreviações" required
                            >
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">CPF</label>
                            <input type="text" class="form-control" name="cpf" id="doctorCpf">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">CNS</label>
                            <input type="text" class="form-control" name="cns" id="doctorCns">
                        </div>
                    </div>

                    {{-- CONSELHO DE CLASSE, UF DE REGISTRO E NUMERO --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Conselho de Classe<span class="text-danger">*</span></label>
                            <select class="form-control" name="class_council_id" id="doctorClassCouncilId" required>
                                <option value="">Selecione</option>
                                @foreach ($classCouncils as $classCouncil)
                                    <option value="{{ $classCouncil->id }}"
                                        @selected(old('class_council_id') == $classCouncil->id)
                                    >
                                        {{ $classCouncil->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="control-label">Estado Emissor<span class="text-danger">*</span></label>
                                    <select class="form-control" name="issuing_state_id" id="doctorIssuingState" required>
                                        <option value="">Selecione</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}"
                                                @selected(old('issuing_state_id') == $state->id)
                                            >
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="control-label">Nº de Registro<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="counsil_number" 
                                        id="doctorCounsilNumber" value="{{ old('counsil_number') }}" required
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end modal-footer border-0 mt-2">
                        <button type="button" data-dismiss="modal" 
                            class="btn btn-white text-primary" style="font-weight: 600;"
                        >
                            Fechar
                        </button>
                        <button type="button" class="btn btn-primary rounded-pill px-3" 
                            style="font-weight: 600;" data-js="create-doctor"
                        >
                            Salvar
                        </button>
                    </div>
            
                </form>
            </div>   
        </div>
        
    </div>
</div>
