$(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
});



const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
// console.log(timezone);

// var slider = document.getElementById("myRange");
// var output = document.getElementById("demo");
// output.innerHTML = slider.value;

// slider.oninput = function() {
//    output.innerHTML = this.value;
// }

// function for load list
/*const loadList = () =>{

   let collectionId = $("#created-list").val();
   var type = $("#created-list").data('type');
   $('.meter').show();
  $('.meter').css('width','100%');
   $.post("/loadList", {action: 'loadList', "_token": $('meta[name="csrf-token"]').attr('content'), collectionId:collectionId}).done(function(data){
      if(Object.keys(data.collections).length>0){
         let results = '';
         for (let i = 0; i < Object.keys(data.collections).length; i++){

            let emails = ''
            let emls = Object.values(JSON.parse(data.collections[i].emails));
            for(let k = 0; k<1; k++){
               if(emls.length>1){
                  emails += `${emls[k]} <span class="badge bg-primary">${emls.length-1} more</span>`;
               }else{
                  emails += `${emls[k]}`;
               }
            }

            let tags = '';
            if(data.collections[i].tags ==''){
               tags = 'NA';
            }else{
               var keyval = Object.keys(JSON.parse(data.collections[i].tags));
               let len = keyval.length;
               if(keyval.length>3){
                  for(let j = 0; j<3; j++){
                     tags += `<span class="badge bg-theme rounded-pill text-capitalize me-2">${keyval[j]}</span>`;
                  }
               }else if(len>0){
                  
                  for(let j = 0; j<len; j++){
                     tags += `<span class="badge bg-theme rounded-pill text-capitalize me-2">${keyval[j]}</span>`;
                  }
               }else{
                  tags = 'NA';
               }
            }

            results += `<tr><th scope="row">${i+1}</th><td>Name</td><td><a href="https://${data.collections[i].website}" class="text-decoration-none" target="_blank">${data.collections[i].website}</a></td><td>${emails}</td><td>${tags}</td></tr>`;
            $('#list-name').html(data.collections[i].name);
         }
         $('.meter').hide();
         $('.meter').css('width','0%');
         $("#customtype").val(type);
         $('#domain-count').html(data.collections_count);
         $('#upload-count').html(data.collections_count);
         $('#total_count').val(data.collections_count);
         $('#domain-list').html(results);
         $('#recipientsdata').removeClass('d-none');
      }else{
        $('.meter').hide();
         $('.meter').css('width','0%');
         $(".toast-msg").html('Domain not added to this list');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         $('#recipientsdata').addClass('d-none');
      }
   });
}
*/


$(document).on('change', '.rec_type', function(){
  var  type = $(this).val();
   $("#camp_id").val('');
   $('#created-list').val('');
   $("#file-csv").val('');
   $('#old_csv').val('');
   $('#validcontact').val(0);
   $('#recipientsdata').html('');

  if(type=='1'){
   if( $('#created-list option').length<= 1  ){
      $('.campaignType').hide();
      $('.csvType').hide();
      $('#not-list-modal').modal('show');
   } else{
      $('.listType').show();
      $('.campaignType').hide();
      $('.show-features').addClass('d-none');
       $('.show-features').html('');
      $('.csvType').hide();
      $(".listType").css({
          height:   '100%', // Optional if #myDiv is already absolute
          visibility: 'visible',
         
      });
      $(".campaignType").css({
          height:   '0', // Optional if #myDiv is already absolute
          visibility: 'hidden',
         
      });
      $(".csvType").css({
          height:   '0', // Optional if #myDiv is already absolute
          visibility: 'hidden',
         
      });
   
   }
  
  }
  if(type=='2'){
   if(userplan=='1'){
      $('#title_plan').html('Import site feature is only available in Paid plans');
      $('#upgrade-modal').modal('show');
      $('.listType').hide();
      $('.campaignType').hide();
   } else{
      
      $('.listType').hide();
      $('.campaignType').hide();
      $('.show-features').html('');
      $('.show-features').removeClass('d-none');
      $('.csvType').show();
      $(".listType").css({
          height:   '0', // Optional if #myDiv is already absolute
          visibility: 'hidden',
         
      });
      $(".campaignType").css({
          height:   '0', // Optional if #myDiv is already absolute
          visibility: 'hidden',
         
      });
       $(".csvType").css({
          height:   '100%', // Optional if #myDiv is already absolute
          visibility: 'visible',
         
      });
   }

  }
  if(type=='3'){
  if( $('#camp_id option').length<= 1  ){
      $('.listType').hide();
      $('.csvType').hide();
      $('#not-campiagn-modal').modal('show');
   } else {
      $('.listType').hide();
      $('.campaignType').show();
      $('.csvType').hide();
      $('.show-features').addClass('d-none');
       $('.show-features').html('');
       $(".listType").css({
          height:   '0', // Optional if #myDiv is already absolute
          visibility: 'hidden',
         
      });
     $(".campaignType").css({
          height:   '100%', // Optional if #myDiv is already absolute
          visibility: 'visible',
         
      });
      $(".csvType").css({
          height:   '0', // Optional if #myDiv is already absolute
          visibility: 'hidden',
         
      });
     

  }



}

});
function loadList(){
  var collectionId = $("#created-list").val();
 
  if(collectionId!=''){
      $('.listType').show();
      $(".listType").css({
          height:   '100%', // Optional if #myDiv is already absolute
          visibility: 'visible',
         
      });
      listDataLoading();
      $("#customtype").val('1');
      customTagTypes();

  }

  

}

function listDataLoading(){
   $("#form-submit-loading").addClass('show');
  $('#recipientsdata').html('');
  $('.show-features').html('');
  $('#schedule-div').removeClass('d-none');
  $('#draft-btn').removeClass('d-none');
  $('#schedule-div').removeClass('note-editor');
  $('#schedule-note-div').addClass('d-none');
  $('.show-features').addClass('d-none');
  $('.features').val('');
  $('.is_feature').val('');
  $('.credit_deduct').val('');
  $("#camp_id").val('');
  $("#file-csv").val('');
  var collectionId = $("#created-list").val();
  var size = $("#created-list").find(":selected").data('size');
  $('#validcontact').val(size);
  var type = $("#created-list").data('type');
  $('.meter').show();
  $('.meter').css('width','100%');
  $.ajax({
        url: appurl+"list-domain-collection",
        type: "post",
        data: {collectionId:collectionId,type:type},
        success: function(text) {
         if(text['type']=='limit'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%');
            $('#title_plan').html(text['message']);
            $('#upgrade-modal').modal('show');
             //.error('Your current plan allows for upto '+text['size_limit']+' contacts in a single list so if you wish to upload upto 1500 contacts in a single list  upgrade your plan');
          }else if(text['type']=='no_plan'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%')
             toastr.error(text['message']);
          
          } else {
         $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%');
             toastr.success('Your list Imported successfully');
            $('#recipientsdata').html(text);
            $('.refine-list').addClass('d-none');
         }
            
             
        },
        error: function(xhr, status, error) 
        {
         $("#form-submit-loading").removeClass('show');
          $('.meter').hide();
          $('.meter').css('width','0%');
           $(".toast-msg").html('Opps! Something went wrong');
               var toast = new bootstrap.Toast(toastLiveExample);
               toast.show();
         
        },
  });
}

