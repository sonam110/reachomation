
$(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
});


// const insPop = document.getElementById("insert-template-btn");
// insPop.addEventListener("click", function () {
//   $("#insert-template").modal('show');
//   $('#follow-id').val('');
// });

// var cId = function() {
//     var attribute = this.getAttribute("data-myattribute");
//     alert(attribute);
// };

const crick = document.querySelectorAll('.pallav');
// console.log(crick);
crick.forEach(el => el.addEventListener('click', event => {
  // console.log(event.target.getAttribute("data-setdefaultid"));
}));

// const openTemplate = () =>{
//    // alert(11);
//    $("#insert-template").modal('show');
//    $('#follow-id').val('');
// }

function setDefault(id) {

  $.ajax({
    url: appurl + "set-default-templete",
    type: "post",
    data: { id: id },
    success: function (data) {

          if (data['type'] == 'success') {
            $(".setDefault").removeClass('text-warning');
            $("#setdefault_" + id).addClass('text-warning');
            toastr.success("Set successfully");
          }
          if (data['type'] == 'unset') {

            const openTemplate = () =>{
               // alert(11);
               $("#insert-template").modal('show');
               $('#follow-id').val('');
            }
        }

 
    }
});
}




/*function setDefault(id){

     $.ajax({
        url: appurl+"set-default-templete",
        type: "post",
        data: {id:id},
        success: function(data) {
            
            if(data['type']=='success')
            {
                $(".setDefault").removeClass('text-warning');
                $("#setdefault_"+id).addClass('text-warning');
                toastr.success("Set successfully"); 
            }
            if(data['type']=='unset')
            {
               
              $(".setDefault").removeClass('text-warning');
               toastr.error("Removed successfully"); 
            }
            if(data['type']=='error')
            {
               
              $(".setDefault").removeClass('text-warning');
               toastr.error("Opps! Something went wrong"); 
            }

        }
    });
 
}*/

// function for load template group
const templateGroups = (name) =>{
   $.post("templateGroups", {action: 'templateGroups', "_token": $('meta[name="csrf-token"]').attr('content'), name:name}).done(function(data){
      if(Object.keys(data.templates).length>0){
         let results = '';
         for (let i = 0; i < Object.keys(data.templates).length; i++){
            body=$(document.createElement("DIV")).html(data.templates[i].body).text();
            check = '';
            if(data.tid==data.templates[i].id){
               check = '<i class="bi bi-star-fill  text-warning" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Default" ></i>';
            }else{
               check = '';
            }
            // results += `<tr class="align-middle"><td><div class="form-check form-check-inline mt-2"><input class="form-check-input set_as_default rounded-0 shadow-none" type="checkbox" name="set_as_default"  onclick="setDefault('${data.templates[i].id}')" value="${data.templates[i].id}" ${check}></div></td><td>${data.templates[i].subject}<div><span class="badge bg-warning rounded-pill ms-2 text-dark px-2" style="font-size: 9px;">${data.templates[i].name}</span></div></td><td><span class="badge bg-green text-white rounded-1 px-3 fw-500" role="button" tabindex="0" onclick="insertTemplate('${data.templates[i].id}')">Select</span></td></tr>`;
            results += `<div class="row">
            <div class="col-md-9"><div class="d-flex"><div><h6>${data.templates[i].subject}</h6></div><div></div></div></div><div class="col-md-3 text-center"><ul class="list-inline mb-0">${check}<li class="list-inline-item"><span class="badge bg-green text-white rounded-0 px-3 fw-500" role="button" tabindex="0" onclick="insertTemplate('${data.templates[i].id}')">Select</span></li></ul></div></div><hr>`;
         }
         $("#group-name").html(name);
         $("#group-template").html(results);
        // $("#template-list").html(results);
      }
   });
}

$(".alert").css("display", "block");


const buyCredits = () =>{
   $("#balance-low-modal").modal('hide');
   $("#buy-credits").modal('show');
}

document.addEventListener('DOMContentLoaded', function() {
    $('.meter').show();
	$('.meter').css('width','100%');
 
}, false);



document.onreadystatechange = function () {
  if (document.readyState === 'complete') {
    removeLoader();
  }
}
/*$(window).on("load", function(e) {
	//$('.meter').show();
	//$('.meter').css('width','100%');
	setTimeout(
	  function() 
	  {
	    removeLoader()
	  }, 1000);

});*/

function removeLoader(){
	$('.meter').hide();
    $('.meter').css('width','0%');
}

