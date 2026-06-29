/*
 Template Name: Sislac - Sistema para Laboratorios, Clinicas e Hospitais
 Author: Marcelo Henrique
 File: Appointment Search Exam
 */

const baseUrl = $('[data-js="baseUrl"]').val();

$.ajax({
    type: 'GET',
    url: `${baseUrl}/biomedicals/search`,
    success: function (response) {
        window.biomedicals = response.biomedicals;
    },
    error: function (error) {
        toastr.error(error.responseJSON.message);
    }
});

$('#searchPatient').select2({
        
    ajax: {
        url: $('#urlSearchPatient').val(),
        type: 'POST',
        dataType: 'json',
        data: function () {
            return {
                filter: $('.select2-search__field').val(),
                _token: $('[name="_token"]').val()
            }
        },
        delay: 400,
        processResults: function (response) {
            return {
                results: response.patients
            }
        },
        cache: true
    },

    templateResult: function (patients) {
        if (patients.loading) {
            return $(
                `<span class="spinner-border spinner-border-sm text-primary mr-2" role="status" aria-hidden="true">
                </span><span class="text-primary fw-600">Buscando pacientes...</span>`
            );
        } else {
            return $(
                `<div><strong>NOME COMPLETO:</strong> ${patients.name} ${patients.name_social}</div>
                <div><strong>DATA DE NASCIMENTO:</strong> ${patients.date_of_birth_formatted}</div>
                <div><strong>CPF:</strong> ${patients.cpf_masked} <strong>CNS:</strong> ${patients.cns_masked}</div>
                <div style="border-bottom: 1px solid #CCC;"></div>`
            );
        }
    },

    templateSelection: function (patient) {
        if (patient.id) {
            if (typeof patient.name !== 'undefined') {
                return $(`<option value="${patient.id}">${patient.name} ${patient.name_social ? patient.name_social : ''}</option>`);
            } else {
                return $(`<option value="${patient.id}">${patient.text} ${patient.name_social ? patient.name_social : ''}</option>`);
            }
        } else {
            return patient.text;
        }
    },

    placeholder: 'Buscar paciente por nome, CPF ou CNS',
    minimumInputLength: 2,
    language: {
        'noResults': () => 'Nenhum resultado encontrado',
        'searching': () => 'Buscando...',
        'errorLoading': () => 'Os resultados não puderam ser carregados.',
        'inputTooShort': () => 'Digite 2 ou mais caracteres',
    },
        
});

$('#searchExamAbbreviation').select2({
    
    ajax: {
        url: $('#urlSearchExamAbbreviation').val(),
        type: 'POST',
        dataType: 'json',
        data: function () {
            return {
                filtro: document.querySelector('.select2-search__field').value,
                _token: document.querySelector("[name='_token']").value
            }
        },
        delay: 400,
        processResults: function (data) {
            return {
                results: data.exams
            }
        },
        cache: true
    },

    templateResult: function (exams) {
        if (exams.loading) {
            return $(
                `<span class="spinner-border spinner-border-sm text-primary mr-2" role="status" aria-hidden="true">
                </span><span class="text-primary fw-600">Buscando exames...</span>`
            );
        } else {
            return $(
                `<div><strong>NOME:</strong> ${exams.name}</div>
                <div><strong>ABREVIAÇÃO:</strong> ${exams.abbreviation}</div>
                <div style="border-bottom: 1px solid #CCC;"></div>`
            );
        }
    },

    templateSelection: function (exam) {
        if (exam.id) {
            return $(
                `<div data-id="${exam.id}" data-abbreviation="${exam.abbreviation}" data-name="${exam.name}">
                    Buscar...
                </div>`
            );
        } else {
            return exam.text;
        }
    },

    placeholder: 'Sigla',
    minimumInputLength: 2,
    language: {
        'noResults': () => 'Nenhum resultado encontrado',
        'searching': () => 'Buscando...',
        'errorLoading': () => 'Os resultados não puderam ser carregados.',
        'inputTooShort': () => `Digite 2 ou mais caracteres`,
    },
        
});

