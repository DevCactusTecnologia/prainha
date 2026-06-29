<?php $__env->startSection('title'); ?><?php echo e(__('Atualizar dados do médico')); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> <a href="<?php echo e(route('doctors.index')); ?>"><?php echo e(__('Médicos')); ?></a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Atualizar dados do médico <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    
    <div class="row">
        <div class="col-12">
            <?php if($doctor && $doctor_info): ?>
                <?php if($role == 'doctor'): ?>
                    <a href="<?php echo e(url('/')); ?>" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i><?php echo e(__('Voltar ao Dashboard')); ?>

                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('doctors.show', $doctor->id)); ?>" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i><?php echo e(__('Voltar ao perfil')); ?>

                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?php echo e(route('doctors.index')); ?>" class="btn btn-primary waves-effect mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i><?php echo e(__('Voltar à lista de médicos')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('doctors.update', $doctor->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label">
                                    <?php echo e(__('Nome Completo ')); ?><span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                    class="form-control text-uppercase <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    name="first_name" id="firstname" placeholder="<?php echo e(__('Digite o primeiro nome')); ?>"
                                    value="<?php echo e(old('first_name', $doctor->first_name)); ?>" required
                                >
                                <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="control-label">
                                    <?php echo e(__('Inativo ')); ?><span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="is_deleted" required>
                                    <option value="1" <?php if(old('is_deleted', $doctor_info->is_deleted->value) == '1'): echo 'selected'; endif; ?>>
                                        Sim
                                    </option>
                                    <option value="0" <?php if(old('is_deleted', $doctor_info->is_deleted->value) == '0'): echo 'selected'; endif; ?>>
                                        Não
                                    </option>
                                </select>
                                <?php $__errorArgs = ['is_deleted'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label"><?php echo e(__('CPF ')); ?><span class="text-danger"></span></label>
                                <input type="text" 
                                    class="form-control <?php $__errorArgs = ['doctor_cpf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    placeholder="<?php echo e(__('Digite o nº do CPF')); ?>" name="doctor_cpf" id="doctor_cpf" 
                                    value="<?php echo e(old('doctor_cpf', $doctor_info->doctor_cpf)); ?>"
                                >
                                <?php $__errorArgs = ['doctor_cpf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="control-label"><?php echo e(__('CNS ')); ?><span class="text-danger"></span></label>
                                <input type="text" 
                                    class="form-control <?php $__errorArgs = ['doctor_cns'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    name="doctor_cns" id="doctor_cns" placeholder="<?php echo e(__('Digite o nº do cartão SUS')); ?>"
                                    value="<?php echo e(old('doctor_cns', $doctor_info->doctor_cns)); ?>"
                                >
                                <?php $__errorArgs = ['doctor_cns'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>    
                        
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label">
                                    <?php echo e(__('Conselho de Classe ')); ?><span class="text-danger">*</span>
                                </label>
                                <select class="form-control <?php $__errorArgs = ['class_council_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    name="class_council_id" id="class_council" required
                                >
                                    <option value="">Selecione</option>
                                    <?php $__currentLoopData = $classCouncils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classCouncil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($classCouncil->id); ?>"
                                            <?php if(old('class_council_id', $doctor_info->class_council_id) == $classCouncil->id): echo 'selected'; endif; ?>
                                        >
                                            <?php echo e($classCouncil->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['class_council_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3 form-group">
                                <label class="control-label">
                                    <?php echo e(__('Estado Emissor ')); ?><span class="text-danger">*</span>
                                </label>
                                <select class="form-control <?php $__errorArgs = ['issuing_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    name="issuing_state_id" id="issuing_state" required
                                >
                                    <option value="">Selecione</option>
                                    <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>"
                                            <?php if(old('issuing_state_id', $doctor_info->issuing_state_id) == $state->id): echo 'selected'; endif; ?>
                                        >
                                            <?php echo e($state->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['issuing_state_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="control-label">
                                    <?php echo e(__('Nº de Registro do Conselho ')); ?><span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['counsil_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    placeholder="<?php echo e(__('Digite o nº do conselho')); ?>" 
                                    id="counsil_number" name="counsil_number" 
                                    value="<?php echo e(old('counsil_number', $doctor_info->counsil_number)); ?>" required
                                >
                                <?php $__errorArgs = ['counsil_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                               
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label"><?php echo e(__('E-mail ')); ?></label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    name="email" id="email" placeholder="<?php echo e(__('Digite o email')); ?>"
                                    value="<?php echo e(old('email', $doctor->email)); ?>" 
                                >
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3 form-group">
                                <label class="control-label"><?php echo e(__('Senha de acesso ')); ?></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    name="password" id="password" placeholder="<?php echo e(__('Digite a senha')); ?>"
                                    value="<?php echo e(old('password', $doctor->password)); ?>"
                                >
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="control-label"><?php echo e(__('Nº de Contato ')); ?></label>
                                <input type="tel" class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    name="mobile" id="patientMobile" placeholder="<?php echo e(__('Digite o nº de contato')); ?>"
                                    value="<?php echo e(old('mobile', $doctor->mobile)); ?>" 
                                >
                                <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                                
                        
                        <div class="row">         
                            <div class="col-md-6 form-group">
                                <label class="control-label"><?php echo e(__('Foto do Perfil ')); ?></label>
                                <img class="<?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    src="<?php if($doctor && $doctor_info && $doctor->profile_photo != 'noImage.png' && $doctor && $doctor_info && $doctor->profile_photo != ''): ?> <?php echo e(URL::asset('storage/images/users/' . $doctor->profile_photo)); ?>  <?php else: ?> <?php echo e(URL::asset('assets/images/users/noImage.png')); ?> <?php endif; ?>" 
                                    id="profile_display" onclick="triggerClick()"
                                    data-toggle="tooltip" data-placement="top"
                                    title="Clique para enviar a foto do perfil" 
                                />
                                <input type="file"
                                    class="form-control <?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="profile_photo" id="profile_photo" style="display:none;"
                                    onchange="displayProfile(this)">
                                <?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo e(__('Atualizar detalhes')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/libs/inputmask/jquery.inputmask.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pages/doctors/create.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/doctors/edit.blade.php ENDPATH**/ ?>