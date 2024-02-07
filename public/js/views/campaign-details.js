/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

// const openSentMailss = document.querySelectorAll('.open-sent-mail');
// openSentMail.forEach(el => el.addEventListener('click', event => {
//     let mailid = event.target.getAttribute("data-setdefaultid");
//     openSentMail(mailid);
// }));

function openSentMail(id) {
    $.ajax({
        url: appurl + "preview-message",
        type: "post",
        data: {
            id: id
        },
        success: function (text) {
            $("#mail-preview-modal").modal('show');
            $('#message-preview-section').html(text);
        }
    });
}

function strip_tags(input, allowed) {

    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(
        ''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}

function showStatics(id, uuid) {
    $.ajax({
        url: appurl + "show-statics",
        type: "post",
        data: {
            id: id,
            uuid: uuid
        },
        success: function (html) {
            $('#statics').html(html)

        }
    });

}

$(document).on("click", "#show-delete-model", function (event) {
    var id = $(this).data('id');
    $('#campid').val(id);
    $("#deleteCampaign").modal('show');
});

$(document).ready(function () {
    var id = document.getElementById('dataId').value;
    var uuid = document.getElementById('dataUuid').value;
    showStatics(id, uuid);
});

function getDownloadLink(i, id, key) {

    $('.reportLevel').addClass('d-none');
    $('#download-report-' + i + '-' + id).removeClass('d-none');
    $('.attempt-class').removeClass('fade show active');
    $('.atmclass' + i + id).addClass('fade show active');

}

function playPauseCamp(id, cond) {
    if (cond === 'play') {
        $('#pause-btn-' + id).hide();
        $('#play-btn-' + id).show();
    } else {
        $('#pause-btn-' + id).show();
        $('#play-btn-' + id).hide();
    }
    // console.log(cond);
    $.ajax({
        url: appurl + "play-pause-campaign",
        type: "post",
        data: {
            id: id,
            cond: cond
        },
        success: function (text) {
            if (text['type'] == 'success') {
                $('#status-' + id).text(text['status']);

            }
        }
    });
}