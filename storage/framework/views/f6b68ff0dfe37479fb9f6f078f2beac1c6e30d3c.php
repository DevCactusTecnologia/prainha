<?php $__env->startSection('title'); ?> <?php echo e(__('Lista de Atendimentos')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Lista de Atendimentos <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Atendimentos <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12" style="overflow-x: hidden; overflow-y: scroll; height: 650px;">
                            <table class="table table-sm table-centered table-bordered table-hover dt-responsive nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 2%;"><?php echo e(__('Nº')); ?></th>
                                        <th style="width: 22%;"><?php echo e(__('Solicitante')); ?></th>
                                        <th style="width: 24%;"><?php echo e(__('Nome do Paciente')); ?></th>
                                        <th style="width: 9%;" class="text-center"><?php echo e(__('Protocolo')); ?></th>
                                        <th style="width: 17%;"><?php echo e(__('Data')); ?></th>
                                        <th style="width: 13%;"><?php echo e(__('Status')); ?></th>
                                        <th style="width: 13%"><?php echo e(__('Ação')); ?></th>
                                    </tr>
                                    <tr>
                                        <th style="width: 2%;"></th>
                                        <th style="width: 22%;"></th>
                                        <th style="width: 24%;">
                                            <input type="text" class="form-control" id="changePatient">
                                        </th>
                                        <th style="width: 9%;">
                                            <input type="text" class="form-control text-center" id="changeProtocol">
                                        </th>
                                        <th style="width: 17%;">
                                            <input type="date" class="form-control" id="changeDate">
                                        </th>
                                        <th style="width: 13%;">
                                            <input type="hidden" id="baseUrl" value="<?php echo e(url('/')); ?>">
                                            <select class="form-control" id="changeStatus">
                                                <option selected disabled value="">Selecione</option>
                                                <option value="pending">Pendente</option>
                                                <option value="completed">Finalizado</option>
                                                <option value="canceled">Cancelado</option>
                                            </select>
                                        </th>
                                        <th style="width: 13%"></th>
                                    </tr>
                                </thead>                               

                                <tbody id="contentAppointment">
                                    <?php 
                                        $per_page = 10;
                                        $currentpage = $appointments->currentPage(); 
                                    ?>
                                    <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <tr>
                                            <td><?php echo e($loop->iteration + 0 + $per_page * ($currentpage - 1)); ?></td>
                                            <td><?php echo e($appointment->doctor_name); ?></td>
                                            <td><?php echo e($appointment->patient_name); ?></td>
                                            <td class="text-center"><?php echo e($appointment->protocol); ?></td>
                                            <td><?php echo e(date('d/m/Y H:i:s', strtotime($appointment->created_at))); ?></td>
                                            <td>
                                                <span class="alert
                                                    <?php if($appointment->status == '0'): ?> alert-warning rounded-pill px-2 py-1
                                                    <?php elseif($appointment->status == '1'): ?> alert-success rounded-pill px-2 py-1
                                                    <?php else: ?> alert-danger rounded-pill px-2 py-1 <?php endif; ?>"
                                                >
                                                    <?php if($appointment->status == '0'): ?>
                                                        Pendente
                                                    <?php elseif($appointment->status == '1'): ?>
                                                        Finalizado
                                                    <?php else: ?>
                                                        Cancelado
                                                    <?php endif; ?>
                                                </span>

                                                <?php if($appointment->urgency == 'yes'): ?> 
                                                    <span class="ml-1" title="Urgência">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#f46a6a" height="22" width="22" viewBox="0 0 48 48">
                                                            <path d="M24 34q.7 0 1.175-.475.475-.475.475-1.175 0-.7-.475-1.175Q24.7 30.7 24 30.7q-.7 0-1.175.475-.475.475-.475 1.175 0 .7.475 1.175Q23.3 34 24 34Zm-1.35-7.65h3V13.7h-3ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 23.95q0-4.1 1.575-7.75 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24.05 4q4.1 0 7.75 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z"/>
                                                        </svg>
                                                    <span>
                                                <?php endif; ?> 
                                                
                                            </td>
                                            <td>
                                                <?php if($role == 'admin' || $role == 'receptionist' || $role == 'biomedical'): ?>
                                                    <button type="button" class="btn btn-primary px-2" data-toggle="modal"
                                                        title="Visualizar atendimento" data-target="#viewPending"
                                                        data-id="<?php echo e($appointment->protocol); ?>"
                                                        onclick="modalPending(this)"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" fill="#FFF" viewBox="0 0 48 48">
                                                            <path d="M24 31.5q3.55 0 6.025-2.475Q32.5 26.55 32.5 23q0-3.55-2.475-6.025Q27.55 14.5 24 14.5q-3.55 0-6.025 2.475Q15.5 19.45 15.5 23q0 3.55 2.475 6.025Q20.45 31.5 24 31.5Zm0-2.9q-2.35 0-3.975-1.625T18.4 23q0-2.35 1.625-3.975T24 17.4q2.35 0 3.975 1.625T29.6 23q0 2.35-1.625 3.975T24 28.6Zm0 9.4q-7.3 0-13.2-4.15Q4.9 29.7 2 23q2.9-6.7 8.8-10.85Q16.7 8 24 8q7.3 0 13.2 4.15Q43.1 16.3 46 23q-2.9 6.7-8.8 10.85Q31.3 38 24 38Z"/>
                                                        </svg>
                                                    </button>
                                                    <a href="<?php echo e(route('appointments.result.create', $appointment->protocol)); ?>" 
                                                        class="btn btn-success px-2" title="Inserir resultado"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" fill="#FFF" viewBox="0 0 48 48">
                                                            <path d="M18.9 35.7 7.7 24.5l2.15-2.15 9.05 9.05 19.2-19.2 2.15 2.15Z"/>
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <!-- MAIS OPÇÕES -->
                                                <div class="btn-group dropleft">
                                                    <button class="btn btn-light rounded-circle p-2 dropdown-toggle" 
                                                        type="button" data-toggle="dropdown" title="Mais opções"
                                                        aria-haspopup="true" aria-expanded="false"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" 
                                                            viewBox="0 0 48 48" stroke-width="4" stroke="black"
                                                        >
                                                            <path d="M24 40q-1 0-1.7-.7t-.7-1.7q0-1 .7-1.7t1.7-.7q1 0 1.7.7t.7 1.7q0 1-.7 1.7T24 40Zm0-13.6q-1 0-1.7-.7t-.7-1.7q0-1 .7-1.7t1.7-.7q1 0 1.7.7t.7 1.7q0 1-.7 1.7t-1.7.7Zm0-13.6q-1 0-1.7-.7t-.7-1.7q0-1 .7-1.7T24 8q1 0 1.7.7t.7 1.7q0 1-.7 1.7t-1.7.7Z"/>
                                                        </svg>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <li>
                                                            <a href="<?php echo e(route('appointments.print', $appointment->protocol)); ?>"
                                                                class="dropdown-item" target="_blank"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" height="15" width="15" viewBox="0 0 48 48">
                                                                    <path d="M35.9 14.1H12.1V6h23.8Zm1.05 9.25q.6 0 1.05-.45.45-.45.45-1.05 0-.6-.45-1.05-.45-.45-1.05-.45-.6 0-1.05.45-.45.45-.45 1.05 0 .6.45 1.05.45.45 1.05.45ZM32.9 39v-9.6H15.1V39Zm3 3H12.1v-8.8H4V20.9q0-2.25 1.525-3.775T9.3 15.6h29.4q2.25 0 3.775 1.525T44 20.9v12.3h-8.1Z"/>
                                                                </svg>
                                                                <span class="ml-2">Imprimir 2ª via do comprovante</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a type="button" class="dropdown-item" 
                                                                href="<?php echo e(route('appointments.edit', $appointment->protocol)); ?>"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" height="15" width="15" viewBox="0 0 48 48">
                                                                    <path d="m39.7 14.7-6.4-6.4 2.1-2.1q.85-.85 2.125-.825 1.275.025 2.125.875L41.8 8.4q.85.85.85 2.1t-.85 2.1Zm-2.1 2.1L12.4 42H6v-6.4l25.2-25.2Z"/>
                                                                </svg>
                                                                <span class="ml-2">Editar atendimento</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="dropdown-item cancel" 
                                                                data-id="<?php echo e($appointment->protocol); ?>" onclick="cancel(<?php echo e($appointment->protocol); ?>)"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" height="15" width="15" viewBox="0 0 48 48">
                                                                    <path d="M6 40.9V16.3q-.7-.1-1.35-1Q4 14.4 4 13.35V7q0-1.15.9-2.075Q5.8 4 7 4h34q1.15 0 2.075.925Q44 5.85 44 7v6.35q0 1.05-.65 1.95-.65.9-1.35 1v24.6q0 1.15-.925 2.125Q40.15 44 39 44H9q-1.2 0-2.1-.975Q6 42.05 6 40.9Zm35-27.55V7H7v6.35Zm-23 13.5h12v-3H18Z"/>
                                                                </svg>
                                                                <span class="ml-2">Cancelar atendimento</span>
                                                            </button>
                                                        </li>
                                                    </div>
                                                </div>
                                                
                                            </td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tbody id="loader" style="display: none;">
                                    <tr>
                                        <td colspan="5">
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
                                    <?php echo e($appointments->links()); ?>

                                </div>
                            </div>

                        </div>
                            
                    </div>
                </div>
            </div>
        </div>

        <?php echo $__env->make('appointments.modal.pending.view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/pages/notification.init.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pages/appointments/script.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hsdrjarques/lab.hospitaldrjarques.com.br/resources/views/appointments/index.blade.php ENDPATH**/ ?>