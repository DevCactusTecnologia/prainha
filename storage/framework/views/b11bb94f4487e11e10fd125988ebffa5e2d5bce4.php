<div class="modal fade" id="viewPending" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-primary" id="exampleModalLongTitle">
                Detalhes do atendimento | Protocolo nº <strong id="viewId"></strong>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div id="patientModalContent" style="display: none;">
                <div class="d-md-flex mb-3">
                    <div class="col-md-7 pl-0">
                        <div>
                            <strong>Paciente:</strong> <span id="viewPatient"></span>
                        </div>
                        <div>
                            <strong>Idade:</strong> <span id="viewAge"></span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div>
                            <strong>CPF:</strong> <span id="viewCpf"></span>
                        </div>
                        <div>
                            <strong>CNS:</strong> <span id="viewCns"></span>
                        </div>
                    </div>
                </div>

                <table class="table table-centered table-bordered table-sm table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Nº</th>
                            <th>Exame</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="viewTbody">
                    </tbody>
                </table>
            </div>
            <div id="patientModalLoader" style="display: block;">
                </span><span class="text-primary fw-600"></span>
            </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-primary rounded-pill px-3" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
</div>
<?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/appointments/modal/pending/view.blade.php ENDPATH**/ ?>