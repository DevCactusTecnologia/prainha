const baseUrl = $('[data-js="base-url"]').val();
let uri = '';
let exams = [];

$('[data-js="search-exam"]').click(function (event) {
    event.preventDefault();
    exams = [];

    if ($('[data-js="date"]').val() == '') {
        return alert('Atenção! Preencha a data do atendimento!');
    }

    $.ajax({
        url: $('[data-js="form-map-exam"]').attr('action'),
        type: 'POST',
        data: {
            registered_at: $('[data-js="date"]').val(),
            category_id: $('[data-js="select-sector"]').val(),
            _token: $('[name="_token"]').val(),
        },
        beforeSend: () => {
            $(this).html(loader());
            $(this).prop('disabled', true);
        },
        success: (response) => {
            response.exams.length <= 0
                ? $('[data-js="content"]').html(notFound())
                : $('[data-js="content"]').html(getContent(response.exams));
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

$('[data-js="select-biomedical"]').change(function () {
    const biomedicalLabel = $('[data-js="label-biomedical"]');
    const biomedicalName = $(this).children('option').filter(':selected').text();
    biomedicalLabel.text(biomedicalName);

    uri = `?biomedical=${biomedicalName}&registered_at=${$('[data-js="date"]').val()}&exams=`;
})

function examClicked(elementActive) {
    const containerExams = $('[data-js="container-exams"]');
    const buttonInsertExamSelected = $('[data-js="button-insert-exam-selected"]');
    const containerExamLabelSelected = $('[data-js="container-exam-label-selected"]');
    const valueExamLabelSelected = $('[data-js="value-exam-label-selected"]');

    Array.from(containerExams.children()).forEach((exam) => {
        exam.style.backgroundColor = '#fff'; 
    })

    elementActive.style.backgroundColor = '#eff2f7';
    buttonInsertExamSelected.prop('disabled', false);

    const element = (`<div class="text-primary px-1 py-2" data-exam-id="${elementActive.dataset.examId}" style="cursor: pointer;" onclick="examClickedAvailable(this)">${elementActive.innerHTML}</div>`);
    containerExamLabelSelected.html(element);
    valueExamLabelSelected.html(elementActive.innerHTML);
}

function examClickedAvailable(elementActive) {
    const containerExamsAvailables = $('[data-js="container-exams-availables"]');
    const containerExamAvailableLabelSelected = $('[data-js="container-exam-available-label-selected"]');
    const buttonRemoveExamSelected = $('[data-js="button-remove-exam-selected"]');
    const valueExamAvailableLabelSelected = $('[data-js="value-exam-available-label-selected"]');

    Array.from(containerExamsAvailables.children()).forEach((exam) => {
        exam.style.backgroundColor = '#fff';
    })

    elementActive.style.backgroundColor = '#eff2f7';

    const element = (`<div class="px-1 py-2" data-exam-id="${elementActive.dataset.examId}" style="cursor: pointer;" onclick="examClicked(this)">${elementActive.innerHTML}</div>`);
    containerExamAvailableLabelSelected.html(element);
    buttonRemoveExamSelected.prop('disabled', false);
    valueExamAvailableLabelSelected.html(elementActive.innerHTML);
}

function insertExamSelected(element) {
    const containerExams = $('[data-js="container-exams"]');
    const valueExamLabelSelected = $('[data-js="value-exam-label-selected"]');
    const buttonRemoveAllExamSelected = $('[data-js="button-removel-all-exam-selected"]');
    const containerExamsAvailables = $('[data-js="container-exams-availables"]');
    const containerExamLabelSelected = $('[data-js="container-exam-label-selected"]');

    Array.from(containerExams.children()).forEach((exam) => {
        if (exam.innerHTML == valueExamLabelSelected.html()) {
            exams.push(exam.dataset.examId);
            exam.remove();
        }
    })

    element.disabled = true;
    buttonRemoveAllExamSelected.prop('disabled', false);
    containerExamsAvailables.append(containerExamLabelSelected.html());
}

function insertAllExamSelected(element) {
    let contentExam = '';
    const containerExams = $('[data-js="container-exams"]');
    const containerExamsAvailables = $('[data-js="container-exams-availables"]');
    const buttonRemoveAllExamSelected = $('[data-js="button-removel-all-exam-selected"]');

    Array.from(containerExams.children()).forEach((exam) => {
        contentExam = `<div class="text-primary px-1 py-2" data-exam-id="${exam.dataset.examId}" style="cursor: pointer;" onclick="examClickedAvailable(this)">${exam.innerHTML}</div>`;
        containerExamsAvailables.append(contentExam);
        exams.push(exam.dataset.examId);

        exam.remove();
    })

    element.disabled = true;
    buttonRemoveAllExamSelected.prop('disabled', false);
}

function removeExamSelected(element) {
    const containerExams = $('[data-js="container-exams"]');
    const containerExamsAvailables = $('[data-js="container-exams-availables"]');
    const valueExamAvailableLabelSelected = $('[data-js="value-exam-available-label-selected"]');
    const containerExamAvailableLabelSelected = $('[data-js="container-exam-available-label-selected"]');

    Array.from(containerExamsAvailables.children()).forEach((exam) => {
        if (exam.innerHTML == valueExamAvailableLabelSelected.html()) {
            exams = exams.filter(value => value !== exam.dataset.examId)
            exam.remove();
        }
    })

    element.disabled = true;
    containerExams.append(containerExamAvailableLabelSelected.html());
}

function removeAllExamSelected(element) {
    let contentExam = '';
    const containerExams = $('[data-js="container-exams"]');
    const containerExamsAvailables = $('[data-js="container-exams-availables"]');
    const buttonInsertAllExamSelected = $('[data-js="button-insert-all-exam-selected"]');

    Array.from(containerExamsAvailables.children()).forEach((exam) => {
        contentExam = `<div class="px-1 py-2" data-exam-id="${exam.dataset.examId}" style="cursor: pointer;" onclick="examClicked(this)">${exam.innerHTML}</div>`;
        containerExams.append(contentExam);
        exams = [];

        exam.remove();
    })

    element.disabled = true;
    buttonInsertAllExamSelected.prop('disabled', false);
}

function getContent (listExams) {
    const contentExams = Object.entries(listExams).reduce((accumulator, exam) => {
        const examName = exam[0].split('|')[0];
        const examId = exam[0].split('|')[1];

        return accumulator + (
            `<div class="px-1 py-2" data-exam-id="${examId}" style="cursor: pointer;" onclick="examClicked(this)">&#8226; ${exam[1]}x ${examName}</div>`
        );
    }, '');

    return (
        `<div class="d-md-flex">
            <div class="col-md-5">
                <div style="height: 200px; overflow-y: scroll" data-js="container-exams">
                    ${contentExams}
                </div>
                <div data-js="container-exam-label-selected" class="d-none"></div>
                <div data-js="value-exam-label-selected" class="d-none"></div>
            </div>
            <div class="col-md-2">
                <div class="align-items-center d-flex flex-column justify-content-center" style="height: 200px;">
                    <button type="button" class="bg-light mb-2 px-4 py-1 rounded-lg" style="border: none;"
                        onclick="insertExamSelected(this)" data-js="button-insert-exam-selected" disabled
                        title="Inserir exame selecionado (lado esquerdo)"
                    >
                        <i class='mdi mdi-arrow-right'></i>
                    </button>
                    <button type="button" class="bg-light mb-2 px-4 py-1 rounded-lg" style="border: none;"
                        onclick="insertAllExamSelected(this)" data-js="button-insert-all-exam-selected"
                        title="Inserir todos os exames (lado esquerdo)"
                    >
                        <i class='mdi mdi-arrow-expand-right'></i>
                    </button>
                    <button type="button" class="bg-light mb-2 px-4 py-1 rounded-lg" style="border: none;"
                        onclick="removeExamSelected(this)" data-js="button-remove-exam-selected" disabled
                        title="Remover exame selecionado (lado direito)"
                    >
                        <i class='mdi mdi-arrow-left'></i>
                    </button>
                    <button type="button" class="bg-light mb-2 px-4 py-1 rounded-lg" style="border: none;"
                        data-js="button-removel-all-exam-selected" onclick="removeAllExamSelected(this)" disabled
                        title="Remover todos os exames (lado direito)"
                    >
                        <i class='mdi mdi-arrow-expand-left'></i>
                    </button>
                </div>
            </div>
            <div class="col-md-5">
                <div style="height: 200px; overflow-y: scroll" data-js="container-exams-availables">
                </div>
                <div data-js="container-exam-available-label-selected" class="d-none"></div>
                <div data-js="value-exam-available-label-selected" class="d-none"></div>
            </div>
        </div>
        <div>
            <hr>
            <div class="d-md-flex justify-content-md-end align-items-center">
                <span class="mr-4" style="font-weight: 600;">Imprimir Mapa por Exames</span>
                <span onclick="print(event)" style="cursor: pointer;" title="Imprimir">
                    <i class="mdi mdi-printer font-size-24 align-middle text-success"></i>
                </span>
            </div>
        </div>`
    );
}

const loader = () =>
    `<span class="spinner-border spinner-border-sm mr-2" 
        role="status" aria-hidden="true">
    </span>Buscar`

const notFound = () =>
    `<div class="d-flex justify-content-center bg-light p-4">
        <h4>Nenhum resultado encontrado</h4>
    </div>`

function print({target}) {
    if ($('[data-js="select-biomedical"]').val() == '') {
        return alert('Atenção! Selecione o analista/biomédico!');
    }

    if (exams.length === 0) {
        return alert('Atenção! Selecione os exames que serão gerados no mapa!');
    }

    if (target.nodeName == 'span' || target.nodeName == 'I') {
        window.open(`${baseUrl}/${uri}${exams.join(',')}`, '_blank');
    }
}
