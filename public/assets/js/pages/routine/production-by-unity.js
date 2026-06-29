$('[data-js="searchProductionByBiomedical"]').click(function (event) {
    event.preventDefault();

    const alert = $('[data-js="alert"]');
    const dateStart = $('[data-js="dateStart"]').val();
    const checkDateStart = Date.parse(dateStart);
    const dateEnd = $('[data-js="dateEnd"]').val();
    const checkDateEnd = Date.parse(dateEnd);
    
    if (isNaN(checkDateStart)) {
        return alert
            .css({display: 'block'})
            .text('A data inicial está inválida ou vazia!')
            .delay(3000)
            .hide('fast');
    }

    if (isNaN(checkDateEnd)) {
        return alert
            .css({display: 'block'})
            .text('A data final está inválida ou vazia!')
            .delay(3000)
            .hide('fast');
    }

    if (compareDates(dateStart, dateEnd)) {
        return alert
            .css({display: 'block'})
            .text('A data inicial deve ser menor ou igual a data final!')
            .delay(3000)
            .hide('fast');
    }

    $.ajax({
        url: $('[data-js="formProductionByUnity"]').attr('action'),
        type: 'POST',
        data: {
            date_start: dateStart,
            date_end: dateEnd,
            _token: $('[name="_token"]').val(),
        },
        beforeSend: () => {
            $(this).html(loader());
            $(this).prop('disabled', true);
        },
        success: (response) => {
            response.unitys.length <= 0 
                ? $('[data-js="content"]').html(notFound())
                : $('[data-js="content"]').html(getContent(response.unitys))
        },
        error: function (response) {
            toastr.error(response.responseJSON.message);
        },
        complete: () => {
            $('[data-js="tableContent"]').css({ display: 'block'});

            $(this).prop('disabled', false);
            $(this).html( 
                `<i class="fa fa-search"></i>
                <span class="ml-2">Buscar</span>`
            );
        },
    }); 
    
});

const compareDates = (dateStart, dateEnd) => {
    const timeStart = new Date(dateStart).getTime();
    const timeEnd = new Date(dateEnd).getTime();

    if (timeStart > timeEnd) {
        return true;
    }

    return false;
};

const loader = () =>
    `<span class="spinner-border spinner-border-sm mr-2" 
        role="status" aria-hidden="true">
    </span>Aguarde...`

const notFound = () =>
    `<tr>
        <td class="text-center p-3" colspan="4">Nenhum resultado encontrado</td>
    </tr>`

const getContent = (unitys) => {

    const baseUrl = $('[data-js="baseUrl"]').val();
    const dateStart = $('[data-js="dateStart"]').val();
    const dateEnd = $('[data-js="dateEnd"]').val();

    return Object.entries(unitys).reduce((acumulator, unity, index) => 
        acumulator + `<tr>
            <td>${index + 1}</td>
            <td>${unity[0]}</td>
            <td class="text-center">${unity[1].exams_total}</td>
            <td>
                <a href="${baseUrl}/routine/production-by-unity/${unity[1].id}/between/${dateStart}/${dateEnd}/view" 
                    class="btn btn-sm rounded-circle bg-primary" target="_blank" title="Imprimir produção"
                >
                    <i class="mdi mdi-printer font-size-14 align-middle text-white"></i>
                </a>
            </td>
        </tr>`
    , '');

}
