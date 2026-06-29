<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Mapa do bio - <?php echo e($registers['biomedical_name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
</head>

<body style="font-family: Arial, Helvetica, sans-serif">

    <h4 style="text-align: center; margin-bottom: 20px;">
        MAPA DE TRABALHO - <?php echo e($registers['biomedical_name']); ?>

    </h4>

    <?php $__currentLoopData = $registers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $register): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php if($key === 'patients_map_page_separated'): ?>

                <?php $__currentLoopData = $register; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php $__currentLoopData = $item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examName => $records): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <table style="width: 100%; font-size: 11px; border-collapse: collapse; border: 1px solid #000;">
                            <tr>
                                <td colspan="19" style="padding: 5px; text-align: right; background-color: #F2F2F2; font-weight: 600;">
                                    <?php echo e($examName); ?>, Impresso em <?php echo e(date('d/m/Y H:i:s')); ?> por <?php echo e($user->first_name); ?>

                                </td>
                            </tr>

                            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                
                                <?php if($record['exam_id'] == '347'): ?>
                                    <?php echo $__env->make('routine.map.biomedical.inc.hemogram', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endif; ?>

                                
                                <?php if($record['exam_id'] == '367'): ?>
                                    <?php echo $__env->make('routine.map.biomedical.inc.hiv', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endif; ?>

                                
                                <?php if($record['exam_id'] == '546'): ?>
                                    <?php echo $__env->make('routine.map.biomedical.inc.fezes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endif; ?>

                                
                                <?php if($record['exam_id'] == '709'): ?>
                                    <?php echo $__env->make('routine.map.biomedical.inc.urina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endif; ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>

                        <?php if( (count($item) >= 2) && !array_key_exists('patients_map_normal', $registers)): ?>
                            <div style="page-break-before: always;"></div>
                        <?php endif; ?>  

                        <?php if( (count($item) >= 1) && array_key_exists('patients_map_normal', $registers)): ?>
                            <div style="page-break-before: always;"></div>
                        <?php endif; ?>                
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php endif; ?>

        <?php if($key === 'patients_map_normal'): ?>

            <?php $__currentLoopData = $register; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <table style="width: 100%; font-size: 11px; border-collapse: collapse; border: 1px solid #000;">
                    <tr>
                        <td colspan="19" style="padding: 5px; text-align: right; background-color: #F2F2F2; font-weight: 600;">
                            MAPA DO ANALISTA <?php echo e($registers['biomedical_name']); ?>, Impresso em <?php echo e(date('d/m/Y H:i:s')); ?> por <?php echo e($user->first_name); ?>

                        </td>
                    </tr>

                    <?php $__currentLoopData = $item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patientName => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('routine.map.biomedical.inc.others-exams', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </table>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php endif; ?>
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>

<script>
    window.onafterprint = () => window.close();
    window.print();
</script>

</html>
<?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/routine/map/biomedical/print.blade.php ENDPATH**/ ?>