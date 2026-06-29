<?php $__env->startSection('title'); ?> <?php echo e(__('Atualizar recepcionista')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Atualizar recepcionista <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> <a href="<?php echo e(url('/')); ?>"><?php echo e(__('Dashboard')); ?></a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('receptionists.index')); ?>"><?php echo e(__('Recepcionistas')); ?></a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_3'); ?> Atualizar recepcionista <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <?php if($receptionist): ?>
                <?php if($role == 'receptionist' || $role == 'biomedical'): ?>
                    <a href="<?php echo e(url('/')); ?>" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                        <?php echo e(__('Voltar ao Dashboard')); ?>

                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('receptionists.show', $receptionist->id)); ?>" class="btn btn-primary waves-effect mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                        <?php echo e(__('Voltar para o perfil do recepcionista')); ?>

                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?php echo e(route('receptionists.index')); ?>" class="btn btn-primary waves-effect mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                    <?php echo e(__('Voltar à lista de recepcionistas')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="<?php echo e(route('receptionists.update', $receptionist->id)); ?>" method="POST" 
                    enctype="multipart/form-data" class="card-body"
                >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                <?php echo e(__('Nome completo ')); ?><span class="text-danger">*</span>
                            </label>
                            <input type="text" name="first_name" id="firstname"
                                class="form-control text-uppercase <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                placeholder="<?php echo e(__('Digite o nome sem abreviações')); ?>"
                                value="<?php echo e(old('first_name', $receptionist->first_name)); ?>" required
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
                                <option value="1" <?php if(old('is_deleted', $receptionist_info->is_deleted->value) == '1'): echo 'selected'; endif; ?>>
                                    Sim
                                </option>
                                <option value="0" <?php if(old('is_deleted', $receptionist_info->is_deleted->value) == '0'): echo 'selected'; endif; ?>>
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
                            <label class="control-label"><?php echo e(__('CPF')); ?></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['cpf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="cpf" id="cpf" value="<?php echo e(old('cpf', $receptionist_info->cpf)); ?>" 
                                placeholder="<?php echo e(__('Digite o nº do CPF')); ?>"
                            >
                            <?php $__errorArgs = ['cpf'];
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
                            <label class="control-label"><?php echo e(__('CNS')); ?></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['cns'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="cns" id="cns" value="<?php echo e(old('cns', $receptionist_info->cns)); ?>" 
                                placeholder="<?php echo e(__('Digite o nº do cartão SUS')); ?>"
                            >
                            <?php $__errorArgs = ['cns'];
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
                            <label class="control-label"><?php echo e(__('E-mail ')); ?><span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="email" id="email" placeholder="<?php echo e(__('Digite o email')); ?>"
                                value="<?php echo e(old('email', $receptionist->email)); ?>" required
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
                            <label class="control-label"><?php echo e(__('Senha de acesso')); ?></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="password" id="password" value="<?php echo e(old('password', $receptionist->password)); ?>" 
                                placeholder="<?php echo e(__('Digite a senha')); ?>"
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
                            <label class="control-label">
                                <?php echo e(__('Nº de Contato ')); ?><span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="mobile" id="mobile" value="<?php echo e(old('mobile', $receptionist->mobile)); ?>" 
                                placeholder="<?php echo e(__('Digite o nº de contato')); ?>" required
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
                        <div class="col-md-3 form-group">
                            <label class="control-label"><?php echo e(__('Foto do perfil')); ?></label>
                            <img class="<?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" src="<?php if($receptionist && $receptionist->profile_photo != null): ?><?php echo e(URL::asset('storage/images/users/' . $receptionist->profile_photo)); ?><?php else: ?><?php echo e(URL::asset('assets/images/users/noImage.png')); ?><?php endif; ?>" 
                                id="profile_display" onclick="triggerClick()" 
                                data-toggle="tooltip" data-placement="top" title="Clique para enviar a foto do perfil" 
                            />
                            <input type="file" class="form-control <?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="profile_photo" id="profile_photo" 
                                style="display:none;" onchange="displayProfile(this)"
                            >
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
                        <div class="col-md-3 form-group"></div>
                        <div class="col-md-6 form-group"></div>
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
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/libs/inputmask/jquery.inputmask.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pages/receptionists/script.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/hospi580/lab.hospitaldrjarques.com.br/resources/views/receptionists/edit.blade.php ENDPATH**/ ?>