@extends('layouts.master')
@section('content')

<div class="py-3">

   <div class="card card-primary card-outline shadow-sm">
      <div class="card-header bg-white">
         <div class="hstack">
            <div>
               <h4 class="fw-500 mb-0">
                  Inbox
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
         <div class="mailbox-controls">

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

         </div>
         <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
               <tbody>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check1">
                           <label for="check1"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"></td>
                     <td class="mailbox-date">5 mins ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check2">
                           <label for="check2"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star-o text-warning"></i></a>
                     </td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                     <td class="mailbox-date">28 mins ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check3">
                           <label for="check3"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star-o text-warning"></i></a>
                     </td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                     <td class="mailbox-date">11 hours ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check4">
                           <label for="check4"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"></td>
                     <td class="mailbox-date">15 hours ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check5">
                           <label for="check5"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                     <td class="mailbox-date">Yesterday</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check6">
                           <label for="check6"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star-o text-warning"></i></a>
                     </td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                     <td class="mailbox-date">2 days ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check7">
                           <label for="check7"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star-o text-warning"></i></a>
                     </td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                     <td class="mailbox-date">2 days ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check8">
                           <label for="check8"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"></td>
                     <td class="mailbox-date">2 days ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check9">
                           <label for="check9"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"></td>
                     <td class="mailbox-date">2 days ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check10">
                           <label for="check10"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star-o text-warning"></i></a>
                     </td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"></td>
                     <td class="mailbox-date">2 days ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check11">
                           <label for="check11"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star-o text-warning"></i></a>
                     </td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                     <td class="mailbox-date">4 days ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check12">
                           <label for="check12"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"></td>
                     <td class="mailbox-date">12 days ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check13">
                           <label for="check13"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star-o text-warning"></i></a>
                     </td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                     <td class="mailbox-date">12 days ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check14">
                           <label for="check14"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                     <td class="mailbox-date">14 days ago</td>
                  </tr>
                  <tr>
                     <td>
                        <div class="icheck-primary">
                           <input type="checkbox" value="" id="check15">
                           <label for="check15"></label>
                        </div>
                     </td>
                     <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                     <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                     <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to
                        this problem...
                     </td>
                     <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                     <td class="mailbox-date">15 days ago</td>
                  </tr>
               </tbody>
            </table>

         </div>

      </div>

      <div class="card-footer p-0">
         <div class="mailbox-controls">

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

         </div>
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
       "ajax":{
           'url' : '{{ route('api.inbox-list') }}',
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
    { "data": 'DT_RowIndex', orderable: false, searchable: false},
    { "data": "entityID" },
    { "data": "headerID" },
    { "data": "senderID" },
    { "data": "companyName" },
    { "data": "sampleMsg" },
    { "data": "creator" },
    { "data": "created_at" },
    { "data": "status" },
    { "data": "action", orderable: false, searchable: false },
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