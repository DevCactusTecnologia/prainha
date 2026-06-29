<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="alert alert-danger showMsgFilter" style="display:none"></div>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filtro</h5>
                <button type="button" onclick="closeModel()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="filterForm" method="POST">
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="hiddenFilterId" name="id">
                    <input type="hidden" id="filterExamId" name="exam_id" value="<?php echo e($exam->id); ?>"/>

                    <table class="table table-sm table-centered">
                        <thead class="thead-light">
                            <tr>
                                <th>Sexo</th>
                                <th>Ano inicial</th>
                                <th>Mês inicial</th>
                                <th>Dia inicial</th>
                                <th>Ano final</th>
                                <th>Mês final</th>
                                <th>Dia final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="gender" id="gender" class="form-control" required>
                                        <option value="">Selecione</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Feminino</option>                                
                                        <option value="A">Ambos</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="intial_age_year" 
                                       min="0" max="199" id="intialAgeYear" required
                                    >
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="intial_age_month" 
                                        min="0" max="12" id="intialAgeMonth" required
                                    >
                                </td>                                    
                                <td>
                                    <input type="number" class="form-control" name="intial_age_day" 
                                        min="0" max="31" id="intialAgeDay" required
                                    >
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="final_age_year" 
                                        min="0" max="199" id="finalAgeYear" required
                                    >
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="final_age_month" 
                                        min="0" max="12" id="finalAgeMonth" required
                                    >
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="final_age_day" 
                                        min="0" max="31" id="finalAgeDay" required
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-md-flex">
                        <div class="col-md-12">
                            <textarea id="contentFilter" name="exam_editor"></textarea>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="col-md-12">
                            <div class="text-right my-4">
                                <button type="button" onclick="closeModel()" class="btn btn-secondary" data-dismiss="modal">
                                    Fechar
                                </button>
                                <button type="submit" class="btn btn-primary ml-2 saveChangeFilter">
                                    Salvar
                                </button>
                                <button type="submit" class="btn btn-primary ml-2 updateChangeFilter" style="display: none;">
                                    Atualizar
                                </button>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/exams/modal/filters/save.blade.php ENDPATH**/ ?>