function loadCampData(){
   $("#form-submit-loading").addClass('show');
  $('#recipientsdata').html('');
  $('.show-features').html('');
  $('#schedule-div').removeClass('d-none');
  $('#draft-btn').removeClass('d-none');
  $('#schedule-div').removeClass('note-editor');
  $('#schedule-note-div').addClass('d-none');
  $('.show-features').addClass('d-none');
  $('.features').val('');
  $('.is_feature').val('');
  $('.credit_deduct').val('');
  $("#created-list").val('');
  $("#file-csv").val('');
  var camp_id = $("#camp_id").val();
  var size = $("#camp_id").find(":selected").data('size');
  $('#validcontact').val(size);
  var type = $("#camp_id").data('type');
  $("#customtype").val('3');
  $('.meter').show();
  $('.meter').css('width','100%');
  $.ajax({
        url: appurl+"list-campaign-data",
        type: "post",
        data: {camp_id:camp_id,type:type},
        success: function(text) {
         if(text['type']=='limit'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%');
            $('#title_plan').html(text['message']);
            $('#upgrade-modal').modal('show');
             //toastr.error('Your current plan allows for upto '+text['size_limit']+' contacts in a single list so if you wish to upload upto 1500 contacts in a single list  upgrade your plan');
          }else if(text['type']=='no_plan'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%')
             toastr.error(text['message']);
          
          } else {
            $('.meter').hide();
            $('.meter').css('width','0%');
            $('#recipientsdata').html(text);
            customTagTypes();
            $("#form-submit-loading").removeClass('show');
             toastr.success('Your list Import successfully');
         }
        },
        error: function(xhr, status, error) 
        {
         $("#form-submit-loading").removeClass('show');
          $('.meter').hide();
          $('.meter').css('width','0%');
           $(".toast-msg").html('Opps! Something went wrong');
               var toast = new bootstrap.Toast(toastLiveExample);
               toast.show();
         
        },
  });
}




const insertTemplate = (id) =>{
   var rid = $('#follow-id').val();
   $.post("/insertTemplate", {action: 'insertTemplate', "_token": $('meta[name="csrf-token"]').attr('content'), id:id}).done(function(data){
      if(data!=''){
         $('#subject-'+rid).summernote('code', data.template.subject);
         $('#summernote-'+rid).summernote('code', data.template.body);
         $('#tid-'+rid).val(data.template.id);
         $("#insert-template").modal('hide');
         
      }
   });
}

/*// function for create campaign
const createCampaign = () =>{
   let campaignName = $("#campaign-name").val();
   if(campaignName==''){
      $("#liveToast").removeClass('bg-dark');
      $("#liveToast").addClass('bg-danger');
      $(".toast-msg").html(`Please enter Campaign Name`);
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return false;
   }

   let target = ''
   if ($("#target").prop("checked")) {
      target = $("#target").val();
   }else{
      $("#liveToast").removeClass('bg-dark');
      $("#liveToast").addClass('bg-danger');
      $(".toast-msg").html(`Please choose whom you are targeting`);
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return false;
   }
   
   let listid = $("#created-list").val();
   let csv = document.getElementById("file-csv").files[0];
   // console.log(csv);
   if(listid=='' ||  csv ==''){
      $("#liveToast").removeClass('bg-dark');
      $("#liveToast").addClass('bg-danger');
      $(".toast-msg").html(`Please select recipients`);
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return false;
   }

 let subject = $('.subject').summernote('code');
   if($('.subject').summernote('isEmpty')){
      $("#liveToast").removeClass('bg-dark');
      $("#liveToast").addClass('bg-danger');
      $(".toast-msg").html(`Please enter outreach body`);
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return false;
   }

   let obody = $('.message').summernote('code');
   if($('.message').summernote('isEmpty')){
      $("#liveToast").removeClass('bg-dark');
      $("#liveToast").addClass('bg-danger');
      $(".toast-msg").html(`Please enter outreach body`);
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return false;
   }

   let tid = $("#tid").val();
   let day = $('input[name="day"]:checked').serialize();
   let startDate = $("#start-date").val();
   let startTime = $("#start-time").val();
   let endTime = $("#end-time").val();
   let range = $("#myRange").val();
   // $.post("/createCampaign", {action: 'createCampaign', "_token": $('meta[name="csrf-token"]').attr('content'), campaignName:campaignName, target:target, listid:listid, tid:tid, day:day, startDate:startDate, startTime:startTime, endTime:endTime, range:range}).done(function(data){
   //    if(data=='1'){
   //       $(".toast-msg").html('Campaign Created');
   //       var toast = new bootstrap.Toast(toastLiveExample);
   //       toast.show();
   //    }else{
   //       $(".toast-msg").html('Something went wrong');
   //       var toast = new bootstrap.Toast(toastLiveExample);
   //       toast.show();
   //    }
   // });
}
*/
const addAutofollowup = () =>{
   $("#auto-followup-section").removeClass('d-none');
   $("#auto-followup-btn").attr('disabled','disabled');
}

/*// function for add personalize Name
const personalizeName = () =>{
   let Name = '<span >Name</span>';
   $('#summernote').summernote('pasteHTML', Name);
}

// function for add personalize URL
const personalizeURL = () =>{
   let url = '<span >Website</span>';
   $('#summernote').summernote('pasteHTML', url);
}

// function for add personalize Title
const personalizeTitle = () =>{
   let title = '<span >Title</span>';
   $('#summernote').summernote('pasteHTML', title);
}*/


/*$(".subject").on("summernote.mouseup summernote.mousedown", function (e, mouseEvent) {
   var rid = $(this).data('rid');
   $("#custom-ele-"+rid).addClass('elementSub');
   $("#custom-ele-"+rid).removeClass('elementMsg');
});
$(".summernote").on("summernote.mouseup summernote.mousedown", function (e, mouseEvent) {
   var rid = $(this).data('rid');
   $("#custom-ele-"+rid).removeClass('elementSub');
   $("#custom-ele-"+rid).addClass('elementMsg');
})*/





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
          var title = '<span contenteditable="false"  class="custom-data custom-txt-'+dynamic_field_val+' msg  msg-'+dynamic_field_val+'-'+pid+'  custom-data-'+dynamic_field_val+'" id="custom-data-'+pid+'" data-val="'+pid+'">'+field_val+'</span>';
         $('#summernote-'+pid).summernote('pasteHTML', title);
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
      var field_val =  $(this).data('customval');
   }
   if(dynamic_field_val != ''){
      var title = '<span contenteditable="false"  class="custom-data custom-txt-'+dynamic_field_val+' sub  sub-'+dynamic_field_val+'-'+pid+' custom-data-'+dynamic_field_val+'"   id="custom-data-'+pid+'  data-val="'+pid+'">'+field_val+'</span>';
         $('#subject-'+pid).summernote('pasteHTML', title);
         dNum++;
   }

});

function unSubscribeTag(pid){
   var dynamic_field_val = 'Unsubscribe';
   var title = '<a  href="" class="" id="custom-data-'+pid+'" data-val="'+pid+'">'+dynamic_field_val+'</a>';
   $('#summernote-'+pid).summernote('pasteHTML', title);
}
function ordinal_suffix_of(i) {
    var j = i % 10,
        k = i % 100;
    if (j == 1 && k != 11) {
        return  "st";
    }
    if (j == 2 && k != 12) {
        return  "nd";
    }
    if (j == 3 && k != 13) {
        return  "rd";
    }
    return  "th";
}

