<?php $__env->startSection('title'); ?> Lista de ocorrências por atendimento <?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <body data-topbar="dark" data-layout="horizontal">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Lista de ocorrências por atendimento <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('routine.occurrence.index')); ?>">Ocorrências</a> <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_3'); ?> Atendimento <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row bg-white" style="border-radius: 10px; border: 2px #e9ecef solid;">
        <div class="bg-light w-100 my-3 p-3">
            <div class="d-md-flex">
                <div class="col-md-2">
                    <div class="font-weight-bold">Nº do protocolo</div>
                    <div class="font-size-16 font-weight-bold mt-2 ml-2">
                        <?php echo e($occurrence->appointment_id); ?>

                    </div>
                </div>
                <div class="col-md-5 pl-0">
                    <div class="invisible">.</div>
                    <div>Paciente &nbsp;  :&nbsp;<strong><?php echo e($occurrence->appointment->patient?->first_name); ?></strong></div>
                    <div>Idade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;<?php echo e($occurrence->appointment->patient?->patient?->ageExtended()); ?></div>
                </div>
                <div class="col-md-2 pl-0">
                    <div class="invisible">.</div>
                    <div>Sexo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;<?php echo e($occurrence->appointment->patient?->patient?->gender_name); ?></div>
                    <div>Cadastro &nbsp;:&nbsp;<?php echo e($occurrence->appointment->registered_at_formatted); ?></div>
                </div>
            </div>
        </div>

        <div class="w-100">
            <table class="table table-sm table-hover table-centered table-borderless dt-responsive nowrap">
                <thead style="background-color: #f8f9fa">
                    <th class="pl-4">Nº</th>
                    <th>EXAME</th>
                    <th>DATA DA OCORRÊNCIA</th>
                    <th>TIPO</th>
                    <th>MOTIVO DA OCORRÊNCIA</th>
                    <th>IDENTIFICADO POR</th>
                    <th class="pr-4">CRIADO POR</th>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="pl-4"><?php echo e($loop->iteration); ?></td>
                            <td class="text-primary font-weight-bold"><?php echo e($exam->name); ?></td>
                            <td>
                                <?php echo e($exam->pivot->updated_at ? date('d/m/Y H:i:s', strtotime($exam->pivot->updated_at)) : '-'); ?>

                            </td>
                            <td class="font-weight-bold text-<?php echo e($exam->pivot->re_test == '1' ? 'warning' : 'danger'); ?>">
                                <?php echo e($exam->pivot->re_test == '1' ? 'RETESTE' : 'CANCELADO'); ?>

                            </td>
                            <td><?php echo e($exam->pivot->observation); ?></td>
                            <td class="text-uppercase">
                                <?php echo e(App\Models\User::find($exam->pivot->biomedical_id)?->first_name ?: 'Não informado'); ?>

                            </td>
                            <td class="pr-4 text-uppercase">
                                <?php echo e(App\Models\User::find($exam->pivot->user_id)?->first_name); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="w-100 mb-3" style="border-top: 2px solid #e6e9ed;"></div>
        <div class="text-white font-weight-bold w-100 px-2 py-1 mb-2" style="background-color: #aab5c8">RESOLUÇÃO</div>

        <input type="hidden" data-js="user_name" value="<?php echo e(explode(' ', $user->first_name)[0]); ?>">
        <input type="hidden" data-js="user_id" value="<?php echo e($user->id); ?>">

        <form action="<?php echo e(route('routine.occurrence.update', $occurrence->id)); ?>" method="POST" class="w-100">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div data-js="container-procediment">
                <?php $__currentLoopData = $occurrence->procediments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $procediment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-md-flex">
                        <div class="col-md-2 mb-3">
                            <label>Data do Registro <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="registered_ats[]" 
                                value="<?php echo e($procediment->registered_at?->format('Y-m-d')); ?>" required
                            >
                        </div>
                        <div class="col-md-7 mb-3">
                            <label>Relato/Procedimento</label>
                            <input type="text" class="form-control" name="procediments[]" placeholder="Digite o texto aqui" 
                               value="<?php echo e($procediment->procediment); ?>" required
                            >
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Usuário</label>
                            <input type="text" class="border-0 form-control pl-0" 
                                value="<?php echo e(explode(' ', $procediment->user->first_name)[0]); ?>" readonly
                            >
                            <input type="hidden" name="user_ids[]" value="<?php echo e($procediment->user->id); ?>" readonly>
                        </div>
                        <div class="col-md-1 mb-3">
                            <div class="invisible">Ação</div>
                            <button type="button" class="btn btn-danger rounded-circle btn-sm" 
                                onclick="this.parentElement.parentElement.remove()"
                                title="Excluir relato/procedimento"
                            >
                                <i class="bx bx-trash align-middle"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="w-100 d-flex align-items-center px-2 mb-3">
                <button type="button" class="btn btn-primary rounded-circle btn-sm" onclick="addProcediment()"
                    style="font-size: 8px;"
                >
                    <i class="bx bx-plus align-middle"></i>
                </button>
                <span class="text-primary ml-1">Adicionar <strong>Relato/Procedimento</strong></span>
            </div>

            <div class="w-100 px-2 mb-3">
                <select class="form-control" name="solution_id" required>
                    <option value="">Selecione uma solução</option>
                    <?php $__currentLoopData = $resolutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($resolution->value); ?>" 
                            <?php if($occurrence->solution_id?->value == $resolution->value): echo 'selected'; endif; ?>
                        >
                            <?php echo e($resolution->getName()); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        
            <div class="d-flex justify-content-end mb-5 px-2 w-100">
                <button class="btn btn-primary font-weight-bold px-5 rounded-lg text-right" onclick="loader(this)">
                    Salvar
                </button>
            </div>
        </form>
        
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        const containerProcediment = document.querySelector('[data-js="container-procediment"]');
        const userName = document.querySelector('[data-js="user_name"]');
        const userId = document.querySelector('[data-js="user_id"]');

        function addProcediment() {
            const procediment = document.createElement('div');
            procediment.classList.add('d-md-flex');

            procediment.innerHTML = (
                `<div class="col-md-2 mb-3">
                    <label>Data do Registro <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="registered_ats[]" required>
                </div>
                <div class="col-md-7 mb-3">
                    <label>Relato/Procedimento</label>
                    <input type="text" class="form-control" name="procediments[]" placeholder="Digite o texto aqui" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label>Usuário</label>
                    <input type="text" class="border-0 form-control pl-0" value="${userName.value}" readonly>
                    <input type="hidden" name="user_ids[]" value="${userId.value}" readonly>
                </div>
                <div class="col-md-1 mb-3">
                    <div class="invisible">Ação</div>
                    <button type="button" class="btn btn-danger rounded-circle btn-sm" 
                        onclick="this.parentElement.parentElement.remove()"
                        title="Excluir relato/procedimento"
                    >
                        <i class="bx bx-trash align-middle"></i>
                    </button>
                </div>`
            );

            containerProcediment.appendChild(procediment);
        }

        function loader(button) {
            setTimeout(() => {
                button.innerHTML = (
                    `<span class="spinner-border spinner-border-sm mr-2" 
                        role="status" aria-hidden="true">
                    </span>Aguarde...`
                );
                button.disabled = true;
            }, 20);

            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = 'Salvar';
            }, 7000);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/sislac63/prainha.sislac.com.br/resources/views/routine/occurrence/show.blade.php ENDPATH**/ ?>