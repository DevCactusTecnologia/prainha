const baseUrl = $('[data-js="baseUrl"]').val();
const company = $('[name="company_id"]');
let incrementExam = 0;

const discount = $('[name="discount"]');
const discountInput = $('[data-js="discount"]');
const amount = $('[name="amount"]');
const amountInput = $('[data-js="amount"]');

$('[data-js="cpf"]').inputmask({
    mask: [{ "mask": "###.###.###-##"}, { "mask": "###.###.###-##"}], 
    greedy: false, 
    definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
});

$('#searchExamAbbreviation').select2({
    
    ajax: {
        url: $('#urlSearchExamAbbreviation').val(),
        type: 'POST',
        dataType: 'json',
        data: function () {
            return {
                filtro: document.querySelector('.select2-search__field').value,
                company_id: company.val() ? company.val() : 0,
                _token: $("[name='_token']").val(),
            }
        },
        delay: 400,
        processResults: function (data) {
            return {results: data.exams}
        },
        cache: true
    },

    templateResult: function (exams) {
        if (exams.loading) {
            return $(
                `<span class="spinner-border spinner-border-sm text-primary mr-2" role="status" aria-hidden="true">
                </span><span class="text-primary fw-600">Buscando exames...</span>`
            );
        }

        return $(
            `<div><strong>NOME:</strong> ${exams.name}</div>
            <div><strong>ABREVIAÇÃO:</strong> ${exams.abbreviation}</div>
            <div style="border-bottom: 1px solid #CCC;"></div>`
        );
    },

    templateSelection: function (exam) {
        if (exam.id) {
            return $(
                `<div data-id="${exam.id}" data-abbreviation="${exam.abbreviation}"
                    data-name="${exam.name}" data-price="${exam.price}" data-price-masked="${exam.price_masked}"
                >
                    Buscar por sigla
                </div>`
            );
        } 

        return exam.text;
    },

    placeholder: 'Buscar por sigla',
    minimumInputLength: 2,
    language: {
        'noResults': () => 'Nenhum resultado encontrado',
        'searching': () => 'Buscando...',
        'errorLoading': () => 'Os resultados não puderam ser carregados.',
        'inputTooShort': () => `Digite 2 ou mais caracteres`,
    },
        
});

$('#searchExamName').select2({
        
    ajax: {
        url: $('#urlSearchExamName').val(),
        type: 'POST',
        dataType: 'json',
        data: function () {
            return {
                filtro: document.querySelector('.select2-search__field').value,
                company_id: company.val(),
                _token: $("[name='_token']").val(),
            }
        },
        delay: 400,
        processResults: function (data) {
            return {results: data.exams}
        },
        cache: true
    },

    templateResult: function (exams) {
        if (exams.loading) {
            return $(
                `<span class="spinner-border spinner-border-sm text-primary mr-2" role="status" aria-hidden="true">
                </span><span class="text-primary fw-600">Buscando exames...</span>`
            );
        } 

        return $(
            `<div><strong>NOME:</strong> ${exams.name}</div>
            <div><strong>ABREVIAÇÃO:</strong> ${exams.abbreviation}</div>
            <div style="border-bottom: 1px solid #CCC;"></div>`
        );
    },

    templateSelection: function (exam) {
        if (exam.id) {
            return $(
                `<div data-id="${exam.id}" data-abbreviation="${exam.abbreviation}" 
                    data-name="${exam.name}" data-price="${exam.price}" data-price-masked="${exam.price_masked}"
                >
                    Buscar por nome
                </div>`
            );
        } 

        return exam.text;
    },

    placeholder: 'Buscar por nome',
    minimumInputLength: 4,
    language: {
        'noResults': () => 'Nenhum resultado encontrado',
        'searching': () => 'Buscando...',
        'errorLoading': () => 'Os resultados não puderam ser carregados.',
        'inputTooShort': () => `Digite 4 ou mais caracteres`,
    },
        
});

const tbody = document.getElementById('examContent');

function changeExamName() {
    const containerSelected = document.getElementById('select2-searchExamName-container').firstChild;
    const exam = containerSelected.dataset;

    const tr = document.createElement('tr');
    const tdId = document.createElement('td');
    const tdName = document.createElement('td');
    const tdDescription = document.createElement('td');
    const tdPrice = document.createElement('td');

    tdId.innerText = ++incrementExam;
    tdName.innerText = exam.abbreviation;
    tdDescription.innerHTML = getExam(exam);
    tdPrice.innerText = exam.priceMasked;
    setAmount(getPriceCurrent() + parseFloat(exam.price));

    tr.appendChild(tdId);
    tr.appendChild(tdName);
    tr.appendChild(tdDescription);
    tr.appendChild(tdPrice);

    const inputHiddenId = document.createElement('input');
    const inputHiddenAbbreviation = document.createElement('input');
    const inputHiddenName = document.createElement('input');
    const inputHiddenPrice = document.createElement('input');

    inputHiddenId.type = 'hidden';
    inputHiddenId.name = 'exam_ids[]';
    inputHiddenId.value = exam.id;

    inputHiddenAbbreviation.type = 'hidden';
    inputHiddenAbbreviation.name = 'exam_abbreviations[]';
    inputHiddenAbbreviation.value = exam.name;

    inputHiddenName.type = 'hidden';
    inputHiddenName.name = 'exam_names[]';
    inputHiddenName.value = exam.abbreviation;

    inputHiddenPrice.type = 'hidden';
    inputHiddenPrice.name = 'exam_prices[]';
    inputHiddenPrice.value = exam.price;

    tr.appendChild(inputHiddenId);
    tr.appendChild(inputHiddenAbbreviation);
    tr.appendChild(inputHiddenName);
    tr.appendChild(inputHiddenPrice);

    tbody.appendChild(tr);
}

