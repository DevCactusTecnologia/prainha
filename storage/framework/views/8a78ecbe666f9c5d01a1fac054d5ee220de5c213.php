
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
        <td style="width: 9%; padding: 0px; border: 1px solid #000;">GIARD. LAMB</td>
        <td style="width: 9%; padding: 0px;border: 1px solid #000;">ENTAM. HISTO</td>
        <td style="width: 9%; padding: 0px; border: 1px solid #000;">ENTAM. COLI</td>
        <td style="width: 9%; padding: 0px; border: 1px solid #000;">ENDOL. NANA</td>
        <td style="width: 9%; padding: 0px; border: 1px solid #000;">HYMEM. NANA</td>
        <td style="width: 9%; padding: 0px; border: 1px solid #000;">ENT. BIUS</td>
        <td style="width: 9%; padding: 0px; border: 1px solid #000;">ANCYLOST.</td>
        <td style="width: 9%; padding: 0px; border: 1px solid #000;">ASCAR. LUMBRI</td>
        <td style="width: 9%; padding: 0px; border: 1px solid #000;">NEGATIVO</td>
    </tr>
    <tr>
        <td style="width: 9%; border: 1px solid #000; height: 26px;"></td>
        <td style="width: 9%; border: 1px solid #000;"></td>
        <td style="width: 9%; border: 1px solid #000;"></td>
        <td style="width: 9%; border: 1px solid #000;"></td>
        <td style="width: 9%; border: 1px solid #000;"></td>
        <td style="width: 9%; border: 1px solid #000;"></td>
        <td style="width: 9%; border: 1px solid #000;"></td>
        <td style="width: 9%; border: 1px solid #000;"></td>
        <td style="width: 9%; border: 1px solid #000;"></td>
    </tr>
    <tr>
        <td style="border: 1px solid #000; text-align: right; padding-right: 5px;">Observações</td>
        <td colspan="9" style="border: 1px solid #000; height: 15px;"></td>
    </tr>
    <tr>
        <td colspan="11" style="border: 1px solid #000; height: 10px;"></td>
    </tr>
<?php /**PATH /home/hsdrjarques/public_html/resources/views/routine/map/biomedical/inc/fezes.blade.php ENDPATH**/ ?>