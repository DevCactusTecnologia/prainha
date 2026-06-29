<?php $__env->startSection('title'); ?> <?php echo e(__('Ver resultado')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> RESULTADO DO EXAME <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> Resultado do exame <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <a href="<?php echo e(route('appointments.result.create', $appointment->id)); ?>"
               class="btn btn-primary text-white waves-effect waves-light mb-4"
            >
                <i class="mdi mdi-arrow-left font-size-16 align-middle mr-2"></i> 
                <?php echo e(__('Voltar a lista de exames')); ?>

            </a>
            
            <a href="<?php echo e(route('appointments.result.pdf', $appointment->id)); ?>" 
                class="btn btn-success text-white ml-2 mb-4 py-0" target="_blank"
            >
                <i class="mdi mdi-printer font-size-24 align-middle"></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="card p-2">

                <div class="d-md-flex mt-3 border-top">

                    <div class="col-md-2">
                        <div class="text-center my-3">
                            <strong>Exames realizados</strong>   
                        </div>
                        <div class="list-group" id="list-tab" role="tablist">
                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a class="list-group-item list-group-item-action text-center <?php echo e($loop->first ? 'active' : ''); ?>" 
                                    id="list-<?php echo e($exam->id); ?>-list" data-toggle="list" href="#list-<?php echo e($exam->id); ?>"
                                    role="tab" aria-controls="<?php echo e($exam->id); ?>"
                                >
                                    <?php echo e($exam->abbreviation); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="col-md-10">

                        <header class="d-flex mb-2">
                            <div class="mr-3">
                                <img src="<?php echo e(asset('assets/images/brasao.png')); ?>" width="80px" alt="brasão">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <strong>PREFEITURA DE SÃO BENTO</strong>
                                <strong>SECRETARIA MUNICIPAL DE SAÚDE</strong>
                                <strong>LABORATÓRIO MUNICIPAL DE ANÁLISES CLÍNICAS DR. ALICIO ALEXANDRE DA SILVA</strong>
                            </div>
                        </header>
                        <hr>

                        <div class="d-md-flex">
                            <?php 
                                $patient = App\Models\Patient::firstWhere('user_id', $appointment->patient->id); 

                                $patientAgeYear = $patient->ageYear($appointment->appointment_date);
                                $patientAgeMonth = $patient->ageMonth($appointment->appointment_date);
                                $patientAgeDay = $patient->ageDay($appointment->appointment_date);
                            ?>

                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <div>
                                        Paciente &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                                        <strong><?php echo e($appointment->patient->first_name); ?></strong>
                                    </div>
                                    <div>
                                        Idade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                                        <?php echo e($patient->ageExtended($appointment->appointment_date)); ?>

                                    </div>
                                    <div>
                                        Convênio &nbsp;&nbsp;&nbsp;:&nbsp;
                                        <?php echo e($appointment->company?->name); ?>

                                    </div>
                                    <div>
                                        Solicitante &nbsp;:&nbsp;
                                        <?php echo e($appointment->doctor->first_name); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <div>
                                        Protocolo &nbsp;&nbsp;:&nbsp;
                                        <?php echo e($appointment->id); ?>

                                    </div>
                                    <div>
                                        Sexo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                                        <?php echo e($patient->gender_name); ?>

                                    </div>
                                    <div>
                                        Cadastro &nbsp;&nbsp;:&nbsp;
                                        <?php echo e(date('d/m/Y', strtotime($appointment->appointment_date))); ?>

                                    </div>
                                    <div>
                                        Conferido &nbsp;:&nbsp;
                                        <?php echo e(date('d/m/Y', strtotime($appointment->checked_at))); ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content" id="nav-tabContent">
                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php if($exam->pivot->status == '1' && $exam->filters->isEmpty()): ?>
                                    <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>" 
                                        id="list-<?php echo e($exam->id); ?>" data-id="<?php echo e($exam->id); ?>" data-name="<?php echo e($exam->name); ?>"
                                        role="tabpanel" aria-labelledby="list-<?php echo e($exam->id); ?>-list"
                                    >
                                        <?php
                                            $content = $exam->exam_editor;
                                            $results = App\Models\Appointment\Result::with(['parameter'])
                                                ->where('appointment_id', $appointment->id)
                                                ->where('exam_id', $exam->id)
                                                ->get();

                                            foreach ($results as $result) {
                                                $input = "{$result->result}";

                                                $content = str_replace($result->parameter->parameter, $input, $content);
                                            }

                                            $biomedical = App\Models\User::find($exam->pivot->biomedical_id);
                                        ?>

                                        <div style="background-color: #eff2f7;" class="my-3 p-2">
                                            <strong><?php echo e($exam->name); ?></strong>
                                        </div>
                                        <div class="d-flex">
                                            <div class="content-result-exam">
                                                <?php echo $content; ?>

                                            </div>
                                        </div>

                                        
                                        <?php
                                            $previousResults = [];

                                            foreach ($exam->parameters as $parameter) {
                                                if ($parameter->with_previous_result == '1') {
                                                    $previousResultsList = DB::select(
                                                        "SELECT 
                                                            appointments.checked_at,
                                                            results.result,
                                                            new_parameter.parameter

                                                        FROM results

                                                        INNER JOIN appointments
                                                        ON results.appointment_id = appointments.id

                                                        INNER JOIN new_parameter
                                                        ON results.parameter_id = new_parameter.id

                                                        INNER JOIN users
                                                        ON appointments.appointment_for = users.id

                                                        WHERE users.id = ? AND new_parameter.id = ?
                                                        ORDER BY appointments.checked_at DESC", [
                                                            $appointment->appointment_for,
                                                            $parameter->id,
                                                    ]);

                                                    $previousResults[] = json_decode(json_encode($previousResultsList), true);
                                                }
                                            }

                                            $previousResultsCollection = collect($previousResults)
                                                ->collapse()
                                                ->groupBy('checked_at')
                                                ->forget($appointment->checked_at);
                                        ?>

                                        <?php if($previousResultsCollection->isNotEmpty()): ?>
                                            <div class="mb-3">
                                                <div class="rounded p-2 mb-3" style="background-color: #e7e7e7; font-weight: bold; font-size: 15px;">
                                                    Resultados anteriores:
                                                </div>
                                                <div class="d-flex flex-wrap">
                                                    <?php $__currentLoopData = $previousResultsCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checkedAt => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="d-flex flex-column flex-wrap border-primary p-2 mb-3 mr-3" 
                                                            style="border: 2px solid #000"
                                                        >
                                                            <div class="border-bottom border-primary mb-2">
                                                                <span style="font-weight: 600;">Data:</span> 
                                                                <span><?php echo e(date('d/m/Y', strtotime($checkedAt))); ?></span>
                                                            </div>
                                                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div><?php echo e(explode('##', $item['parameter'])[1]); ?> => <?php echo e($item['result']); ?></div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="d-flex align-items-center justify-content-end my-2">
                                            <div>Conferido e liberado por: <?php echo e($biomedical?->first_name); ?></div>
                                        </div>
                                    </div>

                                <?php else: ?>
                                    
                                    <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>" 
                                        id="list-<?php echo e($exam->id); ?>" data-id="<?php echo e($exam->id); ?>" data-name="<?php echo e($exam->name); ?>"
                                        role="tabpanel" aria-labelledby="list-<?php echo e($exam->id); ?>-list"
                                    >
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

                                            $results = App\Models\Appointment\Result::with(['parameter'])
                                                ->where('appointment_id', $appointment->id)
                                                ->where('exam_id', $exam->id)
                                                ->get();

                                            foreach ($results as $result) {
                                                $input = "{$result->result}";

                                                $content = str_replace($result->parameter->parameter, $input, $content);
                                            }

                                            $biomedical = App\Models\User::find($exam->pivot->biomedical_id);
                                        ?>

                                        <div style="background-color: #eff2f7;" class="my-3 p-2">
                                            <strong><?php echo e($exam->name); ?></strong>
                                        </div>
                                        <div class="d-flex">
                                            <div><?php echo $content; ?></div>
                                        </div>

                                        
                                        <?php
                                            $previousResults = [];

                                            foreach ($exam->parameters as $parameter) {
                                                if ($parameter->with_previous_result == '1') {
                                                    $previousResultsList = DB::select(
                                                        "SELECT 
                                                            appointments.checked_at,
                                                            results.result,
                                                            new_parameter.parameter

                                                        FROM results

                                                        INNER JOIN appointments
                                                        ON results.appointment_id = appointments.id

                                                        INNER JOIN new_parameter
                                                        ON results.parameter_id = new_parameter.id

                                                        INNER JOIN users
                                                        ON appointments.appointment_for = users.id

                                                        WHERE users.id = ? AND new_parameter.id = ?
                                                        ORDER BY appointments.checked_at DESC", [
                                                            $appointment->appointment_for,
                                                            $parameter->id,
                                                    ]);

                                                    $previousResults[] = json_decode(json_encode($previousResultsList), true);
                                                }
                                            }

                                            $previousResultsCollection = collect($previousResults)
                                                ->collapse()
                                                ->groupBy('checked_at')
                                                ->forget($appointment->checked_at);
                                        ?>

                                        <?php if($previousResultsCollection->isNotEmpty()): ?>
                                            <div class="mb-3">
                                                <div class="rounded p-2 mb-3" style="background-color: #e7e7e7; font-weight: bold; font-size: 15px;">
                                                    Resultados anteriores:
                                                </div>
                                                <div class="d-flex flex-wrap">
                                                    <?php $__currentLoopData = $previousResultsCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checkedAt => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="d-flex flex-column flex-wrap border-primary p-2 mb-3 mr-3" 
                                                            style="border: 2px solid #000"
                                                        >
                                                            <div class="border-bottom border-primary mb-2">
                                                                <span style="font-weight: 600;">Data:</span> 
                                                                <span><?php echo e(date('d/m/Y', strtotime($checkedAt))); ?></span>
                                                            </div>
                                                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div><?php echo e(explode('##', $item['parameter'])[1]); ?> => <?php echo e($item['result']); ?></div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="d-flex align-items-center justify-content-end my-2">
                                            <div>Conferido e liberado por: <?php echo e($biomedical?->first_name); ?></div>
                                        </div>
                                    </div>
                                    
                                <?php endif; ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hsdrjarques/lab.hospitaldrjarques.com.br/resources/views/appointments/result/show.blade.php ENDPATH**/ ?>