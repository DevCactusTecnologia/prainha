<?php $__env->startSection('title'); ?> <?php echo e(__('Impressão geral')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Impressão geral <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Rotina <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Impressão geral <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-xl-12">
            <form class="card p-3 mb-3">
                <input type="hidden" data-js="base-url" value="<?php echo e(url('/')); ?>">
                <input type="hidden" name="url_current" url="<?php echo e(route('routine.appointment.by.day.search')); ?>">
                <?php echo csrf_field(); ?>

                <div class="d-md-flex">
                    <div class="col-md-3 mb-4">
                        <label class="form-label">Data do atendimento</label>
                        <input type="date" id="date" class="form-control">
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label">Unidade de atendimento</label>
                        <select class="form-control" id="unity">
                            <option value="">Selecione</option>
                            <?php $__currentLoopData = $unitys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($unity->id); ?>"><?php echo e($unity->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-4">
                        <label class="form-label invisible">.</label>
                        <button id="search" class="btn btn-primary form-control">
                            <i class="fa fa-search"></i>
                            <span class="ml-2">Buscar</span>
                        </button>
                    </div>
                </div>

                <div id="result" class="pl-2"></div>
            </form>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/pages/routine/print-by-day.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/routine/appointment-by-day/index.blade.php ENDPATH**/ ?>