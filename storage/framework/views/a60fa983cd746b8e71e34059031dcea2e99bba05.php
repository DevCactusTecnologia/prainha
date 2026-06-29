<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Resultado - Protocolo - <?php echo e($appointment->id); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
    <meta name="robots" content="noindex,nofollow">

    <style>
        /* @media print {
            @page {
                size: A4;
            }  
        } */

        #background-logo { 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
            opacity: 0.1;
        }
        .bgwidth { 
            width: 100% !important; 
        }
        .bgheight { 
            height: auto !important;
        }
    </style>
</head>

<body>
    
    <img id="background-logo" src="<?php echo e(asset('assets/images/backgroundsb.png')); ?>" width="650px">
    <table style="background-color: #FFF !important;">

        <thead>
            <td>

                
                <div style="font-family: Arial, Helvetica, sans-serif;">
                    <div data-js="header" style="display: flex; margin-bottom: 5px; align-items: center;">
                        <div style="margin-right: 10px;">
                            <img src="<?php echo e(asset('assets/images/brasao.png')); ?>" width="75px" alt="brasão">
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; align-items: flex-start;">
                            <h5 style="font-weight: 700; margin: 1px 0px;">PREFEITURA DE SÃO BENTO</h5>
                            <h5 style="font-weight: 700; margin: 1px 0px;">SECRETARIA MUNICIPAL DE SAÚDE</h5>
                            <h5 style="font-weight: 700; margin: 1px 0px;">LABORATÓRIO MUNICIPAL DE ANÁLISES CLÍNICAS DR. ALICIO ALEXANDRE DA SILVA</h5>
                        </div>
                    </div>
                    <hr>
        
                    <div data-js="patient-data" style="margin-bottom: 15px; display: flex;">
                        <?php $patient = App\Models\Patient::firstWhere('user_id', $appointment->patient->id); ?>
                        <div style="width: 50%;">
                            <div>
                                <div>
                                    Paciente &nbsp;&nbsp;:
                                    <strong><?php echo e($appointment->patient->first_name); ?></strong>
                                </div>
                                <div>
                                    Idade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                    <?php echo e($appointment->patient->patient->age_extended); ?>

                                </div>
                                <div>
                                    Convênio &nbsp;:
                                    <?php echo e($appointment->company?->name); ?>

                                </div>
                                <div>
                                    Solicitante :
                                    <?php echo e($appointment->doctor->first_name); ?>

                                </div>
                            </div>
                        </div>
                        <div style="width: 50%;">
                            <div>
                                <div>
                                    Protocolo &nbsp;:
                                    <?php echo e($appointment->id); ?>

                                </div>
                                <div>
                                    Sexo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                    <?php echo e($appointment->patient->patient->gender_name); ?>

                                </div>
                                <div>
                                    Cadastro &nbsp;&nbsp;:
                                    <?php echo e(date('d/m/Y', strtotime($appointment->appointment_date))); ?>

                                </div>
                                <div>
                                    Conferido &nbsp;:
                                    <?php echo e(date('d/m/Y', strtotime($appointment->checked_at))); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </td>
        </thead>
        
        <?php $counter = 0; ?>

        <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php $counter++; ?>

            <tbody>

                <td>
                    <div class="mb-4" style="font-family: Courier New, Courier, monospace;">

                        <?php if($exam->pivot->status == '1' && $exam->filters->isEmpty()): ?>

                            <?php
                                $content = $exam->exam_editor;

                                if (preg_match('/{{(.*?)}}/', $content, $match) == 1) {
                                    $content = str_replace($match, '', $content);
                                }

                                $results = App\Models\Appointment\Result::with(['parameter'])
                                    ->where('appointment_id', $appointment->id)
                                    ->where('exam_id', $exam->id)
                                    ->get();

                                foreach ($results as $result) {
                                    $input = "{$result->result}";

                                    $content = str_replace($result->parameter->parameter, $input, $content);
                                }

                                $biomedical = App\Models\Biomedical::where('user_id', $exam->pivot->biomedical_id)->first();
                                $userBiomedical = App\Models\User::find($exam->pivot->biomedical_id);
                            ?>

                            <div data-js="exam-name" style="font-size: 18px; background-color: #eff2f7 !important;">
                                <strong><?php echo e($exam->name); ?></strong>
                            </div>
                            <div data-js="content-no-filter" style="display: flex;">
                                <div>
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
                                            AND appointments.checked_at < ?
                                            ORDER BY appointments.checked_at DESC", [
                                                $appointment->appointment_for,
                                                $parameter->id,
                                                $appointment->checked_at,
                                        ]);

                                        foreach ($previousResultsList as $key => $value) {
                                            $previousResults[$value->checked_at][] = [
                                                'result' => $value->result,
                                                'parameter' => $value->parameter,
                                            ];
                                        }
                                    }
                                   
                                }
                                    
                            ?>

                            <?php if(count($previousResults) > 0): ?>
                                <div style="margin-bottom: 10px; margin-top: 10px;">
                                    <div style="background-color: #e7e7e7; font-weight: bold; font-size: 18px; margin-bottom: 10px; padding: 10px; border-radius: 5px;">
                                        Resultados anteriores:
                                    </div>
                                    <div style="font-size: 18px;">
                                        <?php $__currentLoopData = $previousResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checkedAt => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div style="margin-bottom: 5px;">
                                                <span style="font-weight: 600;"><?php echo e(date('d/m/Y', strtotime($checkedAt))); ?>:</span>
                                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span><?php echo e(explode('##', $item['parameter'])[1]); ?> => <?php echo e($item['result']); ?>&nbsp;|&nbsp;</span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <br />
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                                
                            
                                <div data-js="biomedical-name" style="display: flex; float: right;">
                                    <div>Conferido e liberado por:&nbsp;</div>
                                
                                    <div style="font-weight: 600;">
                                        <?php echo e($userBiomedical->first_name); ?>

                                    </div>
                                    
                                </div>
                                
                            

                            <?php if(in_array($exam->id, $contentLong) && ! $loop->last): ?>
                                <div style="page-break-before: always"></div>
                                <?php $counter = 0; ?>
                            <?php endif; ?>

                        <?php elseif($exam->pivot->status == '1' && $exam->filters->isNotEmpty()): ?>

                            <div class="mb-4">

                                <?php
                                    $content = $exam->exam_editor;

                                    foreach ($exam->filters as $filter) {

                                        // SEXO FEMININO (RECÉM-NASCIDO)
                                        if (
                                            ($patient->age_year <= 0) && ($patient->age_month <= 0) &&
                                            ($patient->age_day >= $filter->intial_age_day && $patient->age_day <= $filter->final_age_day) &&
                                            (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
                                        ) {
                                            $content = $filter->exam_editor;
                                            break;

                                        // SEXO MASCULINO (RECÉM-NASCIDO)
                                        } elseif (
                                            ($patient->age_year <= 0) && ($patient->age_month <= 0) &&
                                            ($patient->age_day >= $filter->intial_age_day && $patient->age_day <= $filter->final_age_day) &&
                                            (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
                                        ) {
                                            $content = $filter->exam_editor;
                                            break;
                                            
                                        // CRIANÇAS, JOVENS E ADULTOS
                                        } else {

                                            // SEXO FEMININO
                                            if (
                                                ($patient->age_year >= $filter->intial_age_year && $patient->age_year <= $filter->final_age_year) &&
                                                ($patient->age_month >= $filter->intial_age_month && $patient->age_month <= $filter->final_age_month) &&
                                                (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
                                            ) {
                                                $content = $filter->exam_editor;
                                                break;

                                            // SEXO MASCULINO
                                            } elseif (
                                                ($patient->age_year >= $filter->intial_age_year && $patient->age_year <= $filter->final_age_year) &&
                                                ($patient->age_month >= $filter->intial_age_month && $patient->age_month <= $filter->final_age_month) &&
                                                (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
                                            ) {
                                                $content = $filter->exam_editor;
                                                break;
                                            }
                                        }

                                    }

                                    if (preg_match('/{{(.*?)}}/', $content, $match) == 1) {
                                        $content = str_replace($match[0], '', $content);
                                    }

                                    $results = App\Models\Appointment\Result::with(['parameter'])
                                        ->where('appointment_id', $appointment->id)
                                        ->where('exam_id', $exam->id)
                                        ->get();

                                    foreach ($results as $result) {
                                        $input = "{$result->result}";

                                        $content = str_replace($result->parameter->parameter, $input, $content);
                                    }

                                    $biomedical = App\Models\Biomedical::where('user_id', $exam->pivot->biomedical_id)->first();
                                    $userBiomedical = App\Models\User::find($exam->pivot->biomedical_id);
                                ?>

                                <div data-js="exam-name" style="font-size: 18px; background-color: #eff2f7 !important;">
                                    <strong><?php echo e($exam->name); ?></strong>
                                </div>
                                <div data-js="content-filter" style="display: flex;">
                                    <?php echo $content; ?>

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
                                                AND appointments.checked_at < ?
                                                ORDER BY appointments.checked_at DESC", [
                                                    $appointment->appointment_for,
                                                    $parameter->id,
                                                    $appointment->checked_at,
                                            ]);

                                            foreach ($previousResultsList as $key => $value) {
                                                $previousResults[$value->checked_at][] = [
                                                    'result' => $value->result,
                                                    'parameter' => $value->parameter,
                                                ];
                                            }
                                        }
                                    }

                                ?>

                                <?php if(count($previousResults) > 0): ?>
                                    <div style="margin-bottom: 10px;">
                                        <div style="background-color: #e7e7e7; font-weight: bold; font-size: 15px; margin-bottom: 10px; padding: 10px; border-radius: 5px;">
                                            Resultados anteriores:
                                        </div>
                                        <div>
                                            <?php $__currentLoopData = $previousResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checkedAt => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div style="margin-bottom: 5px;">
                                                    <span style="font-weight: 600;"><?php echo e(date('d/m/Y', strtotime($checkedAt))); ?>:</span>
                                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span><?php echo e(explode('##', $item['parameter'])[1]); ?> => <?php echo e($item['result']); ?>&nbsp;|&nbsp;</span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <br />
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div data-js="biomedical-name" style="display: flex; justify-content: flex-end; align-items: center;">
                                    <div>Conferido e liberado por:&nbsp;</div>
                                    <div style="font-weight: 600;">
                                        <?php echo e($userBiomedical->first_name); ?>

                                    </div>
                                    
                                </div>

                            </div>

                            <?php if(in_array($exam->id, $contentLong) && ! $loop->last): ?>
                                <div style="page-break-before: always"></div>
                                <?php $counter = 0; ?>
                            <?php endif; ?>
                        
                        <?php endif; ?>

                        <?php if($counter >= 4 && ! $loop->last): ?>
                            <div style="page-break-before: always"></div>
                            <?php $counter = 0; ?>
                        <?php endif; ?>
                    
                    </div>

                    <div id="footer" style="display: none; position: absolute; font-family: Arial, Helvetica, sans-serif;">
                        <div style="position: fixed; bottom: 0; width: 100%;">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div style="width: 100%; border-top: 4px solid #deeaf6; margin-bottom: 1px;"></div>
                                <div
                                    style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px; padding: 10px; background-color: #deeaf6;"
                                >
                                    <div style="color: #0070c4; font-weight: 600;">
                                        Loteamento Silvio Cassimiro, Bairro Santo Antônio, PB-293
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </td>

            </tbody>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        

    </table>

