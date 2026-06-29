<?php $__env->startSection('title'); ?> <?php echo e(__('Lista de Médicos')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Lista de Médicos <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Médicos <?php $__env->endSlot(); ?>
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
        <input type="hidden" data-js="base-url" value="<?php echo e(url('/')); ?>">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php echo csrf_field(); ?>
                    <?php if($role != 'patient'): ?>
                        <div class="row">
                            <div class="col-lg-3">
                                <a href="<?php echo e(route('doctors.create')); ?>" class="btn btn-primary waves-effect mb-4">
                                    <i class="bx bx-plus font-size-16 align-middle mr-2"></i> <?php echo e(__('Novo Médico')); ?>

                                </a>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-5 text-right">
                                <?php echo csrf_field(); ?>
                                
                                <input class="form-control" id="searchDoctor" type="text" name="search_name" 
                                    placeholder="Pesquisar médico pelo nome, CPF ou CNS"  
                                />
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-12" style="overflow-x: hidden; overflow-y: scroll; height: 650px;">
                            <table class="table table-sm table-hover table-centered table-bordered dt-responsive nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 2%;"><?php echo e(__('Nº')); ?></th>
                                        <th><?php echo e(__('Nome')); ?></th>
                                        <th style="width: 30%;"><?php echo e(__('Conselho de Classe')); ?></th>
                                        <th style="width: 10%;"><?php echo e(__('Estado Emissor')); ?></th>
                                        <th style="width: 12%;"><?php echo e(__('Nº do Conselho')); ?></th>
                                        <th><?php echo e(__('Status')); ?></th>
                                        <?php if($role != 'patient'): ?>
                                            <th style="width: 10%;"><?php echo e(__('Opções')); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody id="contentDoctor">
                                    <?php
                                        $currentPage = $doctors->currentPage();
                                    ?>

                                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1 + $limit * ($currentPage - 1)); ?></td>
                                            <td><?php echo e($item->first_name); ?> <?php echo e($item->last_name); ?></td>
                                            <td><?php echo e($item->doctor->council->name); ?></td>
                                            <td><?php echo e($item->doctor->state->name); ?></td>
                                            <td><?php echo e($item->doctor->counsil_number); ?></td>
                                            <td>
                                                <span class="<?php echo e($item->doctor->is_deleted?->getColor()); ?>">
                                                    <?php echo e($item->doctor->is_deleted?->getName()); ?>

                                                </span>
                                            </td>
                                            <?php if($role != 'patient'): ?>
                                                <td>
                                                    <?php if($role == 'admin'): ?>
                                                        <a href="<?php echo e(route('doctors.show', $item->id)); ?>" title="Visualizar Perfil"
                                                            class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                        >
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>
                                                    <?php elseif($role == 'receptionist' || $role == 'biomedical' || $role == 'admin'): ?>
                                                        <a href="<?php echo e(route('doctors.show', $item->id)); ?>" title="Visualizar Perfil"
                                                            class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                        >
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if($role == 'receptionist' || $role == 'biomedical' || $role == 'admin'): ?>
                                                        <a href="<?php echo e(route('doctors.edit', $item->id)); ?>" title="Editar Perfil"
                                                            class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                        >
                                                            <i class="mdi mdi-lead-pencil"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tbody id="loader" style="display: none;">
                                    <tr>
                                        <td colspan="5" >
                                            <div class="d-flex justify-content-center align-items-center text-primary" 
                                                style="font-size: 20px; height: 200px;"
                                            >
                                                <span class="spinner-border spinner-border-sm mr-2" 
                                                    role="status" aria-hidden="true">
                                                </span> Carregando...
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="col-md-12 text-center mt-3" id="paginate">
                                <div class="d-flex justify-content-end">
                                    <?php echo e($doctors->links()); ?>

                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/pages/doctors/index.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/doctors/index.blade.php ENDPATH**/ ?>