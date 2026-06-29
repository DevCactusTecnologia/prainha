<?php $__env->startSection('title'); ?> Impressão de etiquetas por unidade <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Impressão de etiquetas por unidade <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Impressão de etiquetas por unidade <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row"> 

        <div class="col-xl-12">

            <input type="hidden" data-js="baseUrl" value="<?php echo e(url('/')); ?>">
            <div data-js="alert" class="alert alert-warning" style="display: none"></div>

            <div class="card p-3 mb-3">
                <form action="<?php echo e(route('routine.tag.by.unity.search')); ?>" 
                    data-js="formTagByUnity" method="POST"
                >
                    <?php echo csrf_field(); ?>
                    <div class="d-md-flex">
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Data inicial</label>
                            <input type="date" class="form-control" data-js="dateStart">
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Data final</label>
                            <input type="date" class="form-control" data-js="dateEnd">
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Unidade de atendimento</label>
                            <select class="form-control" data-js="unityId">
                                <option value="">Selecione</option>
                                <?php $__currentLoopData = $unitys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($unity->id); ?>" 
                                        <?php if(old('gender') == $unity->id): echo 'selected'; endif; ?>
                                    >
                                        <?php echo e($unity->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-4">
                            <label class="form-label invisible">.</label>
                            <button type="submit" data-js="searchTag" 
                                class="btn btn-primary form-control"
                            >
                                <i class="fa fa-search"></i>
                                <span class="ml-2">Buscar</span>
                            </button>
                        </div>
                    </div>
                </form>

                <div data-js="tableContent" style="display: none;">
                    <table class="table table-responsive table-sm table-centered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Unidade de atendimento</th>
                                <th>Total de etiquetas</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody data-js="content"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/pages/routine/tag.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/routine/tag/index.blade.php ENDPATH**/ ?>