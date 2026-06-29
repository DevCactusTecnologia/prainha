const baseUrl = $('[data-js="base-url"]').val();
const csrf = $('meta[name="csrf-token"]').attr('content');
let timer;

$('#searchFaq').keyup(function (event) {
    clearTimeout(timer);
    const milliseconds = 500;
    timer = setTimeout((event) => {

        // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
        if (event.keyCode !== 13) {
            $.ajax({
                type: 'POST',
                url: `${baseUrl}/faqs/search`,
                data: {
                    'name': $(this).val(),
                    '_token': $("input[name='_token']").val(),
                },
                beforeSend: () => {
                    $('#contentFaq').css({
                        display: 'none'
                    });
                    $('#paginate').css({
                        display: 'none'
                    });
                    $('#loader').removeAttr('style');
                },
                success: function (response) {
                    response.faqs.length <= 0 ?
                        $('#contentFaq').html(notFound()) :
                        $('#contentFaq').html(getContent(response.faqs))
                },
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({
                        display: 'none'
                    });
                    $('#contentFaq').removeAttr('style');
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

const getContent = (faqs) => {
    return faqs.reduce((acumulator, faq, index) => {

        const statusName = faq.is_deleted == '1' ? 'Inativo' : 'Ativo';
        const statusColor = faq.is_deleted == '1' ?
            'alert-danger rounded-pill px-3 py-1' :
            'alert-success rounded-pill px-3 py-1';

        acumulator +
            `<tr>
            <td>${index + 1}</td>
            <td>${faq.quetion}</td>
            <td>${faq.order}</td>
            <td>${faq.created_at}</td>
            <td>
                <a href="${baseUrl}/faqs/${faq.id}/edit" title="edit faq" class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-lead-pencil"></i>
                </a>
                <a href="javascript:void(0)" onclick="confirm('Are you sure?')?document.getElementById('delete-form-${faq.id}').submit():'';" title="delete faqs" class="btn btn-danger btn-sm btn-rounded waves-effect mb-2 mb-md-0">
                    <i class="mdi mdi-delete"></i>
                </a>
                <form method="POST" action="${baseUrl}/faqs/${faq.id}" accept-charset="UTF-8" id="delete-form-${faq.id}"><input name="_method" type="hidden" value="DELETE"><input name="_token" type="hidden" value="${csrf}">
                </form>
            </td>
        </tr>`
    }, '')
}
