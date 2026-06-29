<div class="modal fade" id="parameterModal" tabindex="-1" aria-labelledby="parameterModalLabel" aria-hidden="true" 
    data-backdrop="static" data-keyboard="false"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="alert alert-danger showMsg" style="display:none"></div>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Parâmetro</h5>
                <button type="button" onclick="closeModel()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="parameterForm" method="POST">
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="hiddenId" name="id">

                    <div class="d-md-flex">
                        <input type="hidden" name="exam_id" value="<?php echo e($exam->id); ?>"/>
                        <div class="col-md-6 mb-3">
                            <label  class="form-label">Parâmetro (##NOME##)</label>
                            <input name="parameter" id="parameter" type="text"
                                class="form-control text-uppercase" required
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="text">Texto</option>
                                <option value="numeric">Numérico</option>                                
                                <option value="formula">Fórmula</option>
                                <option value="abbreviation">Abreviação</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-md-flex">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Fórmula</label>
                            <input type="text" class="form-control" name="formula" id="formula">
                        </div>
                    </div>

                    <div class="d-md-flex">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantidade de caracteres</label>
                            <input type="number" name="size" id="size" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Casas decimais</label>
                            <input type="number" name="decimal_places" id="decimal_places" class="form-control">
                        </div>
                    </div>

                    <div class="d-md-flex">
                        <div class="col-md-3 mb-3">
                            <label  class=" form-label">Valor mínimo</label>
                            <input type="number" min="0" class="form-control" name="minimum" id="minimum">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label  class=" form-label">Valor máximo</label>
                            <input type="number" min="0" class="form-control" name="maximum" id="maximum">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label  class=" form-label">Com resultado anterior?</label>
                            <select class="form-control" name="with_previous_result" id="with_previous_result">
                                <option value="">Selecione</option>
                                <option value="1">Sim</option>                                
                                <option value="0">Não</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-md-flex">
                        <div class="col-md-3 mb-3">
                            <label  class=" form-label">Impresso no mapa?</label>
                            <select class="form-control" name="with_printed_map" id="with_printed_map">
                                <option value="">Selecione</option>
                                <option value="1">Sim</option>                                
                                <option value="0">Não</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label  class=" form-label">Obrigatório?</label>
                            <select class="form-control" name="required" id="required">
                                <option value="">Selecione</option>
                                <option value="1">Sim</option>                                
                                <option value="0">Não</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label  class=" form-label">Status</label>
                            <select class="form-control" name="is_active" id="is_active">
                                <option value="">Selecione</option>
                                <option value="1">Ativo</option>                                
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="col-md-12">
                            <div class="text-right my-4">
                                <button type="button" onclick="closeModel()" class="btn btn-secondary" data-dismiss="modal">
                                    Fechar
                                </button>
                                <button type="submit" class="btn btn-primary ml-2 saveChange">
                                    Salvar
                                </button>
                                <button type="submit" class="btn btn-primary ml-2 updateChange" style="display: none;">
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
<?php /**PATH /home/u444904474/domains/sislac.com.br/public_html/prainha.sislac.com.br/resources/views/exams/modal/parameters/save.blade.php ENDPATH**/ ?>