function googleLogin() {
    $("#connect-email-modal").modal('hide');
    $('.meter').show();
    $('.meter').css('width', '100%');
    var account_type = 'gmail';
    if (account_type == '') {
        $(".toast-msg").html('Please Select Account Type');
        var toast = new bootstrap.Toast(toastLiveExample);
        toast.show();
    } else {
        $.ajax({
            url: appurl + "redirect-to-account",
            type: "post",
            data: {account_type: account_type},
            success: function (data) {
                if (data['type'] == 'success') {
                    $('.meter').hide();
                    $('.meter').css('width', '0%');
                    window.location.href = data['redirectUrl'];

                }
                if (data['type'] == 'error') {
                    $('.meter').hide();
                    $('.meter').css('width', '0%');
                    $('#title_plan').html(data['message']);
                    $('#upgrade-modal').modal('show');
                    // toastr.error(data['message']); 
                    return false;

                }

            },
            error: function (xhr, status, error)
            {
                $('.meter').hide();
                $('.meter').css('width', '0%');
                $(".toast-msg").html('Opps! Something went wrong');
                var toast = new bootstrap.Toast(toastLiveExample);
                toast.show();

            },
        });
    }
}

// intialize toast
var toastLiveExample = document.getElementById('liveToast');

// intialize tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});


$("#sidebarMenu").niceScroll();

const beforeProceed = () => {
    $("#before-proceed-modal").modal('show');
}

const ratings = () => {
    $("#ratings-modal").modal('show');
}

const report = () => {
    $("#report-modal").modal('show');
}

const reportOther = () => {
    $("#report-textbox").removeClass('d-none');
}
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
      (function(){
      var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
      s1.async=true;
      s1.src='https://embed.tawk.to/62ecf7e954f06e12d88d14f7/1g9mqgqqb';
      s1.charset='UTF-8';
      s1.setAttribute('crossorigin','*');
      s0.parentNode.insertBefore(s1,s0);
      })();
window.$crisp=[];window.CRISP_WEBSITE_ID="918f52c5-319f-421f-b373-7b76f0e56e2b";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();
