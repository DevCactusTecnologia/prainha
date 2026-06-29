/*
 Template Name: Sislac - Sistema para Laborat�rios, Clinicas e Hospitais
 Author: Lndinghub(Themesbrand)
 File: Appointment
 */

$(document).ready(function() {

    let timerDate;
    $('#changeDate').on('change', function (event) {
        clearTimeout(timerDate);

        const milliseconds = 400;

        timerDate = setTimeout((event) => {

            // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
            if (event.keyCode !== 8 && event.keyCode !== 13) {
                $.ajax({
                    type: 'POST',
                    url: '/appointments/search/by-date',
                    data: {
                        'date': $(this).val(), 
                        '_token': $("input[name='_token']").val(),   
                    },
                    beforeSend: () => {
                        $('#contentAppointment').css({display: 'none'});
                        $('#paginate').css({display: 'none'});
                        $('#loader').removeAttr('style');
                    },
                    success: function (response) {
                        if (response.appointments.length <= 0) {
                            $('#contentAppointment').html(notFound());
                        } else {
                            $('#contentAppointment').html(getContent(response.appointments));
                        }
                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.message);
                    },
                    complete: function () {
                        $('#loader').css({display: 'none'});
                        $('#paginate').css({display: 'block'});
                        $('#contentAppointment').removeAttr('style');
                        $('#changeProtocol').val('');
                        $('#changePatient').val('');
                    },
                });
            }

        }, milliseconds, event);
    });

    let timerProtocol;
    $('#changeProtocol').on('keyup', function (event) {
        clearTimeout(timerProtocol);

        const milliseconds = 500;

        timerProtocol = setTimeout((event) => {

            // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
            if (event.keyCode !== 8 && event.keyCode !== 13) {
                $.ajax({
                    type: 'POST',
                    url: '/appointments/search/by-protocol',
                    data: {
                        'protocol': $(this).val(), 
                        '_token': $("input[name='_token']").val(),   
                    },
                    beforeSend: () => {
                        $('#contentAppointment').css({display: 'none'});
                        $('#paginate').css({display: 'none'});
                        $('#loader').removeAttr('style');
                    },
                    success: function (response) {
                        if (response.appointments.length <= 0) {
                            $('#contentAppointment').html(notFound());
                        } else {
                            $('#contentAppointment').html(getContent(response.appointments));
                        }
                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.message);
                    },
                    complete: function () {
                        $('#loader').css({display: 'none'});
                        $('#paginate').css({display: 'block'});
                        $('#contentAppointment').removeAttr('style');
                        $('#changeDate').val('');
                        $('#changePatient').val('');
                    },
                });
            }

        }, milliseconds, event);
    });

    let timerPatient;
    $('#changePatient').on('keyup', function (event) {
        clearTimeout(timerPatient);

        const milliseconds = 500;

        timerPatient = setTimeout((event) => {

            // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
            if (event.keyCode !== 8 && event.keyCode !== 13) {
                $.ajax({
                    type: 'POST',
                    url: '/appointments/search/by-patient',
                    data: {
                        'patient': $(this).val(), 
                        '_token': $("input[name='_token']").val(),   
                    },
                    beforeSend: () => {
                        $('#contentAppointment').css({display: 'none'});
                        $('#paginate').css({display: 'none'});
                        $('#loader').removeAttr('style');
                    },
                    success: function (response) {
                        if (response.appointments.length <= 0) {
                            $('#contentAppointment').html(notFound());
                        } else {
                            $('#contentAppointment').html(getContent(response.appointments));
                        }
                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.message);
                    },
                    complete: function () {
                        $('#loader').css({display: 'none'});
                        $('#paginate').css({display: 'block'});
                        $('#contentAppointment').removeAttr('style');

                        $('#changeDate').val('');
                        $('#changeProtocol').val('');
                    },
                });
            }

        }, milliseconds, event);
    });

});

function cancel(element) {
    const appointment = element.dataset;

    $('[data-js="protocol"]').text(appointment.protocol);
    $('[data-js="form-cancel"]').attr('action', appointment.url);
}

