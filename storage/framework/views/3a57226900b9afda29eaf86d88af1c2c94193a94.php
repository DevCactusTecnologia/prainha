
    <tr style="text-align: center; font-weight: 600; padding: 0px">
        <td style="width: 1%; border: 1px solid #000;"><?php echo e($loop->iteration); ?></td>
        <td style="width: 15%; border: 1px solid #000; background-color: #FFF; text-align: left; font-weight: 500;">
            <div style="padding: 5px;">
                <div style="display: flex; justify-content: space-between; font-size: 9px;">
                    <div><strong>PROTOCOLO:</strong> <?php echo e($record['protocol']); ?></div>
                    <div style="font-weight: bolder; background-color: #CCC;">GUIA: <?php echo e($record['guide_number']); ?></div>
                </div>
                <div style="font-size: 9px;"><?php echo e($record['patient_name']); ?></div>
                <div style="font-size: 9px;"><?php echo e($record['patient_gender']); ?> <?php echo e($record['patient_age']); ?></div>
            </div>
        </td>
        <td style="width: 80%; padding: 0px; border: 1px solid #000;">
            <div style="display: flex; flex-wrap: wrap;">
                <?php $__currentLoopData = $record['exams']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display: flex; flex-direction: column; border-right: 1px solid #000;">
                        <div style="font-size: 8px; margin-top: 1px; margin-bottom: 3px; background-color: #F2F2F2;"><?php echo e($exam->name); ?></div>
                        <div style="display: flex; background-color: #FFF;">
                            <?php $__currentLoopData = $exam->parameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parameter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="height: 15px; padding: 0px 25px; text-align: center; border-bottom: 1px dashed #000;
                                <?php echo e(! $loop->last ? 'border-right: 1px dashed #000;' : ''); ?>"
                                >
                                    <?php echo e(str_replace('##', '', $parameter->parameter)); ?>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div style="display: flex; background-color: #FFF;">
                            <?php $__currentLoopData = $exam->parameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parameter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="visibility: hidden; height: 20px; padding: 0px 25px; text-align: center;">
                                    <?php echo e(str_replace('##', '', $parameter->parameter)); ?>

                                </div>
                                <?php if(! $loop->last): ?>
                                    <div style="border-right: 1px dashed #000; height: 20px;"></div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </td>
    </tr>
    
<?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/routine/map/biomedical/inc/others-exams.blade.php ENDPATH**/ ?>