$('#searchExamName').select2({
        
    ajax: {
        url: $('#urlSearchExamName').val(),
        type: 'POST',
        dataType: 'json',
        data: function () {
            return {
                filtro: document.querySelector('.select2-search__field').value,
                _token: document.querySelector("[name='_token']").value
            }
        },
        delay: 400,
        processResults: function (data) {
            return {
                results: data.exams
            }
        },
        cache: true
    },

    templateResult: function (exams) {
        if (exams.loading) {
            return $(
                `<span class="spinner-border spinner-border-sm text-primary mr-2" role="status" aria-hidden="true">
                </span><span class="text-primary fw-600">Buscando exames...</span>`
            );
        } else {
            return $(
                `<div><strong>NOME:</strong> ${exams.name}</div>
                <div><strong>ABREVIAÇÃO:</strong> ${exams.abbreviation}</div>
                <div style="border-bottom: 1px solid #CCC;"></div>`
            );
        }
    },

    templateSelection: function (exam) {
        if (exam.id) {
            return $(
                `<div data-id="${exam.id}" data-abbreviation="${exam.abbreviation}" data-name="${exam.name}">
                    Buscar por nome
                </div>`
            );
        } else {
            return exam.text;
        }
    },

    placeholder: 'Buscar por nome',
    minimumInputLength: 4,
    language: {
        'noResults': () => 'Nenhum resultado encontrado',
        'searching': () => 'Buscando...',
        'errorLoading': () => 'Os resultados não puderam ser carregados.',
        'inputTooShort': () => `Digite 4 ou mais caracteres`,
    },
        
});

let incrementExam = 0;
const tbody = document.getElementById('examContent');

function changeExamName() {
    const containerSelected = document.getElementById('select2-searchExamName-container').firstChild;
    const exam = containerSelected.dataset;

    const tr = document.createElement('tr');
    const tdId = document.createElement('td');
    const tdName = document.createElement('td');
    const tdDescription = document.createElement('td');
    const tdBiomedical = document.createElement('td');
    const tdCollectedAt = document.createElement('td');

    tdId.innerText = ++incrementExam;
    tdName.innerText = exam.abbreviation;
    tdDescription.innerHTML = getExam(exam);
    tdBiomedical.innerHTML = getBiomedical();
    tdCollectedAt.innerHTML = getDateCurrent();

    tr.appendChild(tdId);
    tr.appendChild(tdName);
    tr.appendChild(tdDescription);
    tr.appendChild(tdBiomedical);
    tr.appendChild(tdCollectedAt);

    const inputHiddenId = document.createElement('input');
    const inputHiddenAbbreviation = document.createElement('input');
    const inputHiddenName = document.createElement('input');

    inputHiddenId.type = 'hidden';
    inputHiddenId.name = 'exam_ids[]';
    inputHiddenId.value = exam.id;

    inputHiddenAbbreviation.type = 'hidden';
    inputHiddenAbbreviation.name = 'exam_abbreviations[]';
    inputHiddenAbbreviation.value = exam.name;

    inputHiddenName.type = 'hidden';
    inputHiddenName.name = 'exam_names[]';
    inputHiddenName.value = exam.abbreviation;

    tr.appendChild(inputHiddenId);
    tr.appendChild(inputHiddenAbbreviation);
    tr.appendChild(inputHiddenName);

    tbody.appendChild(tr);
}

