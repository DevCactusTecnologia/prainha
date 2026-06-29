<?php $__env->startSection('title'); ?> Editar Atendimento <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/libs/select2/select2.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Editar Atendimento <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Editar Atendimento <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <?php if(session()->has('status')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Atendimento <strong>alterado</strong> com sucesso!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php echo e(session()->forget('status')); ?>

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

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <input type="hidden" data-js="baseUrl" value="<?php echo e(url('/')); ?>">
                
                <form action="<?php echo e(route('appointments.update', $appointment->id)); ?>" method="POST" class="card-body">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <input type="hidden" name="access_key" value="<?php echo e($appointment->access_key); ?>">
                    
                    
                    <?php if($role != 'patient'): ?>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input type="hidden" id="urlSearchPatient" value="<?php echo e(route('appointment.patient.search')); ?>">
                                <label class="control-label">Paciente <span class="text-danger">*</span></label>
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
                                    <option value="<?php echo e($appointment->patient->id); ?>" selected>
                                        <?php echo e($appointment->patient->first_name); ?> <?php echo e($appointment->patient->patient->patient_social_name); ?>

                                    </option>
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
                                    Solicitante <span class="text-danger">*</span>
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
                                    <option selected disabled>Selecionar</option>
                                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($doctor->id); ?>"
                                            <?php echo e(old('appointment_with', $appointment->doctor->id) == $doctor->id ? 'selected' : ''); ?>

                                        >
                                            <?php echo e($doctor->first_name); ?> <?php echo e($doctor->last_name); ?>

                                        </option>
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
                                <label class="control-label">Convênio<span class="text-danger">*</span></label>
                                <select class="form-control" name="company_id" required>
                                    <option value="">Selecione</option>
                                    <?php if($companies->count() === 1): ?>
                                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($company->id); ?>" <?php if($companies->count() === 1): echo 'selected'; endif; ?>>
                                                <?php echo e($company->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($company->id); ?>"
                                                <?php if(old('company_id', $appointment->company_id) == $company->id): echo 'selected'; endif; ?>
                                            >
                                                <?php echo e($company->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="appointment_with" value="<?php echo e($user->id); ?>" id="doctor">
                    <?php endif; ?>

                    
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label class="control-label">Nº do protocolo</label>
                            <input type="text" class="form-control bg-light" value="<?php echo e($appointment->id); ?>" readonly>
                        </div>
                        <div class="col-md-4 form-group">
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
                                    value="<?php echo e(old('appointment_date', date('d/m/Y', strtotime($appointment->appointment_date)))); ?>"
                                    data-date-autoclose="true" autocomplete="off" data-date-format="dd/mm/yyyy"
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
                                    <option value="<?php echo e($unity->id); ?>"
                                        <?php if(old('unity_id', $appointment->unity_id) == $unity->id): echo 'selected'; endif; ?>
                                    >
                                        <?php echo e($unity->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12 table-resposive">
                            <h5 class="text-primary fw-bold">Exames</h5>
                            <table class="table table-bordered table-centered table-sm table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nº</th>
                                        <th>CÓD</th>
                                        <th>DESCRIÇÃO</th>
                                        <th>ANALISTA</th>
                                        <th style="width: 8%">COLETADO EM</th>
                                        <th class="text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody id="examContent">
                                    <?php $__currentLoopData = $appointment->exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($exam->abbreviation); ?></td>
                                            <td><span style="margin-left: 5px;"><?php echo e($exam->name); ?></span></td>
                                            <td>
                                                <select class="form-control form-select" name="exam_biomedicals[]">
                                                    <option value="">Selecione</option>
                                                    <?php $__currentLoopData = $biomedicals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $biomedical): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($biomedical->id); ?>" style="font-size: 17px;"
                                                            <?php echo e($exam->pivot->biomedical_id == $biomedical->id ? 'selected' : ''); ?>

                                                        >
                                                            <?php echo e($biomedical->first_name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" name="exam_collected_at[]" 
                                                    value="<?php echo e($exam->pivot->collected_at); ?>" required
                                                >
                                            </td>
                                            <td class="text-center">
                                                <?php if($exam->pivot->status == '0'): ?>
                                                    <span style="color: #efc681;" title="Pendente">
                                                        <i class="mdi mdi-information-outline font-size-22 align-middle"></i>
                                                    </span>
                                                <?php endif; ?>

                                                <?php if($exam->pivot->status == '1'): ?>
                                                    <span style="color: #33c38e;" title="Finalizado">
                                                        <i class="mdi mdi-checkbox-marked-circle font-size-22 align-middle"></i>
                                                    </span>
                                                <?php endif; ?>

                                                <?php if($exam->pivot->status == '2' && $exam->pivot->re_test == '1'): ?>
                                                    <div style="display: flex; justify-content: center;">
                                                        <div title="Reteste: <?php echo e($exam->pivot->observation ?: 'NÃO INFORMADO'); ?>"
                                                            class="d-flex justify-content-center align-items-center rounded-circle"
                                                            style="background-color: #ff7e24; color: #fff; width: 19px; height: 19px; cursor: default;"
                                                        >
                                                            R
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if($exam->pivot->status == '2' && $exam->pivot->re_test == '0'): ?>
                                                    <span title="Cancelado: <?php echo e($exam->pivot->observation ?: 'NÃO INFORMADO'); ?>"
                                                        style="color: #ff0000;"
                                                    >
                                                        <i class="mdi mdi-cancel font-size-22 align-middle"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <input type="hidden" name="exam_ids[]" value="<?php echo e($exam->pivot->exam_id); ?>">
                                            <input type="hidden" name="exam_abbreviations[]" value="<?php echo e($exam->abbreviation); ?>">
                                            <input type="hidden" name="exam_names[]" value="<?php echo e($exam->name); ?>">
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td style="width: 9%">
                                            <input type="hidden" id="urlSearchExamAbbreviation" 
                                                value="<?php echo e(route('exams.search.abbreviation')); ?>"
                                            >
                                            <select class="form-control" id="searchExamAbbreviation" 
                                                onchange="changeExamAbbreviationEdit()"
                                            >
                                            </select>
                                        </td>
                                        <td style="width: 40%">
                                            <input type="hidden" id="urlSearchExamName" value="<?php echo e(route('exams.search.name')); ?>" >
                                            <select class="form-control" id="searchExamName" 
                                                onchange="changeExamNameEdit()"
                                            >
                                            </select>
                                        </td>
                                        <td></td>
                                        <td style="width: 9%"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                    </div>

                    
                    <div class="d-md-flex">
                        <div class="col-md-7 pl-md-0">
                            <div class="d-md-flex">
                                <div class="col-md-4 pl-md-0 form-group">
                                    <label class="control-label">Prioridade</label>
                                    <select class="form-control" name="priority_id">
                                        <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($priority->value); ?>" 
                                                <?php if(old('priority_id', $appointment->priority_id?->value) == $priority->value): echo 'selected'; endif; ?>
                                            >
                                                <?php echo e($priority->getName()); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Data de entrega</label>
                                    <input type="date" class="form-control" name="delivery_date" 
                                        value="<?php echo e(old('delivery_date', $appointment->delivery_date)); ?>"
                                    />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Jejum</label>
                                    <select name="fast" class="form-control">
                                        <option value="yes" <?php if(old('fast', $appointment->fast) == 'yes'): echo 'selected'; endif; ?>>Sim</option>
                                        <option value="no" <?php if(old('fast', $appointment->fast) == 'no'): echo 'selected'; endif; ?>>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-md-flex">
                                <div class="col-md-4 pl-md-0 form-group" id="dum_group">
                                    <label class="control-label" title="Data da Última Menstruação">DUM</label>
                                    <input type="date" class="form-control" name="dum" 
                                        value="<?php echo e(old('dum', $appointment->dum)); ?>"
                                    />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Nº da Guia</label>
                                    <input type="number" class="form-control" name="guide_number"
                                        value="<?php echo e($appointment->guide_number); ?>" required
                                    >
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Documentos</label>
                                    <?php if($appointment->documents->isNotEmpty()): ?>
                                        <div class="form-control bg-light d-flex align-items-center" title="Visualizar documentos carregados" 
                                            style="cursor: pointer;" data-toggle="modal" data-target="#show-document"
                                        >
                                            <i class="bx bxs-file font-size-24 mr-2"></i> <?php echo e($appointment->documents->count()); ?>

                                        </div>
                                        <?php echo $__env->make('appointments.modal.document.show', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php else: ?> 
                                        <input type="text" class="form-control bg-light"
                                            value="Não carregados" readonly
                                        > 
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 pr-md-0">
                            <div class="form-group">
                                <label class="control-label">Observações, doenças e medicamentos</label>
                                <textarea class="form-control" name="observation" rows="5"><?php echo e(old('observation', $appointment->observation)); ?></textarea>
                            </div>
                        </div>
                    </div>

                    
                    <div class="d-md-flex justify-content-md-between text-center">
                        <a href="<?php echo e(route('appointments.index')); ?>" class="btn font-weight-medium text-primary rounded-lg">
                            <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>
                            Voltar
                        </a>
                        <button type="submit" id="createAttendance" 
                            class="btn btn-primary font-weight-medium waves-effect px-4 rounded-lg"
                        >
                            Editar Atendimento
                        </button>
                    </div>
                    
                </form>
                
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    
    <script src="<?php echo e(asset('assets/libs/jquery-ui/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/moment/moment.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/select2/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>

    
    <script src="<?php echo e(asset('assets/js/pages/form-advanced.init.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pages/appointments/search.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/appointments/edit.blade.php ENDPATH**/ ?>