$(document).ready(function(){

var i = $('#stage-count').val();

var k = i-1;

var count = 0;

$('.addMorenumber').click(function() {
 i++;
 k++;
 count++;
 
var st = ordinal_suffix_of(k);

if(k== 1){
   var tname = "Outreach";
} else{
   var sf = ordinal_suffix_of(k-1);
   var tname = ''+(k-1)+''+sf+' Followup';
}
//console.log(k);
   var email = $('.test-mail').val();
   var is_same_thread = $('#is_same_thread').val();
   
   if(is_same_thread== 1){
      var subject = $('#subject-1').summernote('code');
   } else{
      var subject = '';
   }
   $('.blockNumbers').append('<div class="blockNumber rem'+i+'"> <div id="auto-followup-section"> <div class="card rounded-0 border-0 shadow-sm mb-3"> <div class="card-body"> <div class="row"> <div class="col-sm-8"> <div class="hstack gap-3 mb-3"> <div> <h5 class="fw-bold template-title titleF-'+st+'"> <span class="rnumber ees">'+k+''+st+'</span> Followup </h5> </div> <div class="ms-auto"> <button type="button" class="btn btn-outline-dark btn-sm fw-500" onClick="showTemplateList('+i+')" id="open-template-'+i+'"> <i class="bi bi-plus-lg"></i> Insert Template </button> <button id="'+i+'" class="btn btn-danger btn-sm shadow-none removeNumber" type="button" data-toggle="tooltip" data-placement="top" title="Remove this numbers"> <i class="bi bi-dash"></i> </button> </div> </div> <div class="form-floating mb-3"> <textarea class="form-control rounded-4 shadow-none form-control-sm  subject followup-subject" name="subject[]" data-rid="'+i+'" id="subject-'+i+'" rows="2" autocomplete="off">'+subject+'</textarea> </div> <textarea name="message[]" data-rid="'+i+'" id="summernote-'+i+'" class="form-control message summernote"></textarea> <input type="hidden" name="is_followup[]" value="1" id="is_followup-'+i+'" class="is_followup"> <input type="text" value="Note: Unsubscribe option will be auto appended to all your outbound e-mails" class="form-control" readonly> <input type="hidden" name="temp_id[]" class="tid" id="tid-'+i+'"> <div class="d-flex align-items-center mt-2"> <span class="me-3">If status of '+tname+' email is</span> <label class="btn bg-white btn-sm border border-warning shadow-sm rounded-0 me-3 text-start  followupRadio fol-condition-1-'+i+' " id="op-condition-1-'+i+'" data-cond="'+i+'" onClick="radioCheck('+i+',1)" data-fol_type="1" for="option1"> <input type="radio" class="btn-check my-radio-btn" name="followup_condition[]" id="open_condition-'+i+'" value="1" autocomplete="off"> Not Opened </label> <label class="btn bg-white btn-sm border border-warning shadow-sm rounded-0 me-3 text-start  followupRadio  fol-condition-2-'+i+'" id="rep-condition-2-'+i+'" onClick="radioCheck('+i+',2)" data-cond="'+i+'" data-fol_type="2" for="option1"> <input type="radio" class="btn-check my-radio-btn" name="followup_condition[]" id="reply_condition-'+i+'" value="2" autocomplete="off"> Not Replied </label> <label class="btn bg-white border border-warning btn-sm shadow-sm me-3 rounded-0 text-start   radio-active followupRadio fol-condition-3-'+i+' " id="reg-condition-3-'+i+'""data-cond="'+i+'" data-fol_type=" 3" onClick="radioCheck('+i+',3)" checked for="option1"> <input type="radio" class="btn-check my-radio-btn" name="followup_condition[]" id="regard_condition-'+i+'" value="3" autocomplete="off"> Regardless </label> </div> <input type="hidden" name="followup_cond[]" id="followup_cond-'+i+'" value="3"> <div class="hstack gap-3 mt-3 '+i+'"> <div class="input-group" ><label class="input-group-text fw-600" for="from_email">From</label> <select class="form-select form-select-sm shadow-none accountEmails" id="from" name="from"> <option value="">Select</option></select></div> <div class="input-group"> <span class="input-group-text" id="to">To</span> <input class="form-control form-control-sm me-auto shadow-none" type="text" placeholder="Send test mail" name="test-mail[]" id="test-mail-'+i+'" value="'+email+'"> </div> <button class="btn btn-primary btn-sm text-nowrap shadow-sm fw-500 d-none" type="button" disabled id="wait-test-btn-'+i+'"> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="">Sending...</span> </button> <button type="button" class="btn btn-green text-nowrap shadow-sm btn-sm SendMailCamp rounded-1" onClick="SendMailCamp('+i+')" data-rNum="'+i+'" id="send-mail-'+i+'">Send test email</button> </div> </div> <div class="col-sm-4 customTag" id="customTag-'+i+'"> <div class="card shadow-sm h-100"> <div class="card-header fw-500"> Personalization tags </div> <div class="card-body"> <div class="alert-info p-2 shadow-sm" role="alert"> <p class="mb-0"> <i class="bi bi-info-circle-fill"></i> Use the merge tags to personalize your campaigns and avoid spam filters: </p> </div> <div class="row mb-3"> <div class="col-sm-6"> <div class="alert alert-light  py-1 mb-0 rounded-0" role="alert"> <span class="fw-500"> <small>Name</small> </span> </div> </div> <div class="col-sm-6"> <button type="button" class="btn btn-outline-success btn-sm fw-500 resetBt resetBtnmsg  resetText-Name" data-customval="Name" id="custom-ele-'+i+'" data-pid="'+i+'"> <i class="bi bi-dash"></i> </button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg" data-customval="Name" id="custom-ele-'+i+'" data-pid="'+i+'"> <i class="bi bi-plus-lg"></i> </button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallback" data-customval="Title" id="custom-ele-'+i+'" data-pid="'+i+'"> <i class="bi bi-pencil"></i> </button> </div> </div> <div class="row mb-3"> <div class="col-sm-6"> <div class="alert alert-light  py-1 mb-0 rounded-0 element" role="alert"> <span class="fw-500"> <small>Website</small> </span> </div> </div> <div class="col-sm-6"> <button type="button" class="btn btn-outline-success btn-sm fw-500 resetBt  resetBtnmsg resetText-Website " data-customval="Website" id="custom-ele-'+i+'" data-pid="'+i+'"> <i class="bi bi-dash"></i> </button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg" data-customval="Website" id="custom-ele-'+i+'" data-pid="'+i+'"> <i class="bi bi-plus-lg"></i> </button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallback" data-customval="Website" id="custom-ele-'+i+'" data-pid="'+i+'"> <i class="bi bi-pencil"></i> </button> </div> </div> <div class="row mb-3"> <div class="col-sm-6"> <div class="alert alert-light  py-1 mb-0 rounded-0" role="alert"> <span class="fw-500"> <small>Title</small> </span> </div> </div> <div class="col-sm-6"> <button type="button" class="btn btn-outline-success btn-sm fw-500 resetBt resetBtnmsg resetText-Title" data-customval="Title" id="custom-ele-'+i+'" data-pid="'+i+'"> <i class="bi bi-dash"></i> </button> <button type="button" class="btn btn-outline-success btn-sm fw-500 element elementMsg" data-customval="Title" id="custom-ele-'+i+'" data-pid="'+i+'"> <i class="bi bi-plus-lg"></i> </button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallback" data-customval="Title" id="custom-ele-'+i+'" data-pid="'+i+'"> <i class="bi bi-pencil"></i> </button> </div> </div> </div> <div class="card-footer text-muted"></div> </div> </div> </div> </div> </div> </div> </span> </div> </div> </div>');
   $('.message').summernote({
         toolbar: [ ["style", ["style"]], ["font", ["bold", "italic", "underline", "fontname", "clear"]], ["color", ["color"]], ["paragraph", ["ul", "ol", "paragraph"]], ["table", ["table"]], ["insert", ["link", "picture"]], ["view", ["codeview"]], ],
         
         placeholder: `Hi there,<br><br>I wanted to follow up on the email I sent you a few days back about paid guest post collaboration on your blog. You must be incredibly busy, but I do hope we can connect.<br>Are you the right person to contact about this? If yes, do you accept paid article deals on your blog.<br><br><br>Kind regards `,
         tabsize: 2,
         height:300,
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
      });
      $('.subject').summernote({
        toolbar: false  ,
         placeholder: `Subject`,
         followingToolbar: false   ,
         focus: true,
         spellcheck: false,
         height: 46,
         disableResizeEditor: true ,
         callbacks: {
           onKeyup: function (contents, $editable) {
              var rid = $(this).data('rid');
               $(".element").addClass('elementSub');
               $(".element").removeClass('elementMsg');

               $(".resetBt").addClass('resetBtsub');
               $(".resetBt").removeClass('resetBtnmsg');

           }
           
         }
      });

      $('#stage-count').val(i);
      getEmailAccount();
      customTagTypes();
      
});



$(document).on('click', '.removeNumber', function(){

   var id = $(this).attr('id');
    $('.rem'+id+'').remove();
 
  $.each($('.rnumber.ees'), function(index, value) {
      //if(index > 0) {  
         //console.log(index)  ;
        var st = ordinal_suffix_of(index+1);  
        var txt =  ''+(index+1)+''+st;
        $(value).text(txt);
      //}
    });
  var len = $('.rnumber.ees').length;
  //console.log('-----------');
  //console.log(len);
   k = $('.rnumber.ees').length;

}); 

}); 


