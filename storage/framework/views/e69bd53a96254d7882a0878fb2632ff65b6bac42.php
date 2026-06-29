<?php $__env->startSection('title'); ?> <?php echo e(__('Mapa individual')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Mapa individual <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Rotina <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Mapa por paciente <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-xl-12">
            <div class="card p-3 mb-3">
                <form action="<?php echo e(route('routine.map.patient.search')); ?>" id="formMapPatient" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="d-md-flex">
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Data do atendimento</label>
                            <input type="date" id="date" class="form-control">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Nome do paciente</label>
                            <input type="text" id="patient" class="form-control">
                        </div>
                        <div class="col-md-2 mb-4">
                            <label class="form-label invisible">.</label>
                            <button type="submit" id="searchPatient" class="btn btn-primary form-control">
                                <i class="fa fa-search"></i>
                                <span class="ml-2">Buscar</span>
                            </button>
                        </div>
                    </div>
                </form>

                <div id="contentTable" style="display: none;">
                    <table class="table table-sm table-centered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Protocolo</th>
                                <th>Paciente</th>
                                <th>CPF</th>
                                <th class="text-center">Data do atendimento</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="contentMap"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/pages/routine/patient.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u444904474/domains/sislac.com.br/public_html/prainha.sislac.com.br/resources/views/routine/map/patient/index.blade.php ENDPATH**/ ?>