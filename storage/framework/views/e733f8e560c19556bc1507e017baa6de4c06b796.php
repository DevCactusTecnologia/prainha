<div class="modal fade" id="listParameterModal" tabindex="-1" aria-labelledby="newParameterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content form-group">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista de Parâmetros do Exame</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="p-3">
                <div class="row">
                    <div class="col-lg-3">
                        <button type="button" class="btn btn-primary waves-effect waves-light mb-4" data-toggle="modal" 
                            data-target="#parameterModal" data-backdrop="static" data-keyboard="false" onclick="closeModal()"
                        >
                            <i class="bx bx-plus font-size-16 align-middle mr-2"></i> <?php echo e(__('Adicionar novo parâmetro')); ?>

                        </button>
                    </div>
                </div>
                           
                <div class="table-responsive">
                    <table class="table table-sm table-centered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Id</th>
                                <th>Parâmetro (##NOME##)</th>
                                <th>Tipo</th>                                
                                <th>Fórmula</th>
                                <th>Tamanho</th>
                                <th>Casas decimais</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="listTableParameter">
                            <?php $__currentLoopData = $exam->parameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th><?php echo e($item->id); ?></th>
                                    <th><?php echo e($item->parameter); ?></th>
                                    <th><?php echo e(ucfirst($item->type)); ?></th>               
                                    <th><?php echo e(Str::limit($item->formula, 50)); ?></th>
                                    <th class="text-center"><?php echo e($item->size); ?></th>
                                    <th class="text-center"><?php echo e($item->decimal_places); ?></th>
                                    <th>
                                        <span class="alert rounded-pill px-2 py-1 <?php echo e($item->is_active == '1' ? 'alert-success' : 'alert-danger'); ?>">
                                            <?php echo e($item->is_active == '1' ? 'Ativo' : 'Inativo'); ?>

                                        </span>
                                    </th>
                                    <th>
                                        <button type="button" data-id="<?php echo e($item->id); ?>"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0 updateParameter"
                                            data-toggle="modal" data-target="#listParameterModal"
                                            title="Atualizar Parâmetro" data-backdrop="static" data-keyboard="false">
                                            <i class="mdi mdi-lead-pencil"></i>
                                        </button>
                                    </th>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                   
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/exams/modal/parameters/index.blade.php ENDPATH**/ ?>