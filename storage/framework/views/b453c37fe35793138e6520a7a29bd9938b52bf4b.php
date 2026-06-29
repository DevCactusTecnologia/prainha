<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Atendimentos do Paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>" type="text/css" />
</head>

<body style="background-color: #FFF; padding: 20px;">
    
    <header class="d-flex align-items-center mb-2">
        <div class="mr-2">
            <img src="<?php echo e(asset('assets/images/brasao.png')); ?>" class="img-fluid" width="55px" alt="brasão">
        </div>
        <div class="d-flex flex-column justify-content-center">
            <strong>PREFEITURA DE SÃO BENTO</strong>
            <strong>SECRETARIA MUNICIPAL DE SAÚDE</strong>
            <strong>RESULTADOS DO PACIENTE</strong>
        </div>
    </header>
    <hr>

    <div class="d-md-flex mb-4">
        Olá, &nbsp;<strong><?php echo e($appointments->first()->patient->first_name); ?></strong>, 
        seja bem vind<?php echo e($patient->gender == 'Female' ? 'a' : 'o'); ?> ao painel de atendimentos ao paciente.
    </div>

    <div class="d-flex align-items-center text-primary mb-3">
        <div class="mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#556ee6 " height="25" width="25" viewBox="0 0 48 48">
                <path d="M10 42v-3h10.4v-5.2h-.75q-4.05 0-6.85-2.8T10 24.15q0-3.05 1.75-5.55t4.6-3.5q-.1.75.025 1.5t.475 1.45q-1.85.7-2.85 2.4t-1 3.7q0 2.75 1.95 4.7 1.95 1.95 4.7 1.95H37v3H25.4V39H38v3Zm19-18.35-.7-1.85-2.15.8-1.25-3.4q.7-.7 1.05-1.575.35-.875.35-1.875 0-1.95-1.3-3.375t-3.25-1.625l-1.1-2.95 2.05-.75L22 5.2 25.3 4l.7 1.85 2-.7 5.65 14.75-2.15.75.7 1.85Zm-7.7-5.4q-1.05 0-1.775-.725-.725-.725-.725-1.775 0-1.05.725-1.775.725-.725 1.775-.725 1.05 0 1.775.725.725.725.725 1.775 0 1.05-.725 1.775-.725.725-1.775.725Z"/>
            </svg>
        </div>
        <h3 class="text-primary mb-0" style="font-weight: 600;">Exames realizados</h3>
    </div>

    <div id="accordion">

        <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php
                $patientAgeYear = $patient->ageYear($appointment->appointment_date);
                $patientAgeMonth = $patient->ageMonth($appointment->appointment_date);
                $patientAgeDay = $patient->ageDay($appointment->appointment_date);
            ?>
            
            <div class="mb-2">
                <div class="d-flex justify-content-between align-items-center border py-2 pr-3" id="headingOne"
                    style="background-color: #f7f7f7; !important"
                >
                    <a href="#collapse-<?php echo e($appointment->id); ?>" class="btn btn-link" data-toggle="collapse"
                        aria-expanded="true" aria-controls="collapse-<?php echo e($appointment->id); ?>"
                        onclick="collapse(this, <?php echo e($appointment->id); ?>)"
                    >
                        <div class="d-flex align-items-center">
                            <div name="icon" class="mr-2" onclick="changeIcon(<?php echo e($appointment->id); ?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#888" height="30" width="30" viewBox="0 0 48 48">
                                    <path d="m24 30.75-12-12 2.15-2.15L24 26.5l9.85-9.85L36 18.8Z"/>
                                </svg>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="text-left">Protocolo: <strong><?php echo e($appointment->id); ?></strong></div>
                                <div>Registrado em: <strong><?php echo e(date('d/m/Y', strtotime($appointment->appointment_date))); ?></strong></div>
                            </div>
                        </div>
                    </a>
                    <?php if($appointment->status == '1'): ?>
                        <a href="<?php echo e(route('patient.result.pdf', Crypt::encrypt($appointment->id))); ?>" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#7b6ee6" height="30" width="30" viewBox="0 0 48 48">
                                <path d="M35.9 14.1H12.1V6h23.8Zm1.05 9.25q.6 0 1.05-.45.45-.45.45-1.05 0-.6-.45-1.05-.45-.45-1.05-.45-.6 0-1.05.45-.45.45-.45 1.05 0 .6.45 1.05.45.45 1.05.45ZM32.9 39v-9.6H15.1V39Zm3 3H12.1v-8.8H4V20.9q0-2.25 1.525-3.775T9.3 15.6h29.4q2.25 0 3.775 1.525T44 20.9v12.3h-8.1Z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
        
                <div id="collapse-<?php echo e($appointment->id); ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">

                    <div class="card accordion" id="accordionExams">
                        <?php
                            $exams = $appointment->exams->filter(fn ($exam) =>
                                $exam->pivot->status == '0' || $exam->pivot->status == '1'
                            );
                        ?>
                        <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle bg-light text-body collapsed d-flex align-items-center py-3" 
                                        data-toggle="collapse" data-parent="#accordionExams" href="#exam-<?php echo e($appointment->id); ?>-<?php echo e($exam->id); ?>"
                                    >
                                        <?php if($exam->pivot->status == '1'): ?>
                                            <span class="ml-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="#34c38f" height="25" width="25" viewBox="0 0 48 48">
                                                    <path d="M21.05 33.1 35.2 18.95l-2.3-2.25-11.85 11.85-6-6-2.25 2.25ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 24q0-4.15 1.575-7.8 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24 4q4.15 0 7.8 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z"/>
                                                </svg>
                                            </span>
                                        <?php else: ?>
                                            <span class="ml-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="#f1b44c" height="25" width="25" viewBox="0 0 48 48">
                                                    <path d="M22.65 34h3V22h-3ZM24 18.3q.7 0 1.175-.45.475-.45.475-1.15t-.475-1.2Q24.7 15 24 15q-.7 0-1.175.5-.475.5-.475 1.2t.475 1.15q.475.45 1.175.45ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 23.95q0-4.1 1.575-7.75 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24.05 4q4.1 0 7.75 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z"/>
                                                </svg>
                                            </span>
                                        <?php endif; ?>

                                        <span class="ml-2">
                                            <span class="mr-1" style="font-weight: 600;"><?php echo e($exam->name); ?></span>
                                            <span><?php echo e($exam->pivot->status == '0' ? '(pendente)' : ''); ?></span>
                                        </span>
                                    </a>
                                </div>
                                <div id="exam-<?php echo e($appointment->id); ?>-<?php echo e($exam->id); ?>" class="accordion-body collapse">
                                    <div class="accordion-inner">
                                        <div>
                                            <div class="d-flex">
                                                <div class="content-result-exam" style="overflow-x: scroll;">

                                                    <?php if($exam->pivot->status == '1' && $exam->filters->isEmpty()): ?>

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

                                                            $biomedicalUser = App\Models\User::find($exam->pivot->biomedical_id);
                                                        ?>     

                                                        
                                                        <div class="d-flex">
                                                            <div class="content-result-exam">
                                                                <?php echo $content; ?>

                                                            </div>
                                                        </div>
                                        
                                                        <div class="d-flex align-items-center justify-content-end my-2">
                                                            <div class="mr-2">Conferido e liberado por:</div>
                                                            <div><?php echo e($biomedicalUser->first_name); ?></div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if($exam->pivot->status == '1' && $exam->filters->isNotEmpty()): ?>

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
                
                                                            $biomedicalUser = App\Models\User::find($exam->pivot->biomedical_id);
                                                        ?>
        
                                                        
                                                        <div class="d-flex">
                                                            <div>
                                                                <?php echo $content; ?>

                                                            </div>
                                                        </div>
                                                        
                                                        <div class="d-flex align-items-center justify-content-end my-2">
                                                            <div class="mr-2">Conferido e liberado por:</div>
                                                            <div><?php echo e($biomedicalUser->first_name); ?></div>
                                                        </div>

                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                            
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div> 
                    
                </div>
            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

    <script src="<?php echo e(asset('assets/libs/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/bootstrap/bootstrap.min.js')); ?>"></script>

    <script>
        let isCollapsed = true;
        const icons = document.querySelectorAll('[name="icon"]');

        function collapse(element, protocol) {
            Array.from(icons).forEach(icon => {
                icon.innerHTML = 
                    `<svg xmlns="http://www.w3.org/2000/svg" fill="#888" height="30" width="30" viewBox="0 0 48 48">
                        <path d="m24 30.75-12-12 2.15-2.15L24 26.5l9.85-9.85L36 18.8Z"/>
                    </svg>`;
            });
            
            const iconCurrent = element.firstElementChild.firstElementChild;

            if (isCollapsed) {
                iconCurrent.innerHTML = 
                    `<svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 48 48">
                        <path d="M14.15 30.75 12 28.6l12-12 12 11.95-2.15 2.15L24 20.85Z"/>
                    </svg>`;
                isCollapsed = false;
            } else {
                iconCurrent.innerHTML = 
                    `<svg xmlns="http://www.w3.org/2000/svg" fill="#888" height="30" width="30" viewBox="0 0 48 48">
                        <path d="m24 30.75-12-12 2.15-2.15L24 26.5l9.85-9.85L36 18.8Z"/>
                    </svg>`;
                isCollapsed = true;
            }
        }

        function changeIcon(protocol) {
            $(`#collapse-${protocol}`).collapse('toggle');
        }
    </script>   

</body>

</html>
<?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/web/patients/index.blade.php ENDPATH**/ ?>