<?php $__env->startSection('title'); ?> <?php echo e(__('Editar setor')); ?> <?php $__env->stopSection(); ?>

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
                        <?php echo e(__('Editar Setor')); ?>

                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                            <li class="breadcrumb-item active">
                                <?php echo e(__('Editar Setor')); ?>

                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>  
        <div class="row">
            <div class="col-lg-12">
                <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-primary waves-effect waves-light mb-4">
                    <i class=" font-size-16 align-middle mr-2"></i> <?php echo e(__('Voltar a Lista de Setores')); ?>

                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                            
                        <form action="<?php echo e(route('categories.update', $category->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="d-md-flex col-md-12">
                                <h4>Editar Setor</h4>
                            </div>
    
                            <div class="d-md-flex">
                                <div class="col-md-5 mb-2">
                                    <label class="form-label">Nome<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control text-uppercase" name="name" 
                                        value="<?php echo e(old('name', $category->name)); ?>" max="191" required
                                    >
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Abreviação<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control text-uppercase" name="abbreviation" 
                                        value="<?php echo e(old('abbreviation', $category->abbreviation)); ?>" required
                                    >
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Status<span class="text-danger">*</span></label>
                                    <select class="form-control" name="is_active" required>
                                        <?php $__currentLoopData = $actives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($status->value); ?>"
                                                <?php if(old('is_active', $category->is_active->value) == $status->value): echo 'selected'; endif; ?>
                                            >
                                                <?php echo e($status->getName()); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label invisible">.</label>
                                    <button type="submit" class="btn btn-primary form-control">Editar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/hospi580/lab.hospitaldrjarques.com.br/resources/views/categories/edit.blade.php ENDPATH**/ ?>