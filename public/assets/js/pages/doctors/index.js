const baseUrl = $('[data-js="base-url"]').val();
let timer;

$('#searchDoctor').keyup(function(event) {
    clearTimeout(timer);

    const milliseconds = 500;
    timer = setTimeout((event) => {

        // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
        if (event.keyCode !== 8 && event.keyCode !== 13) {
            $.ajax({
                type: 'POST',
                url: `${baseUrl}/doctors/search`,
                data: {
                    'name': $(this).val(), 
                    '_token': $("input[name='_token']").val(),   
                },
                beforeSend: () => {
                    $('#contentDoctor').css({display: 'none'});
                    $('#paginate').css({display: 'none'});
                    $('#loader').removeAttr('style');
                },
                success: function (response) {
                    response.doctors.length <= 0 
                        ? $('#contentDoctor').html(notFound())
                        : $('#contentDoctor').html(getContent(response.doctors))
                },
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({display: 'none'});
                    $('#contentDoctor').removeAttr('style');
                    $('#paginate').css({display: 'block'});
                },
            });
        }

    }, milliseconds, event);
});

const notFound = () =>
    `<tr>
        <td colspan="7" class="bg-light">
            <div class="d-flex justify-content-center align-items-center" 
                style="height: 80px; font-size: 16px;"
            >
                Nenhum resultado encontrado.
            </div>
        </td>
    </tr>`

const getContent = (doctors) => {
    return doctors.reduce((acumulator, doctor, index) => {

        const statusName = doctor.is_deleted == '1' ? 'Inativo' : 'Ativo';
        const statusColor = doctor.is_deleted == '1' 
            ? 'alert-danger rounded-pill px-3 py-1' 
            : 'alert-success rounded-pill px-3 py-1';
   
        return acumulator + 
            `<tr>
                <td>${index + 1}</td>
                <td>${doctor.first_name}</td>
                <td>${doctor.class_council_name}</td>
                <td>${doctor.state_name}</td>
                <td>${doctor.counsil_number}</td>
                <td>
                    <span class="${statusColor}">
                        ${statusName}
                    </span>
                </td>
                <td>
                    <a href="${baseUrl}/doctors/${doctor.id}" title="Visualizar Perfil"
                        class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                    >
                        <i class="mdi mdi-eye"></i>
                    </a>
                    <a href="${baseUrl}/doctors/${doctor.id}/edit" title="Editar Perfil"
                        class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                    >
                        <i class="mdi mdi-lead-pencil"></i>
                    </a>
                </td>
            </tr>`
    }, '')
}
