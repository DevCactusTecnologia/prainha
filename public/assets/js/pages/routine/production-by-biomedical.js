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
        url: $('[data-js="formProductionByBiomedical"]').attr('action'),
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
            response.biomedicals.length <= 0 
                ? $('[data-js="content"]').html(notFound())
                : $('[data-js="content"]').html(getContent(response.biomedicals))
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

const getContent = (biomedicals) => {

    const baseUrl = $('[data-js="baseUrl"]').val();
    const dateStart = $('[data-js="dateStart"]').val();
    const dateEnd = $('[data-js="dateEnd"]').val();

    return biomedicals.reduce((acumulator, biomedical, index) => 
        acumulator + `<tr>
            <td>${index + 1}</td>
            <td>${biomedical.name}</td>
            <td>${biomedical.exams_analyzeds_total}</td>
            <td>
                <a href="${baseUrl}/routine/production-by-biomedical/${biomedical.id}/between/${dateStart}/${dateEnd}/view" 
                    target="_blank" class="btn btn-sm rounded-circle bg-primary" 
                    target="_blank" title="Visualizar produção"
                >
                    <i class="mdi mdi-eye font-size-14 align-middle text-white"></i>
                </a>
                <a href="${baseUrl}/routine/production-by-biomedical/${biomedical.id}/between/${dateStart}/${dateEnd}/amount" 
                    target="_blank" class="btn btn-sm rounded-circle bg-success" 
                    target="_blank" title="Visualizar quantitativo"
                >
                    <i class="mdi mdi-google-analytics font-size-14 align-middle text-white"></i>
                </a>
            </td>
        </tr>`
    , '');

}

const getContentByBiomedical = (biomedicals) => {

    return biomedicals.map(function (biomedical) {
        
        return `<h5>${biomedical.name}</h5>
            <table class="table table-sm table-centered table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>Exames analisados</th>
                        <th>Data da análise</th>
                    </tr>
                </thead>
                <tbody>
                    ${Object.entries(biomedical.exam_collected_at).map(function ([collectedAt, exams]) {
                        return `<tr>
                            <td>
                                ${Object.keys(  getExams(exams)  ).map(function(key) {
                                    return `<div>${getExams(exams)[key]}x ${key}</div>`
                                }).join('')}
                            </td>
                            <td>${collectedAt}</td>
                        </tr>`
                    }).join('')}
                </tbody>
            </table>`;
    }).join('');

}

const getExams = (exams) => {
    return exams.reduce(function(acumulator, exam) {
        acumulator[exam] = (acumulator[exam] || 0) + 1;
        return acumulator;
    }, {})
}
