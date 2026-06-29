let timer;
const baseUrl = $('[data-js="base-url"]').val();
const fillUrl = $('[data-js="fill-url"]').val();
const companyId = $('[data-js="company-id"]').val();
const csrfToken = $('[name="_token"]').val();

function updateCompany(button, examId) {
    const price = $(`[data-js="${examId}-price"]`);
    const deadline = $(`[data-js="${examId}-deadline"]`);

    $.ajax({
        type: 'POST',
        url: `${baseUrl}/companies/update-exam`,
        headers: {'X-CSRF-TOKEN': csrfToken},
        data: {
            _token: csrfToken,
            id: examId,
            price: price.val(),
            deadline: deadline.val(),
        },
        beforeSend: function() {
            button.disabled = true;
            button.innerHTML = (
                `<span class="spinner-border spinner-border-sm mr-2" 
                    role="status" aria-hidden="true">
                </span>`
            );
        },
        success: function (response) {
            toastr.success(response.message);
        },
        error: function (response) {
            toastr.error(response.responseJSON.message);
        },
        complete: function () {
            button.disabled = false;
            button.innerHTML = (
                `<span style="color: #33c38e;">
                    <i class="mdi mdi-checkbox-marked-circle font-size-18 align-middle"></i>
                </span>`
            );
        }
    });
};

$('[data-js="search-exam"]').keyup(function(event) {
    clearTimeout(timer);

    const milliseconds = 500;
    timer = setTimeout((event) => {

        // SE NAO TIVER SIDO PRESSIONADA A TECLA BACKSPACE OU ENTER
        if (event.keyCode !== 8 && event.keyCode !== 13) {
            $.ajax({
                type: 'POST',
                url: `${baseUrl}/companies/search`,
                data: {
                    'name': $(this).val(), 
                    'company_id': companyId,
                    '_token': $("input[name='_token']").val(),   
                },
                beforeSend: () => {
                    $('[data-js="content-exam"]').css({display: 'none'});
                    $('#paginate').css({display: 'none'});
                    $('#loader').removeAttr('style');
                },
                success: function (response) {
                    response.exams.length <= 0 
                        ? $('[data-js="content-exam"]').html(notFound())
                        : $('[data-js="content-exam"]').html(getContent(response.exams))
                },
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({display: 'none'});
                    $('[data-js="content-exam"]').removeAttr('style');
                    $('#paginate').css({display: 'block'});
                },
            });
        }

    }, milliseconds, event);
});

const notFound = () =>
    `<tr>
        <td colspan="9" class="bg-light">
            <div class="d-flex justify-content-center align-items-center" 
                style="height: 80px; font-size: 16px;"
            >
                Nenhum resultado encontrado.
            </div>
        </td>
    </tr>`

const getContent = (exams) => {

    return exams.reduce((acumulator, exam, index) => {
        return acumulator + 
            `<tr>
                <td>${index + 1}</td>
                <td>${exam.abbreviation}</td>
                <td>${exam.name}</td>
                <td>${exam.category}</td>
                <td>${exam.destiny}</td>
                <td class="text-center">
                    <input type="number" class="form-control text-center form-control-sm" 
                        min="1" max="255" data-js="${exam.id}-deadline" value="${exam.deadline ? exam.deadline : ''}"
                    >
                </td>
                <td class="text-center">
                    ${exam.code_cbhpm ? exam.code_cbhpm : ''}
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-center" 
                        id="price-${index + 1}" value="${exam.price}"
                        data-js="${exam.id}-price"
                    >
                    <script>
                        (async () => {
                            const { Fill } = await import("${fillUrl}");
                            Fill.mask({currencyBrl: '#price-${index + 1}'});
                        })();
                    </script>
                </td>
                <td>
                    <button class="btn mx-0 py-0" onclick="updateCompany(this, ${exam.id})"
                        title="Salvar preço do exame"
                    >
                        <span style="color: #33c38e;">
                            <i class="mdi mdi-checkbox-marked-circle font-size-18 align-middle"></i>
                        </span>
                    </button>
                </td>
        </tr>`
    }, '')
}

function loader(button) {
    setTimeout(() => {
        button.disabled = true;
        button.innerHTML = `<span class="spinner-border spinner-border-sm" 
            role="status" aria-hidden="true">
        </span>`;
    }, 20);

    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = 'Salvar';
    }, 7000);
}

(async () => {
    const { Fill } = await import(fillUrl);
    
    Fill.mask({currencyBrl: '#price'});
})();