function modalPending(element) {
    const data = element.dataset;
    const baseUrl = $('#baseUrl').val();

    $.ajax({
        type: 'GET',
        url: `${baseUrl}/appointments/${data.id}/search-patient`,
        beforeSend: function () {
            $('#patientModalLoader').css({
                'display': 'flex',
                'flex-direction': 'column',
                'align-items': 'center',
                'justify-content': 'center',
                'font-size': '20px',
                'height': '100px',
            });

            $('#patientModalContent').css({'display': 'none'});
        },
        success: function (response) {
            $('#viewId').text(response.patient.protocol);
            $('#viewPatient').text(response.patient.name);
            $('#viewAge').text(response.patient.age);
            $('#viewCpf').text(response.patient.cpf);
            $('#viewCns').text(response.patient.cns);

            const tr = response.patient.exams.reduce((acumulator, exam, index) => {

                let status = exam.status;
                if (status == '1') {
                    status = `
                        <div class="col-4" title="Verificado">
                            <span style="color: #33c38e;">
                                <i class="mdi mdi-checkbox-marked-circle font-size-18 align-middle"></i>
                            </span>
                        </div>
                    `;
                } else if (status == '2') {
                    status = `
                        <div class="col-4" title="Cancelado">
                            <span style="color: #ff0000;">
                                <i class="mdi mdi-cancel font-size-18 align-middle"></i>
                            </span>
                        </div>
                    `;
                } else {
                    status = `
                        <div class="col-4" title="Pendente">
                            <span style="color: #efc681;">
                                <i class="mdi mdi-information-outline font-size-18 align-middle"></i>
                            </span>
                        </div>
                    `;
                }

                return acumulator + (
                    `<tr>
                        <td>${index + 1}</td>
                        <td>${exam.name}</td>
                        <td>${status}</td>
                    </tr>`
                );
            }, '');

            $('#viewTbody').html(tr);
        },
        error: function (response) {
            toastr.error(response.responseJSON.message);
        },
        complete: function () {
            $('#patientModalLoader').css({'display': 'none'});
            $('#patientModalContent').css({'display': 'block'});
        }
    });

}

function notFound() {
    return (
        `<tr>
            <td colspan="7" class="bg-light">
                <div class="d-flex justify-content-center align-items-center" 
                    style="height: 80px; font-size: 16px;"
                >
                    Nenhum resultado encontrado.
                </div>
            </td>
        </tr>`
    );
}

function getContent(appointments) {
    return appointments.map((appointment, index) => {
        const status = {};

        if (appointment.status == '0') {
            status.color = 'alert-warning rounded-pill px-2 py-1';
            status.name = 'Pendente';
        }

        if (appointment.status == '1') {
            status.color = 'alert-success rounded-pill px-2 py-1';
            status.name = 'Finalizado';
        }

        if (appointment.status == '2') {
            status.color = 'alert-danger rounded-pill px-2 py-1';
            status.name = 'Cancelado';
        }

        return `
            <tr>
                <td>${index + 1}</td>
                <td>${appointment.doctor_name}</td>
                <td>${appointment.patient_name}</td>
                <td class="text-center">${appointment.protocol}</td>
                <td>${appointment.date}</td>
                <td><span class="alert ${status.color}">${status.name}</span></td>
                <td>
                    <button type="button" class="btn btn-primary"
                        title="Visualizar atendimento" data-toggle="modal"
                        data-target="#viewPending"
                        data-patient="${appointment.patient_name}"
                        data-id="${appointment.protocol}" data-cpf="${appointment.patient_cpf}"
                        data-cns="${appointment.patient_cns}" data-age="${appointment.patient_age}"

                        data-exams-names="${appointment.exams.map(exam => exam.name).join(',')}"
                        data-exams-status="${appointment.exams.map(exam => exam.status).join(',')}"

                        onclick="modalPending(this)"
                    >
                        <i class="mdi mdi-eye"></i>
                    </button>
                    <a href="/appointments/${appointment.protocol}/result/create" 
                        class="btn btn-success" title="Inserir resultado"
                    >
                        <i class='bx bx-check'></i>
                    </a>

                    <div class="btn-group dropleft">
                        <button class="btn btn-light rounded-circle p-2 dropdown-toggle" 
                            type="button" data-toggle="dropdown" title="Mais opções"
                            aria-haspopup="true" aria-expanded="false"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" 
                                viewBox="0 0 48 48" stroke-width="4" stroke="black"
                            >
                                <path d="M24 40q-1 0-1.7-.7t-.7-1.7q0-1 .7-1.7t1.7-.7q1 0 1.7.7t.7 1.7q0 1-.7 1.7T24 40Zm0-13.6q-1 0-1.7-.7t-.7-1.7q0-1 .7-1.7t1.7-.7q1 0 1.7.7t.7 1.7q0 1-.7 1.7t-1.7.7Zm0-13.6q-1 0-1.7-.7t-.7-1.7q0-1 .7-1.7T24 8q1 0 1.7.7t.7 1.7q0 1-.7 1.7t-1.7.7Z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu">
                            <li>
                                <a href="/appointments/${appointment.protocol}/print"
                                    class="dropdown-item" target="_blank"
                                >
                                    <i class="mdi mdi-printer mr-2"></i>
                                    Imprimir 2ª via do comprovante
                                </a>
                            </li>
                            <li>
                                <a type="button" class="dropdown-item" 
                                    href="/appointments/${appointment.protocol}/edit"
                                >
                                    <i class="mdi mdi-lead-pencil mr-2"></i>
                                    Editar atendimento
                                </a>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item cancel" 
                                    data-id="${appointment.protocol}" onclick="cancel(${appointment.protocol})"
                                >
                                    <i class="bx bx-box mr-2"></i>
                                    Cancelar atendimento
                                </button>
                            </li>
                        </div>
                    </div>

                </td>
            </tr>
        `;
    }).join('');
}

$('#changeStatus').change(function () {
    const statusId = $(this).val();
    const baseUrl = $('#baseUrl').val();
    const url = `${baseUrl}/appointments/status/${statusId}`;

    window.location.href = url;
});
