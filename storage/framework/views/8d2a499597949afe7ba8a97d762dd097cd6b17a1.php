<?php $__env->startSection('title'); ?> Sistema Indisponível <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<body>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <h1 class="display-2 font-weight-medium">5<i class="bx bx-buoy bx-spin text-primary display-3"></i>3</h1>
                        <h3 class="text-primary font-weight-bold mb-0">PÁGINA BLOQUEADA!</h3>
                        <p>
                            Contate o administrador do sistema para saber mais informações.
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

<?php echo $__env->make('layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/hospi580/lab.hospitaldrjarques.com.br/resources/views/errors/503.blade.php ENDPATH**/ ?>