let timer;

$('#searchExam').keyup(function(event) {
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
                    $('#contentExam').css({display: 'none'});
                    $('#paginate').css({display: 'none'});
                    $('#loader').removeAttr('style');
                },
                success: (response) =>
                    response.exams.length <= 0
                        ? $('#contentExam').html(empty())
                        : $('#contentExam').html(getContent(response.exams))
                ,
                error: function (response) {
                    toastr.error(response.responseJSON.message);
                },
                complete: function () {
                    $('#loader').css({display: 'none'});
                    $('#contentExam').removeAttr('style');
                    $('#paginate').css({display: 'block'});
                },
            });
        }

    }, milliseconds, event);
});

const empty = () => 
    `<tr>
        <td colspan="10" class="bg-light">
            <div class="d-flex justify-content-center align-items-center" 
                style="height: 80px; font-size: 16px;"
            >
                Nenhum resultado encontrado.
            </div>
        </td>
    </tr>`;

const getContent = (exams) =>
    exams.reduce((acumulator, exam) => {
        return acumulator + 
            `<tr>
                <td>${exam.name}</td>
                <td>${exam.abbreviation}</td>
                <td>${exam.category}</td>
                <td>${exam.deadline}</td>
                <td>${exam.destiny}</td>
                <td>${exam.label_group}</td>
                <td>${exam.quantity_label}</td>
                <td>${exam.exam_kit}</td>
                <td><span class="${statusColor(exam)}">${statusName(exam)}</span></td>
                <td>
                    <a href="/exams/${exam.id}/edit" title="Atualizar Exame"
                        class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                    >
                        <i class="mdi mdi-lead-pencil"></i>
                    </a>
                </td>
            </tr>`;
    }, '');

const statusName = (exam) => 
    exam.is_active == '1'
        ? 'Ativo'
        : 'Inativo';

const statusColor = (exam) =>
    exam.is_active == '1'
        ? 'alert alert-success rounded-pill px-3 py-1'
        : 'alert alert-danger rounded-pill px-3 py-1';
