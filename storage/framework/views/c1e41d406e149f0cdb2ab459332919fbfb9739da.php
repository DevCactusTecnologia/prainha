<div class="modal fade" id="listFilterModal" tabindex="-1" aria-labelledby="newParameterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content form-group">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista de Filtros do Exame</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="p-3">
                <div class="row">
                    <div class="col-lg-3">
                        <button type="button" class="btn btn-primary waves-effect waves-light mb-4" data-toggle="modal" 
                            data-target="#filterModal" data-backdrop="static" data-keyboard="false" onclick="closeModal()"
                        >
                            <i class="bx bx-plus font-size-16 align-middle mr-2"></i> 
                            <?php echo e(__('Adicionar novo filtro')); ?>

                        </button>
                    </div>
                </div>
                           
                <div class="table-responsive">
                    <table class="table table-sm table-centered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Id</th>
                                <th>Sexo</th>
                                <th>Ano inicial</th>                                
                                <th>Mês inicial</th>
                                <th>Dia inicial</th>
                                <th>Ano final</th>                                
                                <th>Mês final</th>
                                <th>Dia final</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody id="listTableFilter">
                            <?php $__currentLoopData = $exam->filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th><?php echo e($filter->id); ?></th>
                                <th><?php echo e($filter->gender); ?></th>
                                <th><?php echo e($filter->intial_age_year); ?></th>
                                <th><?php echo e($filter->intial_age_month); ?></th>
                                <th><?php echo e($filter->intial_age_day); ?></th>
                                <th><?php echo e($filter->final_age_year); ?></th>
                                <th><?php echo e($filter->final_age_month); ?></th>
                                <th><?php echo e($filter->final_age_day); ?></th>

                                <th>
                                    <button type="button" data-id="<?php echo e($filter->id); ?>"
                                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0 updateFilter"
                                        data-toggle="modal" data-target="#listFilterModal"
                                        title="Atualizar Filtro"
                                        >
                                        <i class="mdi mdi-lead-pencil"></i>
                                    </button>
                                    <button type="button" onclick="deleteFilter(<?php echo e($filter->id); ?>)"
                                        class="btn btn-danger btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0"
                                        title="Deletar Filtro" data-backdrop="static" data-keyboard="false">
                                        <i class="mdi mdi-trash-can"></i>
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
<?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/exams/modal/filters/index.blade.php ENDPATH**/ ?>