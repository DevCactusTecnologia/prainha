$('[data-js="searchTag"]').click(function (event) {
    event.preventDefault();

    const alert = $('[data-js="alert"]');
    const dateStart = $('[data-js="dateStart"]').val();
    const checkDateStart = Date.parse(dateStart);
    const dateEnd = $('[data-js="dateEnd"]').val();
    const checkDateEnd = Date.parse(dateEnd);
    const unityId = $('[data-js="unityId"]').val();
    
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

    if (dateDiffInDays(dateStart, dateEnd) > 90) {
        return alert
            .css({display: 'block'})
            .text('Atenção! O período de seleção (data inicial e final) deve ser menor do que 90 dias!')
            .delay(3000)
            .hide('fast');
    }

    if (unityId == '') {
        return alert
            .css({display: 'block'})
            .text('Selecione a unidade de atendimento!')
            .delay(3000)
            .hide('fast');
    }

    $.ajax({
        url: $('[data-js="formTagByUnity"]').attr('action'),
        type: 'POST',
        data: {
            date_start: dateStart,
            date_end: dateEnd,
            unity_id: unityId,
            _token: $('[name="_token"]').val(),
        },
        beforeSend: () => {
            $(this).html(loader());
            $(this).prop('disabled', true);
        },
        success: (response) => {
            response.tags <= 0 
                ? $('[data-js="content"]').html(notFound())
                : $('[data-js="content"]').html(getContent(response.tags))
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

const dateDiffInDays = (dateStart, dateEnd) => {
    const start = new Date(dateStart);
    const end = new Date(dateEnd);

    const diffInTime = Math.abs(end - start);
    const diffInDays = Math.ceil(diffInTime / (1000 * 60 * 60 * 24)); 

    return diffInDays;
};

const loader = () =>
    `<span class="spinner-border spinner-border-sm mr-2" 
        role="status" aria-hidden="true">
    </span>Aguarde...`

const notFound = () =>
    `<tr>
        <td class="text-center bg-soft-secondary p-3" colspan="3">Nenhum resultado encontrado</td>
    </tr>`

const getContent = (tags) => {
    const baseUrl = $('[data-js="baseUrl"]').val();
    const dateStart = $('[data-js="dateStart"]').val();
    const dateEnd = $('[data-js="dateEnd"]').val();
    const unityName = $("[data-js='unityId'] option:selected").text();
    const unityId = $("[data-js='unityId']").val();

    return (
        `<tr>
            <td>${unityName}</td>
            <td class="text-center">${tags}</td>
            <td>
                <a href="${baseUrl}/routine/tag/by-unity/${unityId}/between/${dateStart}/${dateEnd}/view" 
                    class="btn btn-sm rounded-circle bg-primary" target="_blank" title="Imprimir etiquetas"
                >
                    <i class="mdi mdi-printer font-size-14 align-middle text-white"></i>
                </a>
            </td>
        </tr>`
    );
}