/*$('.numberBox').on('click','.removeNumber',function() {
  $(this).tooltip("hide");
  $(this).parents(".blockNumber").remove();

});
*/


/*$(function() {
  $(".addMorenumber").click(function (e) {
      var newProd = $('#cloneDiv')
              .clone()
              .appendTo('.blockNumber')
              .removeAttr('id')
              .addClass('newClone-'+i);
       $('.blockNumber').append(newProd);
       $('.newClone-'+i).find('.subject').attr('id', 'subject-' + i);
       $('.newClone-'+i).find('.message').attr('id', 'summernote-' + i);
       $('.newClone-'+i).find('.subject').attr('data-rid',i);
       $('.newClone-'+i).find('.message').attr('data-rid',i);
       $('.newClone-'+i).find('.element').attr('data-pid',i);
       $('.newClone-'+i).find('.element').attr('id','custom-ele-' + i);
       $('.newClone-'+i).find('.tid').attr('id','tid-' + i);
       $('.newClone-'+i).find('.is_followup').attr('id','is_followup-' + i);
       $('.newClone-'+i).find('.test-mail').attr('id','test-mail-' + i);
       $('.newClone-'+i).find('.template-title').text('FollowUp Stage-'+i);
      $('#stage-count').val(i);
      i++;
  });

  
});*/

function showTemplateList(id){
   $("#insert-template").modal('show');
   $('#follow-id').val(id);
}

function compareDates() {
     var endTime = $('#endtime').parseValToNumber();
     var startTime = $('#starttime').parseValToNumber();
     return endTime > startTime;
    
} 
function compareStartTime() {
     var nowtime =  $('#current_time').parseValToNumber();
     var startTime = $('#starttime').parseValToNumber();
     return startTime >= nowtime;
    
} 

$.fn.parseValToNumber = function() {
    return parseInt($(this).val().replace(':',''), 10);
}

