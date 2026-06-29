<?php $__env->startSection('title'); ?> Editar dados do Exame <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Editar dados do Exame <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> <a href="<?php echo e(url('/')); ?>"><?php echo e(__('Dashboard')); ?></a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('exams.index')); ?>"><?php echo e(__('Exames')); ?></a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_3'); ?> Editar dados do Exame <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    
    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo session()->get('success'); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php echo e(session()->forget('success')); ?>

    <?php endif; ?>

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
                    <div class="detail_box">
                        <form action="<?php echo e(route('exams.update', $exam->id)); ?>" name="examform" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <input type="hidden" name="id" value="<?php echo e($exam->id); ?>" id="form_id" />

                            
                            <div class="d-md-flex">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nome do Exame <span class="text-danger">*</span></label>
                                    <input class="form-control text-uppercase" type="text" name="name" 
                                    value="<?php echo e(old('name', $exam->name)); ?>" required 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Abreviação <span class="text-danger">*</span></label>
                                    <input class="form-control text-uppercase" type="text" name="abbreviation" 
                                        value="<?php echo e(old('abbreviation', $exam->abbreviation)); ?>" required 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Categoria <span class="text-danger">*</span></label>
                                    <select class="form-control" name="category" required>
                                        <option value="">Selecione</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->abbreviation); ?>"
                                                <?php if(old('category', $exam->category) == $category->abbreviation): echo 'selected'; endif; ?>
                                            >
                                                <?php echo e($category->abbreviation); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Prazo</label>
                                    <input type="number" min="0" class="form-control" name="deadline"  
                                        value="<?php echo e(old('deadline', $exam->deadline)); ?>" required
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Destino</label>
                                    <input class="form-control" type="text" name="destiny" 
                                        value="<?php echo e(old('destiny', $exam->destiny)); ?>" />
                                </div>
                            </div>

                            
                            <div class="d-md-flex">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">G. Rótulos</label>
                                    <input class="form-control" type="text" name="label_group" 
                                        value="<?php echo e(old('label_group', $exam->label_group)); ?>" 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Qtd. Etiquetas</label>
                                    <input type="number" min="0" class="form-control" name="quantity_label" 
                                        value="<?php echo e(old('quantity_label', $exam->quantity_label)); ?>"
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Kit</label>
                                    <input type="number" min="0" class="form-control" name="exam_kit" 
                                        value="<?php echo e(old('exam_kit', $exam->exam_kit)); ?>" 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Código</label>
                                    <input type="text" class="form-control" name="code" 
                                        value="<?php echo e(old('code', $exam->code)); ?>" 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="is_active" required>
                                        <option value="1" <?php if($exam->is_active == '1'): echo 'selected'; endif; ?>>
                                            Ativo
                                        </option>
                                        <option value="0" <?php if($exam->is_active == '0'): echo 'selected'; endif; ?>>
                                            Inativo
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Modelo <span class="text-danger">*</span></label>
                                    <select class="form-control" name="model_id" required>
                                        <option value="">Selecione</option>
                                        <?php $__currentLoopData = $exam->models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($model->id); ?>"
                                                <?php if(old('model_id', $exam->model_id) == $model->id): echo 'selected'; endif; ?>
                                            >
                                                <?php echo e($model->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-9 text-right">
                                    <div class="form-group d-inline-flex">
                                        <button type="button" class="btn waves-effect waves-light mb-2" 
                                            data-toggle="modal" data-target="#listFilterModal" 
                                            data-backdrop="static" data-keyboard="false" 
                                            style="color: black;background-color: #e3ebf2;"
                                        >
                                            <i class="bx bx-hash font-size-16 align-middle mr-2"></i> <?php echo e(__('Filtros')); ?>

                                        </button>
                                    </div>
                                    <div class="form-group d-inline-flex">
                                        <button type="button" class="btn waves-effect waves-light mb-2" 
                                            data-toggle="modal" data-target="#listParameterModal" data-backdrop="static" 
                                            data-keyboard="false" style="color: black;background-color: #e3ebf2;"
                                        >
                                            <i class="bx bx-hash font-size-16 align-middle mr-2"></i> <?php echo e(__('Parâmetros do Exame')); ?>

                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-md-flex justify-content-md-end mb-3">
                                <button type="submit" class="btn btn-primary">
                                    Atualizar
                                </button>
                            </div>

                            <div class="mb-2" style="font-weight: bold;">EDITOR DE TEXTO</div>
                            <textarea id="summery-ckeditor" name="exam_editor"><?php echo e(old('exam_editor', $exam->exam_editor)); ?></textarea>

                            
                        </form>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="d-md-flex align-items-center justify-content-md-between mb-3">
                        <h4 class="text-primary mb-0">Modelos de laudos</h4>
                        <a href="<?php echo e(route('exams.models.create', $exam->id)); ?>" 
                            class="btn btn-sm btn-primary rounded-pill px-3 py-2"
                        >
                            <i class="mdi mdi-plus fs-2"></i>
                            Novo laudo
                        </a>
                    </div>
                    
                    <table class="table table-sm table-centered table-bordered table-hover dt-responsive nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>Nº</th>
                                <th>Nome</th>
                                <th>Data de registro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>                           
                        <tbody>
                            <?php $__currentLoopData = $exam->models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($model->name); ?></td>
                                    <td><?php echo e($model->created_at?->format('d/m/Y')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('exams.models.edit', [$exam->id, $model->id])); ?>" title="Editar modelo"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                        >
                                            <i class="mdi mdi-lead-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    <input type="hidden" id="baseUrl" value="<?php echo e(url('/')); ?>">
     
    
    <?php echo $__env->make('exams.modal.parameters.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('exams.modal.parameters.save', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('exams.modal.filters.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('exams.modal.filters.save', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
    
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('ckeditor/ckeditor.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pages/exams/parameter-filter.js')); ?>"></script>

    <script src="<?php echo e(asset('assets/libs/inputmask/jquery.inputmask.min.js')); ?>"></script>
    <script>
        $('[name="code"]').inputmask({
            mask: [{'mask': '##.##.##.###-#'}], 
            greedy: false, 
            definitions: {'#': {validator: '[0-9]', cardinality: 1}} 
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/exams/edit.blade.php ENDPATH**/ ?>