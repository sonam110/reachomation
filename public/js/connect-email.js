
$(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
});


function connectEmailAccount(){
   
   $("#connect-email-modal").modal('show');
   $("#email_connect_model").modal('hide');
   
}

$(document).on("click", ".edit-account", function(event){
   $("#edit-email-modal").modal('show');
   var id = $(this).data('id');
   var email = $(this).data('email');
   var status = $(this).data('status');
   var name = $(this).data('name');
   var isdefault = $(this).data('isdefault');
   $("#edit_id").val(id);
   $("#edit_email").val(email);
   $("#edit_name").val(name);
   $("#edit_name").val(name);
   $("div.statusId select").val(status);
   if(isdefault == '1'){
    $('#is_default').prop('checked', true);
   }
   
   
});


$(document).on("click", ".accountOption", function(event){
    $("#redirectToAccount").removeAttr('disabled','disabled');
});
$(document).on("click", "#redirectToAccount", function(event){
   $("#connect-email-modal").modal('hide');
   $('.meter').show();
   $('.meter').css('width','100%');
   var account_type = '1';//$('#accounttype').val();
   if(account_type ==''){
      $(".toast-msg").html('Please Select Account Type');
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
   } else {
      $.ajax({
           url: appurl+"redirect-to-account",
           type: "post",
           data: {account_type:account_type},
           success: function(data) {
            if(data['type']== 'success'){
               $('.meter').hide();
               $('.meter').css('width','0%');
               window.location.href = data['redirectUrl']; 

            }
            if(data['type']== 'error'){
               $('.meter').hide();
               $('.meter').css('width','0%');
               $('#title_plan').html(data['message']);
               $('#upgrade-modal').modal('show');
              // toastr.error(data['message']); 
               return false;
               
            }
                
           },
           error: function(xhr, status, error) 
           {
              $('.meter').hide();
              $('.meter').css('width','0%');
              $(".toast-msg").html('Opps! Something went wrong');
               var toast = new bootstrap.Toast(toastLiveExample);
               toast.show();
            
           },
       });
   }
});