$( document ).ready(function() {
   jQuery.validator.addMethod("checkDates", function(value, element) {
  /* see function above*/
  return  compareDates() ;
 }, "End time must be after start time");

jQuery.validator.addMethod("compareStartTime", function(value, element) {
  /* see function above*/
  return  compareStartTime() ;
 }, "Start time must be after current time");
    var is_tools = $('#is_feature').val();
    $('.create-campaign').each(function () {
      $.validator.setDefaults({
           ignore: []
       });
        $(this).validate({
              ignore: ".note-editor *",
              onfocusout: false,
            rules: {
                campaign_name : {
                    required: true,
                },
                target : {
                    required: true,
                },
                rec_type : {
                    required: true,
                },
                
                list_id:{
                    require_from_group: [1, '.customTagType']
                },
                camp_id:{
                    require_from_group: [1, '.customTagType']
                },
                csv_file:{
                    require_from_group: [1, '.customTagType'],

                },
                old_csv:{
                    require_from_group: [1, '.customTagType']
                },
                groups: {
                    mygroup: "list_id csv_file old_csv camp_id"
                },

                from_email : {
                    required: true,
                },
               "subject[]": "required",
               "message[]": "required",

                start_date : {
                    required: true,
                },
                starttime : {
                    required: true,
                    
                },
                endtime : {
                    required: true,
                    
                },
                cooling_period : {
                    required: true,
                },
                
                
            },
          
           
            messages: {
               "campaign_name": {
                   required: 'Campaign field is required',
               },
               "rec_type": {
                   required: 'Please select  recipients type',
               },
             
               "target": {
                   required: 'Target type field is required',
               },
               "list_id": {
                   require_from_group: 'Please select  recipients data',
               },
               "camp_id": {
                   require_from_group: 'Please select  recipients data',
               },
               "csv_file": {
                   require_from_group: 'Please select  recipients data',

               },
            
                "from_email": {
                   required: 'Please select sender email',

               },
               "subject[]": {
                   required: 'Please enter subject line',
               },
               "message[]": {
                   required: 'Please enter Message',
               },
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
            
            showErrors: function(errorMap, errorList) {
                $(".create-campaign").find("input").each(function() {
                    $(this).removeClass("error");
                });
                //$("#divErrorContainer").html("");
             
                if(errorList.length) {
                    //$("#divErrorContainer").html(errorList[0]['message']);
                    $(".toast-msg").html(errorList[0]['message']);
                 var toast = new bootstrap.Toast(toastLiveExample);
                 toast.show();
                   // toastr.error(errorList[0]['message']);
                    $(errorList[0]['element']).addClass("error");
                }
            },

            

        });
    });
});

$(document).on("click", "#draft-btn", function(event){
    $('#btn_type').val('draft');
    if ($(".create-campaign").valid()) {
      $("#form-submit-loading").addClass('show');
      $(".create-campaign").submit();
    }
});

$('#preview-btn').on('click', function() {  
    if ($(".create-campaign").valid()) {
      $("#form-submit-loading").addClass('show');
      $('#btn_type').val('lunch');
      var data = $('.create-campaign').serialize();
       $.ajax({
        url: appurl+"preview-campaign",
        type: 'POST',
        data: data,  
        success:function(info){
          if(info['type']=='error'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%');
            $('#upload-error-modal').modal('show');
         } else{
             $("#form-submit-loading").removeClass('show');
             $("#preview-campaign").modal('show');
             $('#preview-campaign-section').html(info);
             $('#preview-campaign-section').show();
         }
        },
        error: function(xhr, status, error) 
        {
         $("#form-submit-loading").removeClass('show');
           $(".toast-msg").html('Opps! Something went wrong');
               var toast = new bootstrap.Toast(toastLiveExample);
               toast.show();
         
        },
      });
    }
});


$(document).on("click", "#launch-campaign", function(event){
    if ($(".create-campaign").valid()) {
      $("#form-submit-loading").addClass('show');
     // $(this).val('Submitting. Please Sending...');
     
      $(".create-campaign").submit();
    }
});



$(document).on('change', '#file-csv', function(){  
   var input = this;
   $('#file_name').html('');
   var url = $(this).val();
   var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
   if (input.files && input.files[0]&& (ext == "csv" )) 
  {
  $("#form-submit-loading").addClass('show');
  $('.refine-list').removeClass('d-none');
  $('#schedule-div').removeClass('d-none');
  $('#draft-btn').removeClass('d-none');
  $('#schedule-div').removeClass('note-editor');
  $('#schedule-note-div').addClass('d-none');
  $('.show-features').addClass('d-none');
  $('.features').val('');
  $('.is_feature').val('');
  $('.credit_deduct').val('');
  $('#created-list').val('');
  $('#camp_id').val('');
  $('#recipientsdata').html('');
  var filename = $('input[type=file]').val().replace(/C:\\fakepath\\/i, '')
 
 
   

  $('.meter').show();
  $('.meter').css('width','100%');
   
    var fd = new FormData();
    fd.append('file', this.files[0]); // since this is your file input
   // console.log(this.files[0]);
   $("#customtype").val('2');
   var type = $(this).data('type');
    $.ajax({
        url: appurl+"domain-upload",
        type: "post",
        processData: false, // important
        contentType: false, // important
        data: fd,
        success: function(text) {
          
           if(text['type']=='error'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%');
            $('#upload-error-modal').modal('show');

             //toastr.error('You must have a column name  Email in your excel sheet');
           }else if(text['type']=='limit'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%');
            // toastr.error('Your current plan allows for upto '+text['size_limit']+' contacts in a single list so if you wish to upload upto 1500 contacts in a single list  upgrade your plan');
            $('#title_plan').html(text['message']);
            $('#upgrade-modal').modal('show');
          }else if(text['type']=='not_allow'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%');
            $('#title_plan').html('Import site feature is only available in Paid plans');
            $('#upgrade-modal').modal('show');
          }else if(text['type']=='no_plan'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%')
             toastr.error(text['message']);
         
          } else {
             $('#file_name').html(filename);
            $('.meter').hide();
            $('.meter').css('width','0%');
            $('#recipientsdata').removeClass('d-none');
            $('#recipientsdata').html(text);
            $('#is_file_change').val(1);
            customTagTypes();
            creditModel();

            $("#form-submit-loading").removeClass('show');
            toastr.success('Your list Import successfully');

            
          }
            
             
        },
        error: function(xhr, status, error) 
        {
         $("#form-submit-loading").removeClass('show');
          $('.meter').hide();
           $('.meter').css('width','0%');
          var err = JSON.parse(xhr.responseText);
           toastr.error(xhr.responseText);
          $.each(xhr.responseText.errors, function (key, item) 
          {

            toastr.error(item);
          });
        },
    });
 } else{
      $('#upload-error-modal').modal('show');
      return false;
 }
});



// Delivery Detail
/*$(document).on("change", ".customTagType", function () {
  $('.loading').hide();
  var collectionId = $("#created-list").val();
  var stageCount = $("#stage-count").val();
  var type = $('#customtype').val();
  $.ajax({
    url: appurl+"get-custom-data",
    type: 'POST',
    data:{collectionId:collectionId,type:type,stageCount:stageCount}, 
    success:function(data){
      $('.loading').hide();
      if(data['type']=='success')
      {
        if(data['custom-type']=='1')
        {
          var stage = data['stageCount'];
          for (let p = 0; p <= stage; p++) {
           var e = $('<div class="card shadow-sm h-100 tag-div"> <div class="card-header fw-500"> Personalization tags </div> <div class="card-body"> <div class=" alert-info p-2 shadow-sm" > <p class="mb-0"><i class="bi bi-info-circle-fill"></i> Use the merge tags to personalize your campaigns and avoid spam filters:</p> </div> <div class="row mb-3"> <div class="col-sm-8"> <div class=" alert-light  py-1 mb-0 rounded-0 " > <span class="fw-500"><small>Name</small></span> </div> </div> <div class="col-sm-4"> <button type="button" class="btn btn-outline-success btn-sm fw-500 resetBt resetBtnmsg" data-pid="'+p+'" id="custom-ele-'+p+'"  data-customval="Name"><i class="bi bi-dash"></i></button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg"  data-pid="'+p+'" id="custom-ele-'+p+'"  data-customval="Name"><i class="bi bi-plus-lg"></i></button> </div> </div> <div class="row mb-3"> <div class="col-sm-8"> <div class=" alert-light  py-1 mb-0 rounded-0" > <span class="fw-500"><small>Website</small></span> </div> </div> <div class="col-sm-4"> <button type="button" class="btn btn-outline-success btn-sm fw-500  resetBt resetBtnmsg" data-pid="'+p+'"  id="custom-ele-'+p+'" data-customval="Website"><i class="bi bi-dash"></i></button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element  elementMsg"   data-pid="'+p+'"  id="custom-ele-'+p+'" data-customval="Website" ><i class="bi bi-plus-lg"></i></button> </div> </div> <div class="row mb-3"> <div class="col-sm-8"> <div class=" alert-light  py-1 mb-0 rounded-0" > <span class="fw-500"><small>Title</small></span> </div> </div> <div class="col-sm-4"> <button type="button" class="btn btn-outline-success btn-sm fw-500 resetBt resetBtnmsg" data-pid="'+p+'"  id="custom-ele-'+p+'" data-customval="Title"><i class="bi bi-dash"></i></button> <button type="button" class="btn btn-outline-success btn-sm fw-500  element elementMsg" data-pid="'+p+'"  id="custom-ele-'+p+'" data-customval="Title" ><i class="bi bi-plus-lg"></i></button> </div> </div> </div> <div class="card-footer text-muted">  <button type="button" class="btn btn-outline-dark btn-sm fw-500" onClick="unSubscribeTag('+p+')" id="unsubscribe-tag-'+p+'" data-pid="'+p+'"><i class="bi bi-plus-lg"></i> Use Unsubscribe tag</button></div> </div>'); 
           $('#customTag-'+p).html(e);
        
          }
              
        }
        if(data['custom-type']=='2')
        {
          var stage = data['stageCount'];
          var heading = data['heading'];
          var tag = '';
          for (let k = 0; k <= stage; k++) {
            tag ='<div class="card shadow-sm h-100 tag-div-csv"> <div class="card-header fw-500"> Personalization tags </div> <div class="card-body"> <div class=" alert-info p-2 shadow-sm"> <p class="mb-0"> <i class="bi bi-info-circle-fill"></i> Use the merge tags to personalize your campaigns and avoid spam filters: </p> </div>';
            
            if(heading != null && heading.length>0){
             for (let j = 0; j <= 5; ++j) {
                if(heading[j]!=undefined){

                tag +='<div class="row mb-3"> <div class="col-sm-8"> <div class=" alert-light  py-1 mb-0 rounded-0 "> <span class="fw-500"> <small>'+heading[j]+'</small> </span> </div> </div> <div class="col-sm-4"> <button type="button" class="btn btn-outline-success btn-sm fw-500 resetBt resetBtnmsg" data-pid="'+k+'" id="custom-ele-'+k+'" data-customval="'+heading[j]+'"> <i class="bi bi-dash"></i> </button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg" data-pid="'+k+'" id="custom-ele-'+k+'" data-customval="'+heading[j]+'"> <i class="bi bi-plus-lg"></i> </button> </div> </div>'; 
              
                }
              }
            }
            tag += '</div> <div class="card-footer text-muted"> <button type="button" class="btn btn-outline-dark btn-sm fw-500" onClick="unSubscribeTag('+k+')" id="unsubscribe-tag-'+k+'" data-pid="'+k+'"><i class="bi bi-plus-lg"></i> Use Unsubscribe tag</button> </div> </div>'; 

            $('#customTag-'+k).html(tag);
          }
             
        }
        
      }
      else
      {
        toastr.error(data['message'], {timeOut: 5000})
        //location.reload();
      }
    
    }
  });
});*/

function customTagTypes() {

  var collectionId = $("#created-list").val();
  var stageCount = $("#stage-count").val();

  var type = $('#customtype').val();
  if(type!=''){
   $.ajax({
    url: appurl+"get-custom-data",
    type: 'POST',
    data:{collectionId:collectionId,type:type,stageCount:stageCount}, 
    success:function(data){
      $('.loading').hide();
      if(data['type']=='success')
      {

        if(data['custom-type']=='1')
        {
         
          var stage = data['stageCount'];
          for (let p = 0; p <= stage; p++) {
            var aval = $('.fallback-Name').val();

            if(aval != undefined){
               var Name = aval;
            } else{
               var Name = 'Name';
            }
            var aweb = $('.fallback-Website').val();
            if(aweb != undefined ){
               var Website = aweb;
            } else{
               var Website = 'Website';
            }
            var atit = $('.fallback-Title').val();
            if(atit != undefined ){
               var Title = atit;
            } else{
               var Title = 'Title';
            }
           var e = $('<div class="card rounded-0 shadow-sm border-0 h-100 tag-div"> <div class="card-header fw-500"> Personalization tags </div> <div class="card-body"> <div class=" alert-info p-2 shadow-sm mb-4" > <p class="mb-0"><i class="bi bi-info-circle-fill"></i> Use the merge tags to personalize your campaigns and avoid spam filters:</p> </div> <div class="row mb-3"> <div class="col-sm-6"> <div class=" alert-light  py-1 mb-0 rounded-0 " > <span class="fw-500"><small>Name</small></span> </div> </div> <div class="col-sm-6">  <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg fallback-Name"  data-pid="'+p+'" id="custom-ele-'+p+'"  data-customval="Name" value="'+Name+'"><i class="bi bi-plus-lg"></i></button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallback"  data-pid="'+p+'" id="custom-ele-'+p+'"  data-customval="Name"><i class="bi bi-pencil"></i></div> </div> <div class="row mb-3"> <div class="col-sm-6"> <div class=" alert-light  py-1 mb-0 rounded-0" > <span class="fw-500"><small>Website</small></span> </div> </div> <div class="col-sm-6">  <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg fallback-Website"  data-pid="'+p+'" id="custom-ele-'+p+'"  data-customval="Website" value="'+Website+'"><i class="bi bi-plus-lg"></i></button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallback"  data-pid="'+p+'" id="custom-ele-'+p+'"  data-customval="Website"><i class="bi bi-pencil"></i></button></div> </div>  </div>  </div> ');
           $('#customTag-'+p).html(e);
          }
              
        }
        if(data['custom-type']=='2' || data['custom-type']=='3')
        {
          var stage = data['stageCount'];
          var heading = data['heading'];
          var tag = '';

          for (let k = 0; k <= stage; k++) {
            tag ='<div class="card rounded-0 shadow-sm border-0 h-100 tag-div-csv"> <div class="card-header fw-500"> Personalization tags </div> <div class="card-body"> <div class=" alert-info p-2 shadow-sm mb-4"> <p class="mb-0" style="font-size:13px;"> <i class="bi bi-info-circle-fill"></i> Use the merge tags to personalize your campaigns and avoid spam filters: </p> </div>';
           
            if(heading != null && heading.length>0){
             for (let j = 0; j <= 10; ++j) {
                if(heading[j]!=undefined ){
                  if(heading[j] !='Level'){
                     var original1 = heading[j];
                     str1 = original1.replace(/\s+/g, '-');

                     var customd = $('.fallback-'+str1).val();
                     //  console.log(customd);
                     if(customd != undefined && customd!='' ){
                        var original = customd;
                        str = original.replace(/\s+/g, '-');
                        var htag = str;
                     } else{
                        var original = heading[j];
                        str = original.replace(/\s+/g, '-');
                        var htag = str;
                     }
                     // console.log(htag);

                  if(htag!='') {
                     tag +='<div class="row mb-3"> <div class="col-sm-6"> <div class=" alert-light  py-1 mb-0 rounded-0 "> <span class="fw-500"> <small>'+heading[j]+'</small> </span> </div> </div> <div class="col-sm-6"><button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg fallback-'+htag+'" data-pid="'+k+'" id="custom-ele-'+k+'" data-customval="'+original+'" value="'+original+'"> <i class="bi bi-plus-lg"></i> </button> <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallback" data-pid="'+k+'" id="custom-ele-'+k+'" data-customval="'+original+'"> <i class="bi bi-pencil"></i> </button>   </div> </div> ';
                  }
                  }
               }
              }
            }
            tag += '</div> <div class="card-footer text-muted"> </div> </div>'; 

            $('#customTag-'+k).html(tag);
          }
             
        }
        
      }
      else
      {
        toastr.error(data['message'], {timeOut: 5000})
        //location.reload();
      }
    
    }
   });
  } else {
      $('.customTag').html('');
  }

}




function creditModel(){

   var valid_contact = $('#valid_contact').val();
   var edit_id = $('#edit_id').val();
   var features = $('.features').val();
   var is_feature = $('.is_feature').val();
   //console.log()

     $.ajax({
        url: appurl+"credit-model",
        type: "post",
        data: {valid_contact:valid_contact,edit_id:edit_id,features:features},
        success: function(text) {
            $("#credit-modal").modal('show');
            $('#credit-section').html(text);
            if(is_feature=='1'){
                  $( ".proceed-btn" ).prop( "disabled", false );
            } else{
               $( ".proceed-btn" ).prop( "disabled", true );
            }
        }
    });
 
}

function editCreditModel(id,features,credit){
     $.ajax({
        url: appurl+"edit-credit-model",
        type: "post",
        data: {id:id,features:features,credit:credit},
        success: function(text) {
          if(text['type']=='error'){
            $('.meter').hide();
            $('.meter').css('width','0%');
             toastr.error('Campiagn Not fund');
          } else {
            $("#edit-credit-modal").modal('show');
            $('#edit-credit-section').html(text);
            
          }
           
        }
    });
 
}
function getEmailAccount(){
   $.ajax({
        url: appurl+"get-email-account",
        type: "post",
        data: {},
        success: function(text) {
            $('.accountEmails').html(text);

        }
    });
}


$(document).on('click', '.proceed-btn', function(){
  $('#schedule-div').removeClass('d-none');
  $('#draft-btn').addClass('d-none');
  $('#schedule-div').removeClass('note-editor');
  $('#schedule-note-div').addClass('d-none');
  var is_only_domain = $('#is_only_domain').val();
  if($("#email_extraction").prop('checked') == false & is_only_domain == true){
    toastr.error('Please select email extraction from domain features');
  }
  else {
         var features = $(".myckx:checked").map(function() {
            return this.value;
         }).get().join(', ');
         var semrus_traff = $('#semrus_traff').val();
         var domain_autho = $('#domain_autho').val();
         var usercredit = $('#usercredit').val();
         $('.domain_authority').val(domain_autho);
         $('.semrus_traffic').val(semrus_traff);
         $('.features').val(features);
         var creditneed = $('#credit').text();
         $('.credit_deduct').val(creditneed);
         $("#credit-modal").modal('hide');
         $("#edit-credit-modal").modal('hide');
         
         if(features !=''){
            $('#schedule-div').addClass('note-editor');
            $('#schedule-div').addClass('d-none');
            $('.refine-list').addClass('d-none');
            $('#schedule-note-div').removeClass('d-none');
            $(".is_feature").val(1);

         }
         if(parseInt(usercredit) <= parseInt(creditneed) ) {
            $('.show-features').html('');
            $('.domain_authority').val('');
            $('.semrus_traffic').val('');
            $('.credit_deduct').val('');
            $('.features').val(''); 
            $(".is_feature").val(0);
            $('#credit-modal').modal('hide');
            $('#credit-cost').html(creditneed);
            $('#title_txt').html('You dont have enough credits to perfom this action');
            $('#balance-low-modal').modal('show');
           
         } else{
            selectedTools();
              $('#draft-btn').removeClass('d-none');
         }
  }
});

$(document).on('click', '.proceed-btn-edit', function(){
  $('.show-features').removeClass('d-none');
  $('#schedule-div').removeClass('d-none');
  $('#schedule-div').removeClass('note-editor');
  $('#schedule-note-div').addClass('d-none');
  var is_only_domain = $('#is_only_domain').val();
  if($("#email_extraction").prop('checked') == false & is_only_domain == true){
    toastr.error('Please select email extraction from domain features');
  }
  else {
       var features = $(".myCheckbox:checked").map(function() {
                return this.value;
            }).get().join(', ');
       $('.features').val(features);
       var creditneed = $('#edit_credit').text();
       $(".is_feature").val(1);
        $('.credit_deduct').val(creditneed);
       $("#credit-modal").modal('hide');
       $("#edit-credit-modal").modal('hide');
       $('#schedule-div').addClass('d-none');
       $('#schedule-div').addClass('note-editor');
       $('#schedule-note-div').removeClass('d-none');
       selectedTools();
  }
});
function selectedTools(){
   $('.show-features').removeClass('d-none');
  var Selfeatures = $('.features').val();
  var Selfeatures = $('.features').val();
  var  features =0;
  if(Selfeatures !=''){
      var features = $('.features').val().split(",").length;

  }
//   console.log(features);
      var creditneed = $('.credit_deduct').val();
      if(Selfeatures !=''){
         /* var fClass1 ='';
          var fClass2 ='';
          var fClass3 ='';
          var fClass4 ='';
          var fClass5 ='';
          var fClass6 ='';
          var fhtml ='<h5> Selected Tools</h5>';
        $.each(Selfeatures.split(","), function(intIndex, objVal) {
         //  console.log(objVal);
          fClass1 = (objVal == 1) ? '' : 'd-none';
          fClass2 = (objVal == 2) ? '' : 'd-none';
          fClass3 = (objVal == 3) ? '' : 'd-none';
          fClass4 = (objVal == 4) ? '' : 'd-none';
          fClass5 = (objVal == 5) ? '' : 'd-none';
          fClass6 = (objVal == 6) ? '' : 'd-none';
          
          fhtml +='<label class="custom-control custom-checkbox '+fClass1+'"> 1. <span class="custom-control-label">Identify and exclude non-blogs from list of <span class="fw-bold">domains</span></span> </label><label class="custom-control custom-checkbox '+fClass3+'"> 2.<span class="custom-control-label">Validate all <span class="fw-bold">emails</span> to exclude invalid contacts</span> </label> <label class="custom-control custom-checkbox '+fClass4+'"> 3.<span class="custom-control-label">>Extract author name of <span class="fw-bold">domains</span> (30% success rate)</span> </label> <label class="custom-control custom-checkbox '+fClass5+'"> 4.<span class="custom-control-label">Compute SEMrush Traffic and Domain Nameity for domain (1 credit per domain)</span> </label> <label class="custom-control custom-checkbox '+fClass6+'"> 5.<span class="custom-control-label">Blog Judgement (1 credit per domain)</span> </label>';
         
        });
        fhtml +='<br><span class="badge">Total Credits need :</span> <span class="badgetext badge badge-primary badge-pill " id="credit">'+creditneed+'</span>';
        */
        fhtml ='<div class="col-sm-6"><div class="alert alert-primary d-flex align-items-center rounded-0 py-1 mt-2 mb-2" role="alert" style="display: none;"><div class="d-flex align-items-center"> <a href="javascript:;" class="validate-contact" style="text-decoration: none;"> <small> View  '+features+' actions selected</small></a></div></div></div>';
        $('.show-features').html(fhtml);
      }

}

// let sendMailOne = document.getElementById('send-mail-1');
// let sendMailOneId = openTemplate.getAttribute('data-rNum');
// sendMailOneId.addEventListener("click", function () {
//    SendMailCamp(sendMailOneId);
// });



function  SendMailCamp(rid) {
   //alert(rid);
var rNum = rid;
var is_mail_send = true;

var validcontact = $('#validcontact').val();
if(validcontact < 1){
   var is_mail_send = false;
}

// console.log(validcontact);
if(is_mail_send == false){
   is_wait = false;
   toastr.error("Please Select list or upload CSV with data to send test mail"); 
   $("#wait-test-btn-"+rNum).addClass('d-none');
   $("#send-mail-"+rNum).removeClass('d-none');
   return false;
}
//console.log(rNum);
$("#wait-test-btn-"+rNum).removeClass('d-none');
$("#send-mail-"+rNum).addClass('d-none');
var type = $('#customtype').val();
var edit_id = $('#edit_id').val();
var camp_id = $('#camp_id').val();
var account_type = $('#account_type').val();
var from_email = $('.accountEmails').val();
var to_email = $('#test-mail-'+rNum).val();

var fallback_text = $('.fallback_text').val();
//console.log(to_email);
var subject = $('#subject-'+rNum).summernote('code');
var message = $('#summernote-'+rNum).summernote('code');

 var is_wait = true;
 if($('#subject-'+rNum).summernote('isEmpty')){
 
    is_wait = false;
    toastr.error("Please enter subject line "); 
      $("#wait-test-btn-"+rNum).addClass('d-none');
    $("#send-mail-"+rNum).removeClass('d-none');
    return false;
 }
 if($('#summernote-'+rNum).summernote('isEmpty')){

    is_wait = false;
    toastr.error("Please enter message "); 
      $("#wait-test-btn-"+rNum).addClass('d-none');
    $("#send-mail-"+rNum).removeClass('d-none');
    return false;
 }

if(from_email =='') 
 {
    is_wait = false;
    toastr.error("Please select sender email address"); 
      $("#wait-test-btn-"+rNum).addClass('d-none');
    $("#send-mail-"+rNum).removeClass('d-none');
    return false;
 }
 if(to_email =='') 
 {
   is_wait = false;
    toastr.error("Please enter email address"); 
      $("#wait-test-btn-"+rNum).addClass('d-none');
    $("#send-mail-"+rNum).removeClass('d-none');
    return false;
 }
 
 if(is_wait == false){
    $("#wait-test-btn-"+rNum).addClass('d-none');
    $("#send-mail-"+rNum).removeClass('d-none');
 }
$.ajax({
      url: appurl+"send-mail",
      type: "post",
      data: {edit_id:edit_id,type:type,subject:subject,message:message,account_type:account_type,from_email:from_email,to_email:to_email,camp_id:camp_id,fallback_text:fallback_text},
      success: function(data) {
        if(data['type']=='success'){
           toastr.success('Mail sent successfully');
        }
        if(data['type']=='error'){
           toastr.error('Mail Not send');
        }
        $("#wait-test-btn-"+rNum).addClass('d-none');
        $("#send-mail-"+rNum).removeClass('d-none');
           
      },
      error: function(xhr, status, error) 
      {
        $("#wait-test-btn-"+rNum).addClass('d-none');
        $("#send-mail-"+rNum).removeClass('d-none');
  
         $(".toast-msg").html('Opps! Something went wrong');
             var toast = new bootstrap.Toast(toastLiveExample);
             toast.show();
       
      },
});
}


/*$(document).ready(function(){
 $("custom-data").on("keyup", function(e) {
  alert(1);
     $('.note-editable').html('').html('Name');
  });
});
*/



$(document).on("click", ".resetBtnmsg", function () {
   var pid = $(this).data('pid');
   var dynamic_field_val = $(this).data('customval');
   if(dynamic_field_val != ''){
          //var title = '<span  class="custom-data" id="custom-data-'+pid+'" data-val="'+pid+'">'+dynamic_field_val+'</span>';
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
          //var title = '<span  class="custom-data" id="custom-data-'+pid+'" data-val="'+pid+'">'+dynamic_field_val+'</span>';
           
           $('.sub-'+dynamic_field_val+'-'+pid+'').addClass('d-none');
           $('.sub-'+dynamic_field_val+'-'+pid+'').text('');
            
         
   }
});




function radioCheck(id,val) {
 
  //$('.followupRadio').removeClass("radio-active");
 
  if(val == 1){
    $('#op-condition-1-'+id).addClass("radio-active");
    $('#rep-condition-2-'+id).removeClass("radio-active");
    $('#reg-condition-3-'+id).removeClass("radio-active");
 // alert(val);
    $('#open_condition-'+id).prop('checked', true).attr('checked', 'checked');
    $('#reply_condition-'+id).prop('checked',false).removeAttr('checked');
    $('#regard_condition-'+id).prop('checked',false).removeAttr('checked');
  }
  if(val == 2){
    $('#op-condition-1-'+id).removeClass("radio-active");
    $('#rep-condition-2-'+id).addClass("radio-active");
    $('#reg-condition-3-'+id).removeClass("radio-active");
    $('#open_condition-'+id).prop('checked',false).removeAttr('checked');
    $('#reply_condition-'+id).prop('checked',true).attr('checked', 'checked');
    $('#regard_condition-'+id).prop('checked',false).removeAttr('checked');
  }
  if(val == 3){
    $('#op-condition-1-'+id).removeClass("radio-active");
    $('#rep-condition-2-'+id).removeClass("radio-active");
    $('#reg-condition-3-'+id).addClass("radio-active");
    $('#open_condition-'+id).prop('checked',false).removeAttr('checked');
    $('#reply_condition-'+id).prop('checked',false).removeAttr('checked');
    $('#regard_condition-'+id).prop('checked',true).attr('checked', 'checked');
  }
  $('#followup_cond-'+id).val(val);
  
}


$(document).on('click', '.addFallback',function () {
   var dynamic_field_val = $(this).data('customval');

   $('#recipient_feild').val(dynamic_field_val);

   var new_fallbac_text_value = 'there';
   var fallbac_text_value = $('.fallback-'+dynamic_field_val).val();
   // console.log('fall'+fallbac_text_value);
   if(fallbac_text_value!='' &&  fallbac_text_value != undefined){
     newVal =  fallbac_text_value.split('|');
     //console.log(newVal);
     new_fallbac_text_value = newVal[1];
   }

   // console.log(new_fallbac_text_value);
   $('#fallback_txt').val(new_fallbac_text_value);
   $('#fallback-modal').modal('show');
});

$(document).on('click', '.saveFallbackTxt',function () {
   var recipient_feild = $('#recipient_feild').val();
   var fallback_txt = $('#fallback_txt').val();
   if(fallback_txt!=''){
      var fallbck_text = ''+recipient_feild+'|'+fallback_txt+'';
   }else{
      var fallbck_text = ''+recipient_feild+'';
   }
   
   var original = recipient_feild;
   str = recipient_feild.replace(/\s+/g, '-');
   $('.fallback-'+str).val(fallbck_text);
   $('.note-editable .custom-data-'+str).text(fallbck_text);
  // $('.resetText-'+recipient_feild).data('customval', fallbck_text);
  
  /* var additem = fallbck_text;
   $(".fallback_text").val(($(".fallback_text").val() + ', ' + additem).replace(/^, /, ''));*/
   fallBackTxt();
   $('#fallback-modal').modal('hide');

    toastr.success('Tag updated');
   
});


function fallBackTxt(){
    var a = [];
   $('.element').each(function(){
        a.push($(this).val()); // Push all values in array
   });
   var unique = a.filter(function(itm, i, a) {
    return i == a.indexOf(itm);
   });
   var fallback_text = unique.join(','); // Concatenate array by comma separated string
   $(".fallback_text").val(fallback_text);
}


/*$(".note-editable .custom-data").keydown(function (e) {
  var key = e.keyCode || e.charCode;
  if (key == 8 || key == 46) {
      e.preventDefault();
     alert("backspace detected!");
  }
});*/
/*$(document).on('keydown', '.note-editable', function(e) {
  var key = event.keyCode || event.charCode;
  if( key == 8 || key == 46 ) {
    if(window.getSelection().anchorNode.parentNode.tagName ==='SPAN'){
      window.getSelection().anchorNode.parentNode.remove();
    }
   $(this).addClass('delete');
   $(this).focus();
    alert("backspace detected!");
     $('.delete').remove();
    return false;
  }
});*/


// $('html').keyup(function (e) {
//     if (e.keyCode == 8) {
//        var editor = summernote.instances.editable,
//        sel = editor.getSelection();
//        var ele = sel.getStartElement();
//        if(ele.getAttribute( 'class' ) == 'acronym'){
//           ele.remove();
//        }
//     }
//  });


