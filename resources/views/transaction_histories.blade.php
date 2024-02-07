@extends('layouts.master')
@section('content')
         <div class="py-3">
            <div class="hstack border-bottom gap-3 mb-3 pb-3">
               <div>
                  <h3 class="fw-bold mb-0">
                     Credit Ledger
                  </h3>
               </div>
               <div class="ms-auto">
                  @if(!empty(Auth::user()->plan) && Auth::user()->plan!='1' )
                  <a href="javascript:;" type="button" class="btn btn-green shadow-sm fw-500 fw-semibold rounded-1"  onclick="buyCredits()">
                           <i class="bi bi-plus-lg"></i>Buy
                     More Credits
                  </a>
                  
                  @endif
               </div>
            </div>

            @if(count($transaction_histories)>0)
            <ul class="list-group shadow-sm rounded-1">
               <li class="list-group-item bg-light fw-500 py-3">
                  <div class="row">
                     <div class="col-sm-5">Description</div>
                     <div class="col-sm-2">Credits added/adjusted</div>
                     <div class="col-sm-2">Date</div>
                     <div class="col-sm-3">Credit Balance</div>
                  </div>
               </li>
               @foreach($transaction_histories as $history)
               <li class="list-group-item list-group-item-action">
                  <div class="row">
                     <div class="col-sm-5">
                        {{ $history->comment }}
                     </div>
                      @if($history->bal_type== '1')
                     <div class="col-sm-2">+{{ $history->credits }}</div>
                       @else
                       <div class="col-sm-2">-{{ $history->credits }}</div>
                     @endif
                     <div class="col-sm-2">
                         {{ $history->created_at }}
                     </div>
                     <div class="col-sm-3">
                          {{ $history->old_credits }}
                     </div>
                  </div>
               </li>
               @endforeach
            </ul>
            @else
            <div class="card">
               <div class="card-body text-center">
                  <img src="{{asset('images/empty.svg')}}" class="mb-3 mx-auto" width="320" alt="...">
                  <h5 class="mt-3">No History found............</h5>
                  
               </div>
            </div> 
            @endif
            
            <div class="d-flex justify-content-end mt-3">
               @if(count($transaction_histories)>0)
                  {{ $transaction_histories->links() }}
               @endif
            </div>

         </div>


   {{-- toast start --}}
   <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
      <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
         <div class="d-flex">
            <div class="toast-body toast-msg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
      </div>
   </div>
   {{-- toast end --}}


@endsection
@section('extrajs')
   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  {!! Html::script('js/list.js') !!}
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
