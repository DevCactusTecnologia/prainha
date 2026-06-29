<?php $__env->startSection('title'); ?> <?php echo e(__('Inserir resultado')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> INSERIR RESULTADO <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('appointments.index')); ?>">Atendimentos</a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_3'); ?> Inserir resultado <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Resultado <strong>salvo</strong> com sucesso!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session()->has('pending')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo session()->get('pending'); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session()->has('finished')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo session()->get('finished'); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php echo e(session()->forget('finished')); ?>

    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <a href="<?php echo e(route('appointments.index')); ?>"
               class="btn btn-primary text-white waves-effect waves-light mb-4"
            >
                <i class="mdi mdi-arrow-left font-size-16 align-middle mr-2"></i> 
                <?php echo e(__('Voltar a lista de atendimentos')); ?>

            </a>
            <?php echo csrf_field(); ?>
        </div>
    </div>

    <style>
        .list-group-item.active {
            background-color: #f8f9fa !important;
            color: #556ee6 !important;
            border: 1px solid #e1dede !important;
            font-weight: 500;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">

            <input type="hidden" data-js="baseUrl" value="<?php echo e(url('/')); ?>">
            <div class="card p-2">

                <div class="d-md-flex" style="background-color: #f8f9fa; border: 1px solid #eff2f7; 
                    border-top-left-radius: 8px; border-top-right-radius: 8px"
                >
                    <div class="col-md-2 p-2" style="border-right: 1px solid #eff2f7;">
                        <div class="d-flex flex-column">
                            <strong class="text-center mb-1 ml-2">Nº do Protocolo</strong>
                            <div class="d-flex ml-2">
                                <div class="bg-light text-center border p-1 w-100"
                                    style="font-size: 22px; font-weight: 600; font-family: monospace;"
                                >
                                    <?php echo e($appointment->id); ?>

                                </div>
                                <input id="protocolNumber" type="hidden" value="<?php echo e($appointment->id); ?>"> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 p-2">
                        <div class="d-flex" style="margin-top: 22px;">
                            <?php 
                                $patient = App\Models\Patient::firstWhere('user_id', $appointment->patient->id); 

                                $patientAgeYear = $patient->ageYear($appointment->appointment_date);
                                $patientAgeMonth = $patient->ageMonth($appointment->appointment_date);
                                $patientAgeDay = $patient->ageDay($appointment->appointment_date);
                            ?>
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <div>
                                        Paciente  &nbsp;:&nbsp;
                                        <strong><?php echo e($appointment->patient->first_name); ?></strong>
                                    </div>
                                    <div>
                                        Idade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                                        <?php echo e($patient->ageExtended($appointment->appointment_date)); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <div>
                                        Sexo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                                        <?php echo e($patient->gender === 'Female' ? 'Feminino' : 'Masculino'); ?>

                                    </div>
                                    <div>
                                        Cadastro &nbsp;:&nbsp;
                                        <?php echo e(date('d/m/Y', strtotime($appointment->appointment_date))); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mt-3 p-2" style="border-left: 1px solid #eff2f7;">

                        <?php if($appointment->status == '1'): ?>
                            <a href="<?php echo e(route('appointments.result.show', $appointment->id)); ?>"
                                class="btn btn-primary px-2 py-0 mr-2" title="Visualizar resultado dos exames"
                            >
                                <i class="mdi mdi-eye font-size-24 align-middle"></i>
                            </a>
                        <?php endif; ?>

                        <?php if(! session()->has('pending') && $appointment->status != '1'): ?>
                            <form method="POST" action="<?php echo e(route('appointments.result.check', $appointment->id)); ?>"
                                style="margin-top: 18px; display: <?php echo e($appointment->results->isEmpty() ? 'none' : 'block'); ?>"
                            >
                                <?php echo csrf_field(); ?>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-success mr-3"
                                        style="display: <?php echo e($appointment->results->isEmpty() ? 'none' : 'block'); ?>"
                                    >
                                        Finalizar
                                    </button>
                                    
                                </div>
                            </form>
                            
                        <?php endif; ?>
                        <?php echo e(session()->forget('pending')); ?>

                    </div>
                    
                </div>

                <div class="d-md-flex mt-3 border-top">

                    <div class="col-2">
                        <div class="list-group" id="list-tab" role="tablist">
                            <?php $__currentLoopData = $appointment->exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a class="d-flex align-items-center list-group-item list-group-item-action px-0 py-1 <?php echo e($loop->first ? 'active' : ''); ?>" 
                                    id="list-<?php echo e($exam->id); ?>-list" data-toggle="list" href="#list-<?php echo e($exam->id); ?>"
                                    role="tab" aria-controls="<?php echo e($exam->id); ?>"
                                    title="Exame <?php if($exam->pivot->status == '0'): ?> pendente
                                        <?php elseif($exam->pivot->status == '1'): ?> verificado
                                        <?php else: ?> cancelado
                                        <?php endif; ?>"
                                    onclick="changeExam(event)"
                                >
                                    <?php if($exam->pivot->status == '0'): ?>
                                        <div class="col-3">
                                            <span style="color: #efc681;">
                                                <i class="mdi mdi-information-outline font-size-22 align-middle"></i>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($exam->pivot->status == '1'): ?>
                                        <div class="col-3">
                                            <span style="color: #33c38e;">
                                                <i class="mdi mdi-checkbox-marked-circle font-size-22 align-middle"></i>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($exam->pivot->status == '2'): ?>
                                        <div class="col-3">
                                            <span style="color: #ff0000;">
                                                <i class="mdi mdi-cancel font-size-22 align-middle"></i>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-5 pl-1"><?php echo e($exam->abbreviation); ?></div>

                                    <?php if($exam->pivot->status == '1'): ?>
                                        <div class="col-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#34c38f" height="24" width="24" viewBox="0 0 48 48"
                                                data-appointment-id="<?php echo e($appointment->id); ?>"  data-exam-id="<?php echo e($exam->id); ?>"
                                            >
                                                <path data-appointment-id="<?php echo e($appointment->id); ?>"  data-exam-id="<?php echo e($exam->id); ?>"
                                                    d="M35.9 14.1H12.1V6h23.8Zm1.05 9.25q.6 0 1.05-.45.45-.45.45-1.05 0-.6-.45-1.05-.45-.45-1.05-.45-.6 0-1.05.45-.45.45-.45 1.05 0 .6.45 1.05.45.45 1.05.45ZM32.9 39v-9.6H15.1V39Zm3 3H12.1v-8.8H4V20.9q0-2.25 1.525-3.775T9.3 15.6h29.4q2.25 0 3.775 1.525T44 20.9v12.3h-8.1Z"/>
                                            </svg>
                                        </div>
                                    <?php else: ?> 
                                        <div class="col-4"></div> 
                                    <?php endif; ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                        <div class="col-10">

                            <div class="tab-content" id="nav-tabContent">

                                <input type="hidden" id="examIds" value="<?php echo e($appointment->exams->pluck('id')->implode(',')); ?>">
                                    
                                    <?php $__currentLoopData = $appointment->exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                        <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>" 
                                            id="list-<?php echo e($exam->id); ?>" data-id="<?php echo e($exam->id); ?>" data-name="<?php echo e($exam->name); ?>"
                                            role="tabpanel" aria-labelledby="list-<?php echo e($exam->id); ?>-list"
                                        >
                                            
                                            <?php if(($exam->pivot->status == '0' || $exam->pivot->status == '2') && $exam->filters->isEmpty()): ?>

                                                <form action="<?php echo e(route('appointments.result.save')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>

                                                    <input type="hidden" name="exam_id" value="<?php echo e($exam->id); ?>">
                                                    <input type="hidden" name="appointment_id" value="<?php echo e($appointment->id); ?>">

                                                    <?php
                                                        $content = $exam->exam_editor;
                                                        $parameters = DB::select(
                                                            "SELECT 
                                                                exams.id, 
                                                                parameters.id AS parameter_id,
                                                                parameters.parameter,
                                                                parameters.type,
                                                                parameters.unit,
                                                                parameters.formula,
                                                                parameters.size,
                                                                parameters.minimum,
                                                                parameters.maximum,
                                                                parameters.decimal_places,
                                                                parameters.required
                                                            FROM exams
                                                            INNER JOIN new_parameter AS parameters
                                                            ON exams.id = parameters.exam_id
                                                            WHERE exams.id = ?", [$exam->id]
                                                        );

                                                        $listParameters = json_decode(json_encode($parameters), true);

                                                        foreach ($listParameters as $item) {
                                                            $input = '';

                                                            $parameter = $item['parameter'];
                                                            $parameterId = $item['parameter_id'];
                                                            $size = $item['size'];

                                                            $minimum = $item['minimum'] ? $item['minimum'] : '0';
                                                            $maximum = $item['maximum'] ? $item['maximum'] : '9999999';
                                                            $decimalPlaces = $item['decimal_places'] ? $item['decimal_places'] : '0';
                                                            
                                                            $formula = $item['formula'];
                                                            $required = $item['required'] == '1' ? 'required' : '';

                                                            $decimalPlaceStep = '';

                                                            if ($decimalPlaces == '0') {
                                                                $decimalPlaceStep = '1';
                                                            }
                                                            
                                                            if ($decimalPlaces == '1') {
                                                                $decimalPlaceStep = '0.1';
                                                            }
                                                            
                                                            if ($decimalPlaces == '2') {
                                                                $decimalPlaceStep = '0.01';
                                                            }

                                                            if ($decimalPlaces == '3') {
                                                                $decimalPlaceStep = '0.001';
                                                            }
                                                            
                                                            if ($item['type'] === 'numeric') {
                                                                $input = "<input type='number' class='form-control' id='$exam->id'
                                                                    data-id='$exam->id' data-parameter='$parameter'
                                                                    min='$minimum' max='$maximum'
                                                                    maxlength='$size' exam-target='$exam->id'
                                                                    data-parameter-id='$parameterId'
                                                                    name='parameter_value[]' step='$decimalPlaceStep'
                                                                    calculate='parameter' {$required}
                                                                    >";
                                                            }

                                                            if ($item['type'] === 'text') {
                                                                $input = "<input type='text' class='form-control' id='$exam->id'
                                                                    data-id='$exam->id' data-parameter='$parameter'
                                                                    exam-target='$exam->id' data-parameter-id='$parameterId' 
                                                                    maxlength='$size' {$required}
                                                                    name='parameter_value[]'
                                                                    >";
                                                            }

                                                            if ($item['type'] === 'formula') {
                                                                $input = "<input type='text' class='form-control bg-light' 
                                                                    id='$exam->id' field='formula-$exam->id' formula='$formula' 
                                                                    data-id='$exam->id' exam-target='$exam->id'
                                                                    data-parameter-id='$parameterId' 
                                                                    data-decimal-place='$decimalPlaces' readonly
                                                                    name='parameter_value[]'
                                                                    >";
                                                            }

                                                            if ($item['type'] === 'abbreviation') {                    
                                                                $input = "<input list='abbreviations-$exam->id' 
                                                                        data-id='$exam->id' exam-target='$exam->id'
                                                                        data-decimal-place='$exam->decimal_places'
                                                                        class='form-control' id='$exam->id' 
                                                                        data-parameter-id='$parameterId'
                                                                        style='width: 100% !important;'
                                                                        name='parameter_value[]'
                                                                    />";
                                                                $input .= "<datalist id='abbreviations-$exam->id'>";

                                                                foreach ($abbreviations as $sigla) {
                                                                    $input .= "<option value='$sigla->abbreviation'>$sigla->code - $sigla->abbreviation</option>";
                                                                }

                                                                $input .= '</datalist>';
                                                            }

                                                            $input .= "<input type='hidden' name='parameter_id[]' value='$parameterId'>";

                                                            $content = str_replace($parameter, $input, $content);
                                                        }

                                                    ?>

                                                    <div style="background-color: #eff2f7;" class="mb-2 p-2">
                                                        <strong><?php echo e($exam->name); ?></strong>
                                                    </div>
                                                    <div class="d-flex">
                                                        <?php echo $content; ?>

                                                    </div>

                                                    <div class="d-flex justify-content-center my-2">
                                                        <button type="submit" class="btn bg-light text-primary" 
                                                            style="font-weight: 600;" onclick="loader(this)"
                                                        >
                                                            Salvar
                                                        </button>
                                                    </div>
                                                </form>
                                    
                                            
                                            <?php elseif(($exam->pivot->status == '0' || $exam->pivot->status == '2') && $exam->filters->isNotEmpty()): ?>
                                            
                                                <form action="<?php echo e(route('appointments.result.save')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>

                                                    <input type="hidden" name="exam_id" value="<?php echo e($exam->id); ?>">
                                                    <input type="hidden" name="appointment_id" value="<?php echo e($appointment->id); ?>">

                                                    <?php
                                                        $content = $exam->exam_editor;

                                                        foreach ($exam->filters as $filter) {

                                                            // SEXO FEMININO (RECÉM-NASCIDO)
                                                            if (
                                                                ($patientAgeYear <= 0) && ($patientAgeMonth <= 0) &&
                                                                ($patientAgeDay >= $filter->intial_age_day && $patientAgeDay <= $filter->final_age_day) &&
                                                                (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
                                                            ) {
                                                                $content = $filter->exam_editor;
                                                                break;

                                                            // SEXO MASCULINO (RECÉM-NASCIDO)
                                                            } elseif (
                                                                (($patientAgeYear <= 0) && ($patientAgeMonth <= 0)) &&
                                                                (($patientAgeDay >= $filter->intial_age_day) && ($patientAgeDay <= $filter->final_age_day)) &&
                                                                (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
                                                            ) {
                                                                $content = $filter->exam_editor;
                                                                break;
                                                                
                                                            // CRIANÇAS, JOVENS E ADULTOS - SEXO FEMININO
                                                            } elseif (
                                                                ($patientAgeYear >= $filter->intial_age_year && $patientAgeYear <= $filter->final_age_year) &&
                                                                ( ((($patientAgeYear * 12) + $patientAgeMonth) >= (($filter->intial_age_year * 12) + $filter->intial_age_month)) && ((($patientAgeYear * 12) + $patientAgeMonth) <= (($filter->final_age_year * 12) + $filter->final_age_month)) ) &&
                                                                (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
                                                            ) {
                                                                $content = $filter->exam_editor;
                                                                break;

                                                            // CRIANÇAS, JOVENS E ADULTOS - SEXO MASCULINO
                                                            } elseif (
                                                                ($patientAgeYear >= $filter->intial_age_year && $patientAgeYear <= $filter->final_age_year) &&
                                                                ( ((($patientAgeYear * 12) + $patientAgeMonth) >= (($filter->intial_age_year * 12) + $filter->intial_age_month)) && ((($patientAgeYear * 12) + $patientAgeMonth) <= (($filter->final_age_year * 12) + $filter->final_age_month)) ) &&
                                                                (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
                                                            ) {
                                                                $content = $filter->exam_editor;
                                                                break;
                                                            }
                                                            
                                                        }

                                                        $parameters = DB::select(
                                                            "SELECT 
                                                                exams.id, 
                                                                parameters.id AS parameter_id,
                                                                parameters.parameter,
                                                                parameters.type,
                                                                parameters.formula,
                                                                parameters.size,
                                                                parameters.required
                                                            FROM exams
                                                            INNER JOIN new_parameter AS parameters
                                                            ON exams.id = parameters.exam_id
                                                            WHERE exams.id = ?", [$exam->id]
                                                        );

                                                        $listParameters = json_decode(json_encode($parameters), true);

                                                        foreach ($listParameters as $item) {
                                                            $input = '';

                                                            $parameter = $item['parameter'];
                                                            $parameterId = $item['parameter_id'];
                                                            $size = $item['size'];
                                                            $formula = $item['formula'];
                                                            $required = $item['required'] == '1' ? 'required' : '';

                                                            if ($item['type'] === 'numeric') {
                                                                $input = "<input type='number' class='form-control' id='$exam->id'
                                                                    data-id='$exam->id' data-parameter='$parameter'
                                                                    maxlength='$size' exam-target='$exam->id'
                                                                    data-parameter-id='$parameterId' 
                                                                    name='parameter_value[]' step='0.01'
                                                                    calculate='parameter' {$required}
                                                                    >";
                                                            }

                                                            if ($item['type'] === 'text') {
                                                                $input = "<input type='text' class='form-control' 
                                                                    id='$exam->id' data-id='$exam->id' 
                                                                    data-parameter='$parameter' exam-target='$exam->id'
                                                                    data-parameter-id='$parameterId' 
                                                                    maxlength='$size' {$required}
                                                                    name='parameter_value[]'
                                                                    >";
                                                            }

                                                            if ($item['type'] === 'formula') {
                                                                $input = "<input type='text' class='form-control bg-light' 
                                                                    id='$exam->id' field='formula-$exam->id' 
                                                                    data-id='$exam->id' exam-target='$exam->id'
                                                                    data-parameter-id='$parameterId' formula='$formula' 
                                                                    data-decimal-place='2' 
                                                                    name='parameter_value[]' readonly
                                                                    >";
                                                            }

                                                            if ($item['type'] === 'abbreviation') {                    
                                                                $input = "<input list='abbreviations-$exam->id' 
                                                                        data-id='$exam->id' exam-target='$exam->id'
                                                                        class='form-control' id='$exam->id' 
                                                                        data-parameter-id='$parameterId'
                                                                        style='width: 100% !important;'
                                                                        name='parameter_value[]'
                                                                    />";
                                                                $input .= "<datalist id='abbreviations-$exam->id'>";

                                                                foreach ($abbreviations as $sigla) {
                                                                    $input .= "<option value='$sigla->abbreviation'>$sigla->code - $sigla->abbreviation</option>";
                                                                }

                                                                $input .= '</datalist>';
                                                            }

                                                            $input .= "<input type='hidden' name='parameter_id[]' value='$parameterId'>";

                                                            $content = str_replace($parameter, $input, $content);
                                                        }

                                                    ?>

                                                    <div style="background-color: #eff2f7;" class="mb-2 p-2">
                                                        <strong><?php echo e($exam->name); ?></strong>
                                                    </div>
                                                    <div class="d-flex">
                                                        <?php echo $content; ?>

                                                    </div>

                                                    <div class="d-flex justify-content-center my-2">
                                                        <button type="submit" class="btn bg-light text-primary" 
                                                            style="font-weight: 600;" onclick="loader(this)"
                                                        >
                                                            Salvar
                                                        </button>
                                                    </div>
                                                </form>

                                            
                                            <?php elseif($exam->pivot->status == '1' && $exam->filters->isNotEmpty()): ?>

                                                <form action="<?php echo e(route('appointments.result.save')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>

                                                    <input type="hidden" name="exam_id" value="<?php echo e($exam->id); ?>">
                                                    <input type="hidden" name="appointment_id" value="<?php echo e($appointment->id); ?>">

                                                    <?php
                                                        $content = $exam->exam_editor;

                                                        foreach ($exam->filters as $filter) {

                                                            // SEXO FEMININO (RECÉM-NASCIDO)
                                                            if (
                                                                ($patientAgeYear <= 0) && ($patientAgeMonth <= 0) &&
                                                                ($patientAgeDay >= $filter->intial_age_day && $patientAgeDay <= $filter->final_age_day) &&
                                                                (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
                                                            ) {
                                                                $content = $filter->exam_editor;
                                                                break;

                                                            // SEXO MASCULINO (RECÉM-NASCIDO)
                                                            } elseif (
                                                                (($patientAgeYear <= 0) && ($patientAgeMonth <= 0)) &&
                                                                (($patientAgeDay >= $filter->intial_age_day) && ($patientAgeDay <= $filter->final_age_day)) &&
                                                                (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
                                                            ) {
                                                                $content = $filter->exam_editor;
                                                                break;
                                                                
                                                            // CRIANÇAS, JOVENS E ADULTOS - SEXO FEMININO
                                                            } elseif (
                                                                ($patientAgeYear >= $filter->intial_age_year && $patientAgeYear <= $filter->final_age_year) &&
                                                                ( ((($patientAgeYear * 12) + $patientAgeMonth) >= (($filter->intial_age_year * 12) + $filter->intial_age_month)) && ((($patientAgeYear * 12) + $patientAgeMonth) <= (($filter->final_age_year * 12) + $filter->final_age_month)) ) &&
                                                                (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
                                                            ) {
                                                                $content = $filter->exam_editor;
                                                                break;

                                                            // CRIANÇAS, JOVENS E ADULTOS - SEXO MASCULINO
                                                            } elseif (
                                                                ($patientAgeYear >= $filter->intial_age_year && $patientAgeYear <= $filter->final_age_year) &&
                                                                ( ((($patientAgeYear * 12) + $patientAgeMonth) >= (($filter->intial_age_year * 12) + $filter->intial_age_month)) && ((($patientAgeYear * 12) + $patientAgeMonth) <= (($filter->final_age_year * 12) + $filter->final_age_month)) ) &&
                                                                (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
                                                            ) {
                                                                $content = $filter->exam_editor;
                                                                break;
                                                            }

                                                        }

                                                        $results = DB::select(
                                                            "SELECT 
                                                                exams.id AS exam_id, 
                                                                parameters.id AS parameter_id,
                                                                parameters.parameter AS parameter_name,
                                                                parameters.type AS parameter_type,
                                                                parameters.unit AS parameter_unit,
                                                                parameters.formula AS parameter_formula,
                                                                parameters.size AS parameter_size,
                                                                parameters.minimum AS parameter_minimum,
                                                                parameters.maximum AS parameter_maximum,
                                                                parameters.decimal_places AS parameter_decimal_places,
                                                                parameters.required AS parameter_required,
                                                                results.result AS result_value
                                                            FROM results
                                                            INNER JOIN appointments
                                                            ON results.appointment_id = appointments.id
                                                            INNER JOIN exams
                                                            ON results.exam_id = exams.id
                                                            INNER JOIN new_parameter AS parameters
                                                            ON results.parameter_id = parameters.id
                                                            WHERE appointments.id = ? AND (results.result_status = 'saved' OR results.result_status = 'confirm')", [$appointment->id]
                                                        );

                                                        $listResults = json_decode(json_encode($results), true);

                                                        foreach ($listResults as $item) {
                                                            $input = '';
                                                            
                                                            $type = $item['parameter_type'];
                                                            $parameterName = $item['parameter_name'];
                                                            $parameterId = $item['parameter_id'];
                                                            $size = $item['parameter_size'];
                                                            $formula = $item['parameter_formula'];
                                                            $required = $item['parameter_required'] == '1' ? 'required' : '';
                                                            $result = $item['result_value'];
        
                                                            if ($type === 'numeric') {
                                                                $input = "<input type='number' class='form-control' id='$exam->id' 
                                                                    value='$result'
                                                                    data-parameter='$parameterName'
                                                                    maxlength='$size' exam-target='$exam->id'
                                                                    data-parameter-id='$parameterId'
                                                                    data-id='$exam->id' 
                                                                    name='parameter_value[]' step='0.01'
                                                                    calculate='parameter' $required
                                                                >";
                                                            }
        
                                                            if ($type === 'text') {
                                                                $input = "<input type='text' class='form-control' id='{$exam->id}'
                                                                    data-parameter='{$parameterName}'
                                                                    exam-target='{$exam->id}'
                                                                    data-parameter-id='{$parameterId}' 
                                                                    value='{$result}' data-id='{$exam->id}'
                                                                    maxlength='{$size}'
                                                                    name='parameter_value[]' {$required}
                                                                >";
                                                            }
        
                                                            if ($type === 'formula') {
                                                                $input = "<input type='text' class='form-control bg-light' 
                                                                    id='{$exam->id}' field='formula-$exam->id' 
                                                                    value='{$result}' data-id='{$exam->id}'
                                                                    formula='{$formula}' exam-target='{$exam->id}'
                                                                    data-parameter-id='{$parameterId}' 
                                                                    data-decimal-place='2'
                                                                    name='parameter_value[]' readonly
                                                                >";
                                                            }
        
                                                            if ($type === 'abbreviation') {                    
                                                                $input = "<input list='abbreviations-{$exam->id}' 
                                                                        style='width: 100% !important;'
                                                                        value='{$result}'
                                                                        data-id='{$exam->id}' exam-target='{$exam->id}'
                                                                        class='form-control' id='{$exam->id}' 
                                                                        data-parameter-id='{$parameterId}'
                                                                        name='parameter_value[]'
                                                                    />";
                                                                $input .= "<datalist id='abbreviations-{$exam->id}'>";
        
                                                                foreach ($abbreviations as $sigla) {
                                                                    $input .= "<option value='$sigla->abbreviation'>$sigla->code - $sigla->abbreviation</option>";
                                                                }
        
                                                                $input .= '</datalist>';
                                                            }

                                                            $input .= "<input type='hidden' name='parameter_id[]' value='{$parameterId}'>";

                                                            $content = str_replace($parameterName, $input, $content);
                                                        }
        
                                                    ?>

                                                    <div style="background-color: #eff2f7;" class="mb-2 p-2">
                                                        <strong><?php echo e($exam->name); ?></strong>
                                                    </div>
                                                    <div class="d-flex">
                                                        <?php echo $content; ?>

                                                    </div>

                                                    <div class="d-flex justify-content-center my-2">
                                                        <button type="submit" class="btn bg-light text-primary" 
                                                            style="font-weight: 600;"
                                                        >
                                                            Atualizar
                                                        </button>
                                                    </div>

                                                </form>

                                            <?php else: ?>

                                                <form action="<?php echo e(route('appointments.result.save')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>

                                                    <input type="hidden" name="exam_id" value="<?php echo e($exam->id); ?>">
                                                    <input type="hidden" name="appointment_id" value="<?php echo e($appointment->id); ?>">

                                                    <?php
                                                        $content = $exam->exam_editor;
                                                        $results = DB::select(
                                                            "SELECT 
                                                                exams.id AS exam_id, 
                                                                parameters.id AS parameter_id,
                                                                parameters.parameter AS parameter_name,
                                                                parameters.type AS parameter_type,
                                                                parameters.unit AS parameter_unit,
                                                                parameters.formula AS parameter_formula,
                                                                parameters.size AS parameter_size,
                                                                parameters.minimum AS parameter_minimum,
                                                                parameters.maximum AS parameter_maximum,
                                                                parameters.decimal_places AS parameter_decimal_places,
                                                                parameters.required AS parameter_required,
                                                                results.result AS result_value
                                                            FROM results
                                                            INNER JOIN appointments
                                                            ON results.appointment_id = appointments.id
                                                            INNER JOIN exams
                                                            ON results.exam_id = exams.id
                                                            INNER JOIN new_parameter AS parameters
                                                            ON results.parameter_id = parameters.id
                                                            WHERE appointments.id = ? AND (results.result_status = 'saved' OR results.result_status = 'confirm')", [$appointment->id]
                                                        );

                                                        $listResults = json_decode(json_encode($results), true);

                                                        foreach ($listResults as $item) {
                                                            $input = '';
                                                            
                                                            $type = $item['parameter_type'];
                                                            $parameterName = $item['parameter_name'];

                                                            $parameterId = $item['parameter_id'];
                                                            $size = $item['parameter_size'];
                                                            $minimum = $item['parameter_minimum'] ? $item['parameter_minimum'] : '0';
                                                            $maximum = $item['parameter_maximum'] ? $item['parameter_maximum'] : '9999999';
                                                            $decimalPlaces = $item['parameter_decimal_places'] ? : '0';
                                                            $formula = $item['parameter_formula'];
                                                            $required = $item['parameter_required'] == '1' ? 'required' : '';
                                                            $result = $item['result_value'];

                                                            $decimalPlaceStep = '';

                                                            if ($decimalPlaces == '0') {
                                                                $decimalPlaceStep = '1';
                                                            }
                                                            
                                                            if ($decimalPlaces == '1') {
                                                                $decimalPlaceStep = '0.1';
                                                            }
                                                            
                                                            if ($decimalPlaces == '2') {
                                                                $decimalPlaceStep = '0.01';
                                                            }

                                                            if ($decimalPlaces == '3') {
                                                                $decimalPlaceStep = '0.001';
                                                            }
                                                            
                                                            if ($type === 'numeric') {
                                                                $input = <<<EOL
                                                                    <input type="number" class="form-control" id="{$exam->id}" 
                                                                        value="{$result}"
                                                                        data-parameter="{$parameterName}"
                                                                        min="{$minimum}" max="{$maximum}"
                                                                        maxlength="{$size}" exam-target="{$exam->id}"
                                                                        data-parameter-id="{$parameterId}"
                                                                        data-id="{$exam->id}" 
                                                                        name="parameter_value[]" step="{$decimalPlaceStep}"
                                                                        calculate="parameter" {$required}
                                                                    >
EOL;
                                                            }
        
                                                            if ($type === 'text') {
                                                                $input = <<<EOL
                                                                    <input type="text" class="form-control" id="{$exam->id}"
                                                                        data-parameter="{$parameterName}"
                                                                        exam-target="{$exam->id}"
                                                                        data-parameter-id="{$parameterId}" 
                                                                        value="{$result}" data-id="{$exam->id}"
                                                                        maxlength="{$size}"
                                                                        name="parameter_value[]" {$required}
                                                                    >
EOL;
                                                            }
        
                                                            if ($type === 'formula') {
                                                                $input = <<<EOL
                                                                <input type="text" class="form-control bg-light" 
                                                                    id="{$exam->id}" field="formula-{$exam->id}" 
                                                                    value="{$result}" data-id="{$exam->id}"
                                                                    formula="{$formula}" exam-target="{$exam->id}"
                                                                    data-parameter-id="{$parameterId}" 
                                                                    data-decimal-place="{$decimalPlaces}"
                                                                    name="parameter_value[]" readonly
                                                                >
EOL;
                                                            }
        
                                                            if ($type === 'abbreviation') {                    
                                                                $input = <<<EOL
                                                                    <input list="abbreviations-{$exam->id}" 
                                                                        style="width: 100% !important;"
                                                                        value="{$result}"
                                                                        data-id="{$exam->id}" exam-target="{$exam->id}"
                                                                        data-decimal-place="{$decimalPlaces}"
                                                                        class="form-control" id="{$exam->id}" 
                                                                        data-parameter-id="{$parameterId}"
                                                                        name="parameter_value[]"
                                                                    />
                                                                    <datalist id="abbreviations-{$exam->id}">
EOL;

                                                                foreach ($abbreviations as $sigla) {
                                                                    $input .= "<option value='{$sigla->abbreviation}'>{$sigla->code} - {$sigla->abbreviation}</option>";
                                                                }
        
                                                                $input .= '</datalist>';
                                                            }

                                                            $input .= "<input type='hidden' name='parameter_id[]' value='{$parameterId}'>";

                                                            $content = str_replace($parameterName, $input, $content);
                                                        }
        
                                                    ?>

                                                    <div style="background-color: #eff2f7;" class="mb-2 p-2">
                                                        <strong><?php echo e($exam->name); ?></strong>
                                                    </div>
                                                    <div class="d-flex">
                                                        <?php echo $content; ?>

                                                    </div>

                                                    <div class="d-flex justify-content-center my-2">
                                                        <button type="submit" class="btn bg-light text-primary" 
                                                            style="font-weight: 600;"
                                                        >
                                                            Atualizar
                                                        </button>
                                                    </div>

                                                </form>

                                            <?php endif; ?>
                                        </div>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                            </div>

                        </div>
                </div>

            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>

    // FUNÇÃO QUE REALIZA O CÁLCULO DOS PARÂMETROS DOS EXAMES
    $('[calculate="parameter"]').blur(() => {

        // PERCORRE TODOS OS IDS DOS EXAMES
        $('#examIds').val().split(',').forEach((examId) => {

            // PERCORRE POR TODAS AS FÓRMULAS QUE PERTEMCEM A UM EXAME EM ESPECÍFICO
            $(`[field="formula-${examId}"]`).each(function () {

                // VARIÁVEIS
                // formulaValue -> VARIÁVEL QUE IRÁ RECEBER O VALOR DA FÓRMULA, EXEMPLO: (##PARAM1##/##PARAM2##+##PARAM3##)
                // expressionNumeric -> VARIÁVEL QUE IRÁ RECEBER O VALOR DA EXPRESSÃO NUMÉRICA, EXEMPLO: (4/4+1)
                // formulaElement -> CONSTANTE QUE IRÁ RECEBER O ELEMENTO FÓRMULA (QUE ESTÁ SENDO PERCORRIDO)
                let expressionNumeric = '';
                let formulaValue = '';
                const formulaElement = $(this);

                // PERCORRE POR TODOS OS PARÂMETROS DOS EXAMES
                $('[data-parameter]').each(function (index) {

                    // SE O INDICE FOR 0, A VARIÁVEL formulaValue VAI RECEBER O VALOR DO ATRIBUTO formula DO ELEMENTO formulaElement
                    // EXEMPLO: formulaValue = (##PARAM1##/##PARAM2##+(##PARAM3##))
                    if (index === 0) {
                        formulaValue = formulaElement.attr('formula').toString();
                    }

                    // A CONSTANTE paramCurrent VAI RECEBER O VALOR DO ATRIBUTO data-parameter DO ELEMENTO DO PARÂMETRO ATUAL
                    // EXEMPLO: ##PARAM1#
                    const paramCurrent = $(this).attr('data-parameter').toString();

                    // COMPARA SE O VALOR DO PARÂMETRO (paramCurrent) ESTÁ CONTIDO NO VALOR DA FÓRMULA (formulaValue)
                    // EXEMPLO: '(##PARAM1##/##PARAM2##+(##PARAM3##))'.includes('##PARAM1##')
                    // (##PARAM1##/##PARAM2##+(##PARAM3##)) POSSUI ##PARAM1## ? SIM
                    if (formulaValue.includes(paramCurrent)) {

                        // SUBSTITUI TODAS AS OCORRÊNCIAS DO VALOR ##PARAM1## PELO VALOR DO PARÂMETRO ATUAL PERCORRIDO
                        // EXEMPLO '(##PARAM1##/##PARAM2##+(##PARAM3##))' ONDE TEM ##PARAM1## TROCAR POR 5
                        // expressionNumeric = (5/##PARAM2##+(##PARAM3##))
                        expressionNumeric = formulaValue.replaceAll(paramCurrent, $(this).val() !== '' ? $(this).val() : '0');

                        // REESCREVE A VARIÁVEL formulaValue QUE AGORA VAI RECEBER O VALOR DA EXPRESSÃO
                        // EXEMPLO: formulaValue = (5/##PARAM2##+(##PARAM3##))
                        formulaValue = expressionNumeric;
                    }

                    // VERIFICA SE A VARIÁVEL expressionNumeric NÃO CONTÉM ##
                    // EXEMPLO: NA PRIMEIRA LISTAGEM expressionNumeric = (5/##PARAM2##+(##PARAM3##))
                    // COM ISSO, SÓ VAI ACESSAR ESTE BLOCO, APÓS AS 3 ITERAÇÕES E A TROCA DO ##PARAM2## E ##PARAM3## POR VALORES NÚMERICOS
                    if (! expressionNumeric.includes('##')) {
                        try {

                            // APÓS TODAS AS ITERAÇÕES, A VARIÁVEL expressionNumeric TERÁ O VALOR DE UMA EXPRESSÃO NUMÉRICA VÁLIDO
                            // EXEMPLO: ##PARAM1# = 5, ##PARAM2# = 5 E ##PARAM3# = 1 
                            // result = '(5/5+(1))' NO FORMATO STRING

                            // A FUNÇÃO eval() CONVERTE O VALOR DO TIPO STRING EM VALOR NÚMRICO CALCULÁVEL
                            // EXEMPLO: eval('(5/5+(1))') = eval((5/5+(1)))
                            // result = 2
                            const result = eval(expressionNumeric);

                            // CONVERTE O VALOR DO ATRIBUTO STRING DE CASAS DECIMAIS DO ELEMENTO FÓRMULA PARA INTEIRO
                            // EXEMPLO: A VARIÁVEL decimalPlaces AO INVÉS DE RECEBER '4' VAI RECEBER O VALOR 4
                            const decimalPlaces = parseInt(formulaElement.attr('data-decimal-place'));

                            // SE NÃO HOUVER CASAS DECIMAIS E O RESULTADO CONTER MAIS DE 3 CARACTERES
                            // EXEMPLO: RESULTADO = 1,400 ==>> RESULTADO = 1.400
                            if (decimalPlaces <= 0 && result.toString().length > 3) {
                                const amount = new Intl.NumberFormat('en-US', {
                                    style: 'decimal'
                                }).format(result);

                                formulaElement.val(amount.replace(',', '.'));

                            // SE HOUVER CASAS DECIMAIS E O RESULTADO CONTER MENOS DE 4 CARACTERES
                            // RESULTADO = 14.0 SERÁ => RESULTADO = 14,0
                            } else {
                                const resultInDecimalPlaces = result
                                    .toFixed(decimalPlaces)
                                    .toString()
                                    .replace('.', ',');

                                formulaElement.val(resultInDecimalPlaces);	
                            }
                        } catch (error) {
                            if (error instanceof SyntaxError) {
                                console.log('Erro de sintaxe!');
                            }
                        }
                    }
                });

            });	

        });
    });

    function loader(button) {
        setTimeout(() => {
            button.innerHTML = `
                <span class="spinner-border spinner-border-sm mr-2" 
                    role="status" aria-hidden="true">
                </span>Aguarde...
            `;
            button.disabled = true;
        }, 10);

        setTimeout(() => {
            button.disabled = false;
            button.innerHTML = `Salvar`;
        }, 7000);
    }

    const baseUrl = document.querySelector('[data-js="baseUrl"]');
    function changeExam({target}) {
        if (target.nodeName == 'svg' || target.nodeName == 'path') {
            const appintmentId = target.dataset.appointmentId;
            const examId = target.dataset.examId;

            window.open(`${baseUrl.value}/appointments/${appintmentId}/exams/${examId}/result`, '_blank');
        }
    }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/appointments/result/create.blade.php ENDPATH**/ ?>