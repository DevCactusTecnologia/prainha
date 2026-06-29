const baseUrl = $('[data-js="base-url"]').val();
const csrf = $('meta[name="csrf-token"]').attr('content');
let timer;

$('#searchmodule').keyup(function (event) {
    clearTimeout(timer);
    const milliseconds = 500;
    timer = setTimeout((event) => {

        // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
        if (event.keyCode !== 13) {
            $.ajax({
                type: 'POST',
                url: `${baseUrl}/modules/search`,
                data: {
                    'name': $(this).val(),
                    '_token': $("input[name='_token']").val(),
                },
                beforeSend: () => {
                    $('#contentmodule').css({
                        display: 'none'
                    });
                    $('#paginate').css({
                        display: 'none'
                    });
                    $('#loader').removeAttr('style');
                },
                success: function (response) {
                    response.modules.length <= 0 ?
                        $('#contentmodule').html(notFound()) :
                        $('#contentmodule').html(getContent(response.modules))
                },
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({
                        display: 'none'
                    });
                    $('#contentmodule').removeAttr('style');
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

const getContent = (modules) => {
    return modules.reduce((acumulator, module, index) => {

        const statusName = module.is_deleted == '1' ? 'Inativo' : 'Ativo';
        const statusColor = module.is_deleted == '1' ?
            'alert-danger rounded-pill px-3 py-1' :
            'alert-success rounded-pill px-3 py-1';

        acumulator +
            `<tr>
            <td>${index + 1}</td>
            <td>${module.name}</td>
            <td>
                <a href="${baseUrl}/modules/${module.id}/edit" title="edit module" class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-lead-pencil"></i>
                </a>
                <a href="javascript:void(0)" onclick="confirm('Are you sure?')?document.getElementById('delete-form-${module.id}').submit():'';" title="delete module" class="btn btn-danger btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-delete"></i>
                </a>
                <form method="POST" action="${baseUrl}/modules/${module.id}" accept-charset="UTF-8" id="delete-form-${module.id}"><input name="_method" type="hidden" value="DELETE"><input name="_token" type="hidden" value="${csrf}">
                </form>
            </td>
        </tr>`
    }, '')
}
