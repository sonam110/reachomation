@extends('layouts.master')
@section('content')

<div class="py-3">

   <div class="card card-primary card-outline shadow-sm">
      <div class="card-header bg-white">
         <div class="hstack">
            <div>
               <h4 class="fw-500 mb-0">
                  Notification
               </h4>

            </div>
           
         </div>
      </div>

      <div>
         <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped" id="datatable">

               <tbody>
                  @if(Auth::user()->notifications->count()>0)
                  @foreach (Auth::user()->notifications as $notification)
                  <?php  
                     $status = '';                      
                     $msg = Auth::user()->notifications()->where('id',$notification->id)->first()->toArray();
                     if(@$msg['data']['notificationdata']['status'] == '0'){
                           $status = 'Scheduled';
                     }
                     if(@$msg['data']['notificationdata']['status'] == '2'){
                        $status = 'Scheduled';
                     }
                     if(@$msg['data']['notificationdata']['status'] == '3'){
                        $status = 'Started';
                     }
                     if(@$msg['data']['notificationdata']['status'] == '4'){
                        $status = 'Completed';
                     }
                     if(@$msg['data']['notificationdata']['status'] == '8'){
                        $status = 'Completed';
                     }
                     if(@$msg['data']['notificationdata']['status'] == '5'){
                        $status = 'Paused';
                     }
                     $fname =  (@$msg['data']['notificationdata']['is_parent']==NULL)  ? 'outreach'  :'followup' ; 
                     $message = 'Your '.$fname.' '.@$msg['data']['notificationdata']['stage']. '  attempt '.@$msg['data']['notificationdata']['attemp']. ' '.@$msg['data']['notificationdata']['name']. '  has been '.$status.'';

                     if(@$msg['data']['notificationdata']['status'] == '7'){
                        $message = 'Data processing and validation on '.@$msg['data']['notificationdata']['name']. ' is now complete';
                     }
                     
                     ?>
                 <tr>
                  <td><a class="dropdown-item noti-message" href="#">
                         @if(@$msg['data']['notificationdata']['from_email'] !='')
                         {{ $message }}
                         @endif  
                         @if(@$msg['data']['notificationdata']['comment'] !='')
                         {{ @$msg['data']['notificationdata']['comment'] }}
                         @endif
                         @if(@$msg['data']['notificationdata']['stripe_id'] !='')
                           Your Plan  {{ @$msg['data']['notificationdata']['name'] }} Successfully activate
                         @endif 
                        </a></td>
                     <td><div class="small text-muted">{{($notification->created_at)->diffForHumans()}}</div></td>
                 </tr>
                  @endforeach
                 @endif


             
               </tbody>
            </table>
            

         </div>

      </div>

      <div class="card-footer p-0">
        
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