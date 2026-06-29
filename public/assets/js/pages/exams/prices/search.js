const baseUrl = $('[data-js="base-url"]').val();
let timer;

$('#searchPrice').keyup(function(event) {
    clearTimeout(timer);

    const milliseconds = 500;
    timer = setTimeout((event) => {

        // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
        if (event.keyCode !== 8 && event.keyCode !== 13) {
            $.ajax({
                type: 'POST',
                url: $('#urlSearch').val(),
                data: {
                    'name': $(this).val(), 
                    '_token': $("input[name='_token']").val(),   
                },
                beforeSend: () => {
                    $('#contentPrice').css({display: 'none'});
                    $('#paginate').css({display: 'none'});
                    $('#loader').removeAttr('style');
                },
                success: (response) =>
                    response.prices.length <= 0
                        ? $('#contentPrice').html(empty())
                        : $('#contentPrice').html(getContent(response.prices))
                ,
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({display: 'none'});
                    $('#contentPrice').removeAttr('style');
                    $('#paginate').css({display: 'block'});
                },
            });
        }

    }, milliseconds, event);
});

const empty = () => 
    `<tr>
        <td colspan="7" class="bg-light">
            <div class="d-flex justify-content-center align-items-center" 
                style="height: 80px; font-size: 16px;"
            >
                Nenhum resultado encontrado.
            </div>
        </td>
    </tr>`;

const getContent = (prices) =>
    prices.reduce((acumulator, price) => {
        return acumulator + 
            `<tr>
                <td>${price.exam_name}</td>
                <td>${price.exam_abbreviation}</td>
                <td>${price.exam_category}</td>
                <td>${price.company_name}</td>
                <td>${price.price}</td>
                <td><span class="${statusColor(price)}">${statusName(price)}</span></td>
                <td>
                    <a href="${baseUrl}/exams/prices/${price.id}/edit" title="Atualizar preço"
                        class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                    >
                        <i class="mdi mdi-lead-pencil"></i>
                    </a>
                </td>
            </tr>`;
    }, '');

const statusName = (price) => 
    price.is_active == '1'
        ? 'Ativo'
        : 'Inativo';

const statusColor = (price) =>
    price.is_active == '1'
        ? 'alert alert-success rounded-pill px-3 py-1'
        : 'alert alert-danger rounded-pill px-3 py-1';
