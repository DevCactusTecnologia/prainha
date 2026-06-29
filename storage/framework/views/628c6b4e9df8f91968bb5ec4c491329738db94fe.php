<?php $__env->startSection('title'); ?> <?php echo e(__('Lista de Biomédicos')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Lista de biomédicos  <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Biomédicos <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    
    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo session()->get('success'); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php echo e(session()->forget('success')); ?>

    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if($role == 'admin'): ?>
                        <a href=" <?php echo e(route('biomedicals.create')); ?> ">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i class="bx bx-plus font-size-16 align-middle mr-2"></i> <?php echo e(__('Novo Biomédico')); ?>

                            </button>
                        </a>
                    <?php endif; ?>
                    <table class="table table-bordered table-sm table-centered table-hover nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th><?php echo e(__('Nº')); ?></th>
                                <th><?php echo e(__('Nome')); ?></th>
                                <th><?php echo e(__('Nº de Contato')); ?></th>
                                <th><?php echo e(__('E-mail')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <th><?php echo e(__('Opções')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $biomedicals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $biomedical): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('biomedicals.show', $biomedical->id)); ?>">
                                            <?php echo e($biomedical->first_name); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($biomedical->mobile); ?></td>
                                    <td><?php echo e($biomedical->email); ?></td>
                                    <td>
                                        <span class="<?php echo e($biomedical->biomedical->is_deleted?->getColor()); ?>">
                                            <?php echo e($biomedical->biomedical->is_deleted?->getName()); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($role == 'admin'): ?>
                                            <a href="<?php echo e(route('biomedicals.show', $biomedical->id)); ?>" title="Visualizar perfil"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                            >
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('biomedicals.edit', $biomedical->id)); ?>" title="Editar perfil"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                            >
                                                <i class="mdi mdi-lead-pencil"></i>
                                            </a>
                                        <?php elseif($role == 'doctor'): ?>
                                            <a href="<?php echo e(route('biomedicals.show', $biomedical->id)); ?>" title="Visualizar perfil"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                            >
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hsdrjarques/lab.hospitaldrjarques.com.br/resources/views/biomedicals/index.blade.php ENDPATH**/ ?>