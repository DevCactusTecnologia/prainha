const baseUrl = $('#baseUrl').val();

CKEDITOR.replace('summery-ckeditor');
const CkEditorContentFilter = CKEDITOR.replace('contentFilter');

$('#parameterForm').on('submit', function(event) {
    event.preventDefault();

    $.ajax({
        type: 'POST',
        url: `${baseUrl}/exams/parameters`,
        data: $(this).serialize(),
        success: function(response) {	
            if (response.parameter_exist) {
                $('.showMsg').show(); 
                $('.showMsg').html('Parâmetro já existe!');

                setTimeout(() => {
                    $('.showMsg').fadeOut(); 
                }, 2000);
                
                return false;
            }

            $('#parameterModal').modal('toggle');
            toastr.success(response.message);

            const hiddenId = $("#hiddenId").val();
            if (hiddenId == '') {
                $('#listParameterModal').modal('toggle');

                const parameter = $('#parameter').val();
                const type = $('#type').find(":selected").text();
                const formula = $('#formula').val();
                const size = $('#size').val();
                const decimalPlaces = $('#decimal_places').val();
                const statusColor = response.data.is_active == '1' ? 'success' : 'danger';
                const statusName = response.data.is_active == '1' ? 'Ativo' : 'Inativo';

                $("#listTableParameter").append( 
                    `<tr>
                        <th>${response.data.id}</th>
                        <th class="text-uppercase">${parameter}</th>
                        <th>${type}</th>
                        <th>${formula}</th>
                        <th class="text-center">${size}</th>
                        <th class="text-center">${decimalPlaces}</th>
                        <th>
                            <span class="alert alert-${statusColor} rounded-pill px-2 py-1">
                                ${statusName}
                            </span>
                        </th>
                        <th>
                            <button type="button" data-id="${response.data.id}" 
                                class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0 updateParameter" 
                                title="Atualizar Parâmetro" data-backdrop="static" data-toggle="modal" 
                                data-target="#parameterModal" data-keyboard="false"
                            >
                                <i class="mdi mdi-lead-pencil"></i>
                            </button>
                        </th>
                    </tr>`
                );
            } else {
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
            
        },
        error: function(response) {
            toastr.error(response.responseJSON.message, {
                timeOut: 20000
            });
        },
    });
});

$('#filterForm').on('submit', function(event) {

    event.preventDefault();
    const formData = $(this);
    const hiddenFilterId = $("#hiddenFilterId").val();
    const gender = $('#gender').find(":selected").val();
    const intialAgeYear = $('#intialAgeYear').val();
    const intialAgeMonth = $('#intialAgeMonth').val();
    const intialAgeDay = $('#intialAgeDay').val();
    const finalAgeYear = $('#finalAgeYear').val();
    const finalAgeMonth = $('#finalAgeMonth').val();
    const finalAgeDay = $('#finalAgeDay').val();

    $.ajax({
        type: 'POST',
        url: `${baseUrl}/exams/filters`,
        data: {
            exam_id: $("#filterExamId").val(),
            id: $("#hiddenFilterId").val(),
            gender: $("#gender").val(),
            intial_age_year: $("#intialAgeYear").val(),
            intial_age_month: $("#intialAgeMonth").val(),
            intial_age_day: $("#intialAgeDay").val(),
            final_age_year: $("#finalAgeYear").val(),
            final_age_month: $("#finalAgeMonth").val(),
            final_age_day: $("#finalAgeDay").val(),
            exam_editor: CkEditorContentFilter.getData(),
            _token: $("[name='_token']").val(),
        },
        success: function(response) {	
            if (response.filter_exist) {
                $('.showMsgFilter').show(); 
                $('.showMsgFilter').html('Filtro já existe!');

                setTimeout(() => {
                    $('.showMsgFilter').fadeOut(); 
                }, 2000);
                
                return false;
            }

            $('#filterModal').modal('toggle');
            toastr.success(response.message);
            
            if (hiddenFilterId == '') {
                $('#listFilterModal').modal('toggle');
                $("#listTableFilter").append( 
                    `<tr>
                        <th>${response.data.id}</th>
                        <th class="text-uppercase">${gender}</th>
                        <th>${intialAgeYear}</th>
                        <th>${intialAgeMonth}</th>
                        <th>${intialAgeDay}</th>
                        <th>${finalAgeYear}</th>
                        <th>${finalAgeMonth}</th>
                        <th>${finalAgeDay}</th>
                        <th>
                            <button type="button" data-id="${response.data.id}" 
                                class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0 updateFilter" 
                                title="Atualizar Filtro" data-backdrop="static" data-toggle="modal" 
                                data-target="#filterModal" data-keyboard="false"
                            >
                                <i class="mdi mdi-lead-pencil"></i>
                            </button>
                            <button type="button" onclick="deleteFilter(${response.data.id})" 
                                class="btn btn-danger btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0" 
                                title="Deletar Filtro" data-backdrop="static" data-keyboard="false"
                            >
                                <i class="mdi mdi-trash-can"></i>
                            </button>
                        </th>
                    </tr>`
                );
            } else {
                setTimeout(function () {
                    location.reload();
                }, 1500);
            }
        },
        error: function(response) {
            toastr.error(response.responseJSON.message, {
                timeOut: 20000
            });
        },
    });
});

$('.updateFilter').click(function () {
    $('#listFilterModal').modal('toggle');
    $("#filterModal").modal('show');
});

$('body').on('click', '.updateParameter', function () {
    $('#listParameterModal').modal('toggle');
    const id = $(this).attr('data-id');

    $.ajax({
        type: 'GET',
        url: `${baseUrl}/exams/parameters/${id}`,
        success: function(response) {
            const value = response.parameter;

            $('#hiddenId').val(value.id);
            $('#parameter').val(value.parameter);
            $('#type').val(value.type);
            $('#unit').val(value.unit);
            $('#abbreviations').val(value.abbreviations);
            $('#standard_value').val(value.standard_value);
            $('#formula').val(value.formula);
            $('#size').val(value.size);
            $('#decimal_places').val(value.decimal_places);
            $('#decimal_mask').val(value.decimal_mask);
            $('#minimum').val(value.minimum);
            $('#maximum').val(value.maximum);
            $('#block_recording').val(value.block_recording);
            $('#mandatory_parameter').val(value.mandatory_parameter);
            $('#imp_ruler').val(value.imp_ruler);
            $('#previous_imp').val(value.previous_imp);
            $('#description').val(value.description);
            $('#reference_value').val(value.reference_value);
            $('#support_parameter').val(value.support_parameter);
            $('#evolutionary_report_parameter').val(value.evolutionary_report_parameter);
            $('#required').val(value.required);
            $('#with_previous_result').val(value.with_previous_result);
            $('#with_printed_map').val(value.with_printed_map);
            $('#is_active').val(value.is_active);

            $('.saveChange').css({'display': 'none'});
            $('.updateChange').removeAttr('style');
            $('#parameterModal').modal('show');
        },
        error: function(response) {
            toastr.error(response.responseJSON.message, {
                timeOut: 20000
            });
        },
    });
});

$('body').on('click', '.updateFilter', function () {
    $('#listFilterModal').modal('toggle');
    const id = $(this).attr('data-id');

    $.ajax({
        type: 'GET',
        url: `${baseUrl}/exams/filters/${id}`,
        success: function(response) {
            const value = response.filter;

            $("#hiddenFilterId").val(value.id);
            $("#gender").val(value.gender);
            $("#intialAgeYear").val(value.intial_age_year);
            $("#intialAgeMonth").val(value.intial_age_month);
            $("#intialAgeDay").val(value.intial_age_day);
            $("#finalAgeYear").val(value.final_age_year);
            $("#finalAgeMonth").val(value.final_age_month);
            $("#finalAgeDay").val(value.final_age_day);
            CkEditorContentFilter.setData(value.exam_editor);
            
            $(".saveChangeFilter").css({'display': 'none'});
            $(".updateChangeFilter").removeAttr('style');
            $("#filterModal").modal('show');
        },
        error: function(response) {
            toastr.error(response.responseJSON.message, {
                timeOut: 20000
            });
        },
    });
});

function deleteFilter(id) {
    if (confirm('Tem certeza que deseja deletar o filtro selecionado?')) {
        $.ajax({
            type: 'DELETE',
            url: `${baseUrl}/exams/filters/${id}/delete`,
            data: {
                _token: $('[name="_token"]').val(),
                id: id,
            },
            success: function(response) {
                toastr.success(response.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function(response) {
                toastr.error(response.responseJSON.message, {
                    timeOut: 20000
                });
            },
        });
    }
}

function closeModel() {
    $("#parameter").val('');
    $("#type").val('text');
    $("#unit").val('');
    $("#abbreviations").val('');
    $("#standard_value").val('');
    $("#formula").val('');
    $("#size").val('');
    $("#decimal_places").val('');
    $("#decimal_mask").val('0');
    $("#minimum").val('');
    $("#maximum").val('');
    $("#block_recording").val('0');
    $("#mandatory_parameter").val('0');
    $("#imp_ruler").val('1');
    $("#previous_imp").val('1');
    $("#description").val('');
    $("#reference_value").val('');
    $("#support_parameter").val('');
    $("#evolutionary_report_parameter").val('1');
    $(".saveChange").removeAttr('style');
    $(".updateChange").css({"display": "none"});
}

function closeModal() {
    $('#listParameterModal').modal('hide');
    $('#listFilterModal').modal('hide');
}
