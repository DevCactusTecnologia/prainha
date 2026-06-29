<div class="modal fade" id="cancel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                <form data-js="form-cancel" action="" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="d-md-flex flex-column justify-content-center">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" height="90" fill="#f46a6a" viewBox="0 -960 960 960" width="90">
                                <path d="m330-288 150-150 150 150 42-42-150-150 150-150-42-42-150 150-150-150-42 42 150 150-150 150 42 42ZM480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-156t86-127Q252-817 325-848.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 82-31.5 155T763-197.5q-54 54.5-127 86T480-80Z"/>
                            </svg>
                        </div>
                        <span class="text-center mb-2">
                            Deseja realmente cancelar o atendimento do protocolo:
                        </span>
                        <div class="text-center p-3">
                            <span class="bg-light rounded-lg fa-3x p-3">Nº <strong data-js="protocol">56</strong></span>
                        </div>
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
<?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/appointments/modal/cancel/view.blade.php ENDPATH**/ ?>