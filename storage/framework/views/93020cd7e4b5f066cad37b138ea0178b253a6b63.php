<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-soft-primary">
                <div class="row">
                    <div class="col-8">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">Bem vindo de volta !</h5>
                            <p><?php echo e(config('app.name')); ?> Dashboard</p>
                        </div>
                    </div>
                    <div class="col-4 align-self-end">
                        <img src="<?php echo e(asset('assets/images/profile-img.png')); ?>" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="avatar-md profile-user-wid mb-4">
                            <img src="<?php if($user->profile_photo != ''): ?><?php echo e(asset('storage/images/users/' . $user->profile_photo)); ?><?php else: ?><?php echo e(asset('assets/images/users/noImage.png')); ?><?php endif; ?>" alt="" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15 text-dark mb-0"><?php echo e($user->first_name); ?></h5>
                        <p class="text-muted mb-0 text-truncate">Super Admin</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-primary" style="border-radius: 5px;">
            <img src="<?php echo e(asset($campaignCurrent->url_image)); ?>" width="100%" height="140px" alt="<?php echo e($campaignCurrent->name); ?>">
        </div>
    </div>

    <div class="col-xl-8">
        <div class="row">
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <h5 class="text-dark font-weight-bold mb-3" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">Total de Atendimentos</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mb-0 font-weight-medium">
                                <h4 class="mb-0 text-secondary"><?php echo e($total_appointment); ?></h4>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-lg bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-copy-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <h5 class="text-dark font-weight-bold mb-3" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">Exames este mês</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mb-0 font-weight-medium">
                                <h4 class="mb-0 text-secondary"><?php echo e($total_exam_month_current); ?></h4>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-lg bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-calendar font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <h5 class="text-dark font-weight-bold mb-3" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">Total de Exames</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mb-0 font-weight-medium">
                                <h4 class="mb-0 text-secondary"><?php echo e($total_exams); ?></h4>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-lg bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-calendar font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <h5 class="text-dark font-weight-bold mb-3" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">Atendimentos Hoje</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mb-0 font-weight-medium">
                                <div>Pacientes: <?php echo e($today_appointment_total); ?></div>
                                <div>Exames: <?php echo e($today_appointment_exam_total); ?></div>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-lg bg-primary align-self-center">
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
                        <h5 class="text-dark font-weight-bold mb-3" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">Atendimentos Pendentes</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mb-0 font-weight-medium">
                                <div>Pacientes: <?php echo e($pending_appointment_total); ?></div>
                                <div>Exames: <?php echo e($pending_appointment_exam_total); ?></div>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-lg bg-primary align-self-center">
                                <span class="avatar-title">
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
                                <h5 class="text-dark font-weight-bold mb-3" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">Ocorrências</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mb-0 font-weight-medium">
                                        <div>Pendentes: <?php echo e($occurrences->pending ?: 0); ?></div>
                                        <div>Resolvidas: <?php echo e($occurrences->resolved ?: 0); ?></div>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm rounded-lg bg-primary align-self-center">
                                        <span class="avatar-title">
                                            <i class="bx bx-info-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-2">Gráfico de atendimentos</h4>
                <div id="monthly_users" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/layouts/admin-dashboard.blade.php ENDPATH**/ ?>