$(document).on('click', '.radio-alert',function () {
	var type = $(this).data('type');
	var email = $(this).data('email');
	var plan = $(this).data('plan');
	var price = $(this).data('price');
  var credtis = $(this).data('credit');
	var account_type = $(this).data('atype');
    $('.radio-alert').removeClass("radio-active");
    $(this).addClass("radio-active");
    $('#accounttype').val(type);
    $('#from_email').val(email);
    $('#credit_price').val(price);
    $('#nocredits').val(credtis);
    $('#planid').val(plan);
    $('#cost_of_credit').text(price);
    $('#no_of_credits').text(credtis);
    $('#account_type').val(account_type);


});




// function for open purchase credits
const purchaseCredits = () =>{
    $('.meter').show();
    $('.meter').css('width','100%');
    $("#form-submit-loading").addClass('show');
    var price = $('#price').text();
    var credtis = $('#credits').val();
      $.ajax({
        url: appurl+"purchase-credit-model",
        type: 'POST',
        data: {price,price,credtis:credtis},  
        success:function(text){
         if(text['type']=='error'){
            $("#form-submit-loading").removeClass('show');
            $('.meter').hide();
            $('.meter').css('width','0%')
             toastr.error(text['message']);
         
          } else {
            $('.meter').hide();
            $('.meter').css('width','0%');
            $("#form-submit-loading").removeClass('show');
            $("#buy-credits").modal('hide');
            window.location.href = text['url'];
              
        }
          
            
    }
});

 
}
$(document).on('click', '.markUnreadMessage',function () {
  $.ajax({
    url: appurl+"click-read-all-notification",
    type: 'POST',
    data: {},  
    success:function(info){
        // console.log(info['type']);
        $('.messageCount').text('0');
        $('.new-notification').text('Notifications');
        
     /* setTimeout(function(){ 
          $('.messageCount').hide();
          $('.user-message-list').hide();
      }, 5000);*/
      
    }
  });
});
$(".close-loader").click(function(){
    $("#form-submit-loading").removeClass('show');
});

$(".hide-loader").on('click', function(){
  setTimeout(function(){ 
    $("#form-submit-loading").removeClass('show');
  }, 3000);
})

/*
$('input[type=submit]').click(function() {
  var validate = true;
   $('input:required').each(function(){
    if($(this).val().trim() === ''){
      validate = false;
    }
   });
   $('textarea:required').each(function(){
    if($(this).val().trim() === ''){
      validate = false;
    }
   });
   $('select:required').each(function(){
    if($(this).val().trim() === ''){
      validate = false;
    }
   });
   if(validate) {
    $("#form-submit-loading").addClass('show');
    //$(this).val('Submitting. Please wait...');
    return true;
   }
});

$('button[type=submit]').click(function() {
  var validate = true;
   $('input:required').each(function(){
    if($(this).val().trim() === ''){
      validate = false;
    }
   });
   $('textarea:required').each(function(){
    if($(this).val().trim() === ''){
      validate = false;
    }
   });
   $('select:required').each(function(){
    if($(this).val().trim() === ''){
      validate = false;
    }
   });
   if(validate) {
    $("#form-submit-loading").addClass('show');
    //$(this).val('Submitting. Please wait...');
    return true;
   }
});
*/



