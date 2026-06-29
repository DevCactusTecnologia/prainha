<?php $__env->startSection('title'); ?> Dashboard <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?> <body data-topbar="dark" data-layout="horizontal"> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if($role == 'admin' || $role == 'doctor'): ?>
        <?php echo $__env->make('layouts.admin-dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif($role == 'receptionist' || $role == 'biomedical'): ?>
        <?php echo $__env->make('layouts.receptionist-dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <?php if($role == 'admin' || $role == 'doctor'): ?>
        <script src="<?php echo e(asset('assets/libs/apexcharts/apexcharts.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/pages/dashboard.init.js')); ?>"></script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u444904474/domains/sislac.com.br/public_html/prainha.sislac.com.br/resources/views/index.blade.php ENDPATH**/ ?>