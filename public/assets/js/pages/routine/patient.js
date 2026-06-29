$('#searchPatient').click(function (event) {
    event.preventDefault();

    if ($('#date').val() == '' && $('#patient').val() == '') {
        alert('Preencha pelo menos um campo!');
    } else {
        $.ajax({
            url: $('#formMapPatient').attr('action'),
            type: 'POST',
            data: {
                date: $('#date').val(),
                patient: $('#patient').val(),
                _token: $('[name="_token"]').val(),
            },
            beforeSend: () => {
                $(this).html(loader());
                $(this).prop('disabled', true);
            },
            success: (response) => {
                response.patients.length <= 0
                    ? $('#contentMap').html(notFound())
                    : $('#contentMap').html(getContent(response.patients));
            },
            error: function (response) {
                toastr.error(response.responseJSON.message);
            },
            complete: () => {
                $('#contentTable').css({ display: 'block'});

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
    `<span class="spinner-border spinner-border-sm" 
        role="status" aria-hidden="true">
    </span>`

const notFound = () =>
    `<tr>
        <td class="text-center p-3" colspan="6">Nenhum resultado encontrado</td>
    </tr>`

const getContent = (patients) => {
    return patients.reduce((acumulator, patient) =>
        acumulator + `<tr>
            <td class="text-center">${patient.protocol}</td>
            <td>${patient.name}</td>
            <td>${patient.cpf}</td>
            <td class="text-center">${patient.registered_at}</td>
            <td>
                <div class="mb-0">
                    <span class="alert alert-warning rounded-pill py-1" 
                        style="font-weight: 500;"
                    >
                        Pendente
                    </span>
                </div>
            </td>
            <td>
                <a href="/routine/map/patient/${patient.protocol}/print" target="_blank"
                    class="btn py-0" target="_blank" title="Imprimir mapa do paciente"
                >
                    <i class="mdi mdi-printer font-size-24 align-middle text-primary"></i>
                </a>
            </td>
        </tr>`
    , '')
}
