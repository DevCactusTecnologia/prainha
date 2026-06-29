<?php $__env->startSection('title'); ?> <?php echo e(__('Novo Setor')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        Criar Setor
                    </h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-primary waves-effect waves-light mb-4">
                    <i class=" font-size-16 align-middle mr-2"></i> <?php echo e(__('Voltar a Lista de Setores')); ?>

                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body row">
                <div class="col-lg-12">

                    <form action="<?php echo e(route('categories.store')); ?>" name="doctorform" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="d-md-flex col-md-12">
                            <h4>Adicionar novo Setor</h4>
                        </div>

                        <div class="d-md-flex">
                            <div class="col-md-5 mb-2">
                                <label class="form-label">Nome<span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" name="name" 
                                   value="<?php echo e(old('name')); ?>" max="191" required
                                >
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Abreviação<span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" name="abbreviation" 
                                    value="<?php echo e(old('abbreviation')); ?>" required
                                >
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label invisible">.</label>
                                <button type="submit" class="btn btn-primary form-control">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/categories/create.blade.php ENDPATH**/ ?>