</body>

<script>

    const footer = document.getElementById('footer');

    window.onbeforeprint = function() {
        footer.style.display = 'block';
    }

    window.onafterprint = function() {
        footer.style.display = 'none';
        window.close();  
    }

    const bg = document.getElementById('background-logo'); 
    const aspectRatio =  bg.clientWidth / bg.clientHeight;

    window.onresize = function() {		
        if ((window.innerWidth / window.innerHeight) < aspectRatio) {
            bg.className = '';
            bg.classList.add("bgheight");
        } else {
            bg.className = '';
            bg.classList.add("bgwidth");
        }				
    }

    window.print();

    // const header = document.querySelector('[data-js="header"]')
    // header.style.fontSize = '24px';

    // const patientDatas = document.querySelectorAll('[data-js="patient-data"]')
    // Array.from(patientDatas).forEach((patientData) => {
    //     patientData.style.fontSize = '20px';
    // })

    // const contentExamNoFilters = document.querySelectorAll('[data-js="content-no-filter"]')
    // Array.from(contentExamNoFilters).forEach((examNoFilter) => {
    //     examNoFilter.style.fontSize = '21px';
    // })

    // const contentExamFilters = document.querySelectorAll('[data-js="content-filter"]')
    // Array.from(contentExamFilters).forEach((examFilter) => {
    //     examFilter.style.fontSize = '21px';
    // })

    // const examNames = document.querySelectorAll('[data-js="exam-name"]')
    // Array.from(examNames).forEach((examFilter) => {
    //     examFilter.style.fontSize = '22px';
    // })

    // const biomedicalNames = document.querySelectorAll('[data-js="biomedical-name"]')
    // Array.from(biomedicalNames).forEach((biomedicalName) => {
    //     biomedicalName.style.fontSize = '22px';
    // })
</script>   

</html>
<?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/appointments/result/print.blade.php ENDPATH**/ ?>