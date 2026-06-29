<div class="modal fade" id="add-document" tabindex="-1" role="dialog" aria-hidden="true" aria-hidden="true">
    <div class="modal-dialog modal-lg bg-white" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">                
                <div class="d-flex justify-content-between">
                    <h4 class="text-dark mb-3" style="font-weight: bold">Carregar documentos</h4>
                    <button type="button" data-dismiss="modal" title="Fechar modal"
                        class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center" 
                        style="width: 20px !important; height: 20px !important; padding: 10px"
                    >
                        X
                    </button>
                </div>
                
                {{-- TIPO E DOCUMENTO --}}
                <div data-js="block-documents">
                    <div class="row">
                        <div class="col-5 form-group">
                            <label class="control-label">Tipo<span class="text-danger">*</span></label>
                            <select class="form-control" name="documents_types[]" id="documentType">
                                <option value="">Selecione</option>
                                @foreach ($documents as $document)
                                    <option value="{{ $document->value }}">
                                        {{ $document->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-7 form-group">
                            <label class="control-label">Documento<span class="text-danger">*</span></label>
                            <input type="file" class="form-control pl-0" name="documents[]"
                                accept="image/png,image/jpg,image/jpeg, .pdf"
                            >
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end modal-footer border-0 pr-0 mt-2">
                    <button type="button" onclick="addFile()"
                        class="btn btn-primary font-weight-medium mr-0"
                    >
                        <i class="bx bx-plus font-size-16 align-middle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
