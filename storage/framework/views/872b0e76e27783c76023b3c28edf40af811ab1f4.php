<?php $__env->startSection('title'); ?> <?php echo e(__('Perfil do Paciente')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Perfil do Paciente  <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('patients.index')); ?>">Pacientes</a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_3'); ?> Perfil do Paciente <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    
    <div class="row">
        <div class="col-xl-4">
            <div class="card overflow-hidden">
                <div class="bg-soft-primary">
                    <div class="row">
                        <div class="col-7">
                            <div class="text-primary p-3">
                                <h5 class="text-primary"><?php echo e(__('Informações do paciente')); ?></h5>
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="<?php echo e(URL::asset('assets/images/profile-img.png')); ?>" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="avatar-md profile-user-wid mb-4">
                                <img src="<?php if($patient->profile_photo != null): ?><?php echo e(URL::asset('storage/images/users/' . $patient->profile_photo)); ?><?php else: ?><?php echo e(URL::asset('assets/images/users/noImage.png')); ?><?php endif; ?>" alt="<?php echo e($patient->first_name); ?>" class="img-thumbnail rounded-circle">
                            </div>
                            <h5 class="font-size-15"><?php echo e($patient->first_name); ?></h5>
                        </div>
                        <div class="col-sm-7">
                            <div class="pt-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="font-size-12"><?php echo e(__('Último Login :')); ?></h5>
                                        <?php if($patient->last_login): ?>
                                            <p class="text-muted mb-0">
                                                <?php echo e(date('d/m/Y H:i:s', strtotime($patient->last_login))); ?>

                                            </p>
                                        <?php else: ?>
                                            <p class="bg-light mb-0 p-2 text-muted">
                                                O paciente ainda não acessou o sistema.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="<?php echo e(route('patients.edit', $patient->id)); ?>" class="btn btn-primary waves-effect btn-sm">
                                        <?php echo e(__('Editar Perfil ')); ?><i class="mdi mdi-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4"><?php echo e(__('Informações pessoais')); ?></h4>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php echo e(__('Nome Completo:')); ?></th>
                                    <td><?php echo e(@$patient->first_name); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo e(__('Sexo:')); ?></th>
                                    <td><?php echo e(@$patient_info->gender_name); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo e(__('Idade:')); ?></th>
                                    <td><?php echo e(@$patient_info->age_extended); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo e(__('CPF:')); ?></th>
                                    <td><?php echo e(@$patient_info->cpf_masked); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo e(__('CNS:')); ?></th>
                                    <td><?php echo e(@$patient_info->cns_masked); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo e(__('Endereço:')); ?></th>
                                    <td><?php echo e(@$patient_info->address); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-xl-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium"><?php echo e(__('Atendimentos')); ?></p>
                                    <h4 class="mb-0"><?php echo e(number_format($data['total_appointment'])); ?></h4>
                                </div>
                                <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-check-circle font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium"><?php echo e(__('Exames pendentes')); ?></p>
                                    <h4 class="mb-0"><?php echo e(number_format($data['pending_bill'])); ?></h4>
                                </div>
                                <div class="avatar-sm align-self-center mini-stat-icon rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-hourglass font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium"><?php echo e(__('Total')); ?></p>
                                    <h4 class="mb-0"><?php echo e(number_format($data['revenue'], 2)); ?></h4>
                                </div>
                                <div class="avatar-sm align-self-center mini-stat-icon rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-package font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4"><?php echo e(__('Lista de Atendimentos')); ?></h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th><?php echo e(__('Nº')); ?></th>
                                    <th><?php echo e(__('Nome do Médico')); ?></th>
                                    <th><?php echo e(__('Data')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(session()->has('page_limit')): ?>
                                    <?php
                                        $per_page = session()->get('page_limit');
                                    ?>
                                <?php else: ?>
                                    <?php
                                        $per_page = Config::get('app.page_limit');
                                    ?>
                                <?php endif; ?>
                                <?php
                                    $currentpage = $appointments->currentPage();
                                ?>
                                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->index + 1); ?></td>
                                        <td><?php echo e($item->doctor->first_name); ?> <?php echo e($item->doctor->last_name); ?></td>
                                        <td><?php echo e(date('d/m/Y', strtotime($item->appointment_date))); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hsdrjarques/lab.hospitaldrjarques.com.br/resources/views/patients/show.blade.php ENDPATH**/ ?>