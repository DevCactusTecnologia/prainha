const baseUrl = $('[data-js="base-url"]').val();
let timer;

$('#searchCitology').keyup(function(event) {
    clearTimeout(timer);

    const milliseconds = 500;
    timer = setTimeout((event) => {

        // SE NÃO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
        if (event.keyCode !== 8 && event.keyCode !== 13) {
            $.ajax({
                type: 'POST',
                url: `${baseUrl}/citologies/search`,
                data: {
                    'name': $(this).val(), 
                    '_token': $("input[name='_token']").val(),   
                },
                beforeSend: () => {
                    $('#contentCytology').css({display: 'none'});
                    $('#paginate').css({display: 'none'});
                    $('#loader').removeAttr('style');
                },
                success: function (response) {
                    response.citologies.length <= 0
                        ? $('#contentCytology').html(notFound())
                        : $('#contentCytology').html(getContent(response.citologies))
                },
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({display: 'none'});
                    $('#contentCytology').removeAttr('style');
                    $('#paginate').css({display: 'block'});
                },
            });
        }

    }, milliseconds, event);
});

const notFound = () =>
    `<tr>
        <td colspan="5" class="bg-light">
            <div class="d-flex justify-content-center align-items-center" 
                style="height: 80px; font-size: 16px;"
            >
                Nenhum resultado encontrado.
            </div>
        </td>
    </tr>`

const getContent = (citologies) => {
    return citologies.reduce((acumulator, citology, index) => {

        const statusName = citology.is_active == '1' ? 'Ativo' : 'Inativo';
        const statusColor = citology.is_active == '1' 
            ? 'alert-success rounded-pill px-3 py-1'
            : 'alert-danger rounded-pill px-3 py-1' ;

        return acumulator + 
            `<tr>
                <td>${index + 1}</td>
                <td>${citology.subitem_name}</td>
                <td>${citology.category_name}</td>
                <td>
                    <span class="${statusColor}">
                        ${statusName}
                    </span>
                </td>
                <td>
                    <a href="${baseUrl}/citologies/${citology.id}" title="Visualizar citologia"
                        class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                    >
                        <i class="mdi mdi-eye"></i>
                    </a>
                    <a href="${baseUrl}/citologies/${citology.id}/edit" title="Editar citologia"
                        class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                    >
                        <i class="mdi mdi-lead-pencil"></i>
                    </a>
                </td>
            </tr>`
    }, '');
}
