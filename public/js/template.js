// function for open create modal
const opencreateModal = () =>{
   $("#create-modal").modal('show');
   $("#group-name").val('');
   $("#subject").summernote('code', '');
   $('#summernote').summernote('code', '');
   $("#modal-title").html('Create Template');
   $("#template-action").html('<button type="button" class="btn btn-success" onclick="createTemplate();" id="create-btn">Save & Preview</button>');
}

// function for create template
const createTemplate = () =>{
   let group = $("#group-name").val();
   if(group==''){
      $("#group-name").addClass('border border-danger');
      $("#group-name").focus();
      return false;
   }
   let subject = $('#subject').summernote('code');
   if($('#subject').summernote('isEmpty')){
      // $(".note-editor").addClass('border border-danger');
      $('#subject').summernote('focus');
      return false;
   }
   
   let body = $('#summernote').summernote('code');
   if($('#summernote').summernote('isEmpty')){
      // $(".note-editor").addClass('border border-danger');
      $('#summernote').summernote('focus');
      return false;
   }
   let fallback_text = $('#fallback_text').val();
   $.post("/createTemplate", {action: 'createTemplate', "_token": $('meta[name="csrf-token"]').attr('content'), group:group, subject:subject, body:body,fallback_text:fallback_text}).done(function(data){
      if(data == 1){
         $('#create-modal').modal('hide');
         $(".toast-msg").html('Template Created');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         mailPreview();
      }else{
         $('#create-modal').modal('hide');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}


// function for select group
const selectGroup = (name) =>{
   $("#group-name").val(name);
   $("#group-name").removeClass('border border-danger');
   $("#group-name").attr("disabled","disabled");
}

// function for send test mail
const sendtestMail = () =>{

   var account_email = '{{ account_email() }}';
   if(account_email== null){
     $("#mail-modal").modal('hide');
     $('#email_connect_model').modal('show')
   }

   var subject = $('#subject').summernote('code');
    if($('#summernote').summernote('isEmpty')){
        $('#summernote').summernote('focus');
       var is_wait = false;
       toastr.error("Please enter subject line "); 
       return false;
    }
    var  body = $('#summernote').summernote('code');
    if($('#summernote').summernote('isEmpty')){
       $('#summernote').summernote('focus');
       var is_wait = false;
       toastr.error("Please enter message "); 
       return false;
    }
   let email = $("#test-email").val();
   var from_email = $("#from_email").val();
   var fallback_text = $('.fallback_text').val();
   const emailregxp = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   
   if(email ==''){
    var is_wait = false;
    $("#wait-test-btn").addClass('d-none');
    $("#test-btn").removeClass('d-none');
    toastr.error("Please enter Email "); 
    return false;
   }
   if(!emailregxp.test(email)){
       var is_wait = false;
       $("#wait-test-btn").addClass('d-none');
       $("#test-btn").removeClass('d-none');
       toastr.error("Please enter valid Email "); 
       return false;
     }
   $("#wait-test-btn").removeClass('d-none');
   $("#test-btn").addClass('d-none');
   $.post("testmail", {action: 'testmail', "_token": $('meta[name="csrf-token"]').attr('content'), subject:subject, body:body,from_email:from_email,fallback_text:fallback_text, email:email}).done(function(data){
      if(data['type']=='success'){
          toastr.success('mail sent successfully');
          $("#wait-test-btn").addClass('d-none');
          $("#test-btn").removeClass('d-none');
           //$("#mail-action-section").html(`<button type="button" class="btn btn-success" onclick="sendMail('${domain_id}')" id="create-btn">Send Mail</button>`);
        
        }
          if(data['type']=='not_found')
        {
           toastr.error('Please Connnect  your email accunt for using mail service');
           $("#wait-test-btn").addClass('d-none');
           $("#test-btn").removeClass('d-none');
           $("#mail-modal").modal('hide');
           $("#create-modal").modal('hide');
           $('#email_connect_model').modal('show')
        }
        if(data['type']=='error'){
            $("#wait-test-btn").addClass('d-none');
           $("#test-btn").removeClass('d-none');
           toastr.error('Mail not send please reconnect your account');
        }
        $("#wait-test-btn").addClass('d-none');
        $("#test-btn").removeClass('d-none');
   });
}

// function check group value
const group = () =>{
   let group = $("#group-name").val();
   if(group!=''){
      $("#group-name").removeClass('border border-danger');
   }
}

// function for check subject
const subject = () =>{
   let subject = $("#subject").val();
   if(subject!=''){
      $("#subject").removeClass('border border-danger');
   }
}

// function for check test email
const testEmail = () =>{
   let email = $("#test-email").val();
   if(email!=''){
      $("#test-email").removeClass('border border-danger');
   }
}

/*// function for add personalize URL
const personalizeURL = () =>{
   let Website = '<span style="background-color: rgb(255, 255, 0);padding:0px 2px;">Website</span>';
   $('#summernote').summernote('pasteHTML', Website);
}

// function for add personalize Email
const personalizeEmail = () =>{
   let email = '<span style="background-color: rgb(255, 255, 0);padding:0px 2px;">Email</span>';
   $('#summernote').summernote('pasteHTML', email);
}

// function for add personalize Title
const personalizeTitle = () =>{
   let title = '<span style="background-color: rgb(255, 255, 0);padding:0px 2px;">Blog Post Title</span>';
   $('#summernote').summernote('pasteHTML', title);
}
*/
// function for edit template
const editTemplate = (id) =>{
   $("#modal-title").html('');
   $("#group-name").val('');
   $("#subject").summernote('code','');
   $('#summernote').summernote('code','');
   
   $.post("editTemplate", {action: 'editTemplate', "_token": $('meta[name="csrf-token"]').attr('content'), id:id}).done(function(data){
      $("#create-modal").modal('show');
      $("#modal-title").html('Edit Template');
      $("#group-name").val(data.template.name);
      $("#subject").summernote('code', data.template.subject);
      $('#summernote').summernote('code', data.template.body);
      $('#fallback_text').val(data.template.fallback_text);
      $("#template-action").html(`<button type="button" class="btn btn-success" onclick="editedTemplate('${id}')" id="create-btn">Save & Preview</button>`);
   
   });
}

// function for edited template
const editedTemplate = (id) =>{
   let group = $("#group-name").val();
   if(group==''){
      $("#group-name").addClass('border border-danger');
      $("#group-name").focus();
      return false;
   }
   let subject = $('.subject').summernote('code');
   if($('.subject').summernote('isEmpty')){
      $('.subject').summernote('focus');
      return false;
   }
   let body = $('#summernote').summernote('code');
   if($('#summernote').summernote('isEmpty')){
      $('#summernote').summernote('focus');
      return false;
   }
   let fallback_text = $('#fallback_text').val();
   $.post("/editedTemplate", {action: 'editedTemplate', "_token": $('meta[name="csrf-token"]').attr('content'), id:id, group:group, subject:subject, body:body,fallback_text:fallback_text}).done(function(data){
      if(data == 1){
         $('#create-modal').modal('hide');
         $(".toast-msg").html('Template Edited');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         mailPreview();
      }else{
         $('#create-modal').modal('hide');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}

function mailPreview(){
  
   var  id = $(this).data('id');
   var  subject = $('.subject').summernote('code');
   var  message = $('#summernote').summernote('code');
   var  fallback_text = $('#fallback_text').val();
    $.ajax({
        url: appurl+"template-mail-preview",
        type: "post",
        data: {subject:subject,message:message,fallback_text:fallback_text,id:id},
        success: function(text) {
            $("#mail-preview-modal").modal('show');
            $('#message-preview-section').html(text);
            
        }
    });
}
function mailPreviewTemp(id){
  
   var  subject = '';
   var  message = '';
   var  fallback_text = '';
    $.ajax({
        url: appurl+"template-mail-preview",
        type: "post",
        data: {subject:subject,message:message,fallback_text:fallback_text,id:id},
        success: function(text) {
            $("#mail-preview-modal").modal('show');
            $('#message-preview-section').html(text);
            
        }
    });
}
// function for copy template
const copyTemplate = (id) =>{
   //alert(id);
   $("#modal-title").html('');
   $("#group-name").val('');
   $("#subject").summernote('code','');
   $('#summernote').summernote('code','');
   $.post("/copyTemplate", {action: 'copyTemplate', "_token": $('meta[name="csrf-token"]').attr('content'), id:id}).done(function(data){
      if(data['type']=='success'){
         // console.log(data.template);
         $("#create-modal").modal('show');
         $("#modal-title").html('Copy Template');
         $("#group-name").val(data.template.name);
         $("#subject").summernote('code', data.template.subject);
         $('#summernote').summernote('code', data.template.body);
         var cid = data.template.id;
         $("#template-action").html(`<button type="button" class="btn btn-success" onclick="editedTemplate('${cid}')" id="create-btn">Save & Preview</button>`);
      } else{
         toastr.error('Opps!Something went wrong');
      }
   });
}

// function for delete alert modal
const deleteAlert = (id) =>{
   $(".deleteAlert").modal('show');
   $("#delete-btn").attr("onclick",`deleteTemplate('${id}')`);
}

// function for delete template
const deleteTemplate = (id) =>{
   $.post("/deleteTemplate", {action: 'deleteTemplate', "_token": $('meta[name="csrf-token"]').attr('content'), id:id}).done(function(data){
      if(data == 1){
         $(".toast-msg").html('Template Deleted');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         location.reload(true);
      }else{
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}

var dNum = 1;
$(document).on("click", ".elementMsg", function () {

   var pid = $(this).data('pid');
   var dynamic_field_val = $(this).data('customval');
   var value = $(this).val();
   if(value !=''){
      var field_val = value;
   } else{
      var field_val = $(this).data('customval');
   }
   if(dynamic_field_val != ''){
          var title = '<span  contenteditable="false" class="custom-data  msg-'+dynamic_field_val+'-'+pid+'  custom-data-'+dynamic_field_val+'" id="custom-data-'+pid+'" data-val="'+pid+'">'+field_val+'</span>';
         $('#summernote').summernote('pasteHTML', title);
         dNum++;
   }
    
});

$(document).on("click", ".elementSub", function () {
   var pid = $(this).data('pid');
   var dynamic_field_val = $(this).data('customval');
   var value = $(this).val();
   if(value !=''){
      var field_val = value;
   } else{
      var field_val = $(this).data('customval');
   }
   if(dynamic_field_val != ''){
          var title = '<span contenteditable="false"  class="custom-data  sub-'+dynamic_field_val+'-'+pid+'  custom-data-'+dynamic_field_val+'" id="custom-data-'+pid+'" data-val="'+pid+'">'+field_val+'</span>';
         $('.subject').summernote('pasteHTML', title);
         dNum++;
   }

});

function unSubscribeTag(pid){
   var dynamic_field_val = 'Unsubscribe';
   var title = '<a  href="" class="custom-data" id="custom-data-'+pid+'" data-val="'+pid+'">'+dynamic_field_val+'</a>';
   $('#summernote').summernote('pasteHTML', title);
}

$(document).on("click", ".resetBtnmsg", function () {
   var pid = $(this).data('pid');
   var dynamic_field_val = $(this).data('customval');
   if(dynamic_field_val != ''){
          var title = '<span style="background-color: rgb(255, 255, 0);padding:0px 2px;" class="custom-data" id="custom-data-'+pid+'" data-val="'+pid+'">'+dynamic_field_val+'</span>';
          //$("#custom-data-"+pid).addClass('d-none');
          //$("#custom-data-"+pid).text('');
          $('.msg-'+dynamic_field_val+'-'+pid+'').addClass('d-none');
          $('.msg-'+dynamic_field_val+'-'+pid+'').text('');
          
            
         
   }
});

$(document).on("click", ".resetBtsub", function () {
   var pid = $(this).data('pid');
   var dynamic_field_val = $(this).data('customval');
   if(dynamic_field_val != ''){
          var title = '<span style="background-color: rgb(255, 255, 0);padding:0px 2px;" class="custom-data" id="custom-data-'+pid+'" data-val="'+pid+'">'+dynamic_field_val+'</span>';
           
           $('.sub-'+dynamic_field_val+'-'+pid+'').addClass('d-none');
           $('.sub-'+dynamic_field_val+'-'+pid+'').text('');
            
         
   }
});