<script src="<?php echo e(asset('assets/libs/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/libs/bootstrap/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/libs/metismenu/metismenu.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/libs/simplebar/simplebar.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/libs/node-waves/node-waves.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/libs/toastr/toastr.min.js')); ?>"></script>

<?php echo $__env->yieldContent('script'); ?>
<script src="<?php echo e(asset('assets/js/app.min.js')); ?>"></script>
<script>
    <?php if(Session::has('success')): ?>
        toastr.options = { 'closeButton': true, 'progressBar': true }
        toastr.success("<?php echo e(session('success')); ?>");
    <?php endif; ?>
    <?php if(Session::has('error')): ?>
        toastr.options = { 'closeButton': true, 'progressBar': true }
        toastr.error("<?php echo e(session('error')); ?>");
    <?php endif; ?>
</script>
<?php echo $__env->yieldContent('script-bottom'); ?>
<?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/layouts/footer-script.blade.php ENDPATH**/ ?>