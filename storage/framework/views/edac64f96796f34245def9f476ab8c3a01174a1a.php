<?php $__env->startSection('title'); ?> <?php echo e(__('Novo Exame')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Novo Exame <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> <a href="<?php echo e(url('/')); ?>"><?php echo e(__('Dashboard')); ?></a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('exams.index')); ?>"><?php echo e(__('Exames')); ?></a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_3'); ?> Novo Exame <?php $__env->endSlot(); ?>
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
                    <form action="<?php echo e(route('exams.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        
                        <div class="d-md-flex">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nome do Exame <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" type="text" name="name" 
                                   value="<?php echo e(old('name')); ?>" required 
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Abreviação <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" type="text" name="abbreviation" 
                                    value="<?php echo e(old('abbreviation')); ?>" required 
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-control" name="category" required>
                                    <option value="">Selecione</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->abbreviation); ?>"
                                            <?php if(old('category') == $category->abbreviation): echo 'selected'; endif; ?>
                                        >
                                            <?php echo e($category->abbreviation); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Prazo</label>
                                <input type="number" min="0" class="form-control" name="deadline"  
                                    value="<?php echo e(old('deadline')); ?>" required
                                />
                            </div>
                        </div>

                        
                        <div class="d-md-flex">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Destino</label>
                                <input class="form-control" type="text" name="destiny" value="<?php echo e(old('destiny')); ?>" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">G. Rótulos</label>
                                <input class="form-control" type="text" name="label_group" 
                                    value="<?php echo e(old('label_group')); ?>" 
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Qtd. Etiquetas</label>
                                <input type="number" min="0" class="form-control" name="quantity_label" 
                                    value="<?php echo e(old('quantity_label')); ?>"
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Kit</label>
                                <input type="number" min="0" class="form-control" name="exam_kit" 
                                    value="<?php echo e(old('exam_kit')); ?>" 
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Código</label>
                                <input type="text" class="form-control" name="code" 
                                    value="<?php echo e(old('code')); ?>" 
                                />
                            </div>
                        </div>

                        
                        <div class="d-md-flex">
                            <div class="col-md-12 mb-3">
                                <label class="form-label" style="font-weight: bold;">EDITOR DE TEXTO</label>
                                <textarea id="summery-ckeditor" name="exam_editor"><?php echo e(old('exam_editor')); ?></textarea>
                            </div>
                        </div>
                        
                        <p class="text-right">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/libs/inputmask/jquery.inputmask.min.js')); ?>"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.1/classic/ckeditor.js"></script>
    <script>
        $('[name="code"]').inputmask({
            mask: [{'mask': '##.##.##.###-#'}], 
            greedy: false, 
            definitions: {'#': {validator: '[0-9]', cardinality: 1}} 
        });

        ClassicEditor
            .create(document.querySelector('#summery-ckeditor'))
            .then(function (editor) {
                editor.ui.view.editable.element.style.height = '100px';
            })
            .catch(error => {
                console.error( error );
            });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/hospi580/lab.hospitaldrjarques.com.br/resources/views/exams/create.blade.php ENDPATH**/ ?>