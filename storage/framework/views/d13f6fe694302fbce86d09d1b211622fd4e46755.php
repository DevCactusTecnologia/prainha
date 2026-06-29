<?php if($exam->pivot->status == '0' || $exam->pivot->status == '1'): ?>
    <div class="d-md-flex justify-content-md-end" style="margin-top: -53px;">
        
        <button type="button" class="btn" data-toggle="modal" data-target="#retest-<?php echo e($exam->id); ?>"
            style="font-weight: 600; color: #ffa600; background-color: #ffe4b3" 
        >
            Reteste <i class="bx bx-columns"></i> 
        </button>
        <div class="modal fade" id="retest-<?php echo e($exam->id); ?>" tabindex="-1" role="dialog" aria-hidden="true" aria-hidden="true">
            <div class="modal-dialog modal-md bg-white" role="document">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <form action="<?php echo e(route('appointments.result.retest', [$appointment->id, $exam->id])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            
                            <div class="d-md-flex flex-column justify-content-center">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="90" fill="#ffa600" viewBox="0 -960 960 960" width="90">
                                        <path d="M200-120v-60h208v-104h-15q-81 0-137-56t-56-137q0-61 35-111t92-70q-2 15 .5 30t9.5 29q-37 14-57 48t-20 74q0 55 39 94t94 39h347v60H508v104h252v60H200Zm380-367-14-37-43 16-25-68q14-14 21-31.5t7-37.5q0-39-26-67.5T435-745l-22-59 41-15-14-37 66-24 14 37 40-14 113 295-43 15 14 37-64 23ZM426-595q-21 0-35.5-14.5T376-645q0-21 14.5-35.5T426-695q21 0 35.5 14.5T476-645q0 21-14.5 35.5T426-595Z"/>
                                    </svg>
                                </div>
                                <span class="text-center mb-2">
                                    Deseja realmente encaminhar o exame <strong><?php echo e($exam->name); ?></strong> para reteste?
                                </span>
                                <div>
                                    <label class="control-label">Motivo<span class="text-danger">*</span></label>
                                    <select class="form-control mb-3" name="motive" required>
                                        <option value="">Selecione</option>
                                        <?php $__currentLoopData = $motives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($motive->getName()); ?>"><?php echo e($motive->getName()); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-center modal-footer border-0 pt-0">
                                <button type="button" data-dismiss="modal" 
                                    class="btn btn-white text-secondary" style="font-weight: 600;"
                                >
                                    Fechar
                                </button>
                                <button type="submit" class="btn rounded-pill px-3" 
                                    style="color: #ffa600; background-color: #ffe4b3; font-weight: 600;"
                                >
                                    Salvar
                                </button>
                            </div>
                    
                        </form>
                    </div>   
                </div>
                
            </div>
        </div>

        
        <button type="button" class="btn mx-2" data-toggle="modal" data-target="#cancel-<?php echo e($exam->id); ?>"
            style="font-weight: 600; color: #c61010; background-color: #f9b1b1" 
        >
            Cancelar <i class="bx bxs-x-circle"></i>  
        </button>
        <div class="modal fade" id="cancel-<?php echo e($exam->id); ?>" tabindex="-2" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <form action="<?php echo e(route('appointments.result.cancel', [$appointment->id, $exam->id])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            
                            <div class="d-md-flex flex-column justify-content-center">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="90" fill="#f46a6a" viewBox="0 -960 960 960" width="90">
                                        <path d="m330-288 150-150 150 150 42-42-150-150 150-150-42-42-150 150-150-150-42 42 150 150-150 150 42 42ZM480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-156t86-127Q252-817 325-848.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 82-31.5 155T763-197.5q-54 54.5-127 86T480-80Z"/>
                                    </svg>
                                </div>
                                <span class="text-center mb-2">
                                    Deseja realmente cancelar o exame <strong><?php echo e($exam->name); ?></strong>?
                                </span>
                                <div>
                                    <label class="control-label">Motivo<span class="text-danger">*</span></label>
                                    <select class="form-control mb-3" name="motive" required>
                                        <option value="">Selecione</option>
                                        <?php $__currentLoopData = $motives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($motive->getName()); ?>"><?php echo e($motive->getName()); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-center modal-footer border-0 pt-0">
                                <button type="button" data-dismiss="modal" 
                                    class="btn btn-white text-danger" style="font-weight: 600;"
                                >
                                    Fechar
                                </button>
                                <button type="submit" class="btn btn-danger rounded-pill px-3">
                                    Cancelar
                                </button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php else: ?>

    <?php if($exam->pivot->status == '2' && $exam->pivot->re_test == '0'): ?>
        <div class="p-3 mt-3" style="color: #c61010; background-color: #f5bbbb;">
            <div class="d-md-flex justify-content-md-between align-items-md-center">
                <div>
                    <div style="font-weight: 600;">Exame cancelado!</div>
                    <div><strong>Motivo:</strong> <?php echo e($exam->pivot->observation ?: 'NÃO INFORMADO'); ?></div>
                </div>
                <button type="button" class="btn my-0 py-0 mx-0 px-0" data-toggle="modal" data-target="#restore-<?php echo e($exam->id); ?>"
                    style="font-weight: 600; color: #000;" title="Restaurar exame <?php echo e($exam->name); ?>"
                >
                    <i class="mdi mdi-delete-restore font-size-24 align-middle"></i>
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="p-3 mt-3" style="color: #000; background-color: #fae5bf;">
            <div class="d-md-flex justify-content-md-between align-items-md-center">
                <div>
                    <div style="font-weight: 600;">O exame foi encaminhado para reteste!</div>
                    <div><strong>Motivo:</strong> <?php echo e($exam->pivot->observation ?: 'NÃO INFORMADO'); ?></div>
                </div>
                <button type="button" class="btn my-0 py-0 mx-0 px-0" data-toggle="modal" data-target="#restore-<?php echo e($exam->id); ?>"
                    style="font-weight: 600; color: #000;" title="Restaurar exame <?php echo e($exam->name); ?>"
                >
                    <i class="mdi mdi-delete-restore font-size-24 align-middle"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div class="modal fade" id="restore-<?php echo e($exam->id); ?>" tabindex="-2" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <form action="<?php echo e(route('appointments.result.restore', [$appointment->id, $exam->id])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="d-md-flex flex-column justify-content-center">
                            <div class="text-center">
                                <i class="mdi mdi-delete-restore mdi-25px align-middle text-secondary" style="font-size: 90px;"></i>
                            </div>
                            <div class="text-center mb-4" style="margin-top: -20px;">
                                Deseja realmente restaurar o exame <strong><?php echo e($exam->name); ?></strong>?
                            </div>
                            <input type="hidden" name="old_motive" value="<?php echo e($exam->pivot->observation ?: 'NÃO INFORMADO'); ?>">
                        </div>
                        
                        <div class="d-flex justify-content-center modal-footer border-0 pt-0">
                            <button type="button" data-dismiss="modal" 
                                class="btn btn-white text-dark" style="font-weight: 600;"
                            >
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-dark rounded-pill px-4" style="font-weight: 600;">
                                Restaurar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<?php endif; ?>
<?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/appointments/result/modal-cancel-retest.blade.php ENDPATH**/ ?>