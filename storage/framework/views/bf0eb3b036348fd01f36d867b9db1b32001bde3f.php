
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
                        <h1 class="display-2 font-weight-medium"><i class="bx bx-buoy bx-spin text-primary display-3"></i></h1>
                        <h3 class="text-primary font-weight-medium mb-2">Sistema Bloqueado</strong></h3>
                        <p>
                        Este site está temporariamente indisponível. Para mais informações, entre em contato com o suporte
                        </p>                        
                        <p>
                            Devcactus Tecnologia.
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

<?php echo $__env->make('layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u444904474/domains/sislac.com.br/public_html/prainha.sislac.com.br/resources/views/errors/503.blade.php ENDPATH**/ ?>