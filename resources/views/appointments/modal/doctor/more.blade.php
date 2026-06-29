<div class="modal fade" id="index-doctor" tabindex="-1" role="dialog" aria-hidden="true" aria-hidden="true">
    <div class="modal-dialog modal-xl bg-white" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                <form action="{{ route('doctors.appointment.store') }}" method="POST" >
                    @csrf

                    <h3 class="text-primary mb-3" style="font-weight: 600;">Mais solicitantes</h3>
                    
                    <div data-js="alert-more-doctor" class="alert alert-warning" style="display: block;">
                        Atenção! Para incluir mais solicitantes, é necessário primeiro <strong>selecionar os exames</strong>!
                    </div>

                    {{-- SOLICITANTE --}}
                    <div data-js="search-more-doctor" style="display: none;">
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
                    <div data-js="container-more-doctors" class="mb-3"></div>

                    {{-- LISTA DE DE EXAMES --}}
                    <table data-js="table-more-doctor" style="visibility: hidden;" 
                        class="table table-bordered table-centered table-sm table-hover"
                    >
                        <thead class="bg-light">
                            <tr data-js="thead-tr-more-doctor">
                                <th>Nº</th>
                                <th>Nome do exame</th>
                            </tr>
                        </thead>
                        <tbody data-js="tbody-more-doctor"></tbody>
                    </table>
            
                </form>
            </div>   
        </div>
        
    </div>
</div>
