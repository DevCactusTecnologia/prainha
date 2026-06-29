<?php $__env->startSection('title'); ?> Novo modelo de laudo <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Novo modelo de laudo <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> <a href="<?php echo e(url('/')); ?>">Dashboard</a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('exams.edit', $exam->id)); ?>">Editar Exame</a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_3'); ?> Novo modelo de laudo <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <form action="<?php echo e(route('exams.models.store', $exam->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            
                            <div class="d-md-flex">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nome do Exame</label>
                                    <input type="text" class="form-control bg-light" 
                                        value="<?php echo e($exam->name); ?>" readonly 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Abreviação</label>
                                    <input type="text" class="form-control bg-light"
                                        value="<?php echo e($exam->abbreviation); ?>" readonly 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Categoria</label>
                                    <input type="text" class="form-control bg-light"
                                        value="<?php echo e($exam->category); ?>" readonly 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Prazo</label>
                                    <input type="text" class="form-control bg-light"
                                        value="<?php echo e($exam->deadline); ?>" readonly
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Destino</label>
                                    <input type="text" class="form-control bg-light" 
                                        value="<?php echo e($exam->destiny); ?>" readonly
                                    />
                                </div>
                            </div>

                            
                            <div class="d-md-flex">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Nome do modelo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="name"
                                        value="<?php echo e(old('name')); ?>" required 
                                    />
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Observações</label>
                                    <input type="text" class="form-control" name="observation"
                                        value="<?php echo e(old('observation')); ?>" 
                                    />
                                </div>
                            </div>

                            
                            <div class="d-md-flex mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" style="font-weight: bold;">
                                        EDITOR DE TEXTO <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="summery-ckeditor" class="form-control" 
                                        name="exam_editor" required><?php echo e(old('exam_editor')); ?></textarea>
                                </div>
                            </div>

                            <div class="d-md-flex justify-content-md-end mb-3">
                                <div class="col-md-3 d-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary" onclick="loader(this)">
                                        Registrar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>
    
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('ckeditor/ckeditor.js')); ?>"></script>
    <script>
        CKEDITOR.replace('summery-ckeditor');

        function loader(button) {
            setTimeout(() => {
                button.innerHTML = ( 
                    `<span class="spinner-border spinner-border-sm mr-2" 
                        role="status" aria-hidden="true">
                    </span>Aguarde...`
                );
                button.disabled = true;
            }, 10);

            setTimeout(() => {
                button.disabled = false;
                button.innerText = 'Registrar';
            }, 7000);
        }
    
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/exams/models/create.blade.php ENDPATH**/ ?>