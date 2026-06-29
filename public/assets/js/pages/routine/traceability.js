
$('#searchTraceability').click(function (event) {
    event.preventDefault();

    if ($('[data-js="protocol"]').val() == '') {
        return alert('Informe o protocolo!');
    }

    $.ajax({
        url: $('[data-js="formTraceability"]').attr('action'),
        type: 'POST',
        data: {
            protocol: $('[data-js="protocol"]').val(),
            _token: $('[name="_token"]').val(),
        },
        beforeSend: () => {
            $(this).html(loader());
            $(this).prop('disabled', true);
        },
        success: (response) => {
            response.appointment.length <= 0
                ? notFound()
                : setContent(response.appointment);
        },
        error: function (response) {
            toastr.error(response.responseJSON.message);
        },
        complete: () => {
            $('[data-js="container-table"]').css({ display: 'block'});

            $(this).prop('disabled', false);
            $(this).html( 
                `<i class="fa fa-search"></i>
                <span class="ml-2">Pesquisar</span>`
            );
        },
    });

});

const loader = () =>
    `<span class="spinner-border spinner-border-sm mr-2" 
        role="status" aria-hidden="true">
    </span>Aguarde`

const notFound = () => {
    $('[data-js="container-header"]').html(``);
    $('[data-js="content-table"]').html(`
        <tr>
            <td class="text-center p-3" colspan="6">Nenhum resultado encontrado</td>
        </tr>
    `);
}
    
const setContent = (appointment) => {
    setHeader(appointment);
    setBody(appointment);
    setModal();
}

const setHeader = (appointment) => {
    $('[data-js="container-header"]').html(
        `<div class="d-md-flex text-primary mb-3">
            <div class="col-md-6 pl-0">
                <div>Nome: <strong>${appointment.patient.name}</strong></div>
                <div>Data de nascimento: <span>${appointment.patient.date_of_birth}</span></div>
            </div>
            <div class="col-md-6 pl-0">
                <div>CPF: ${appointment.patient.cpf}</div>
                <div>CNS: ${appointment.patient.cns}</div>
            </div>
        </div>`
    );
}

const setBody = (appointment) => {
    const body = appointment.exams.reduce((acumulator, exam, index) => 
        acumulator + `<tr>
            <td class="text-center">${index + 1}</td>
            <td class="text-primary" style="font-weight: 600;">${exam.name}</td>
            <td>${appointment.doctor.name}</td>
            <td>${exam.biomedical_name}</td>
            <td class="text-center">${exam.collected_at}</td>
            <td>
                <button type="button" class="btn btn-sm btn-primary waves-effect text-white py-1" data-toggle="modal"
                    title="Visualizar rastreabilidade" data-target="#viewTraceAbility"
                    data-appointment-id="${appointment.id}" data-exam-id="${exam.id}" data-exam-name="${exam.name}"
                >
                    <i class="mdi mdi-eye-plus font-size-16 align-middle"></i>
                </button>
            </td>
        </tr>`
    , '');

    $('[data-js="content-table"]').html(body);
}

const setModal = () => {
    $('[data-js="container-modal"]').html(
        `<div class="modal fade" id="viewTraceAbility" tabindex="-1" role="dialog"
            aria-labelledby="viewTraceAbilityTitle" aria-hidden="true"
        >
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" data-js="container-content-modal">
                    <div class="modal-header border-0 pb-1">
                        <h5 class="modal-title text-primary" id="exampleModalLongTitle">
                            Exame: <strong class="text-primary" data-js="modal-exam-name"></strong>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <table class="table table-centered table-hover table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th><i class="mdi mdi-stack-overflow mdi-18px align-middle"></i></th>
                                    <th>Etapas</th>
                                    <th>Data e hora</th>
                                    <th>Usuário</th>
                                    <th>Resultado</th>
                                </tr>
                            </thead>
                            <tbody id="viewTbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>`
    );

    $(`#viewTraceAbility`).on('show.bs.modal', function ({relatedTarget}) {
        const appointmentId = relatedTarget.dataset.appointmentId;
        const examId = relatedTarget.dataset.examId;
        const examName = relatedTarget.dataset.examName;

        $.ajax({
            url: $('[data-js="url-historic"]').val(),
            type: 'POST',
            data: { 
                appointment_id: appointmentId,
                exam_id: examId,
                _token: $('[name="_token"]').val(),
            },
            success: (response) => {
                response.traceabilities.length <= 0
                    ? setContentEmptyModal()
                    : setContentModal(response.traceabilities, examName);
            },
            error: function (response) {
                toastr.error(response.responseJSON.message);
            },
        });

    })
}

const setContentModal = (traceabilities, examName) => {
    const contentTraceability = traceabilities.reduce((acumulator, traceability) =>
        acumulator + `
            <tr>
                <td>${traceability.stage.icon}</td>
                <td>${traceability.stage.name}</td>
                <td>${traceability.registered_at}</td>
                <td>${traceability.user.name}</td>
                <td>${traceability.result}</td>
            </tr>`
    , '');

    const contentModal = `
        <div class="modal-header border-0 pb-1">
            <h5 class="modal-title text-primary" id="exampleModalLongTitle">
                Exame: <strong class="text-primary">${examName}</strong>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-centered table-hover table-sm">
                <thead class="bg-light">
                    <tr>
                        <th><i class="mdi mdi-stack-overflow mdi-18px align-middle"></i></th>
                        <th>Etapas</th>
                        <th>Data e hora</th>
                        <th>Usuário</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>${contentTraceability}</tbody>
            </table>
        </div>
    `;

    $('[data-js="container-content-modal"]').html(contentModal);
}

const setContentEmptyModal = () => {
    $('[data-js="container-content-modal"]').html(`
        <div class="d-flex flex-column align-items-center bg-light p-5">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#888" version="1.1" id="mdi-stack-overflow" width="100" height="100" viewBox="0 0 24 24">
                    <path d="M17.36,20.2V14.82H19.15V22H3V14.82H4.8V20.2H17.36M6.77,14.32L7.14,12.56L15.93,14.41L15.56,16.17L6.77,14.32M7.93,10.11L8.69,8.5L16.83,12.28L16.07,13.9L7.93,10.11M10.19,6.12L11.34,4.74L18.24,10.5L17.09,11.87L10.19,6.12M14.64,1.87L20,9.08L18.56,10.15L13.2,2.94L14.64,1.87M6.59,18.41V16.61H15.57V18.41H6.59Z" />
                </svg>
            </span>
            <div class="text-primary" style="font-weight: bold; font-size: 26px; margin-top: 15px">
                Rastreamento indisponível!
            </div>
            <div style="margin-top: -5px;">Não foi possível listar o rastreamento do exame informado!</div>
        </div>  
    `);
}
