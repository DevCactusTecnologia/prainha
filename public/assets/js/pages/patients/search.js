const baseUrl = $('[data-js="base-url"]').val();
let timer;

$('#searchPatient').keyup(function(event) {
    clearTimeout(timer);

    const milliseconds = 500;
    timer = setTimeout((event) => {

        // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
        if (event.keyCode !== 8 && event.keyCode !== 13) {
            $.ajax({
                type: 'POST',
                url: `${baseUrl}/patients/search`,
                data: {
                    'name': $(this).val(), 
                    '_token': $("input[name='_token']").val(),   
                },
                beforeSend: () => {
                    $('#contentPatient').css({display: 'none'});
                    $('#paginate').css({display: 'none'});
                    $('#loader').removeAttr('style');
                },
                success: function (response) {
                    response.patients.length <= 0
                        ? $('#contentPatient').html(notFound())
                        : $('#contentPatient').html(getContent(response.patients))
                },
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({display: 'none'});
                    $('#contentPatient').removeAttr('style');
                    $('#paginate').css({display: 'block'});
                },
            });
        }

    }, milliseconds, event);
});

const notFound = () =>
    `<tr>
        <td colspan="6" class="bg-light">
            <div class="d-flex justify-content-center align-items-center" 
                style="height: 80px; font-size: 16px;"
            >
                Nenhum resultado encontrado.
            </div>
        </td>
    </tr>`

const getContent = (patients) => {
    return patients.reduce((acumulator, patient, index) => {

        const statusName = patient.is_deleted == '1' ? 'Inativo' : 'Ativo';
        const statusColor = patient.is_deleted == '1' 
            ? 'alert-danger rounded-pill px-3 py-1' 
            : 'alert-success rounded-pill px-3 py-1';

        return acumulator + 
            `<tr>
                <td>${index + 1}</td>
                <td>${patient.name}</td>
                <td>${patient.cpf_masked}</td>
                <td>${patient.cns_masked}</td>
                <td>
                    <span class="${statusColor}">
                        ${statusName}
                    </span>
                </td>
                <td>
                    <a href="${baseUrl}/patients/${patient.id}" title="Visualizar paciente"
                        class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                    >
                        <i class="mdi mdi-eye"></i>
                    </a>
                    <a href="${baseUrl}/patients/${patient.id}/edit" title="Editar paciente"
                        class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                    >
                        <i class="mdi mdi-lead-pencil"></i>
                    </a>
                </td>
            </tr>`
    }, '');
}
