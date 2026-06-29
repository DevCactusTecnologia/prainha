<?php $__env->startSection('title'); ?> Login <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?> <body class="bg-white"> <?php $__env->stopSection(); ?>
<style> .btn:focus { outline: none; } </style>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center"> 
            <div class="col-lg-6 p-md-5 p-1" style="height: 100vh;">
                <div class="container col-xl-9 col-lg-12 col-md-12 d-flex flex-column justify-content-between" style="height: 100vh">
                    <div class="p-5 py-lg-2">
                        <h3>Bem vindo de volta 👋</h3>
                        <p>Hoje é um dia novo. É seu dia. Favor entrar para começar a gerenciar seu trabalho.</p>

                        <form method="POST" action="<?php echo e(url('login')); ?>">
                            <?php echo csrf_field(); ?>

                            
                            <?php if($msg = Session::get('error')): ?>
                                <div class="alert alert-danger">
                                    <span> <?php echo e($msg); ?> </span>
                                </div>
                            <?php endif; ?>
                            <?php if($msg = Session::get('success')): ?>
                                <div class="alert alert-success">
                                    <span> <?php echo e($msg); ?> </span>
                                </div>
                            <?php endif; ?>

                            
                            <div class="form-group">
                                <label for="username">Usuário</label>
                                <input name="email" type="email" id="email"
                                    class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    <?php if(old('email')): ?> value="<?php echo e(old('email')); ?>" <?php else: ?> value="" <?php endif; ?>
                                    id="username" placeholder="Digite seu usuário" autocomplete="email"
                                    style="border-radius:10px;" autofocus
                                >
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group">
                                <label for="userpassword">Senha</label>
                                <input type="password" name="password" id="pass"
                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="userpassword"
                                    placeholder="Digite sua senha" style="border-radius:10px;"
                                    <?php if(old('password')): ?> value="<?php echo e(old('password')); ?>" <?php else: ?> value="" <?php endif; ?>
                                >
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-block waves-effect"
                                    style="border-radius:10px;background-color:#172D3A;color:white"
                                >
                                    Entrar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="text-center"><p>© <?php echo e(date('Y')); ?> SISLAC TODOS OS DIREITOS RESERVADOS</p></div>
                </div>
            </div>
            <div class="col-lg-6 d-lg-block d-none p-2">
                <div class="container" style="height:100vh">
                    <img src=<?php echo e(asset('assets/images/login.webp')); ?> alt="Login" class="w-100" style='border-radius:14px;object-fit:cover;height:104vh'>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/auth/login.blade.php ENDPATH**/ ?>