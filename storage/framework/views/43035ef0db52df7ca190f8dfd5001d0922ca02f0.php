<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Produção por unidade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
</head>

<body style="font-family: Arial, Helvetica, sans-serif;">

    <div style="display: flex; margin-bottom: 5px; align-items: center;">
        <div style="margin-right: 10px;">
            <img src="<?php echo e(asset('assets/images/brasao.png')); ?>" width="60px" alt="brasão">
        </div>
        <div style="display: flex; flex-direction: column; justify-content: center; align-items: flex-start;">
            <h5 style="font-size: 14px; font-weight: 700; margin: 1px 0px;">
                PREFEITURA DE SÃO BENTO
            </h5>
            <h5 style="font-size: 14px; font-weight: 700; margin: 1px 0px;">
                SECRETARIA MUNICIPAL DE SAÚDE
            </h5>
            <h5 style="font-size: 14px; font-weight: 700; margin: 1px 0px;">
                LABORATÓRIO MUNICIPAL DE ANÁLISES CLÍNICAS DR. ALICIO ALEXANDRE DA SILVA
            </h5>
        </div>
    </div>
    <hr>

    

    <div style="text-align: center; margin-top: 20px; margin-bottom: 5px; font-weight: bold;">
        <?php echo e($unitys['name']); ?>

    </div>

    <div style="text-align: center; margin-top: 0px; margin-bottom: 15px;">
        PRODUÇÃO REALIZADA ENTRE 
        <strong>(<?php echo e(date('d/m/Y', strtotime($dateStart))); ?> à <?php echo e(date('d/m/Y', strtotime($dateEnd))); ?>)</strong>
    </div>

    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <table style="border-collapse: collapse; border: 1px solid #000;">
            <thead>
                <tr style="background-color: #eff2f7;">
                    <th style="padding: 5px; border: 1px solid #000;">Exames analisados</th>
                    <th style="text-align: center; padding: 5px; border: 1px solid #000;">Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $unitys['exams_analyzeds']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examName => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="border: 1px solid #000; padding: 3px 4px"><?php echo e($examName); ?></td>
                        <td style="text-align: center; border: 1px solid #000; padding: 3px 4px"><?php echo e($total); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td></td>
                    <td style="text-align: center; border: 1px solid #000; padding: 3px 4px; background-color: #DDD; font-weight: bold;">
                        <?php echo e(array_sum($unitys['exams_analyzeds'])); ?>

                    </td>
                </tr>
            </tbody>
        </table>
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
<?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/routine/production-by-unity/print.blade.php ENDPATH**/ ?>