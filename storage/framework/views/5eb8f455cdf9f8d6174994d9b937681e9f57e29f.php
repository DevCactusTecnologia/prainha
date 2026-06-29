<div class="modal fade" id="create-patient" tabindex="-1" role="dialog" aria-hidden="true" aria-hidden="true">
    <div class="modal-dialog modal-lg bg-white" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                <form action="<?php echo e(route('patients.appointment.store')); ?>" method="POST" >
                    <?php echo csrf_field(); ?>

                    <h3 class="text-primary font-weight-semibold mb-3">Novo paciente</h3>
                    
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Nome Completo<span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" name="first_name" id="firstName"
                                placeholder="Digite o nome completo sem abreviações" required
                            >
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">Nome Social</label>
                            <input type="text" class="form-control" name="patient_social_name" id="nameSocial" 
                               placeholder="Digite o nome social"
                            >
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Nome da mãe</label>
                            <input type="text" class="form-control text-uppercase" name="mother_name" id="motherName">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">CPF</label>
                            <input type="text" class="form-control" name="patient_cpf" id="cpf" placeholder="Digite o nº do CPF">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">CNS</label>
                            <input type="text" class="form-control" placeholder="Digite o nº do Cartão SUS" name="cns" id="cns">
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Data de Nascimento<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="form-label">Sexo Biológico<span class="text-danger">*</span></label>
                            <select class="form-control" name="gender" required>
                                <option value="">Selecione</option>
                                <option value="Male">Masculino</option>
                                <option value="Female">Feminino</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="form-label">Endereço Atual</label>
                            <textarea id="formmessage" name="address" placeholder="Digite o endereço"
                                class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">E-mail</label>
                            <input type="email" class="form-control text-lowercase" 
                                placeholder="Digite o E-mail" name="email" id="email"
                            >
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">Nº de Contato</label>
                            <input type="tel" class="form-control"
                                placeholder="Digite o nº de Contato" name="mobile" id="mobile"
                            >
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end modal-footer border-0 mt-2">
                        <button type="button" data-dismiss="modal" 
                            class="btn btn-white text-primary" style="font-weight: 600;"
                        >
                            Fechar
                        </button>
                        <button type="button" data-js="create-patient"
                            class="btn btn-primary rounded-pill font-weight-medium px-4"
                        >
                            Salvar
                        </button>
                    </div>
                </form>
            </div>   
        </div>
        
    </div>
</div>
<?php /**PATH /home3/sislac63/saobento.sislac.com.br/resources/views/appointments/modal/patient/create.blade.php ENDPATH**/ ?>