const baseUrl = $('[data-js="base-url"]').val();
const csrf = $('meta[name="csrf-token"]').attr('content');
let timer;

$('#searchUser').keyup(function (event) {
    clearTimeout(timer);
    const milliseconds = 500;
    timer = setTimeout((event) => {

        // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
        if (event.keyCode !== 8 && event.keyCode !== 13) {
            $.ajax({
                type: 'POST',
                url: `${baseUrl}/users/search`,
                data: {
                    'name': $(this).val(),
                    '_token': $("input[name='_token']").val(),
                },
                beforeSend: () => {
                    $('#contentUser').css({
                        display: 'none'
                    });
                    $('#paginate').css({
                        display: 'none'
                    });
                    $('#loader').removeAttr('style');
                },
                success: function (response) {
                    response.users.length <= 0 ?
                        $('#contentUser').html(notFound()) :
                        $('#contentUser').html(getContent(response.users))
                },
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({
                        display: 'none'
                    });
                    $('#contentUser').removeAttr('style');
                    $('#paginate').css({
                        display: 'block'
                    });
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

const getContent = (users) => {
    return users.reduce((acumulator, user, index) => {

        const statusName = user.is_deleted == '1' ? 'Inativo' : 'Ativo';
        const statusColor = user.is_deleted == '1' ?
            'alert-danger rounded-pill px-3 py-1' :
            'alert-success rounded-pill px-3 py-1';

        acumulator +
            `<tr>
            <td>${index + 1}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>`;
        if (user.active_status == 1) {
            acumulator + `<span class="badge rounded-pill bg-success text-white p-2 px-3">Ativa</span>`
        } else {
            acumulator + `<span class="badge rounded-pill bg-danger text-white p-2 px-3">desativado</span>`
        }
        acumulator + `</td>
            <td>
                ${user.domain}
            </td>
            <td>
                ${user.actual_domain}
            </td>
            <td>`;
        if (user.active_status != 1) {
            acumulator + `<a href="${baseUrl}/user-status/${user.id}"
                        title="Ativa usuária"
                        class="btn btn-success btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                        <i class="mdi mdi-check"></i>
                    </a>`
        } else {
            acumulator + `<a href="${baseUrl}/user-status/${user.id}"
                        title="desativado usuária"
                        class="btn btn-danger btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                        <i class="mdi mdi-close"></i>
                    </a>`
        }
        acumulator + `<a href="${baseUrl}/users/${user.id}/impersonate"
                    title="personificar usuária" target="_blanck"
                    class="btn btn-info btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-account-convert"></i>
                </a>
                <a href="${baseUrl}/users/${user.id}/edit" title="Editar usuária"
                    class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-lead-pencil"></i>
                </a>
                <a href="javascript:void(0)"
                    onclick="confirm('Are you sure?')?document.getElementById('delete-form-${user.id}').submit():'';"
                    title="excluir papéis"
                    class="btn btn-danger btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-delete"></i>
                </a>
                <form method="POST" action="${baseUrl}/users/${user.id}" accept-charset="UTF-8" id="delete-form-${user.id}">
                    <input name="_method" type="hidden" value="DELETE">
                    <input name="_token" type="hidden" value="${csrf}">
                </form>
            </td>
        </tr>`
    }, '')
}
