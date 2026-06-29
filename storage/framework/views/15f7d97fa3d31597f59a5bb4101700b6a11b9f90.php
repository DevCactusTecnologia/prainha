<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Mapa indididual - guia - <?php echo e($appointment->guide_number); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="Sistema para Laboratórios, Clinicas e Hospitais" name="description" />
    <meta content="Sislac" name="author" />
</head>

<body style="font-family: Arial, Helvetica, sans-serif">
    
    <h4 style="text-align: center; margin-bottom: 20px;">
        MAPA INDIVIDUAL - GUIA <strong style="background-color: #CCC;"><?php echo e($appointment->guide_number); ?></strong>
    </h4>

    <div style="margin-bottom: 15px; display: flex;">
        <?php $patient = App\Models\Patient::firstWhere('user_id', $appointment->patient->id); ?>
        <div style="width: 50%;">
            <div style="font-size: 14px;">
                <div>
                    Paciente: <strong><?php echo e("{$appointment->patient->first_name} {$appointment->patient->last_name}"); ?></strong>
                </div>
                <div><strong><?php echo e($patient->gender === 'Female' ? 'F' : 'M'); ?></strong> <?php echo e($patient->age_extended); ?></div>
                <div><strong>Protocolo:</strong> <?php echo e($appointment->id); ?></div>
            </div>
        </div>
    </div>

    <?php $__currentLoopData = $appointment->exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
        <?php if(in_array($exam->id, [347, 367, 546, 709])): ?>
            
            <?php if($exam->id == '347'): ?>
                <?php echo $__env->make('routine.map.patient.inc.hemogram', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            
            <?php if($exam->id == '367'): ?>
                <?php echo $__env->make('routine.map.patient.inc.hiv', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            
            <?php if($exam->id == '546'): ?>
                <?php echo $__env->make('routine.map.patient.inc.fezes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            
            <?php if($exam->id == '709'): ?>
                <?php echo $__env->make('routine.map.patient.inc.urina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
        <?php else: ?> 
            
            <table style="width: 100%; font-size: 11px; border-collapse: collapse; border: 1px solid #000; margin-bottom: 10px;">
                <tr>
                    <td colspan="100" style="border: 1px solid #000; height: 8px; padding-left: 5px; background-color: #e7e6e6; font-weight: 600;"><?php echo e($exam->name); ?></td>
                </tr>
                <tr>
                    <td style="display: flex; flex-wrap: wrap;">
                        <?php $__currentLoopData = $exam->parameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parameter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($parameter->with_printed_map == '1'): ?>
                                <span style="text-align: center; border-right: 1px solid #000;">
                                    <div style="padding: 0px 15px; text-align: center;"><?php echo e(str_replace('##', '', $parameter->parameter)); ?></div>
                                    <div style="border-top: 1px solid #000; height: 1px;"></div>
                                    <div style="padding: 0px 15px; height: 15px; border-bottom: 1px solid #000;"></div>
                                </span>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>

<script>
    window.onafterprint = () => window.close();
    window.print();
</script>

</html>
<?php /**PATH /home/hsdrjarques/lab.hospitaldrjarques.com.br/resources/views/routine/map/patient/print.blade.php ENDPATH**/ ?>