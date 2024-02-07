/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */
$(function () {
    $('.selectTimeZone').select2({
        placeholder: "Select Timezone",
        width: '100%'
    });



});

let campaignListURL = document.getElementById('campaign-list-url').value;
let csrfToken = document.getElementById('csrf-token').value;
$(document).ready(function () {
    var table = $('#datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "bSort": true,
        "paging": true,
        "bInfo": true,
        "ajax": {
            'url': campaignListURL,
            'type': 'POST',
            "data": function (d) {
                d.mystatus = $('#mystatus').val();
                d.keyword = $('#keyword').val();
                d.from_email = $('#from_email').val();
            },
            'headers': {
                'X-CSRF-TOKEN': csrfToken
            }
        },
        "order": [["0", "asc"]],
        "columns": [
            { "data": 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false },
            { "data": "name" },
            { "data": "import_contact" },
            { "data": "status" },
            { "data": "total_open" },
            { "data": "total_click" },
            { "data": "total_reply" },
            { "data": "from_email" },
            { "data": "action", 'name': 'DT_RowIndex', orderable: false, searchable: false },
        ],
        preDrawCallback: function (settings) {
            if ($.fn.DataTable.isDataTable('#datatable')) {
                var dt = $('#datatable').DataTable();
                var settings = dt.settings();
                if (settings[0].jqXHR) {
                    settings[0].jqXHR.abort();
                }
            }
        }
    });
    $('#mystatus,#from_email').on('change', function (e) {
        table.draw();
    });

    $('#keyword').bind("enterKey", function (e) {
        table.draw();
    });
    $('#keyword').keyup(function (e) {
        if (e.keyCode == 13) {
            $(this).trigger("enterKey");
        }
    });

});
const openSentMail = () => {
    $("#openSentMail").modal('show');
}

$(document).on("click", "#show-copy-model", function (event) {
    var id = $(this).data('id');
    $('#camp_id').val(id);
    $("#copyCampaign").modal('show');
});
$(document).on("click", "#show-delete-model", function (event) {
    var id = $(this).data('id');
    $('#campid').val(id);
    $("#deleteCampaign").modal('show');
});
$(document).on("click", "#show-schedule-model", function (event) {
    var id = $(this).data('id');
    $('#camp_sid').val(id);
    $("#scheduleCampaign").modal('show');
});

$(document).ready(function () {
    $('#scheduleClass').each(function () {
        $(this).validate({
            onfocusout: false,
            rules: {

                start_date: {
                    required: true,
                },
                starttime: {
                    required: true,
                },
                endtime: {
                    required: true,
                },
                cooling_period: {
                    required: true,
                },


            },

            messages: {

                "start_date": {
                    required: 'Start Date field is required',
                },
                "starttime": {
                    required: 'Start time field is required',
                },
                "endtime": {
                    required: 'End time field is required',
                },
                "cooling_period": {
                    required: 'Please enter cooling period',
                },

            },

            submitHandler: function (form) {
                form.submit();
            }



        });
    });
});

// intialize tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

// chart
let thirtyDays = document.getElementById('thirty-days').value;
let thirtyDaysSentMails = document.getElementById('thirty-days-sent-mails').value;
let thirtyDaysDeliveredMails = document.getElementById('thirty-days-delivered-mails').value;
let thirtyDaysReplyMails = document.getElementById('thirty-days-reply-mails').value;
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            thirtyDays
],
datasets: [{
    label: 'Mails sent',
    data: [
        thirtyDaysSentMails,
    ],

    lineTension: 0,
    backgroundColor: 'transparent',
    borderColor: '#007bff',
    borderWidth: 4,
    pointBackgroundColor: '#007bff'
},
{
    label: 'Delivered ',
    data: [
        thirtyDaysDeliveredMails
    ],

    lineTension: 0,
    backgroundColor: 'transparent',
    borderColor: '#39ac8a',
    borderWidth: 4,
    pointBackgroundColor: '#39ac8a'
},

{
    label: 'Replies received',
    data: [
        thirtyDaysReplyMails
    ],

    lineTension: 0,
    backgroundColor: 'transparent',
    borderColor: '#ffc107',
    borderWidth: 4,
    pointBackgroundColor: '#ffc107'
}]
},
options: {
    title: {
        display: true,
            text: 'CAMPAIGN ACTIVITY',
                fontSize: 16
    },
    scales: {
        yAxes: [{
            scaleLabel: {
                display: true,
                labelString: 'Total Sent/Delivered/Replied'
            },
            ticks: {
                beginAtZero: false
            }
        }],
            xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Date'
                },
                ticks: {
                    beginAtZero: false
                }
            }]
    },
    legend: {
        display: false
    }
}
});
// chart