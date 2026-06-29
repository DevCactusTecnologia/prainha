<div class="modal fade" id="create-doctor" tabindex="-1" role="dialog" aria-hidden="true" aria-hidden="true">
    <div class="modal-dialog modal-lg bg-white" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                <form action="<?php echo e(route('doctors.appointment.store')); ?>" method="POST" >
                    <?php echo csrf_field(); ?>

                    <h3 class="text-primary mb-3" style="font-weight: bold;">Novo solicitante</h3>
                    
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Nome Completo<span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" name="first_name" id="doctorFirstName"
                                placeholder="Digite o nome completo sem abreviações" required
                            >
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">CPF</label>
                            <input type="text" class="form-control" name="cpf" id="doctorCpf">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">CNS</label>
                            <input type="text" class="form-control" name="cns" id="doctorCns">
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Conselho de Classe<span class="text-danger">*</span></label>
                            <select class="form-control" name="class_council_id" id="doctorClassCouncilId" required>
                                <option value="">Selecione</option>
                                <?php $__currentLoopData = $classCouncils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classCouncil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($classCouncil->id); ?>"
                                        <?php if(old('class_council_id') == $classCouncil->id): echo 'selected'; endif; ?>
                                    >
                                        <?php echo e($classCouncil->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="control-label">Estado Emissor<span class="text-danger">*</span></label>
                                    <select class="form-control" name="issuing_state_id" id="doctorIssuingState" required>
                                        <option value="">Selecione</option>
                                        <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($state->id); ?>"
                                                <?php if(old('issuing_state_id') == $state->id): echo 'selected'; endif; ?>
                                            >
                                                <?php echo e($state->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="control-label">Nº de Registro<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="counsil_number" 
                                        id="doctorCounsilNumber" value="<?php echo e(old('counsil_number')); ?>" required
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end modal-footer border-0 mt-2">
                        <button type="button" data-dismiss="modal" 
                            class="btn btn-white text-primary" style="font-weight: 600;"
                        >
                            Fechar
                        </button>
                        <button type="button" class="btn btn-primary rounded-pill px-3" 
                            style="font-weight: 600;" data-js="create-doctor"
                        >
                            Salvar
                        </button>
                    </div>
            
                </form>
            </div>   
        </div>
        
    </div>
</div>
<?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/appointments/modal/doctor/create.blade.php ENDPATH**/ ?>