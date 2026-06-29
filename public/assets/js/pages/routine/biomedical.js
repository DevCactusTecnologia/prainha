$('#searchExamByBiomedical').click(function (event) {
    event.preventDefault();

    if ($('#date').val() == '') {
        alert('Informe a data do atendimento');
    } else {
        $.ajax({
            url: $('#formMapBiomedical').attr('action'),
            type: 'POST',
            data: {
                date: $('#date').val(),
                _token: $('[name="_token"]').val(),
            },
            beforeSend: () => {
                $(this).html(loader());
                $(this).prop('disabled', true);
            },
            success: (response) => {
                response.biomedicals.length <= 0 
                    ? $('#contentMap').html(notFound())
                    : $('#contentMap').html(getContent(response.biomedicals))
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
    `<span class="spinner-border spinner-border-sm mr-2" 
        role="status" aria-hidden="true">
    </span>Aguarde...`

const notFound = () =>
    `<tr>
        <td class="text-center p-3" colspan="5">Nenhum resultado encontrado</td>
    </tr>`

const getContent = (biomedicals) => {
    return biomedicals.reduce((acumulator, biomedical) =>
        acumulator + `<tr>
            <td>${biomedical.name}</td>
            <td>
                <ul>
                    ${biomedical.exams.map((exam) => 
                        `<li>${exam}</li>`
                    ).join('')}
                </ul>
            </td>
            <td>${biomedical.date_formatted}</td>
            <td>
                <span class="alert alert-warning rounded-pill py-1" 
                    style="font-weight: 500;"
                >
                    Pendente
                </span>
            </td>
            <td>
                <a href="/routine/map/biomedical/${biomedical.id}/${biomedical.date}/print" target="_blank"
                    class="btn py-0" target="_blank" title="Imprimir mapa do analista"
                >
                    <i class="mdi mdi-printer font-size-24 align-middle text-primary"></i>
                </a>
            </td>
        </tr>`
    , '')
}
