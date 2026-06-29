<?php $__env->startSection('title'); ?> <?php echo e(__('Agendar Atendimento')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::asset('assets/libs/fullcalendar/fullcalendar.min.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
        
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Agendar Atendimento <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Agendar Atendimento <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    
    <div class="row">
        <div class="col-12">
            <a href="<?php echo e(route('appointments.create')); ?>"
                class="btn btn-primary text-white waves-effect mb-4">
                <i class="bx bx-plus font-size-16 align-middle mr-2"></i> <?php echo e(__('Novo Atendimento')); ?>

            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <?php echo e(__('Lista de Atendimentos')); ?> | 
                        <label id="selected_date"><?php echo e(date('d M, Y')); ?></label>
                    </h4>
                    <div id="appointment_list">
                        <table class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th><?php echo e(__('Nº')); ?></th>
                                    <?php if($role == 'patient'): ?>
                                        <th><?php echo e(__('Nome do Médico')); ?></th>
                                        <th><?php echo e(__('Nº do Médico')); ?></th>
                                    <?php elseif($role == 'doctor'): ?>
                                        <th><?php echo e(__('Nome do Paciente')); ?></th>
                                        <th><?php echo e(__('Nº do Paciente')); ?></th>
                                    <?php else: ?>
                                        <th><?php echo e(__('Nome do Paciente')); ?></th>
                                        <th><?php echo e(__('Nome do Médico')); ?></th>
                                        <th><?php echo e(__('Nº do Paciente')); ?></th>
                                    <?php endif; ?>
                                    <th><?php echo e(__('Data')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 1;
                                ?>
                                <?php if($role == 'receptionist' || $role == 'biomedical'): ?>
                                    <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td>
                                                <?php echo e($appointment->patient->first_name); ?>

                                            </td>
                                            <td>
                                                <?php echo e($appointment->doctor->first_name); ?>

                                            </td>
                                            <td>
                                                <?php echo e($appointment->patient->id); ?>

                                            </td>
                                            <td>
                                                <?php echo e(date('d/m/Y', strtotime($appointment->appointment_date))); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php elseif($role == 'doctor'): ?>
                                    <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i); ?></td>
                                            <td>
                                                <?php echo e($appointment->patient->first_name . ' ' . $appointment->patient->last_name); ?>

                                            </td>
                                            <td>
                                                <?php echo e($appointment->patient->mobile); ?>

                                            </td>
                                            <td>
                                                <?php echo e($appointment->date); ?>

                                            </td>
                                        </tr>
                                        <?php
                                            $i++;
                                        ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php elseif($role == 'patient'): ?>
                                    <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i); ?></td>
                                            <td>
                                                <?php echo e($appointment->doctor->first_name . ' ' . $appointment->doctor->last_name); ?>

                                            </td>
                                            <td>
                                                <?php echo e($appointment->doctor->mobile); ?>

                                            </td>
                                            <td>
                                                <?php echo e($appointment->date); ?>

                                            </td>
                                        </tr>
                                        <?php
                                            $i++;
                                        ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?> 
                                    <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td>
                                                <?php echo e($appointment->patient->first_name); ?>

                                            </td>
                                            <td>
                                                <?php echo e($appointment->doctor->first_name); ?>

                                            </td>
                                            <td>
                                                <?php echo e($appointment->patient->id); ?>

                                            </td>
                                            <td>
                                                <?php echo e(date('d/m/Y', strtotime($appointment->appointment_date))); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="new_list" style="display : none"></div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <!-- LIBS -->
    <script src="<?php echo e(URL::asset('assets/libs/moment/moment.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/fullcalendar/fullcalendar.min.js')); ?>"></script>
    
    <!-- Get App url in Javascript file -->
    <script type="text/javascript">
        var aplist_url = "<?php echo e(url('appointments/schedules/list')); ?>";
    </script>

    <!-- Init js-->
    <script src="<?php echo e(URL::asset('assets/js/pages/calendar-init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/appointments/schedule.blade.php ENDPATH**/ ?>