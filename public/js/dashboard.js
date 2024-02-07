
$(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
});


const month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

// function for filter lists
const filterFunction = (id) =>{
   var input, filter, ul, li, a, i;
   input = document.getElementById("filter-input_"+id);
   filter = input.value.toUpperCase();
   div = document.getElementById("autocom-box_"+id);
   li = div.getElementsByTagName("li");
   for (i = 0; i < li.length; i++) {
   txtValue = li[i].textContent || li[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1){
         li[i].style.display = "";
      }
      else{
         li[i].style.display = "none";
      }
   }
}

// function for check niches limit
var limit = 5;
$('input[name=niches]').on('change', function(evt) {
   if($('input[name=niches]:checked').length >= limit){
      this.checked = false;
      $(".toast-msg").html(`You can select upto 4 categories only.`);
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return false;
   }
});

// function for add to favourites
const addtoFavourites = (domain_id, website) =>{
   $.post("/addtoFavourites", {action: 'addtoFavourites', "_token": $('meta[name="csrf-token"]').attr('content'), domain_id:domain_id,website:website}).done(function(data){
      if(data=='1'){
         $("#fav_"+domain_id).attr("onclick",`removetoFavourites('${domain_id}', '${website}')`);
         $("#fav_"+domain_id).attr("fill","red");

         $("#fav_"+domain_id).attr('data-bs-original-title', 'Added');
         $("#fav_"+domain_id).tooltip('show');
   
         $(".toast-msg").html(`${website} added to favourites`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }else{
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}

// function for remove from favourites
const removetoFavourites = (domain_id, website) =>{
   $.post("/removetoFavourites", {action: 'removetoFavourites', "_token": $('meta[name="csrf-token"]').attr('content'), domain_id:domain_id}).done(function(data){
      if(data=='1'){
         $("#fav_"+domain_id).attr("onclick",`addtoFavourites('${domain_id}', '${website}')`);
         $("#fav_"+domain_id).attr("fill","currentColor");
         $("#fav_"+domain_id).attr('data-bs-original-title', 'Add to Favourites');
         $("#fav_"+domain_id).tooltip('show');
         $(".toast-msg").html(`${website} removed from favourites`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }else{
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}

// function for add to list
const addtoList = (collection_id, domain_id, website) =>{
   $.post("/addtoList", {action: 'addtoList', "_token": $('meta[name="csrf-token"]').attr('content'), collection_id:collection_id,domain_id:domain_id,website:website}).done(function(data){
      if(data['type']=='success'){
         $("#badge_"+collection_id).removeClass('badge border border-dark text-dark');
         $("#badge_"+collection_id).addClass('badge bg-theme');
         $("#collection_"+collection_id).attr("onclick",`removetoList('${collection_id}', '${domain_id}', '${website}')`);
         $(".toast-msg").html(`${website} added to  ${data['name']}`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         location.reload(true);
      
      }else{
         $(".toast-msg").html(data['message']);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}

// function for remove to list
const removetoList = (collection_id, domain_id, website) =>{
   $.post("/removetoList", {action: 'removetoList', "_token": $('meta[name="csrf-token"]').attr('content'), collection_id:collection_id,domain_id:domain_id,website:website}).done(function(data){
     if(data['type']=='success'){
         $("#badge_"+collection_id).removeClass('badge bg-theme');
         $("#badge_"+collection_id).addClass('badge border border-dark text-dark');
         $("#collection_"+collection_id).attr("onclick",`addtoList('${collection_id}', '${domain_id}', '${website}')`);
         $(".toast-msg").html(`${website} removed from  ${data['name']}`);
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

// function for send mail
const openMail = (domain_id) =>{
   $.post("/getEmail", {action: 'getEmail', "_token": $('meta[name="csrf-token"]').attr('content'), domain_id:domain_id}).done(function(data){
      let emails = '';
      for (let i = 0; i < data.emails.length; i++) {
         emails += `<option value="${data.emails[i]}">${data.emails[i]}</option>`; 
      }
      $("#mail-modal").modal('show');
      $("#modal-title").html('Compose Mail');
      $("#site-email").html(emails);
      $("#mail-action-section").html(`<button type="button" class="btn btn-green px-5" onclick="sendMail('${domain_id}')" id="create-btn">Send Mail</button>`);
      $("#test-btn").attr('onclick',`testMail('${domain_id}')`);
   });
}

// function for hide domain
const hide = (domain_id, website) =>{
   $.post("/hide", {action: 'hide', "_token": $('meta[name="csrf-token"]').attr('content'), domain_id:domain_id,website:website}).done(function(data){
      if(data=='1'){
         $("#domain_body_"+domain_id).html(`<div class="hstack mb-2"><div><h6 class="text-dark mb-0"><span><a class="text-decoration-none" href="https://${website}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="${website}">${website}</a>
         </span></h6></div><div class="ms-auto"><svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="red" class="bi bi-eye-slash-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Hidden" role="button" tabindex="0" onclick="unhide('${domain_id}','${website}')"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/></svg></div></div>`);
         $(".toast-msg").html(`${website} marked as hidden`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }else{
         $(".toast-msg").html('Oops.! Something went wrong')
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}

// function for unhide domain
// const hideUnhideBtn = document.getElementById('hide-unhide');
// hideUnhideBtn.addEventListener('click', function() {
//     const domainId = this.getAttribute('data-domain-id');
//     const website = this.getAttribute('data-website');
//     unhide(domainId, website);
// });

const unhide = (domain_id, website) =>{
   $.post("/unhide", {action: 'unhide', "_token": $('meta[name="csrf-token"]').attr('content'), domain_id:domain_id,website:website}).done(function(data){
      if(data=='1'){
         $(".toast-msg").html(`${website} is visible again`);
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


const updateUserInterest = () =>{
    var way_of_support = $('#way_of_support').val();
    if(way_of_support ==''){
      $(".toast-msg").html('Please Choose atleast one option for chat support');
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return false;
    }
    
    var skype = $('#skypeIn').val();
    if( skype=='' &&  way_of_support!='email'){
      $(".toast-msg").html('Please enter '+way_of_support+'');
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return false;
    }


    var selected = new Array();
      $("input[name=niches]:checked").each(function () {
         selected.push(this.value);
      });
      if(selected.length <= 0) {
         $(".toast-msg").html('Please select atleast one category ');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         return false;
      }
      if(selected.length > 0) {
         var niches = selected.join(",");
      }

   
   $.post("/updateUserInterest", {action: 'updateUserInterest', "_token": $('meta[name="csrf-token"]').attr('content'), way_of_support:way_of_support,niches:niches,skype:skype}).done(function(data){
      if(data=='2'){
         $('#welcome-modal').modal('hide');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }else{
         $('#welcome-modal').modal('hide');

         $(".toast-msg").html('Saved successfully');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         //location.reload(true);
      }
   });
}

// function for update niches
/*const updateNiches = () =>{
   var selected = new Array();
   $("input[name=niches]:checked").each(function () {
      selected.push(this.value);
   });
   if(selected.length > 0) {
      var niches = selected.join(",");
   }
   $.post("/updateNiches", {action: 'updateNiches', "_token": $('meta[name="csrf-token"]').attr('content'), niches:niches}).done(function(data){
      if(data=='2'){
         $('#welcome-niches').modal('hide');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }else{
         $('#welcome-niches').modal('hide');
         $(".toast-msg").html('Niches saved successfully');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         //location.reload(true);
      }
   });
}*/

// function for open traffic modal
const openTraffic = (domain_id) =>{
  
   $.post("/openTraffic", {action: 'openTraffic', "_token": $('meta[name="csrf-token"]').attr('content'), domain_id:domain_id}).done(function(data){
      var returnedData = data;
      var labels = returnedData.traffic.map(function(e) {
         var d = new Date( e.checkedon );
         return month[d.getMonth()]+' '+d.getFullYear();
      });

      var sot = returnedData.traffic.map(function(e) {
         return e.sot;
      });
      var ctx = document.getElementById('myChart_traffic').getContext('2d');
      var myChart = new Chart(ctx, {
         type: 'line',
         data: {
            labels: labels,
            datasets: [{
               data: sot,
               lineTension: 0,
               backgroundColor: 'transparent',
               borderColor: '#007bff',
               borderWidth: 4,
               pointBackgroundColor: '#007bff'
            }]
         },
         options: {
            title: {
               display: true,
               text: 'Traffic History',
               fontSize: 16
            },
            scales: {
               yAxes: [{
                  scaleLabel: {
                     display: true,
                     labelString: 'SEMrush traffic (US)'
                  },
                  ticks: {
                     beginAtZero: false
                  }
               }],
               xAxes: [{
                  scaleLabel: {
                     display: true,
                     labelString: 'Timeline'
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

      $("#traffic-modal").modal('show');
      
   });
}

// function for open create modal
const opencreateModal = () =>{
   $("#list-modal").modal('show');
   $("#list-title").html('Create List');
   $("#list-name").val('');
   $("#list-action").html('<button class="w-100 mb-2 btn btn-lg rounded-4 btn-primary" type="submit" onclick="createList()">Submit</button>');
}

// function for create new list
const createList = () =>{
   let name = $("#list-name").val();
   if(name==''){
      $("#list-name").addClass('border border-danger');
      $("#list-name").focus();
      return false;
   }
   $("#list-action").html(`<button class="w-100 mb-2 btn btn-lg rounded-4 btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
   <span>Wait</span></button>`);
   $.post("/createList", {action: 'createList', "_token": $('meta[name="csrf-token"]').attr('content'), name:name}).done(function(data){
      if(data=='2'){
         $("#list-modal").modal('hide');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }else{
         $("#list-modal").modal('hide');
         $(".toast-msg").html(`List created successfully`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         location.reload(true);
      }
      if(data['type']=='error'){
         $("#list-modal").modal('hide');
         $(".toast-msg").html('List name already exists');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();

      }
   });
}

// function for open reveal modal
const reveal = (domain_id,credits) =>{
   $.ajax({
     url: appurl+"get-domain-cost",
     type: 'POST',
     data: {domain_id:domain_id},  
     success:function(credit){
      if(parseInt(usercredit) < credit){
         $("#credit-cost").html(credit);
         $("#balance-low-modal").modal('show');
         
      } else{
         $(".reveal-credit-cost").text(credit +' credits');
         $("#reveal-modal").modal('show');
         $("#reveal-title").html('Reveal Domain');
         $("#credit_cost").html(credit+' credits');
         $("#reveal-action").html(`<button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red fw-600" data-bs-dismiss="modal">No, Cancel</button>
         <button type="button" class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover fw-600" onclick="revealedDomain('${domain_id}','${credit}')">Yes, Proceed</button>`);
      }
     
       
     }
   });
}


// function for open topup modal
const topup = (topup) =>{
   $("#topup-modal").modal('show');
   $("#topup-title").html('Credits Topup');
  // $("#topup-action").html(`<button class="btn bg-white border-dark rounded-4 shadow-none" data-bs-dismiss="modal">No, Cancel</button><button class="btn btn-primary rounded-4" type="submit" onclick="revealedDomain('${domain_id}','${credit}')">Yes, Proceed</button>`);
}



// function for reveal domain
const revealedDomain = (domain_id, credit) =>{
   let dont = $('input[name="dont-show"]:checked').val();
   if(typeof dont=='undefined'){
      dontshow = '0';
   }else{
      dontshow = '1';
   }
   $("#reveal-action").html(`<button class="btn btn-primary rounded-4" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
   <span>Wait</span></button>`);
   $.post("/revealedDomain", {action: 'revealedDomain', "_token": $('meta[name="csrf-token"]').attr('content'), domain_id:domain_id, credit:credit, dontshow:dontshow}).done(function(data){
      if(data=='2'){
         $("#list-modal").modal('hide');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }else{
         $("#list-modal").modal('hide');
         $(".toast-msg").html(`Domain Revealed`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
        location.reload(true);
      }
   });
}

function updateDiv()
{ 
    $( "#refresh-div" ).load(window.location.href + " #refresh-div" );
}


// function for show more description
$(document).on('click', '[data-domain-id]', function() {
   var domainId = $(this).data('domain-id');
   showMore(domainId);
 });
const showMore = (domain_id) =>{
   $("#show-more_"+domain_id).removeClass('d-none');
   $("#show-less_"+domain_id).addClass('d-none');
   $("#show-btn_"+domain_id).html('SHOW LESS');
   $("#show-btn_"+domain_id).attr("onclick",'showLess("'+domain_id+'")');
}

// function for show less description
const showLess = (domain_id) =>{
   $("#show-more_"+domain_id).addClass('d-none');
   $("#show-less_"+domain_id).removeClass('d-none');
   $("#show-btn_"+domain_id).html('SHOW MORE');
   $("#show-btn_"+domain_id).attr("onclick",'showMore("'+domain_id+'")');
}



const insertTemplate = (id) =>{
   $.post("/insertTemplate", {action: 'insertTemplate', "_token": $('meta[name="csrf-token"]').attr('content'), id:id}).done(function(data){
      if(data!=''){
         $('#subject').summernote('code', data.template.subject);
         $('#summernote').summernote('code', data.template.body);
         $('#tid').val(data.template.id);
         $("#insert-template").modal('hide');
      }
   });
}

// function for check subject
const subject = () =>{
   let subject =  $('#subject').summernote('code');
   if(subject!=''){
      $("#subject").removeClass('border border-danger');
   }
}

// function for send mail
/*const sendMail = (domain_id) =>{
   let email = $("#site-email").val();
   let subject = $('#subject').summernote('code');
   if(subject==''){
      $("#subject").addClass('border border-danger');
      $("#subject").focus();
      return false;
   }
   let body = $('#summernote').summernote('code');
   if($('#summernote').summernote('isEmpty')){
      $('#summernote').summernote('focus');
      return false;
   }
   $("#mail-action-section").html(`<button type="button" class="btn btn-success" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
   <span class="">Wait...</span></button>`);
   $.post("sendMail", {action: 'sendMail', "_token": $('meta[name="csrf-token"]').attr('content'), subject:subject, body:body, email:email, domain_id:domain_id}).done(function(data){
      if(data['type']=='success')
      {
         $("#mail-action-section").html(`<button type="button" class="btn btn-success" onclick="sendMail('${domain_id}')" id="create-btn">Send Mail</button>`);
         //$(".toast-msg").html('Mail Sent');
         //var toast = new bootstrap.Toast(toastLiveExample);
         //toast.show();
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
          toastr.success(data['message'], {timeOut: 5000})
      }else{
        // $(".toast-msg").html('Oops.! Something went wrong');
         //var toast = new bootstrap.Toast(toastLiveExample);
         //toast.show();
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
         toastr.error(data['message'], {timeOut: 5000})
      }
   });
}

// function for send test email
const testMail = (domain_id) =>{
   let email = $("#test-email").val();
   let subject =  $('#subject').summernote('code');
   if(subject==''){
      $("#subject").addClass('border border-danger');
      $("#subject").focus();
      return false;
   }
   let body = $('#summernote').summernote('code');
   if($('#summernote').summernote('isEmpty')){
      $('#summernote').summernote('focus');
      return false;
   }
   $("#wait-test-btn").removeClass('d-none');
   $("#test-btn").addClass('d-none');
   $.post("testMail", {action: 'testMail', "_token": $('meta[name="csrf-token"]').attr('content'), subject:subject, body:body, email:email, domain_id:domain_id}).done(function(data){
      if(data['type']=='success')
      {
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
         //$(".toast-msg").html('Mail Sent');
        // var toast = new bootstrap.Toast(toastLiveExample);
         //toast.show();
        toastr.success(data['message'], {timeOut: 5000})
      }else{
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
         //$(".toast-msg").html('Oops.! Something went wrong');
         //var toast = new bootstrap.Toast(toastLiveExample);
         //toast.show();
         toastr.error(data['message'], {timeOut: 5000})
      }
   });
}*/

// function for add personalize Author
const personalizeAuthor = () =>{
   let author = '<span style="background-color: rgb(255, 255, 0);padding:0px 2px;">Author</span>';
   $('#summernote').summernote('pasteHTML', author);
}

// function for add personalize URL
const personalizeURL = () =>{
   let url = '<span style="background-color: rgb(255, 255, 0);padding:0px 2px;">Website</span>';
   $('#summernote').summernote('pasteHTML', url);
}

// function for add personalize Title
const personalizeTitle = () =>{
   let title = '<span style="background-color: rgb(255, 255, 0);padding:0px 2px;">Title</span>';
   $('#summernote').summernote('pasteHTML', title);
}



// function for set as default template
$(document).ready(function() {
   $(document).on('change', ".set_as_default", function () {
      $('.set_as_default').not(this).prop('checked', false);
      if($(this).is(":not(:checked)")){
         $(".set_as_default").val("0");
         tid = $(this).val();    
      }else{
         tid = $(this).val();
      }
      $.post("setDefault", {action: 'setDefault', "_token": $('meta[name="csrf-token"]').attr('content'), tid:tid}).done(function(data){
         if(data==1) {
            $(".toast-msg").html('Template saved as default');
            var toast = new bootstrap.Toast(toastLiveExample);
            toast.show();
         }else{
            $(".toast-msg").html('Oops.! Something went wrong');
            var toast = new bootstrap.Toast(toastLiveExample);
            toast.show();
         }
      });
   });
});

// function for filter channels
const filter = () =>{
   let da = $("#da").val();
   let tf = $("#tf").val();
   let country = $("#country").val();
   let sot = $("#sot").val();
   let sok = $("#sok").val();
   if(da!=''){
      $("#da").attr("name","da");
      $("#da").addClass("bg-primary text-white");
   }
   if(tf!=''){
      $("#tf").attr("name","tf");
      $("#tf").addClass("bg-primary text-white");
   }
   if(country!=''){
      $("#country").attr("name","country");
      $("#country").addClass("bg-primary text-white");
   }
   if(sot!=''){
      $("#sot").attr("name","sot");
      $("#sot").addClass("bg-primary text-white");
   }
   if(sok!=''){
      $("#sok").attr("name","sok");
      $("#sok").addClass("bg-primary text-white");
   }
   $("#filter-btn").click();
}

// function for open add to list modal
const openaddtoList = () =>{
    $("#add-list-action").html(`<button class="mt-1 mb-1 btn rounded-4 btn-green px-5" type="submit" onclick="addSitesToList()">Add to list</button>`);
   $("#add-list-modal").modal('show');
    $('#domain-lists').html();
    getDomainList();
   
}

// function for balance low modal
const balancelow = (credit) =>{
   $("#balance-low-modal").modal('show');
   $("#credit-cost").html(credit);
}

const newlistname = () =>{
   let list = $("#new-list-name").val();
   if(list!=''){
      $("#created-list").prop('disabled', true);
   }else{
      $("#created-list").prop('disabled', false);
   }
}

const createdlist = () =>{
   let list = $("#created-list").val();
   if(list!=''){
      $("#new-list-name").prop('disabled', true);
   }else{
      $("#new-list-name").prop('disabled', false);
   }
}

// function for add domain to list
const addDomaintolist = () =>{
   let keyword = $("#keyword").val();
   let list = $("#created-list").val();
   let newlist = $("#new-list-name").val();
   let size = '';
   if ($("input[name=list-size]").prop("checked")) {
      size = $("input[name=list-size]:checked").val();
   }else{
      $("#list-err").html('Choose list size');
      return false;
   }
   $("#add-list-action").html(`<button type="button" class="w-100 mb-2 mt-2 btn btn-lg rounded-4 btn-primary" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  
   <span class="">Wait...</span></button>`);
   $.post("addDomaintolist", {action: 'addDomaintolist', "_token": $('meta[name="csrf-token"]').attr('content'), size:size, keyword:keyword, newlist:newlist, list:list}).done(function(data){
      if(data==2) {
         $(".toast-msg").html(`Top ${size} domains added to list`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         location.reload(true);
      }else if(data==3){
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         $("#add-list-modal").modal('hide');
         return false;
         location.reload(true);
      }else if(data==4){
         $(".toast-msg").html('Limit Exceeded Max 1000 domain');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         return false;
      }else{
         
         $("#add-list-body").html('<div class="px-3"><h5 class="mb-2">Credit Balance Low</h5><p class="mb-0">You do not have enough credit balance to reveal this domain.</p><div class="d-flex mt-3"><div><h6 class="mb-0">Credit Cost : <span class="text-success" id="credit-cost">${data.credits}</span></h6></div><div class="ms-auto"><h6 class="mb-0">Your Balance : <span class="text-danger">${data.credit_left}</span></h6></div></div><div><a href="javascript:;" onclick="buyCredits()" class="w-100 mb-2 mt-4 btn btn-lg rounded-4 btn-primary">Buy Credits</button></div></div>');
      
      }
   });
}



$('.listSize,.checkReveal').on('click', function() { 
  getDomainList();
});

function getDomainList(){
    $("#form-submit-loading").addClass('show');
   $('.meter').show();
   $('.meter').css('width','100%');
   var keyword = $('#keyword').val();
   var da = $("#da").val();
   var tf = $("#tf").val();
   var country = $("#country").val();
   var sot = $("#sot").val();
   var sok = $("#sok").val();
   var reveal_or_not = $("input.checkReveal:checked").val();
   if(reveal_or_not == undefined ){
     $(".toast-msg").html('Please Choose list option');
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      $("#form-submit-loading").removeClass('show');
      return  false;
   }

   var size = $('.listSize:checked').val();
   // console.log(reveal_or_not);
   if(size == undefined ){
     $(".toast-msg").html('Please choose list size');
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      $("#form-submit-loading").removeClass('show');
      return  false;
   }

   var updation = $('.updation:checked').val();
   var list_type = $('#list_type').val();
   var domain = $('.Checkbox:checked').map(function() {
    return this.value;
   }).get().join(', ');
   $.ajax({
     url: appurl+"get-domain-list",
     type: 'POST',
     data: {keyword:keyword,da:da,tf:tf,country:country,sot:sot,sok:sok,size:size,domain:domain,updation:updation,reveal_or_not:reveal_or_not,list_type:list_type},  
     success:function(info){
      $("#form-submit-loading").removeClass('show');
      $('#domain-lists').html(info);
      var total_sites_list = $('#total_sites').val();
      var total_credits_list = $('#credit_need').val();
      // console.log(total_sites);
      $('.total_sites_list').text(total_sites_list);
      $('.total_credits_list').text(total_credits_list);
      $('.meter').hide();
      $('.meter').css('width','0%');
       
     }
   });
}


function addSitesToList() {
   
   var domain_ids = $('#domain_ids').val();
   var credit_need = $("#credit_need").val();
   if(parseInt(usercredit) <= parseInt(credit_need) ) {
      $('#add-list-modal').modal('hide');
      $('#credit-cost').html(credit_need);
      $('#balance-low-modal').modal('show');
      $('#title_txt').html('<p>You dont have enough credits to perfom this action</p>');
      return  false;
   }
   var new_list_name = $("#new-list-name").val();
   var created_list = $("#created-list").val();
   var reveal_or_not = $("input.checkReveal:checked").val();
   var size = $('#size').val();
   var is_list = false;
   if(created_list !=''){
      var is_list = true;
   }
   if(new_list_name !=''){
      var is_list = true;
   }

   if(is_list == false ){
       $(".toast-msg").html('Please Enter list name or select list');
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return  false;
    }
    if(size ==''){
      $(".toast-msg").html('Please Choose list size');
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return  false;
    }
    if(reveal_or_not == undefined && reveal_or_not==''){
      $(".toast-msg").html('Please Choose list  option');
      var toast = new bootstrap.Toast(toastLiveExample);
      toast.show();
      return  false;
    }
    if(domain_ids ==''){
         $(".toast-msg").html('No domains found');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         return  false;
    }


    $('.meter').show();
  $('.meter').css('width','100%');
    $("#add-list-action").html('<button type="button" class="w-100 mb-2 mt-2 btn btn-lg rounded-4 btn-primary" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
  
    $.ajax({
     url: appurl+"add-sites-tolist",
     type: 'POST',
     data: {domain_ids:domain_ids,credit_need:credit_need,new_list_name:new_list_name,created_list,size:size,reveal_or_not:reveal_or_not},  
     success:function(data){
      if(data['type']=='success') {
         $("#add-list-action").html('<button class=" mb-2 btn btn-lg rounded-4 btn-primary" type="submit" onclick="addSitesToList()">Submit</button>');
         $('.meter').hide();
         $('.meter').css('width','0%');
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
         $(".toast-msg").html(`Top  domains added to list`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         $("#add-list-modal").modal('hide');
         location.reload(true);
      }
      if(data['type']=='failed') {
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         $("#add-list-modal").modal('hide');
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
      }
      if(data['type']=='no_domain') {
         $(".toast-msg").html('No Domain Added');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         $("#add-list-modal").modal('hide');
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
      }

       if(data['type']=='creditlow')
      {
        // $(".add-list-body").html('<div class="px-3"><h5 class="mb-2">Credit Balance Low</h5><p class="mb-0">You do not have enough credit balance to reveal this domain.</p><div class="d-flex mt-3"><div><h6 class="mb-0">Credit Cost : <span class="text-success" id="credit-cost">${data.credits}</span></h6></div><div class="ms-auto"><h6 class="mb-0">Your Balance : <span class="text-danger">${data.credit_left}</span></h6></div></div><div><a href="javascript:;" onclick="buyCredits()" class="w-100 mb-2 mt-4 btn btn-lg rounded-4 btn-primary">Buy Credits</button></div></div>');
        $('#credit-cost').text(data['credits']);
       $("#add-list-modal").modal('hide');
         $("#balance-low-modal").modal('show');
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
      }
      if(data['type']=='error'){
         $(".toast-msg").html(data['message']);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
         $("#add-list-modal").modal('hide')
      }

      $("#add-list-action").html('<button class=" mb-2 btn btn-lg rounded-4 btn-primary" type="submit" onclick="addSitesToList()">Submit</button>');
      $('.meter').hide();
      $('.meter').css('width','0%');
      $("#wait-test-btn").addClass('d-none');
      $("#test-btn").removeClass('d-none');
     }
   });
    
}



// function for send test mail
const sendtestMail = () =>{
   let subject =  $('#subject').summernote('code');
   if(subject==''){
      $("#subject").addClass('border border-danger');
      $("#subject").focus();
      return false;
   }
   let body = $('#summernote').summernote('code');
   if($('#summernote').summernote('isEmpty')){
      $('#summernote').summernote('focus');
      return false;
   }
   let email = $("#test-email").val();
   const emailregxp = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   if(email==''){
      $("#test-email").addClass('border border-danger');
      $("#test-email").focus();
      return false;
   }
   if(!emailregxp.test(email)){
      $("#test-email").addClass('border border-danger');
      $("#test-email").focus();
      return false;
   }
   $("#wait-test-btn").removeClass('d-none');
   $("#test-btn").addClass('d-none');
   $.post("testmail", {action: 'testmail', "_token": $('meta[name="csrf-token"]').attr('content'), subject:subject, body:body, email:email}).done(function(data){
      if(data==1){
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
         $(".toast-msg").html('Mail Sent');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }else{
         $("#wait-test-btn").addClass('d-none');
         $("#test-btn").removeClass('d-none');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}
var dNum =0;
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
          var title = '<span contenteditable="false"  class="custom-data   msg-'+dynamic_field_val+'-'+pid+'  custom-data-'+dynamic_field_val+'" id="custom-data-'+pid+'" data-val="'+pid+'">'+field_val+'</span>';
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
          var title = '<span  contenteditable="false" class="custom-data  sub-'+dynamic_field_val+'-'+pid+'  custom-data-'+dynamic_field_val+'" id="custom-data-'+pid+'" data-val="'+pid+'">'+field_val+'</span>';
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



 