function changeExamAbbreviation() {
    const containerSelected = document.getElementById('select2-searchExamAbbreviation-container').firstChild;
    const exam = containerSelected.dataset;

    const tr = document.createElement('tr');
    const tdId = document.createElement('td');
    const tdName = document.createElement('td');
    const tdDescription = document.createElement('td');
    const tdBiomedical = document.createElement('td');
    const tdCollectedAt = document.createElement('td');

    tdId.innerText = ++incrementExam;
    tdName.innerText = exam.abbreviation;
    tdDescription.innerHTML = getExam(exam);
    tdBiomedical.innerHTML = getBiomedical();
    tdCollectedAt.innerHTML = getDateCurrent();

    tr.appendChild(tdId);
    tr.appendChild(tdName);
    tr.appendChild(tdDescription);
    tr.appendChild(tdBiomedical);
    tr.appendChild(tdCollectedAt);

    const inputHiddenId = document.createElement('input');
    const inputHiddenAbbreviation = document.createElement('input');
    const inputHiddenName = document.createElement('input');

    inputHiddenId.type = 'hidden';
    inputHiddenId.name = 'exam_ids[]';
    inputHiddenId.value = exam.id;

    inputHiddenAbbreviation.type = 'hidden';
    inputHiddenAbbreviation.name = 'exam_abbreviations[]';
    inputHiddenAbbreviation.value = exam.name;

    inputHiddenName.type = 'hidden';
    inputHiddenName.name = 'exam_names[]';
    inputHiddenName.value = exam.abbreviation;

    tr.appendChild(inputHiddenId);
    tr.appendChild(inputHiddenAbbreviation);
    tr.appendChild(inputHiddenName);

    tbody.appendChild(tr);
}

function removeExam(element) {
    element
        .parentElement
        .parentElement
        .parentElement
        .remove();
}

function getExam(exam) {
    return (
        `<div class="d-flex align-items-center">
            <div title="Remover exame" onclick="removeExam(this)">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 48 48"
                    fill="var(--danger)" style="cursor: pointer;" 
                >
                    <path d="M13.05 42q-1.2 0-2.1-.9-.9-.9-.9-2.1V10.5H8v-3h9.4V6h13.2v1.5H40v3h-2.05V39q0 1.2-.9 2.1-.9.9-2.1.9Zm5.3-7.3h3V14.75h-3Zm8.3 0h3V14.75h-3Z"/>
                </svg>
            </div>
            <span style="margin-left: 5px;">${exam.name}</span>
        </div>`
    );
}

function getBiomedical() {
    return (
        `<select class="form-control form-select" name="exam_biomedicals[]">
            <option value=''>Selecione</option>
            ${window.biomedicals.reduce((acumulator, biomedical) => 
                acumulator + `<option value="${biomedical.id}" style="font-size: 17px;">
                    ${biomedical.first_name} ${biomedical.last_name}
                </option>`
            , '')}
        </select>`
    );
}

function getDateCurrent() {
    const date = new Date();
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return (
        `<input type="date" class="form-control" 
            name="exam_collected_at[]" value="${year}-${month}-${day}"
        >`
    );
}

$('#createAttendance').click(function () {
    setTimeout(() => {
        $(this).html( `
            <span class="spinner-border spinner-border-sm mr-2" 
                role="status" aria-hidden="true">
            </span>Aguarde...
        `)
        $(this).prop({'disabled': true})
    }, 10);

    setTimeout(() => {
        $(this).prop({'disabled' : false})
        $(this).html('Salvar Atendimento');
    }, 7000);
});

