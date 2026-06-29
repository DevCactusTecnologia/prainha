const baseUrl = $('[data-js="base-url"]').val();

$('[data-js="search-sector"]').click(function (event) {
    event.preventDefault();

    if ($('[data-js="date"]').val() == '') {
        return alert('Atenção! Preencha a data do atendimento!');
    }

    $.ajax({
        url: $('[data-js="form-map-sector"]').attr('action'),
        type: 'POST',
        data: {
            date: $('[data-js="date"]').val(),
            _token: $('[name="_token"]').val(),
        },
        beforeSend: () => {
            $(this).html(loader());
            $(this).prop('disabled', true);
        },
        success: (response) => {
            response.sectors.length <= 0
                ? $('[data-js="content-map"]').html(notFound())
                : $('[data-js="content-map"]').html(getContent(response.sectors));
        },
        error: function (response) {
            toastr.error(response.responseJSON.message);
        },
        complete: () => {
            $('[data-js="content-table"]').css({ display: 'block'});

            $(this).prop('disabled', false);
            $(this).html( 
                `<i class="fa fa-search"></i>
                <span class="ml-2">Buscar</span>`
            );
        },
    });
});

const loader = () =>
    `<span class="spinner-border spinner-border-sm mr-2" 
        role="status" aria-hidden="true">
    </span>Buscar`

const notFound = () =>
    `<tr>
        <td class="text-center p-3" colspan="6">Nenhum resultado encontrado</td>
    </tr>`


function getContent (sectors) {
    const list = [];
    let exams = '';
    const regsiteredAt = $('[data-js="date"]').val();
    const [year, month, day] = $('[data-js="date"]').val().split('-');

    Object.entries(sectors).forEach(sector => {
        exams = '';
        
        sector.forEach((categories, index) => {
            if (index <= 0) {
                const [categoryId, categoryName] = categories.split('|');
                exams += `${categoryId}|${categoryName}|`;
            } else {
                exams += '<ul>';
                for (const [exam, total] of Object.entries(categories)) {
                    exams += `<li>${total}x ${exam}</li>`;
                }
                exams += '</ul>'
            }
        })

        list.push(exams);
    });

   return list.reduce((acumulator, sector, index) =>
        acumulator + `<tr>
            <td class="text-center">${index + 1}</td>
            <td>${sector.split('|')[1]}</td>
            <td>${sector.split('|')[2]}</td>
            <td class="text-center">${day}/${month}/${year}</td>
            <td>
                <div class="mb-0">
                    <span class="alert rounded-pill py-1" 
                        style="font-weight: 500; background-color: #ffe4b3; color: #825a0e;"
                    >
                        Pendente
                    </span>
                </div>
            </td>
            <td>
                <a href="${baseUrl}/routine/map/sector/${sector.split('|')[0]}/${regsiteredAt}/print" target="_blank"
                    class="btn py-0" title="Imprimir mapa por setor"
                >
                    <i class="mdi mdi-printer font-size-24 align-middle text-success"></i>
                </a>
            </td>
        </tr>`
    , '')
}
