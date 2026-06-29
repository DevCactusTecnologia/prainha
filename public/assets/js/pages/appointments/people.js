
if ($('#mobile').length) {
    const phones = [{ "mask": "(##) # ####-####"}, { "mask": "(##) # ####-####"}];
    $('#mobile').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
    });  
}

if ($('#cns').length) {
    const cns = [{ "mask": "### #### #### ####"}, { "mask": "### #### #### ####"}];
    $('#cns').inputmask({ 
        mask: cns, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
    });  
}

if ($('#cpf').length) {
    const cpf = [{ "mask": "###.###.###-##"}, { "mask": "###.###.###-##"}];
    $('#cpf').inputmask({
        mask: cpf, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
    });  
}

if ($('#doctorCpf').length) {
    const doctorCpf = [{ "mask": "###.###.###-##"}, { "mask": "###.###.###-##"}];
    $('#doctorCpf').inputmask({
        mask: doctorCpf, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
    });   
}

if ($('#doctorCns').length) {
    const doctorCns = [{ "mask": "### #### #### ####"}, { "mask": "### #### #### ####"}];
    $('#doctorCns').inputmask({ 
        mask: doctorCns, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
    });
}

$('[data-js="create-patient"]').click(function (event) {
    const baseUrl = $('[data-js="baseUrl"]').val();
    const name = $('[name="first_name"]').val();
    const nameSocial = $('[name="patient_social_name"]').val();
    const motherName = $('[name="mother_name"]').val();
    const cpf = $('[name="patient_cpf"]').val();
    const cns = $('[name="cns"]').val();
    const dob = $('[name="dob"]').val();
    const gender = $('[name="gender"]').val();
    const address = $('[name="address"]').val();
    const email = $('[name="email"]').val();
    const mobile = $('[name="mobile"]').val();

    if (name == '') return alert('Informe o nome completo do paciente!');
    if (dob == '') return alert('Informe a data de nascimento do paciente!');
    if (gender == '') return alert('Informe o sexo biológico do paciente!');

    $.ajax({
        type: 'POST',
        url: `${baseUrl}/patients/appointment/store`,
        data: {
            '_token': $('[name="_token"]').val(),
            'first_name': name,
            'patient_social_name': nameSocial,
            'mother_name': motherName,
            'patient_cpf': cpf,
            'cns': cns,
            'dob': dob,
            'gender': gender,
            'address': address,
            'email': email,
            'mobile': mobile,
        },
        beforeSend: () => {
            $(this).html(
                `<span class="spinner-border spinner-border-sm mr-2" 
                    role="status" aria-hidden="true">
                </span>Aguarde...`
            );
            $(this).prop('disabled', true);
        },
        success: function (response) {
            $('#create-patient').modal('hide');

            $('[name="first_name"]').val('');
            $('[name="patient_social_name"]').val('');
            $('[name="mother_name"]').val('');
            $('[name="patient_cpf"]').val('');
            $('[name="cns"]').val('');
            $('[name="dob"]').val('');
            $('[name="gender"]').val('');
            $('[name="address"]').val('');
            $('[name="email"]').val('');
            $('[name="mobile"]').val('');

            const option = new Option(response.patient.name, response.patient.id, false, false);
            $('[name="appointment_for"]').append(option).trigger('change');
            toastr.success(response.message);
        },
        error: function (error) {
            toastr.error(error.responseJSON.message);
        },
        complete: () => {
            $(this).prop('disabled', false);
            $(this).html('Salvar');
        },
    });
});

$('[data-js="create-doctor"]').click(function (event) {
    const baseUrl = $('[data-js="baseUrl"]').val();
    const doctorFirstName = $('#doctorFirstName').val();
    const doctorCpf = $('#doctorCpf').val();
    const doctorCns = $('#doctorCns').val();
    const doctorClassCouncilId = $('#doctorClassCouncilId').val();
    const doctorIssuingState = $('#doctorIssuingState').val();
    const doctorCounsilNumber = $('#doctorCounsilNumber').val();

    if (doctorFirstName == '') return alert('Informe o nome completo do solicitante!');
    if (doctorClassCouncilId == '') return alert('Informe o conselho de classe do solicitante!');
    if (doctorIssuingState == '') return alert('Informe o estado emissor do solicitante!');
    if (doctorCounsilNumber == '') return alert('Informe o número de Registro do conselho do solicitante!');

    $.ajax({
        type: 'POST',
        url: `${baseUrl}/doctors/appointment/store`,
        data: {
            '_token': $('[name="_token"]').val(),
            'first_name': doctorFirstName,
            'cpf': doctorCpf,
            'cns': doctorCns,
            'class_council_id': doctorClassCouncilId,
            'issuing_state_id': doctorIssuingState,
            'counsil_number': doctorCounsilNumber,
        },
        beforeSend: () => {
            $(this).html(
                `<span class="spinner-border spinner-border-sm mr-2" 
                    role="status" aria-hidden="true">
                </span>Aguarde...`
            );
            $(this).prop('disabled', true);
        },
        success: function (response) {
            $('#create-doctor').modal('hide');

            $('#doctorFirstName').val();
            $('#doctorCpf').val();
            $('#doctorCns').val();
            $('#doctorClassCouncilId').val();
            $('#doctorIssuingState').val();
            $('#doctorCounsilNumber').val();

            const option = new Option(response.doctor.name, response.doctor.id, false, false);
            $('[name="appointment_with"]').append(option).trigger('change');
            $('[name="appointment_with"]').select2('trigger', 'select', { 
                data: { 
                    id: response.doctor.id, 
                    text: response.doctor.name
                }
            });

            toastr.success(response.message);
        },
        error: function (error) {
            toastr.error(error.responseJSON.message);
        },
        complete: () => {
            $(this).prop('disabled', false);
            $(this).html('Salvar');
        },
    });
});

const blockDocuments = document.querySelector('[data-js="block-documents"]');
const documentType = document.getElementById('documentType');

function addFile() {
    blockDocuments.appendChild(getContainerFile());
}

const getContainerFile = () => {
    const div = document.createElement('div');

    div.innerHTML = (
        `<div class="row">
            <div class="col-5 form-group">
                <select class="form-control" name="documents_types[]">
                    ${documentType.innerHTML}
                </select>
            </div>
            <div class="col-6 form-group">
                <input type="file" class="form-control pl-0" name="documents[]"
                    accept="image/png,image/jpg,image/jpeg, .pdf"
                >
            </div>
            <div class="col-1 form-group pr-0 text-center d-flex align-items-center">
                <i class="bx bxs-trash-alt text-danger font-size-24" style="cursor: pointer" 
                    onclick="this.parentElement.parentElement.remove()" title="Remover arquivo selecionado"
                >
                </i>
            </div>
        </div>`
    );

    return div;
}
