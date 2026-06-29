<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Imprimir comprovante de atendimento - <?php echo e($appointment->id); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
    <meta name="robots" content="noindex, nofollow">

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

    
      
    

        <div style="font-family: Arial, Helvetica, sans-serif">
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

            <div style="font-size: 4mm; font-weight: bold;">Solicitação de exames</div>
            <div style="font-size: 3mm;">
                Paciente: <?php echo e($appointment->patient->first_name); ?>

            </div>
            <div style="font-size: 3mm;">
                Convênio: <?php echo e($appointment->company?->name); ?>

            </div>
            <div style="font-size: 3mm;">
                Data do cadastro: <span style="font-family: cursive;"><?php echo e(date('d/m/Y', strtotime($appointment->appointment_date))); ?></span>
            </div>
            <div style="font-size: 3mm;">
                Protocolo: <span style="font-family: cursive;"><?php echo e($appointment->id); ?></span>
            </div>
            <div style="font-size: 3mm; margin-bottom: 10px;">
                Nº da Guia: <span style="font-family: cursive;"><?php echo e($appointment->guide_number); ?></span>
            </div>

            <div style="font-size: 4mm; font-weight: bold;">Exames:</div>
            <?php $__currentLoopData = $appointment->exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="font-size: 3mm; display: flex; justify-content: space-between;">
                    <div class="horizontal-dotted-line"><?php echo e($exam->name); ?></div>
                    <div>1</div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-top: 10px; margin-bottom: 10px;">
                <div style="text-align: center;">
                    <?php if($urlPatient): ?>
                        <div style="margin: 2mm 0mm;"><?php echo QrCode::size(100)->generate($urlPatient); ?></div>
                    <?php endif; ?>
                    <div style="font-size: 3mm;">Consulte o resultado pela chave de acesso em:</div>
                    <div style="font-size: 3mm; word-break: break-word;"><?php echo e(route('patient.result.search.index')); ?></div>
                    <div style="font-size: 4mm;">Chave de acesso: <strong style="font-family: Consolas;"><?php echo e($appointment->access_key); ?></strong></div>
                </div>
            </div>

            <div style="font-size: 4mm; margin-top: 10px; text-align: center;">
                Previsão de entrega: <span style="font-family: cursive;"><?php echo e(date('d/m/Y', strtotime($appointment->delivery_date))); ?> <strong>09:00 horas</strong></span>
            </div>
        </div> 
   
</body>

<script>
    window.print()

    if ('matchMedia' in window) {
        window.matchMedia('print').addListener((mediaQueryListEvent) => {
            if (!mediaQueryListEvent.matches) {
                window.close()
            }
        })
    } else {
        window.onafterprint = function () {
            window.close()
        }
    }
</script>

</html>
<?php /**PATH /home3/hospi580/lab.hospitaldrjarques.com.br/resources/views/appointments/print.blade.php ENDPATH**/ ?>