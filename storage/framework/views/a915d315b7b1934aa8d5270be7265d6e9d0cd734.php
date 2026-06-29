    <tr style="background-color: #F2F2F2; font-weight: 600; border-top: 1px solid #000;">
        <td colspan="2" style="border: 1px solid #000;"></td>
        <td style="width: 16%; padding: 0px; border: 1px solid #000; padding-left: 5px;">CARACTERES FÍSICOS</td>
        <td colspan="2" style="width: 32%; padding: 0px;border: 1px solid #000; padding-left: 5px;">CARACTERES QUÍMICOS</td>
        <td colspan="2" style="width: 32%; padding: 0px; border: 1px solid #000; padding-left: 5px;">MICROSCOPIA DO SEDIMENTO (400x)</td>
    </tr>
    <tr>
        <td rowspan="6" style="width: 1%; border: 1px solid #000;"><?php echo e($loop->iteration); ?></td>
        <td rowspan="6" style="border: 1px solid #000; background-color: #FFF; text-align: left; font-weight: 500;">
            <div style="padding: 5px;">
                <div style="display: flex; justify-content: space-between; font-size: 9px;">
                    <div><strong>PROTOCOLO:</strong> <?php echo e($record['protocol']); ?></div>
                    <div style="font-weight: bolder; background-color: #CCC;">GUIA: <?php echo e($record['guide_number']); ?></div>
                </div>
                <div style="font-size: 9px;"><?php echo e($record['patient_name']); ?></div>
                <div style="font-size: 9px;"><?php echo e($record['patient_gender']); ?> <?php echo e($record['patient_age']); ?></div>
            </div>
        </td>
        <td style="width: 16%; border: 1px solid #000; padding-left: 5px;">Volume:</td>
        <td style="width: 16%; border: 1px solid #000; padding-left: 5px;">pH:</td>
        <td style="width: 16%; border: 1px solid #000; padding-left: 5px;">Bilirrubina:</td>
        <td colspan="2" style="width: 16%; border: 1px solid #000; padding-left: 5px;">Células Epiteliais:</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000; padding-left: 5px;">Cor:</td>
        <td style="border: 1px solid #000; padding-left: 5px;">Nitrito:</td>
        <td style="border: 1px solid #000; padding-left: 5px;">Sangue:</td>
        <td colspan="2" style="border: 1px solid #000; padding-left: 5px;">Leucócitos</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000; padding-left: 5px;">Aspecto:</td>
        <td style="border: 1px solid #000; padding-left: 5px;">Proteínas:</td>
        <td style="border: 1px solid #000; padding-left: 5px;">Sais Biliares:</td>
        <td colspan="2" style="border: 1px solid #000; padding-left: 5px;">Piocitos:</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000; padding-left: 5px;">Depósito:</td>
        <td style="border: 1px solid #000; padding-left: 5px;">Glicose:</td>
        <td style="border: 1px solid #000;"></td>
        <td colspan="2" style="border: 1px solid #000; padding-left: 5px;">Hemácias:</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000; padding-left: 5px;">Cheiro:</td>
        <td style="border: 1px solid #000; padding-left: 5px;">Corpos Cetônicos:</td>
        <td style="border: 1px solid #000;"></td>
        <td colspan="2" style="border: 1px solid #000; padding-left: 5px;">Cilindro:</td>
    </tr>
    <tr style="margin-bottom: 10px;">
        <td style="border: 1px solid #000; padding-left: 5px;">Densidade:</td>
        <td style="border: 1px solid #000; padding-left: 5px;">Urobilinogênio:</td>
        <td style="border: 1px solid #000;"></td>
        <td colspan="2" style="border: 1px solid #000; padding-left: 5px;">Cristais:</td>
    </tr>
    <tr style="margin-bottom: 10px;">
        <td colspan="5" style="border: 1px solid #000;">Observações:</td>
        <td colspan="2" style="border: 1px solid #000; padding-left: 5px;">Muco:</td>
    </tr>
    <tr>
        <td colspan="7" style="height: 8px; border-left: 1px solid #FFF; border-right: 1px solid #FFF;"></td>
    </tr>
<?php /**PATH /home3/hospi580/lab.hospitaldrjarques.com.br/resources/views/routine/map/biomedical/inc/urina.blade.php ENDPATH**/ ?>