function changeExamAbbreviation() {
    const containerSelected = document.getElementById('select2-searchExamAbbreviation-container').firstChild;
    const exam = containerSelected.dataset;

    const tr = document.createElement('tr');
    const tdId = document.createElement('td');
    const tdName = document.createElement('td');
    const tdDescription = document.createElement('td');
    const tdPrice = document.createElement('td');

    tdId.innerText = ++incrementExam;
    tdName.innerText = exam.abbreviation;
    tdDescription.innerHTML = getExam(exam);
    tdPrice.innerText = exam.priceMasked;
    setAmount(getPriceCurrent() + parseFloat(exam.price));
    
    tr.appendChild(tdId);
    tr.appendChild(tdName);
    tr.appendChild(tdDescription);
    tr.appendChild(tdPrice);

    const inputHiddenId = document.createElement('input');
    const inputHiddenAbbreviation = document.createElement('input');
    const inputHiddenName = document.createElement('input');
    const inputHiddenPrice = document.createElement('input');

    inputHiddenId.type = 'hidden';
    inputHiddenId.name = 'exam_ids[]';
    inputHiddenId.value = exam.id;

    inputHiddenAbbreviation.type = 'hidden';
    inputHiddenAbbreviation.name = 'exam_abbreviations[]';
    inputHiddenAbbreviation.value = exam.name;

    inputHiddenName.type = 'hidden';
    inputHiddenName.name = 'exam_names[]';
    inputHiddenName.value = exam.abbreviation;

    inputHiddenPrice.type = 'hidden';
    inputHiddenPrice.name = 'exam_prices[]';
    inputHiddenPrice.value = exam.price;

    tr.appendChild(inputHiddenId);
    tr.appendChild(inputHiddenAbbreviation);
    tr.appendChild(inputHiddenName);
    tr.appendChild(inputHiddenPrice);

    tbody.appendChild(tr);
}

function removeExam(element) {
    const valueDeleted = element
        .parentElement
        .parentElement
        .parentElement
        .lastElementChild
        .value;

    element
        .parentElement
        .parentElement
        .parentElement
        .remove();

    const result = getPriceCurrent() - parseFloat(valueDeleted);

    if (result <= parseFloat(discount.val())) {
        discountInput.attr('value', 0);
        discount.attr('value', 0);
    }

    setAmount(result);
}

function getExam(exam) {
    return (
        `<div class="d-flex align-items-center">
            <div title="Remover exame" onclick="removeExam(this)">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 48 48"
                    fill="#f46a6a" style="cursor: pointer;" 
                >
                    <path d="M13.05 42q-1.2 0-2.1-.9-.9-.9-.9-2.1V10.5H8v-3h9.4V6h13.2v1.5H40v3h-2.05V39q0 1.2-.9 2.1-.9.9-2.1.9Zm5.3-7.3h3V14.75h-3Zm8.3 0h3V14.75h-3Z"/>
                </svg>
            </div>
            <span style="margin-left: 5px;">${exam.name}</span>
        </div>`
    );
}

function changeDiscount(element) {
    const examsPrices = document.querySelectorAll('[name="exam_prices[]"]');
    const amountCurrent = Array.from(examsPrices).reduce((accumulator, examPrice) => {
        return accumulator + parseFloat(examPrice.value);
    }, 0);

    const discountMaskDefault = element.value.replace(/,/g, '.');
    const newDiscount = parseFloat(discountMaskDefault).toFixed(2);
    discount.attr('value', newDiscount);

    if (newDiscount > amountCurrent) {
        return alert('Atenção! O desconto ofertado não pode ser superior ao valor total dos exames solicitados!');
    }

    setAmount(amountCurrent - newDiscount);
}

const getPriceCurrent = () => {
    return parseFloat(amount.val());
}

const setAmount = (subtotal) => {
    let result = subtotal.toFixed(2);
    const resultMasked = subtotal.toLocaleString('pt-br', {
        style: 'currency', 
        currency: 'BRL',
        minimumFractionDigits: 2,
    });

    amountInput.attr('value', resultMasked);
    amount.attr('value', result);
}

function loader(button) {
    setTimeout(() => {
        button.innerHTML = `<span class="spinner-border spinner-border-sm mr-2" 
            role="status" aria-hidden="true">
        </span>Aguarde...`;
        button.disabled = true;
    }, 20);

    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = 'Registrar orçamento';
    }, 7000);
}
