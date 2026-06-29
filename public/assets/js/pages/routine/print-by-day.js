$('#search').click(function (event) {
    event.preventDefault();

    const date = $('#date').val();
    const checkDate = Date.parse(date);
    const unityId = $('#unity').val();

    if (isNaN(checkDate) || unityId == '') {
        alert('Preencha todos os campos corretamente!');
    } else {
        
        $.ajax({
            url: $('[name="url_current"]').attr('url'),
            type: 'POST',
            data: {
                date: date,
                unity_id: unityId,
                _token: $('[name="_token"]').val(),
            },
            beforeSend: () => {
                $(this).html(loader());
                $(this).prop('disabled', true);
            },
            success: function (response) {
                (response.appointments[0].patients_total <= 0)
                    ? $('#result').html(notFound())
                    : $('#result').html(getContent(response.appointments[0]))
            },
            error: function (response) {
                toastr.error(response.responseJSON.message);
            },
            complete: () => {
                $(this).prop('disabled', false);
                $(this).html( 
                    `<i class="fa fa-search"></i>
                    <span class="ml-2">Buscar</span>`
                );
            },
        });
        
    }
    
});

const loader = () =>
    `<span class="spinner-border spinner-border-sm mr-2" 
        role="status" aria-hidden="true">
    </span>Aguarde...`

const notFound = () =>
    `<div class="d-flex align-items-center p-3 mb-3 bg-light">
        <i class="mdi mdi-information font-size-22 mr-2"></i>
        Nenhum registro foi encontrado com a data informada!
    </div>`

const getContent = (appointment) => {
    const baseUrl = $('[data-js="base-url"]').val();
    const date = $('#date').val();
    const [year, month, day] = date.split('-');
    const unityId = $('#unity').val();

    let columnRetest = '';
    let totalRetest = '';
    let buttonRetest = '';

    let columnCanceled = '';
    let totalCanceled = '';
    let buttonCanceled = '';

    if (appointment.retests_total > 0) {
        columnRetest = `<th style="color: #ff7e24;">Retestes</th>`;
        totalRetest = `<td style="color: #ff7e24; font-weight: bold;">${appointment.retests_total}</td>`;
        buttonRetest = (
            `<a href="${baseUrl}/routine/appointments/by/${date}/${unityId}/print/retest" 
                class="btn text-white px-3 py-0" target="_blank"
                style="background-color: #ff7e24; color: #fff;"
            >
                <i class="mdi mdi-printer font-size-22 mr-2 align-middle"></i>
                Retestes
            </a>`
        );
    }

    if (appointment.canceleds_total > 0) {
        columnCanceled = `<th style="color: #f46a6a;">Cancelados</th>`;
        totalCanceled = `<td style="color: #f46a6a; font-weight: bold;">${appointment.canceleds_total}</td>`;
        buttonCanceled = (
            `<a href="${baseUrl}/routine/appointments/by/${date}/${unityId}/print/canceled" 
                class="btn text-white px-3 py-0" target="_blank"
                style="background-color: #f46a6a; color: #fff;"
            >
                <i class="mdi mdi-printer font-size-22 mr-2 align-middle"></i>
                Cancelados
            </a>`
        );
    }

    return (
        `<div class="d-flex align-items-center alert alert-success mb-3">
            Resultados encontrados! Mas atenção, dependendo da quantidade de exames realizados no dia, essa operação pode levar alguns minutos.
        </div>
        <table class="table table-centered table-bordered table-sm">
            <thead class="bg-light">
                <tr>
                    <th>Total de pacientes</th>
                    <th>Total de exames</th>
                    ${columnRetest}
                    ${columnCanceled}
                    <th>Data do atendimento</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>${appointment.patients_total}</td>
                    <td>${appointment.exams_total}</td>
                    ${totalRetest}
                    ${totalCanceled}
                    <td>${day}/${month}/${year}</td>
                    <td>
                        <a href="${baseUrl}/routine/appointments/by/${date}/${unityId}/print/success" 
                            class="btn btn-success text-white px-3 py-0 mr-2" target="_blank"
                        >
                            <i class="mdi mdi-printer font-size-22 mr-2 align-middle"></i>
                            Imprimir
                        </a>
                        ${buttonRetest}
                        ${buttonCanceled}
                    </td>
                </tr>
            </tbody>
        </table>`
    )
}
