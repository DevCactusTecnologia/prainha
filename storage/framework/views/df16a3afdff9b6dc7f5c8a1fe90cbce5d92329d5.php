<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Imprimir comprovante de atendimento - <?php echo e($appointment->id); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="Sistema para Laboratórios, Clinicas e Hospitais" name="description" />
    <meta content="Sislac" name="author" />

    <style>
        .horizontal-dotted-line {
            display: flex;
            width: 100%;
        } 
        .horizontal-dotted-line:after {
            border-bottom: 1px dashed black;
            content: '';
            flex: 1;
        }
    </style>
</head>

<body>

    <div style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 25px;">
        <div style="font-size: 2mm; display: flex; justify-content: flex-start; margin-bottom: 5px;">
            <div style="display: flex; justify-content: center; align-items: center; margin-right: 8px;">
                <img src="<?php echo e(asset('assets/images/brasao.png')); ?>" width="30mm" alt="brasão" style="filter: grayscale(1);">
            </div>
            <div style="display: flex; flex-direction: column; justify-content: center;">
                <div>PREFEITURA DE SÃO BENTO</div>
                <div>SECRETARIA MUNICIPAL DE SAÚDE</div>
                <div>LABORATÓRIO M. DE ANÁLISES CLÍNICAS DR. A. ALEXANDRE DA SILVA</div>
            </div>
        </div>
        <hr>

        <div style="display: flex; justify-content: space-between;">
            <div style="display: flex; flex-direction: column;">
                <div style="font-size: 4mm; font-weight: bold;">Solicitação de exames</div>
                <div style="font-size: 3mm;">
                    Paciente: <?php echo e($appointment->patient->first_name); ?>

                </div>
                <div style="font-size: 3mm;">
                    Convênio: <?php echo e($appointment->company?->name); ?>

                </div>
                <div style="font-size: 3mm;">
                    Data do cadastro: <?php echo e(date('d/m/Y', strtotime($appointment->appointment_date))); ?>

                </div>
                <div style="font-size: 3mm; margin-bottom: 10px;">
                    Nº da Guia: <?php echo e($appointment->guide_number); ?>

                </div>
            </div>

            <div style="display: flex; flex-direction: column;">
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-bottom: 10px;">
                    <div style="font-size: 3mm;">Protocolo de atendimento</div>
                    <div style="font-family: Consolas; font-size: 6mm; font-weight: bold;"><?php echo e($appointment->id); ?></div>
                </div>
            </div>
        </div>

        <div style="font-size: 4mm; font-weight: bold;">Exames:</div>
        <?php $__currentLoopData = $appointment->exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="font-size: 3mm; display: flex; justify-content: space-between;">
                <div class="horizontal-dotted-line"><?php echo e($exam->name); ?></div>
                <div>1</div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div style="font-size: 3mm; margin-top: 10px;">
            Previsão de entrega: <?php echo e(date('d/m/Y', strtotime($appointment->delivery_date))); ?>

        </div>

        <div style="font-size: 4mm; margin-top: 10px; font-weight: bold; text-align: center; margin-bottom: 2px;">Resultados online:</div>
        <div style="text-align: center"><?php echo QrCode::size(100)->generate($urlPatient); ?></div>
        <div style="word-break: break-word; text-align: center">Link de acesso: <?php echo e($urlPatient); ?></div>
    </div>

    <div style="display: flex; align-items: center; width: 100%;">
        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 48 48">
            <path d="M39.1 42.3 24.05 27.25 18.2 33.1q.55.85.675 1.65.125.8.125 1.75 0 3.2-2.15 5.35Q14.7 44 11.5 44q-3.2 0-5.35-2.15Q4 39.7 4 36.5q0-3.2 2.15-5.35Q8.3 29 11.5 29q.9 0 1.775.25.875.25 1.825.75l5.8-5.8-5.9-5.9q-.85.4-1.725.55Q12.4 19 11.5 19q-3.2 0-5.35-2.15Q4 14.7 4 11.5q0-3.2 2.15-5.35Q8.3 4 11.5 4q3.2 0 5.35 2.15Q19 8.3 19 11.5q0 .95-.125 1.8-.125.85-.525 1.6l25.7 25.7v1.7Zm-9.15-20.65-3.3-3.3L39.1 5.9h4.95v1.65ZM11.5 16q1.9 0 3.2-1.3 1.3-1.3 1.3-3.2 0-1.9-1.3-3.2Q13.4 7 11.5 7 9.6 7 8.3 8.3 7 9.6 7 11.5q0 1.9 1.3 3.2Q9.6 16 11.5 16Zm12.65 9.15q.4 0 .675-.275t.275-.675q0-.4-.275-.675t-.675-.275q-.4 0-.675.275t-.275.675q0 .4.275.675t.675.275ZM11.5 41q1.9 0 3.2-1.3 1.3-1.3 1.3-3.2 0-1.9-1.3-3.2-1.3-1.3-3.2-1.3-1.9 0-3.2 1.3Q7 34.6 7 36.5q0 1.9 1.3 3.2Q9.6 41 11.5 41Z"/>
        </svg>------------------------------------------------------------------------------------------------------------------------------
    </div>
    
</body>

</html>
<?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/patients/print.blade.php ENDPATH**/ ?>