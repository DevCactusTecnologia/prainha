<?php $__env->startSection('title'); ?> <?php echo e(__('Lista de Pacientes')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Lista de Pacientes <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Pacientes <?php $__env->endSlot(); ?>
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
                <input type="hidden" data-js="base-url" value="<?php echo e(url('/')); ?>">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-3">
                            <a href="<?php echo e(route('patients.create')); ?>" class="btn btn-primary waves-effect mb-4">
                                <i class="bx bx-plus font-size-16 align-middle mr-2"></i> <?php echo e(__('Novo Paciente')); ?>

                            </a>
                        </div>
                        <div class="col-lg-4"></div>
                        <div class="col-lg-5 text-right">
                            <?php echo csrf_field(); ?>
                            
                            <input class="form-control" id="searchPatient" type="text" name="search_name" 
                                placeholder="Pesquisar paciente pelo nome, CPF ou CNS"  
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12" style="overflow-x: hidden; overflow-y: scroll; height: 650px;">
                            <table class="table table-hover table-centered table-bordered table-sm dt-responsive nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 2%;"><?php echo e(__('Nº')); ?></th>
                                        <th style="width: 50%;"><?php echo e(__('Nome')); ?></th>
                                        <th style="width: 13%;"><?php echo e(__('CPF')); ?></th>
                                        <th style="width: 20%;"><?php echo e(__('CNS')); ?></th>
                                        <th><?php echo e(__('Status')); ?></th>
                                        <th style="width: 12%;"><?php echo e(__('Opções')); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="contentPatient">
                                    <?php
                                        $currentpage = $patients->currentPage();
                                    ?>
                                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php echo e($loop->index + 1 + $limit * ($currentpage - 1)); ?>

                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('patients.show', $item->id)); ?>">
                                                    <?php echo e($item->first_name); ?> <?php echo e($item->last_name); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($item->patient->cpf_masked); ?></td>
                                            <td><?php echo e($item->patient->cns_masked); ?></td>
                                            <td>
                                                <span class="<?php echo e($item->patient->is_deleted?->getColor()); ?>">
                                                    <?php echo e($item->patient->is_deleted?->getName()); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('patients.show', $item->id)); ?>" title="Visualizar Perfil"
                                                    class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                >
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('patients.edit', $item->id)); ?>" title="Editar Perfil"
                                                    class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                >
                                                    <i class="mdi mdi-lead-pencil"></i>
                                                </a>
                                            </td>
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
                        </div>
                        <div class="col-md-12 text-center mt-3" id="paginate">
                            <div class="d-flex justify-content-end">
                                <?php echo e($patients->links()); ?>

                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/pages/patients/search.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u444904474/domains/sislac.com.br/public_html/prainha.sislac.com.br/resources/views/patients/index.blade.php ENDPATH**/ ?>