function  sendMail(domain_id) {

var from_email = $("#from_email").val();
if(from_email== null){
  $("#mail-modal").modal('hide');
  $('#email_connect_model').modal('show')
}  else {

var is_wait = true;
var subject = $('#subject').summernote('code');
 if($('#subject').summernote('isEmpty')){
     $('#subject').summernote('focus');
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
    var email = $("#site-email").val();

    if(email ==''){
        var is_wait = false;
        toastr.error("Please enter Email "); 
        return false;
    }
}
var fallback_text = $('.fallback_text').val();

 if(is_wait == true){
  $.ajax({
      url: appurl+"sendMail",
      type: "post",
      data: {subject:subject,body:body,email:email,from_email:from_email,domain_id:domain_id,fallback_text:fallback_text},
      success: function(data) {
        if(data['type']=='success'){
          toastr.success('Mail Sent Successfully');
          
           $("#mail-action-section").html(`<button type="button" class="btn btn-success" onclick="sendMail('${domain_id}')" id="create-btn">Send Mail</button>`);
        
        }
        if(data['type']=='not_found')
        {
          
           $("#mail-modal").modal('hide');
           $('#email_connect_model').modal('show')
        }
        if(data['type']=='error'){
           toastr.error('Mail Not sent');
        }
        
           
      },
      error: function(xhr, status, error) 
      {
        
         $(".toast-msg").html('Opps! Something went wrong');
             var toast = new bootstrap.Toast(toastLiveExample);
             toast.show();
       
      },
});
 }

}

function  testMail(domain_id) {

var from_email = $("#from_email").val();
if(from_email== null){
  $("#mail-modal").modal('hide');
  $('#email_connect_model').modal('show')
  return false;
} else {

$("#wait-test-btn").removeClass('d-none');
$("#test-btn").addClass('d-none');
var is_wait = true;
var subject = $('#subject').summernote('code');
 if($('#summernote').summernote('isEmpty')){
     $('#summernote').summernote('focus');
     var is_wait = false;
   $("#wait-test-btn").addClass('d-none');
    $("#test-btn").removeClass('d-none');
    toastr.error("Please enter subject line "); 
    return false;
 }
 var  body = $('#summernote').summernote('code');
 if($('#summernote').summernote('isEmpty')){
    $('#summernote').summernote('focus');
    var is_wait = false;
     $("#wait-test-btn").addClass('d-none');
    $("#test-btn").removeClass('d-none');
    toastr.error("Please enter message "); 
    return false;
 }
}
var fallback_text = $('.fallback_text').val();

var email = $("#test-email").val();
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
 if(is_wait == false){
    $("#wait-test-btn").addClass('d-none');
    $("#test-btn").removeClass('d-none');
 }
 if(is_wait == true){
  $.ajax({
      url: appurl+"testMail",
      type: "post",
      data: {subject:subject,body:body,email:email,from_email:from_email,fallback_text:fallback_text,domain_id:domain_id},
      success: function(data) {
        if(data['type']=='success'){
          toastr.success('Mail Sent Successfully');
          $("#wait-test-btn").addClass('d-none');
          $("#test-btn").removeClass('d-none');
           $("#mail-action-section").html(`<button type="button" class="btn btn-success" onclick="sendMail('${domain_id}')" id="create-btn">Send Mail</button>`);
        
        }
          if(data['type']=='not_found')
        {
           $("#wait-test-btn").addClass('d-none');
           $("#test-btn").removeClass('d-none');
           $("#mail-modal").modal('hide');
           $('#email_connect_model').modal('show')
        }
        if(data['type']=='error'){
           toastr.error('Mail Not Sent');
        }
        $("#wait-test-btn").addClass('d-none');
        $("#test-btn").removeClass('d-none');
           
      },
      error: function(xhr, status, error) 
      {
        $("#wait-test-btn-").addClass('d-none');
        $("#test-btn").removeClass('d-none');
  
         $(".toast-msg").html('Opps! Something went wrong');
             var toast = new bootstrap.Toast(toastLiveExample);
             toast.show();
       
      },
});
 }

}
/*
$(document).on('keydown', '.custom-data',function () {
   $(this).addClass('custom-selected');
   $(this).focus();
})



$(document).on('keydown', function(e){

    if(e.keyCode === 8){

       $('.delete').remove();
    }
});*/

 /*$('html').keyup(function(e){
    
    if(e.keyCode == 8){

    }

      


}) */


$('.checkInt input:checkbox').click(function() {
    $('.checkInt input:checkbox').not(this).prop('checked', false);
});

$('.checkChat input:checkbox').click(function() {
    $('.checkChat input:checkbox').not(this).prop('checked', false);
});

$('input.checkReveal').click(function() {
    $('input.checkReveal').not(this).prop('checked', false);
});





$('.notStopCamp input:checkbox').click(function() {
    $('.notStopCamp input:checkbox').not(this).prop('checked', false);
});


const addCard = () =>{
  $("#add-cards").modal('show');
     
}

 $(document).on("click", "#show-delete-card", function(event){
     var id = $(this).data('id');
     $('#card_id').val(id);
     $("#deleteCard").modal('show');
  });

  $(document).on("click", "#make-default-card", function(event){
     var id = $(this).data('id');
     $('#cardid').val(id);
     $("#defaultCard").modal('show');
  });



$(document).on('click', '.addFallbackAll',function () {
   var dynamic_field_val = $(this).data('customval');
  //  console.log(dynamic_field_val);

   $('#recipient_feild_all').val(dynamic_field_val);
   var new_fallbac_text_value = 'there';
   var fallbac_text_value = $('.fallback-'+dynamic_field_val).val();
   //console.log(fallbac_text_value);
   if(fallbac_text_value!='' &&  fallbac_text_value != undefined){
     newVal =  fallbac_text_value.split('|');
     //console.log(newVal);
     new_fallbac_text_value = newVal[1];
   }
   $('#fallback_txt_all').val(new_fallbac_text_value);
   $('#fallback-modal-all').modal('show');
});

$(document).on('click', '.saveFallbackTxtAll',function () {
   var recipient_feild_all = $('#recipient_feild_all').val();
   var fallback_txt_all = $('#fallback_txt_all').val();
   if(fallback_txt_all!=''){
      var fallbck_text = ''+recipient_feild_all+'|'+fallback_txt_all+'';
   }else{
      var fallbck_text = ''+recipient_feild_all+'';
   }
  
   $('.fallback-'+recipient_feild_all).val(fallbck_text);
   $('.custom-data-'+recipient_feild_all).text(fallbck_text);
  // $('.resetText-'+recipient_feild_all).data('customval', fallbck_text);
  
  /* var additem = fallbck_text;
   $(".fallback_text").val(($(".fallback_text").val() + ', ' + additem).replace(/^, /, ''));*/
   fallBackTxtAll();
   $('#fallback-modal').modal('hide');

    toastr.success('Tag updated');
   
});

function fallBackTxtAll(){
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

$(document).on("change", "#way_of_support", function(event){
    var way_of_support = $(this).val();

    if(way_of_support=='' ){
        $(".skypeIn").css("display", "none");

    } else if(way_of_support=='email' ){ 
        $(".skypeIn").css("display", "none");
    }else{
        $(".skypeIn").css("display", "block");
    }
    
});

$('.niches input:checkbox').click(function() {
    $('.niches input:checkbox').not(this).prop('checked', false);
});

const incredits = () =>{
  let credits = $("#buyCredits").val();
  let newcredit = parseInt(credits)+ 1;
  $("#buyCredits").val(newcredit);
}

const decredits = () =>{
  let credits = $("#buyCredits").val();
  let newcredit = parseInt(credits)- 1;
  $("#buyCredits").val(newcredit);
}

$('.set_as_default input:checkbox').click(function() {
    $('.set_as_default input:checkbox').not(this).prop('checked', false);
});



$(document).ready(function(){


$('.btn-number').click(function(e){
    e.preventDefault();
    
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
            
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1000).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }
            // console.log(currentVal);
            if(currentVal < 1000){
                $('.minus').attr('disabled', true);
            } else{
                $('.minus').attr('disabled', false);
            }


        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1000).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
        var credits = $('#credits').val();
        var credit_value = 10;
        var one_crrdit_value = 10/1000;
        var price = credits* one_crrdit_value;
        $('#price').html('$'+parseInt(price));
        $('#credit_price').val(parseInt(price));
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').keyup(function() {
    
    minValue =  parseInt($(this).attr('min'));
    valueCurrent = parseInt($(this).val());
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
        var credits = $('#credits').val();
        var credit_value = 10;
        var one_crrdit_value = 10/1000;
        var price = credits* one_crrdit_value;
        $('#price').html('$'+parseInt(price));
        $('#credit_price').val(parseInt(price));
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    
});

$(".input-number").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
         // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) || 
         // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});
    
    

    
});

