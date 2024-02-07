@extends('layouts.master')
@section('content')

<div class="py-3">

   <div class="card card-primary card-outline shadow-sm">
      <div class="card-header bg-white">
         <div class="hstack">
            <div>
               <h4 class="fw-500 mb-0">
                  Feeds
               </h4>

            </div>
            <div class="inboxSearch">
               <select class="form-control" name="mystatus" id="mystatus">
                  <option value="" selected="">All Status</option>
                  <option value="1">Opened</option>
                  <option value="3">Clicked</option>
                  <option value="2">Replied</option>
               </select>
            </div>
            <div class="ms-auto">

               <div class="input-group input-group-sm">
                  <input type="text"  name="keyword" id="keyword" class="form-control" placeholder="Search & Hit Enter">
                  <div class="input-group-append">
                     <div class="btn btn-primary">
                        <i class="bi bi-search text-white"></i>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="card-body p-0">
        <!--  <div class="mailbox-controls">

            <button type="button" class="btn btn-light btn-sm"><i class="bi bi-square"></i></button>
            <div class="btn-group">
               <button type="button" class="btn btn-light btn-sm"><i class="bi bi-trash"></i></button>
               <button type="button" class="btn btn-default btn-sm">
                  <i class="fa fa-reply"></i>
               </button>
               <button type="button" class="btn btn-default btn-sm">
                  <i class="fas fa-share"></i>
               </button>
            </div>

            <button type="button" class="btn btn-default btn-sm">
               <i class="fas fa-sync-alt"></i>
            </button>
            <div class="float-right">
               1-50/200
               <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm">
                     <i class="fas fa-chevron-left"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                     <i class="fas fa-chevron-right"></i>
                  </button>
               </div>

            </div>

         </div> -->
         <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped" id="datatable">
               <tbody>
                 
             
               </tbody>
            </table>

         </div>

      </div>

      <div class="card-footer p-0">
         <!-- <div class="mailbox-controls">

            <button type="button" class="btn btn-default btn-sm checkbox-toggle">
               <i class="far fa-square"></i>
            </button>
            <div class="btn-group">
               <button type="button" class="btn btn-default btn-sm">
                  <i class="far fa-trash-alt"></i>
               </button>
               <button type="button" class="btn btn-default btn-sm">
                  <i class="fas fa-reply"></i>
               </button>
               <button type="button" class="btn btn-default btn-sm">
                  <i class="fas fa-share"></i>
               </button>
            </div>

            <button type="button" class="btn btn-default btn-sm">
               <i class="fas fa-sync-alt"></i>
            </button>
            <div class="float-right">
               1-50/200
               <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm">
                     <i class="fas fa-chevron-left"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                     <i class="fas fa-chevron-right"></i>
                  </button>
               </div>

            </div>

         </div> -->
      </div>
   </div>

</div>

   {{-- toast start --}}
   <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
      <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert"
         aria-live="assertive" aria-atomic="true">
         <div class="d-flex">
            <div class="toast-body toast-msg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
               aria-label="Close"></button>
         </div>
      </div>
   </div>
   {{-- toast end --}}

   {{-- Modal for alert start --}}
   <div class="modal modal-alert" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content rounded-4 shadow">
            <div class="modal-body p-4 text-center">
               <h5 class="mb-2">Are you sure?</h5>
               <p class="mb-0">Once deleted, you will not be able to recover this list.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
               <button type="button"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-danger shadow-none"
                  id="delete-btn"><strong>Yes, delete it</strong></button>
               <button type="button"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start text-dark shadow-none"
                  data-bs-dismiss="modal">No thanks</button>
            </div>
         </div>
      </div>
   </div>
   {{-- Modal for alert end --}}

@endsection
@section('extrajs')
   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script>
   $(document).ready( function () {
    var table = $('#datatable').DataTable({
       "processing": true,
       "serverSide": true,
       "searching": false,
       "bSort": false,
       "paging": false,
       "ajax":{
           'url' : '{{ route('api.feeds-list') }}',
           'type' : 'POST',
           "data": function(d) {
            d.mystatus   = $('#mystatus').val();
            d.keyword = $('#keyword').val();
        },
           'headers': {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    },
    "order": [["1", "asc" ]],
    "columns": [
    { "data": "message" },
    { "data": "count" },
    { "data": "c_date" },
    ],
        preDrawCallback: function(settings) {
            if ($.fn.DataTable.isDataTable('#datatable')) {
                var dt = $('#datatable').DataTable();
                var settings = dt.settings();
                if (settings[0].jqXHR) {
                    settings[0].jqXHR.abort();
                }
            }
        }
   });
   $('#mystatus').on('change', function(e) {
       table.draw();
   });

   $('#keyword').bind("enterKey",function(e){
      table.draw();
   });
   $('#keyword').keyup(function(e){
       if(e.keyCode == 13)
       {
           $(this).trigger("enterKey");
       }
   });
   
   });



</script>

   <script>
      // intialize toast
      var toastLiveExample = document.getElementById('liveToast');

      // intialize tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      })

      $("#sidebarMenu").niceScroll(); 
   </script>
 @endsection
