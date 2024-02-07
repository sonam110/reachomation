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
         $(".toast-msg").html(`${website} added to ${data['name']}`);
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
         $(".toast-msg").html(`${website} removed from ${data['name']}`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
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
      $("#mail-modal").modal('show');
      $("#modal-title").html('Compose Mail');
      $("#mail-action-section").html(`<button type="button" class="btn btn-success" onclick="sendMail()" id="create-btn">Send Mail</button>`);
      // console.log(data);
   });
}

// function for hide domain
const hide = (domain_id, website) =>{
   $.post("/hide", {action: 'hide', "_token": $('meta[name="csrf-token"]').attr('content'), domain_id:domain_id,website:website}).done(function(data){
      if(data=='1'){
         $("#domain_body_"+domain_id).html(`<div class="hstack mb-2"><div><h6 class="text-dark mb-0"><span><a class="text-decoration-none" href="https://${website}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="${website}">${website}</a>
         </span></h6></div><div class="ms-auto"><svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="red" class="bi bi-eye-slash-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Hidden" role="button" tabindex="0" onclick="unhide('${domain_id}')"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/></svg></div></div>`);
         $(".toast-msg").html(`${website} marked as hidden`);
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }else{
         $(".toast-msg").html('Oops.! Something went wrong');
         var toast = new bootstrap.Toast(toastLiveExample);
         toast.show();
      }
   });
}

// function for unhide domain
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

// function for open traffic modal
const openTraffic = (domain_id) =>{
   
   $.post("/openTraffic", {action: 'openTraffic', "_token": $('meta[name="csrf-token"]').attr('content'), domain_id:domain_id}).done(function(data){
      var returnedData = data;
      // console.log('dd'+returnedData);
      var labels = returnedData.traffic.map(function(e) {
         return e.checkedon;
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
      if(is_object($checkAlready)){
            $data = [
                'type'      => 'error',
            ];
            return response()->json($data, 200);
        }
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
   });
}

// function for open add to list modal
const openaddtoList = () =>{
   $("#add-list-modal").modal('show');
}