$('.notStopCamp').click(function() {
    if($(this).is(":not(:checked)")){
       // $('.timesetting').removeClass("d-none");
       $('#starttime').prop("disabled", false);
        $('#endtime').prop("disabled", false);
    } else{
       // $('.timesetting').addClass("d-none");
        $('#starttime').prop("disabled", true);
        $('#endtime').prop("disabled", true);
    }
});

function sendPreviewMail(id){

    
    if(id =='0'){
        var subject = $('#mail-subject').text();


        var message = $('#mail-message').text();
        $(".wait-btn").removeClass('d-none');
        $(".preview-mail").addClass('d-none');
    } else{
        var subject = $('#mail-subject').text();
        var message = $('#mail-message').text();
        $(".wait-btn").removeClass('d-none');
        $(".preview-mail").addClass('d-none');
    }
    // console.log(message);
     $.ajax({
        url: appurl+"send-preview-mail",
        type: "post",
        data: {id:id,subject:subject,message:message},
        success: function(data) {
            
            if(data['type']=='success')
            {
                $(".wait-btn").addClass('d-none');
                $(".preview-mail").removeClass('d-none');
                $('#mail-preview-modal').modal('hide')
                toastr.success("Mail sent successfully"); 
            } else if(data['type']=='not_found')
            {
                $(".wait-btn").addClass('d-none');
                $(".preview-mail").removeClass('d-none');
                $("#mail-preview-modal").modal('hide');
                $('#email_connect_model').modal('show')
            }
            else{
                $(".wait-btn").addClass('d-none');
                $(".preview-mail").removeClass('d-none');
                $('#mail-preview-modal').modal('hide')
               toastr.error("Opps! Something went wrong!"); 
            }
        }
    });
 
}



$('#same_thread').on('change', function() {
    var val = this.checked ? '1' : '0';
    $('#is_same_thread').val(val);
    var subject = $('#subject-1').summernote('code');
    if(val==1){
        $('.followup-subject').summernote('code',subject);
        $('.followup-subject').next().find(".note-editable").attr("contenteditable", false);
    } else{
        $('.followup-subject').next().find(".note-editable").attr("contenteditable", true);
    }
});


