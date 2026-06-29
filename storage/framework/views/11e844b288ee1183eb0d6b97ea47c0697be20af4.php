<?php $__env->startSection('title'); ?> Mapa por analista <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Mapa por analista <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Rotina <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Mapa por analista <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-xl-12">
            <div class="card p-3 mb-3">
                <form action="<?php echo e(route('routine.map.biomedical.search')); ?>" id="formMapBiomedical" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="d-md-flex">
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Data do atendimento</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="col-md-2 mb-4">
                            <label class="form-label invisible">.</label>
                            <button type="submit" id="searchExamByBiomedical" class="btn btn-primary form-control">
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
                                <th>Analista</th>
                                <th>Exames a serem analidados</th>
                                <th>Data do atendimento</th>
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
    <script src="<?php echo e(asset('assets/js/pages/routine/biomedical.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/routine/map/biomedical/index.blade.php ENDPATH**/ ?>