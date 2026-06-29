<div class="modal fade" id="show-document" tabindex="-1" role="dialog" aria-hidden="true" aria-hidden="true">
    <div class="modal-dialog modal-md bg-white" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">                
                <div class="d-flex justify-content-between mb-4">
                    <h4 class="text-dark mb-0" style="font-weight: bold">Visualizar documentos</h4>
                    <button type="button" data-dismiss="modal" title="Fechar modal"
                        class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center" 
                        style="width: 20px !important; height: 20px !important; padding: 10px"
                    >
                        X
                    </button>
                </div>
                
                <table class="table table-sm table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Arquivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointment->documents as $document)
                            <tr>
                                <td>{{ $document->type_id?->getName() }}</td>
                                <td><a href="{{ $document->link }}" target="_blank" title="Ver arquivo em nova aba">{!! $document->icon !!}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
