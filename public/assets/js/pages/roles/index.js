const baseUrl = $('[data-js="base-url"]').val();
const csrf = $('meta[name="csrf-token"]').attr('content');
let timer;

$('#searchRole').keyup(function (event) {
    clearTimeout(timer);
    const milliseconds = 500;
    timer = setTimeout((event) => {

        // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
        if (event.keyCode !== 13) {
            $.ajax({
                type: 'POST',
                url: `${baseUrl}/roles/search`,
                data: {
                    'name': $(this).val(),
                    '_token': $("input[name='_token']").val(),
                },
                beforeSend: () => {
                    $('#contentRole').css({
                        display: 'none'
                    });
                    $('#paginate').css({
                        display: 'none'
                    });
                    $('#loader').removeAttr('style');
                },
                success: function (response) {
                    response.roles.length <= 0 ?
                        $('#contentRole').html(notFound()) :
                        $('#contentRole').html(getContent(response.roles))
                },
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({
                        display: 'none'
                    });
                    $('#contentRole').removeAttr('style');
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

const getContent = (roles) => {
    return roles.reduce((acumulator, role, index) => {

        const statusName = role.is_deleted == '1' ? 'Inativo' : 'Ativo';
        const statusColor = role.is_deleted == '1' ?
            'alert-danger rounded-pill px-3 py-1' :
            'alert-success rounded-pill px-3 py-1';

        acumulator +
            `<tr>
            <td>${index + 1}</td>
            <td>${role.name}</td>
            <td>
                <a href="${baseUrl}/roles/${role.id}" title="view paper" class="btn btn-info btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-eye"></i>
                </a>

                <a href="${baseUrl}/roles/${role.id}/edit" title="edit role" class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-lead-pencil"></i>
                </a>
                <a href="javascript:void(0)" onclick="confirm('Are you sure?')?document.getElementById('delete-form-${role.id}').submit():'';" title="delete roles" class="btn btn-danger btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-delete"></i>
                </a>
                <form method="POST" action="${baseUrl}/roles/${role.id}" accept-charset="UTF-8" id="delete-form-${role.id}"><input name="_method" type="hidden" value="DELETE"><input name="_token" type="hidden" value="${csrf}">
                </form>
            </td>
        </tr>`
    }, '')
}
