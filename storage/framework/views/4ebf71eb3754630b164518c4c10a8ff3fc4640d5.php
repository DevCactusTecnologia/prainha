<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Buscar resultados pela chave de acesso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>" type="text/css" />
</head>

<body style="background-color: #FFF; padding: 20px;">
    
    <header class="d-md-flex justify-content-center">
        <div class="col-md-4">
            <img class="img-fluid" src="<?php echo e(asset('assets/images/paciente.svg')); ?>" alt="">
        </div>
    </header>
    <hr>

    <?php if($errors->any()): ?>
        <div class="alert alert-warning">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('patient.result.search')); ?>" method="POST" 
        class="d-flex justify-content-center flex-column align-items-center"
    >
        <?php echo csrf_field(); ?>

        <div class="d-flex justify-content-center align-items-center flex-column" style="width: 400px;">
            <h5 class="text-primary">Informe a chave de acesso</h5>
            <div class="d-flex">
                <input type="number" name="access_key" data-js="search" class="form-control text-center bg-light" 
                    style="font-size: 14px; font-weight: 600;" value="<?php echo e(old('access_key')); ?>" required
                >
                <button type="submit" class="btn btn-primary" title="Consultar resultado">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#FFF" height="20" width="20" viewBox="0 0 48 48">
                        <path d="M39.8 41.95 26.65 28.8q-1.5 1.3-3.5 2.025-2 .725-4.25.725-5.4 0-9.15-3.75T6 18.75q0-5.3 3.75-9.05 3.75-3.75 9.1-3.75 5.3 0 9.025 3.75 3.725 3.75 3.725 9.05 0 2.15-.7 4.15-.7 2-2.1 3.75L42 39.75Zm-20.95-13.4q4.05 0 6.9-2.875Q28.6 22.8 28.6 18.75t-2.85-6.925Q22.9 8.95 18.85 8.95q-4.1 0-6.975 2.875T9 18.75q0 4.05 2.875 6.925t6.975 2.875Z"/>
                    </svg>
                </button>
            </div>
        </div>
    </form>

    <script>
        const search = document.querySelector('[data-js="search"]');
        search.addEventListener('keypress', function(event) {
            event.target.value.length > 7 
                ? event.preventDefault() 
                : '';
        });
    </script>

</body>

</html>
<?php /**PATH /home/hospitaldrjarque/laboratorio/resources/views/web/patients/search.blade.php ENDPATH**/ ?>