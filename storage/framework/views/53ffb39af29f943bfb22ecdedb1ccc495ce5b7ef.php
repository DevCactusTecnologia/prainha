<?php $__env->startSection('title'); ?> <?php echo e(__('Criar Atendimento')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
       
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Criar Atendimento <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Criar Atendimento <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
        
    <div class="row">
        <div class="col-12">
            <a href="<?php echo e(route('appointments.index')); ?>"
                class="btn btn-primary text-white waves-effect waves-light mb-4">
                <i class="mdi mdi-arrow-left  font-size-16 align-middle mr-2"></i> 
                <?php echo e(__('Voltar para a lista de atendimentos')); ?>

            </a>
        </div>
    </div>

    <?php if(session()->has('status')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Atendimento <strong>registrado</strong> com sucesso! Deseja imprimir o comprovante?
            <a href="<?php echo e(route('appointments.print', session()->get('appointment_id'))); ?>" target="_blank"
                class="btn btn-pill btn-success ml-2 rounded rounded-pill text-white py-0 px-3"
            >
                <i class="mdi mdi-printer font-size-22 align-middle"></i>
                Imprimir
            </a>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <?php echo e(session()->forget('status')); ?>

    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                    
                <input type="hidden" data-js="baseUrl" value="<?php echo e(url('/')); ?>">
                <form action="<?php echo e(route('appointments.store')); ?>" method="POST" class="card-body">
                    <?php echo csrf_field(); ?>

                    
                    <?php if($role != 'patient'): ?>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input type="hidden" id="urlSearchPatient" value="<?php echo e(route('appointment.patient.search')); ?>">
                                <label class="control-label"><?php echo e(__('Paciente ')); ?><span class="text-danger">*</span></label>
                                <select class="form-control select2 <?php $__errorArgs = ['appointment_for'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="appointment_for" id="searchPatient" 
                                >
                                </select>
                                <?php $__errorArgs = ['appointment_for'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="appointment_for" value="<?php echo e($user->id); ?>">
                    <?php endif; ?>

                    
                    <?php if($role != 'doctor'): ?>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label">
                                    <?php echo e(__('Solicitante')); ?><span class="text-danger">*</span>
                                </label>
                                <select class="form-control select2 sel-doctor <?php $__errorArgs = ['appointment_with'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="appointment_with" id="doctor"
                                >
                                    <option selected disabled><?php echo e(__('Selecionar')); ?></option>
                                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($doctor->id); ?>"
                                            <?php echo e(old('appointment_with') == $doctor->id ? 'selected' : ''); ?>>
                                            <?php echo e($doctor->first_name); ?>

                                            <?php echo e($doctor->last_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['appointment_with'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="control-label"><?php echo e(__('Convênio')); ?><span class="text-danger">*</span></label>
                                <select class="form-control" name="company_id" required>
                                    <option value="">Selecione</option>
                                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($company->id); ?>"
                                            <?php echo e($companies->count() === 1 ? 'selected' : ''); ?>

                                        >
                                            <?php echo e($company->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="appointment_with" value="<?php echo e($user->id); ?>" id="doctor">
                    <?php endif; ?>

                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                Data do atendimento <span class="text-danger" style="margin-right: 8px;">*</span>
                            </label>
                            <div class="input-group datepickerdiv">
                                <input type="text"
                                    class="form-control appointment-date <?php $__errorArgs = ['appointment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="appointment_date" id="datepicker" data-provide="datepicker"
                                    data-date-format="dd/mm/yyyy" value="<?php echo e(old('appointment_date', date('d/m/Y'))); ?>"
                                    data-date-autoclose="true" autocomplete="off"
                                >
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">
                                Unidade de atendimento<span class="text-danger" style="margin-right: 8px;">*</span>
                            </label>
                            <select class="form-control" name="unity_id" required>
                                <option value="">Selecione</option>
                                <?php $__currentLoopData = $unitys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($unity->id); ?>"><?php echo e($unity->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12 table-resposive">
                            <h6 class="text-primary fw-bold">Exames</h6>
                            <table class="table table-bordered table-centered table-sm table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nº</th>
                                        <th>EXAME</th>
                                        <th>DESCRIÇÃO</th>
                                        <th>BIOMÉDICO</th>
                                        <th>DATA DA COLETA</th>
                                    </tr>
                                </thead>
                                <tbody id="examContent">
                                    <?php if(old('exam_ids')): ?>
                                        <?php $__currentLoopData = old('exam_ids'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e(old('exam_names')[$index]); ?></td>
                                                <td class="d-flex align-items-center">
                                                    <div title="Remover exame" onclick="removeExam(this)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 48 48"
                                                            fill="#b11313" style="cursor: pointer;" 
                                                        >
                                                            <path d="M13.05 42q-1.2 0-2.1-.9-.9-.9-.9-2.1V10.5H8v-3h9.4V6h13.2v1.5H40v3h-2.05V39q0 1.2-.9 2.1-.9.9-2.1.9Zm5.3-7.3h3V14.75h-3Zm8.3 0h3V14.75h-3Z"/>
                                                        </svg>
                                                    </div>
                                                    <span style="margin-left: 5px;"><?php echo e(old('exam_abbreviations')[$index]); ?></span>
                                                </td>
                                                <td>
                                                    <select class="form-control form-select" name="exam_biomedicals[]">
                                                        <option value="">Selecione</option>
                                                        <?php $__currentLoopData = $biomedicals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $biomedical): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($biomedical->id); ?>"
                                                                <?php echo e(old('exam_biomedicals')[$index] == $biomedical->id ? 'selected' : ''); ?>

                                                            >
                                                                <?php echo e($biomedical->first_name); ?> <?php echo e($biomedical->last_name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control" name="exam_collected_at[]" 
                                                        value="<?php echo e(old('exam_collected_at')[$index]); ?>"
                                                    >
                                                </td>

                                                <input type="hidden" name="exam_ids[]" value="<?php echo e($exam); ?>">
                                                <input type="hidden" name="exam_abbreviations[]" value="<?php echo e(old('exam_abbreviations')[$index]); ?>">
                                                <input type="hidden" name="exam_names[]" value="<?php echo e(old('exam_names')[$index]); ?>">
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td style="width: 15%">
                                            <input type="hidden" id="urlSearchExamAbbreviation" 
                                                value="<?php echo e(route('exams.search.abbreviation')); ?>"
                                            >
                                            <select class="form-control" id="searchExamAbbreviation" 
                                                onchange="changeExamAbbreviation()"
                                            >
                                            </select>
                                        </td>
                                        <td style="width: 35%">
                                            <input type="hidden" id="urlSearchExamName" value="<?php echo e(route('exams.search.name')); ?>" >
                                            <select class="form-control" id="searchExamName" 
                                                onchange="changeExamName()"
                                            >
                                            </select>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                    </div>

                    
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label class="control-label"><?php echo e(__('Data de entrega')); ?></label>
                            <input type="date" class="form-control" name="delivery_date" 
                                value="<?php echo e(old('delivery_date', $deliveredAt)); ?>"
                            />
                        </div>
                        <div class="col-md-2 form-group">
                            <label class="control-label"><?php echo e(__('Jejum')); ?></label>
                            <select name="fast" class="form-control">
                                <option value="yes" <?php echo e(old('fast', 'yes') == 'yes' ? 'selected' : ''); ?>>Sim</option>
                                <option value="no" <?php echo e(old('fast') == 'no' ? 'selected' : ''); ?>>Não</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group" id="dum_group">
                            <label class="control-label" title="Data da Última Menstruação"><?php echo e(__('DUM')); ?></label>
                            <input type="date" class="form-control" name="dum"  
                                value="<?php echo e(old('dum')); ?>"
                            />
                        </div>
                        <div class="col-md-2 form-group">
                            <label class="control-label"><?php echo e(__('Urgência')); ?></label>
                            <select name="urgency" class="form-control"
                            >
                                <option value="yes" <?php echo e(old('urgency') == 'yes' ? 'selected' : ''); ?>>Sim</option>
                                <option value="no" <?php echo e(old('urgency', 'no') == 'no' ? 'selected' : ''); ?>>Não</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label class="control-label"><?php echo e(__('Nº da Guia')); ?></label>
                            <input type="text" class="form-control bg-light" value="Automático" 
                                title="O número da guia será gerado automaticamente conforme a unidade de atendimento"
                                style="cursor: help;" readonly
                            >
                        </div>
                        <div class="col-md-2 form-group">
                            <label class="control-label invisible"><?php echo e(__('.')); ?></label>
                            <button type="submit" id="createAttendance" class="form-control btn btn-primary">
                                <?php echo e(__('Criar Atendimento')); ?>

                            </button>
                        </div>
                    </div>
                    
                </form>
                
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <!-- LIBS -->
    <script src="<?php echo e(URL::asset('assets/libs/jquery-ui/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/moment/moment.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/select2/select2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>

    <!-- PAGES -->
    <script src="<?php echo e(URL::asset('assets/js/pages/form-advanced.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/js/pages/appointments/search.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hsdrjarques/lab.hospitaldrjarques.com.br/resources/views/appointments/create.blade.php ENDPATH**/ ?>