function changeExamNameEdit() {
    const containerSelected = document.getElementById('select2-searchExamName-container').firstChild;
    const exam = containerSelected.dataset;

    const tr = document.createElement('tr');
    const tdId = document.createElement('td');
    const tdName = document.createElement('td');
    const tdDescription = document.createElement('td');
    const tdBiomedical = document.createElement('td');
    const tdCollectedAt = document.createElement('td');
    const tdStatus = document.createElement('td');

    tdId.innerText = ++ $('#examContent').children().length;
    tdName.innerText = exam.abbreviation;
    tdDescription.innerHTML = getExam(exam);
    tdBiomedical.innerHTML = getBiomedical();
    tdCollectedAt.innerHTML = getDateCurrent();
    tdStatus.style.textAlign = 'center';
    tdStatus.innerHTML = getStatusPending();

    tr.appendChild(tdId);
    tr.appendChild(tdName);
    tr.appendChild(tdDescription);
    tr.appendChild(tdBiomedical);
    tr.appendChild(tdCollectedAt);
    tr.appendChild(tdStatus);

    const inputHiddenId = document.createElement('input');
    const inputHiddenAbbreviation = document.createElement('input');
    const inputHiddenName = document.createElement('input');
    const inputHiddenStatus = document.createElement('input');

    inputHiddenId.type = 'hidden';
    inputHiddenId.name = 'exam_ids[]';
    inputHiddenId.value = exam.id;

    inputHiddenAbbreviation.type = 'hidden';
    inputHiddenAbbreviation.name = 'exam_abbreviations[]';
    inputHiddenAbbreviation.value = exam.name;

    inputHiddenName.type = 'hidden';
    inputHiddenName.name = 'exam_names[]';
    inputHiddenName.value = exam.abbreviation;

    inputHiddenStatus.type = 'hidden';
    inputHiddenStatus.name = 'exam_status[]';
    inputHiddenStatus.value = 0;

    tr.appendChild(inputHiddenId);
    tr.appendChild(inputHiddenAbbreviation);
    tr.appendChild(inputHiddenName);
    tr.appendChild(inputHiddenStatus);

    tbody.appendChild(tr);
}

function changeExamAbbreviationEdit() {
    const containerSelected = document.getElementById('select2-searchExamAbbreviation-container').firstChild;
    const exam = containerSelected.dataset;

    const tr = document.createElement('tr');
    const tdId = document.createElement('td');
    const tdName = document.createElement('td');
    const tdDescription = document.createElement('td');
    const tdBiomedical = document.createElement('td');
    const tdCollectedAt = document.createElement('td');
    const tdStatus = document.createElement('td');

    tdId.innerText = ++ $('#examContent').children().length;
    tdName.innerText = exam.abbreviation;
    tdDescription.innerHTML = getExam(exam);
    tdBiomedical.innerHTML = getBiomedical();
    tdCollectedAt.innerHTML = getDateCurrent();
    tdStatus.style.textAlign = 'center';
    tdStatus.innerHTML = getStatusPending();

    tr.appendChild(tdId);
    tr.appendChild(tdName);
    tr.appendChild(tdDescription);
    tr.appendChild(tdBiomedical);
    tr.appendChild(tdCollectedAt);
    tr.appendChild(tdStatus);

    const inputHiddenId = document.createElement('input');
    const inputHiddenAbbreviation = document.createElement('input');
    const inputHiddenName = document.createElement('input');
    const inputHiddenStatus = document.createElement('input');

    inputHiddenId.type = 'hidden';
    inputHiddenId.name = 'exam_ids[]';
    inputHiddenId.value = exam.id;

    inputHiddenAbbreviation.type = 'hidden';
    inputHiddenAbbreviation.name = 'exam_abbreviations[]';
    inputHiddenAbbreviation.value = exam.name;

    inputHiddenName.type = 'hidden';
    inputHiddenName.name = 'exam_names[]';
    inputHiddenName.value = exam.abbreviation;

    inputHiddenStatus.type = 'hidden';
    inputHiddenStatus.name = 'exam_status[]';
    inputHiddenStatus.value = 0;

    tr.appendChild(inputHiddenId);
    tr.appendChild(inputHiddenAbbreviation);
    tr.appendChild(inputHiddenName);
    tr.appendChild(inputHiddenStatus);

    tbody.appendChild(tr);
}

function getStatusPending() {
    return (
        `<span style="color: #efc681;" title="Pendente">
            <i class="mdi mdi-information-outline font-size-22 align-middle"></i>
        </span>`
    );
}
