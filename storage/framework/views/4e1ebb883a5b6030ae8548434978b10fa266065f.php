<?php $__env->startSection('title'); ?> <?php echo e(__('Lista de Setores')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div>
        
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                      <?php echo e(__('Lista de Setores')); ?>

                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                            <li class="breadcrumb-item active">
                                <?php echo e(__('Lista de Setores')); ?>

                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <a href="<?php echo e(route('categories.create')); ?>">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i class="bx bx-plus font-size-16 align-middle mr-2"></i> <?php echo e(__('Novo Setor')); ?>

                            </button>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="detail_box">
                            <table class="table table-sm table-centered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="20%">Nome</th>
                                        <th width="20%">Abreviação</th>
                                        <th width="10%">Status</th>
                                        <th width="50%">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($category->name); ?></td>
                                            <td><?php echo e($category->abbreviation); ?></td>
                                            <td>
                                                <span class="<?php echo e($category->is_active->getColor()); ?>">
                                                    <?php echo e($category->is_active->getName()); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('categories.edit', $category->id)); ?>" title="Atualizar setor"
                                                    class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0"
                                                >
                                                    <i class="mdi mdi-lead-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 text-center mt-3">
                        <div class="d-flex justify-content-start">
                            <?php echo e($category->count()); ?> registros encontrados
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u444904474/domains/sislac.com.br/public_html/prainha.sislac.com.br/resources/views/categories/index.blade.php ENDPATH**/ ?>