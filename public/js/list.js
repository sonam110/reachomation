$(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
});


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
      if(data['type']=='error'){
         $("#list-modal").modal('hide');
         $(".toast-msg").html('List name already exists');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();

      }
      else if(data['type']=='success'){
         $("#list-modal").modal('hide');
         $(".toast-msg").html(`List Created : ${name}`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         location.reload(true);

      } else {
         $("#list-modal").modal('hide');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
     
   });
}

// function for open edit modal
const openeditModal = (id, name) =>{
   $("#list-modal").modal('show');
   $("#list-title").html('Edit List');
   $("#list-name").val(name);
   $("#list-action").html(`<button class="w-100 mb-2 btn btn-lg rounded-4 btn-primary" type="submit" onclick="editList('${id}')">Submit</button>`);
}

// function for edit list
const editList = (id) =>{
   let name = $("#list-name").val();
   if(name==''){
      $("#list-name").addClass('border border-danger');
      $("#list-name").focus();
      return false;
   }
   $("#list-action").html(`<button class="w-100 mb-2 btn btn-lg rounded-4 btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
   <span>Wait</span></button>`);
   $.post("/editList", {action: 'editList', "_token": $('meta[name="csrf-token"]').attr('content'), name:name, id:id}).done(function(data){
      if(data['type']=='error'){
         $("#list-modal").modal('hide');
         $(".toast-msg").html('List name already exists');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();

      }
      else if(data['type']=='success'){
         $("#list-modal").modal('hide');
         $(".toast-msg").html(`List Edited : ${name}`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         location.reload(true);

      } else {
         $("#list-modal").modal('hide');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}

// function for check list name
const listName = () =>{
   let name = $("#list-name").val();
   if(name!=''){
      $("#list-name").removeClass('border border-danger');
   }
}

// function for show alert
const deleteAlert = (id) =>{
   $(".deleteAlert").modal('show');
   $("#delete-btn").attr("onclick",`deleteList('${id}')`);
}

// function for delete list
const deleteList = (id) =>{
   $.post("/deleteList", {action: 'deleteList', "_token": $('meta[name="csrf-token"]').attr('content'), id:id}).done(function(data){
      if(data == 1){
         $(".deleteAlert").modal('show');
         $(".toast-msg").html('List Deleted');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
         location.reload(true);
      }else{
         $(".deleteAlert").modal('hide');
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}
function showStatics(){
   var id = $('#static-stage').val();
     $.ajax({
        url: appurl+"show-statics",
        type: "post",
        data: {id:id},
        success: function(html) {
          $('#statics').html(html)
           
        }
    });
 
}
