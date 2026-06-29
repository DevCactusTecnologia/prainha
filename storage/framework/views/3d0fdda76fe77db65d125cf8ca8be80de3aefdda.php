<?php $__env->startSection('title'); ?> Rastreabilidade <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Rastreabilidade <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Rotina <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Rastreabilidade <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-xl-12">
            <div class="card p-3 mb-3">
                <input type="hidden" data-js="url-historic" value="<?php echo e(route('routine.traceability.historic')); ?>">
                <form action="<?php echo e(route('routine.traceability.search')); ?>" data-js="formTraceability" method="POST">
                    <?php echo csrf_field(); ?>

                    
                    <div class="d-md-flex bg-light p-2 mb-3">
                        <div class="col-md-2">
                            <label class="form-label" style="font-weight: 600;">Protocolo</label>
                            <input type="text" class="form-control" data-js="protocol">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label invisible">.</label>
                            <button type="submit" id="searchTraceability" class="btn btn-primary waves-effect form-control">
                                <i class="fa fa-search"></i>
                                <span class="ml-2">Pesquisar</span>
                            </button>
                        </div>
                    </div>

                    
                    <div data-js="container-header"></div>

                    
                    <div data-js="container-table" style="display: none;">
                        <table class="table table-sm table-centered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>EXAME</th>
                                    <th>SOLICITANTE</th>
                                    <th>ANALISTA RESPONSÁVEL</th>
                                    <th class="text-center">DATA DO CADASTRO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody data-js="content-table"></tbody>
                        </table>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div data-js="container-modal"></div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/pages/routine/traceability.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/routine/traceability/index.blade.php ENDPATH**/ ?>