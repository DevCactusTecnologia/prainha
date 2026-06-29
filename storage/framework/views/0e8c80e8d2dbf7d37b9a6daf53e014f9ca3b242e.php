
    <tr style="background-color: #F2F2F2; text-align: center; font-weight: 600; padding: 0px">
        <td rowspan="3" style="border: 1px solid #000;"><?php echo e($loop->iteration); ?></td>
        <td rowspan="2" style="border: 1px solid #000; background-color: #FFF; text-align: left; font-weight: 500;">
            <div style="padding: 5px;">
                <div style="display: flex; justify-content: space-between; font-size: 9px;">
                    <div><strong>PROTOCOLO:</strong> <?php echo e($record['protocol']); ?></div>
                    <div style="font-weight: bolder; background-color: #CCC;">GUIA: <?php echo e($record['guide_number']); ?></div>
                </div>
                <div style="font-size: 9px;"><?php echo e($record['patient_name']); ?></div>
                <div style="font-size: 9px;"><?php echo e($record['patient_gender']); ?> <?php echo e($record['patient_age']); ?></div>
            </div>
        </td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">HEMAC</td>
        <td style="width: 6%; padding: 0px;border: 1px solid #000;">HEMOG</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">HEMAT</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">RDW</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">LEUC</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">MIEL</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">META</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">BAST</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">SEGM</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">EOSI</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">BASO</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">LINK</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">MONO</td>
        <td style="width: 6%; padding: 0px; border: 1px solid #000;">PLAQ</td>
    </tr>
    <tr>
        <td style="width: 6%; border: 1px solid #000; height: 26px;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
        <td style="width: 6%; border: 1px solid #000;"></td>
    </tr>
    <tr>
        <td style="border: 1px solid #000; text-align: right; padding-right: 5px;">Observações</td>
        <td colspan="14" style="border: 1px solid #000; height: 15px;"></td>
    </tr>
    <tr>
        <td colspan="16" style="border: 1px solid #000; height: 10px;"></td>
    </tr>
<?php /**PATH /home/hsdrjarques/lab.hospitaldrjarques.com.br/resources/views/routine/map/biomedical/inc/hemogram.blade.php ENDPATH**/ ?>