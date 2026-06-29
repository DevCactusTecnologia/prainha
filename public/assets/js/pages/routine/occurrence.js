const baseUrl = document.querySelector('[data-js="base-url"]').value;
const patient = document.querySelector('[data-js="filter-patient"]');
const protocol = document.querySelector('[data-js="filter-protocol"]');
const situation = document.querySelector('[data-js="filter-status"]');

const ENTER_KEY = 13;
const MILLISECONDS = 400;

let timerName;
let timerProtocol;
let filesIds = [];

$('[data-js="datatable"]').DataTable({
    processing: true,
    serverSide: true,
    paging: true,
    lengthChange: false,
    searching: false,
    searchDelay: 500,
    ordering: false,
    ajax: $('[data-js="datatable-url"]').val(),
    columns: [
        {data: 'patient', name: 'patient'},
        {data: 'protocol', name: 'protocol', className: 'text-center'},
        {data: 'registered_at', name: 'registered_at'},
        {data: 'motive', name: 'motive'},
        {data: 'user', name: 'user'},
        {data: 'status', name: 'status', className: 'text-center'},
        {data: 'actions', name: 'actions', className: 'text-center'},
    ],
    language: {
        url: $('[data-js="datatable-lang-pt-br"]').val(),
    },
});

function filterPatient(event) {
    clearTimeout(timerName);

    timerName = setTimeout(({keyCode}) => {
        if (keyCode !== ENTER_KEY) {
            runFilter();
        }
    }, MILLISECONDS, event);
}

function filterProtocol(event) {
    clearTimeout(timerProtocol);

    timerProtocol = setTimeout(({keyCode}) => {
        if (keyCode !== ENTER_KEY) {
            runFilter();
        }
    }, MILLISECONDS, event);
}

function runFilter() {
    $('[data-js="datatable"]').on('preXhr.dt', function (event, settings, data) {
        if (patient.value != '') {
            data.filter_name = patient.value;
        }

        if (protocol.value != '') {
            data.filter_protocol = protocol.value;
        }

        if (situation.value != '') {
            data.filter_status = situation.value;
        }
    });

    $('[data-js="datatable"]').DataTable().ajax.reload();
}
