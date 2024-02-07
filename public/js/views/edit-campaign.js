function formatSelection(val) {
    return val.id;
}

$(function () {
    $('.selectTimeZone').select2({
        placeholder: "Select Timezone",
        width: '100%'
    });



});

var is_feature = $('.is_feature').val();
if (is_feature == '1') {
    $('.refine-list').addClass('d-none');
} else {
    $('.refine-list').removeClass('d-none');
}

// intialize tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

// intialize toast
var toastLiveExample = document.getElementById('liveToast');

// intialize popover
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
})
$(document).ready(function () {



    document.querySelector(".subject").spellcheck = false;
    document.querySelector(".message").spellcheck = false;

    $(".subject").summernote(
        {
            toolbar: [],

            placeholder: `Subject`,
            //followingToolbar: false   ,
            // focus: true,
            disableResizeEditor: true,
            spellcheck: false,
            callbacks: {
                onKeyup: function (contents, $editable) {
                    var rid = $(this).data('rid');
                    $(".element").addClass('elementSub');
                    $(".element").removeClass('elementMsg');

                    $(".resetBt").addClass('resetBtsub');
                    $(".resetBt").removeClass('resetBtnmsg');
                }

            }
        }
    );
    $(".message").summernote(
        {
            toolbar: [["style", ["style"]], ["font", ["bold", "italic", "underline", "fontname", "clear"]], ["color", ["color"]], ["paragraph", ["ul", "ol", "paragraph"]], ["table", ["table"]], ["insert", ["link", "picture"]], ["view", ["codeview"]],],
            placeholder: `Hi there,<br><br>I saw your site and liked the content. I am keen to explore advertising opportunities with you.<br>Could I know your charges for publishing a guest post on . We pay via PayPal.<br><br>Looking forward to hearing from you. `,
            tabsize: 2,
            height: '300px',
            spellcheck: false,
            callbacks: {
                onKeyup: function (contents, $editable) {
                    var rid = $(this).data('rid');
                    $(".element").removeClass('elementSub');
                    $(".element").addClass('elementMsg');
                    $(".resetBt").removeClass('resetBtsub');
                    $(".resetBt").addClass('resetBtnmsg');
                }
            }
        }
    );

});

$("#sidebarMenu").niceScroll(); 