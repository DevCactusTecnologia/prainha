<?php $__env->startSection('title'); ?> Sistema Indisponível <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<body>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="account-pages my-5 pt-5 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <h1 class="display-2 font-weight-medium">7.5<i class="bx bx-buoy bx-spin text-primary display-3"></i>0</h1>
                        <h3 class="text-primary font-weight-medium mb-2">Sistema Bloqueado por <strong>Falta de Pagamento!</strong></h3>
                        <p>
                            Estamos bloqueando o sistema de gestão laboratorial de forma temporária, por falta de pagamento!
                        </p>
                        <p>
                            É necessário esclarecer que o sistema está hospedado em servidores particulares de empresas privadas, o que gera custos mensais para a empresa, além disso a empresa possui compromisso com seus colaboradores.
                        </p>
                        <hr>
                        <p class="mb-0">
                            Por fim, ficamos a inteira disposição para futuros esclarecimentos.
                        </p>
                        <p>
                            DevCactus Tecnologia.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-6">
                    <div>
                        <img src="<?php echo e(asset('assets/images/error-img.png')); ?>" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/errors/503.blade.php ENDPATH**/ ?>