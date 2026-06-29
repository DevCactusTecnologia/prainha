
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Bem-vindo ao <?php echo e(config('app.name')); ?> Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-soft-primary">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">Bem vindo de volta!</h5>
                            <p><?php echo e(config('app.name')); ?> Dashboard</p>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="<?php echo e(asset('assets/images/profile-img.png')); ?>" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="avatar-md profile-user-wid mb-4">
                            <img src="<?php if($user->profile_photo != null): ?><?php echo e(asset('storage/images/users/' . $user->profile_photo)); ?><?php else: ?><?php echo e(asset('assets/images/users/noImage.png')); ?><?php endif; ?>" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15 mb-1"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></h5>
                        <p class="text-muted mb-0 text-truncate">Recepcionista</p>
                    </div>
                </div>
            </div>
        </div>
       
        
        <div class="bg-primary" style="border-radius: 5px;">
            <img src="<?php echo e(asset($campaignCurrent->url_image)); ?>" width="100%" height="180px" alt="<?php echo e($campaignCurrent->name); ?>">
        </div>

    </div>
    <div class="col-xl-8">
        <div class="row">
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <p class="text-muted font-weight-medium">Atendimentos Hoje</p>
                                <div class="mb-0 font-weight-medium font-size-24">
                                    <h4 class="mb-0"><?php echo e($today_appointment_total); ?></h4>
                                </div>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-calendar-check font-size-24"></i>
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
                                <p class="text-muted font-weight-medium">Atendimentos Pendentes</p>
                                <h4 class="mb-0"><?php echo e($pending_appointment_total); ?></h4>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-calendar-event font-size-24"></i>
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
                                <p class="text-muted font-weight-medium">Pacientes Agendados</p>
                                <div class="mb-0 font-weight-medium font-size-24">
                                    <h4 class="mb-0"><?php echo e($upcoming_appointment_total); ?></h4>
                                </div>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="d-md-flex justify-content-md-between align-items-center mb-3">
                    <h4 class="card-title">Atendimentos Hoje</h4>
                    <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-primary waves-effect">
                        Ir para lista de atendimentos
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-1 text-center">#</th>
                                <th class="py-1">Nome do Paciente</th>
                                <th class="py-1">Nome do Médico</th>
                                <th class="py-1">Data</th>
                                <th class="py-1">Status</th>
                                <th class="py-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $per_page = 10;
                                $currentpage = $appointments->currentPage(); 
                            ?>
                            <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($loop->iteration + $per_page * ($currentpage - 1)); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('patients.show', $appointment->patient_id)); ?>"
                                            style="font-weight: 600;"
                                        >
                                            <?php echo e($appointment->patient_name); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($appointment->doctor_name); ?></td>
                                    <td><?php echo e(date('d/m/Y H:i:s', strtotime($appointment->created_at))); ?></td>
                                    <td>
                                        <span class="alert
                                            <?php if($appointment->status == '0'): ?> alert-warning rounded-pill px-2 py-1
                                            <?php elseif($appointment->status == '1'): ?> alert-success rounded-pill px-2 py-1
                                            <?php else: ?> alert-danger rounded-pill px-2 py-1 <?php endif; ?>"
                                        >
                                            <?php if($appointment->status == '0'): ?>
                                                <span style="font-weight: 600;">Pendente</span>
                                            <?php elseif($appointment->status == '1'): ?>
                                                <span style="font-weight: 600;">Finalizado</span>
                                            <?php else: ?>
                                                <span style="font-weight: 600;">Cancelado</span>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($appointment->status == '1'): ?>
                                            <a href="<?php echo e(route('appointments.result.pdf', $appointment->protocol)); ?>" 
                                                class="text-success" title="Imprimir resultado" target="_blank"
                                            >
                                                <i class="mdi mdi-printer font-size-24 align-middle"></i>
                                            </a>
                                        <?php else: ?>
                                            <span style="color: #d4d4d4">
                                                <i class="mdi mdi-printer font-size-24 align-middle"></i>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12 text-center mt-3" id="paginate">
                    <div class="d-flex justify-content-end">
                        <?php echo e($appointments->links()); ?>

                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/hsdrjarques/lab.hospitaldrjarques.com.br/resources/views/layouts/receptionist-dashboard.blade.php ENDPATH**/ ?>