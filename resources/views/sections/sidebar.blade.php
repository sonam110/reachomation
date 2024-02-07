@php $countries = Helper::countries();

@endphp
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse">
   <div class="position-sticky pt-2">
      <div class="list-group list-group-flush border-end">
         <a href="{{route('dashboard')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('dashboard')) ? 'active' : '' }}"
            aria-current="true">
            <i class="bi bi-house-door-fill me-1"></i>
            <small>Dashboard</small>
         </a>
          {{-- <a href="{{ route('feeds')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('feeds')) ? 'active' : '' }}">
            <i class="bi bi-chat-square-dots-fill me-1"></i>
            <small>Feeds</small>
         </a> --}}
         <a href="{{route('campaigns')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('campaigns')) ? 'active' : '' }}">
            <i class="bi bi-stack me-1"></i>
             @if(userCampiagn(auth()->user()->id) >0)
            <small>Campaigns ({{ userCampiagn(auth()->user()->id) }})</small>
            @else
            <small>Campaigns </small>
            @endif
         </a>
         <a href="{{route('schedulecampaign')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('schedulecampaign')) ? 'active' : '' }}">
            <i class="bi bi-plus-lg me-1"></i>
            <small>Create Campaign</small>
         </a>
         <a href="{{route('templates')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('templates')) ? 'active' : '' }}">
            <i class="bi bi-layers me-1"></i>
            @if(TemplateCount(auth()->user()->id) >0)
            <small>Templates ({{ TemplateCount(auth()->user()->id) }})</small>
            @else
             <small>Templates</small>
            @endif
         </a>
         <a href="{{route('lists')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('lists')) ? 'active' : '' }}">
            <i class="bi bi-list me-1"></i>
            @if(userList(auth()->user()->id) >0)
            <small>Lists ({{ userList(auth()->user()->id) }})</small>
            @else
             <small>Lists</small>
            @endif
         </a>
         <a href="{{route('revealed')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('revealed')) ? 'active' : '' }}">
            <i class="bi bi-unlock-fill me-1"></i>
            @if(RevealedCount(auth()->user()->id) >0)
            <small>Revealed ({{ RevealedCount(auth()->user()->id) }})</small>
            @else
             <small>Revealed </small>
            @endif
         </a>
         <a href="{{route('favourites')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('favourites')) ? 'active' : '' }}">
            <i class="bi bi-suit-heart-fill me-1"></i>
            @if(FavouriteCount(auth()->user()->id) >0)
            <small>Favourites ({{ FavouriteCount(auth()->user()->id) }})</small>
            @else
             <small>Favourites </small>
            @endif
         </a>

         <!-- <a href="{{route('sendbox')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('sendbox')) ? 'active' : '' }}">
            <i class="bi bi-chat-square-dots-fill me-1"></i>
            <small>Inbox</small>
         </a> -->
         {{-- <a href="{{route('billing')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('billing')) ? 'active' : '' }}">
            <i class="bi bi-credit-card me-1"></i>
            <small>Payment & Billing</small>
         </a> --}}
          {{-- <a href="{{route('transaction-history')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('transaction-history')) ? 'active' : '' }}">
            <i class="bi bi-file me-1"></i>
            <small>Credit Ledger</small>--}}
         {{-- </a>  --}}
         <a href="{{route('settings')}}"
            class="list-group-item list-group-item-action list-anchor {{ (request()->is('settings')) ? 'active' : '' }}">
            <i class="bi bi-gear me-1"></i>
            <small>Settings</small>
         </a>
         <!--           <a href="{{route('mail')}}" class="list-group-item list-group-item-action list-anchor {{ (request()->is('mail') || request()->is('gmail_list')) ? 'active' : '' }}">
            <i class="bi bi-chat-square-dots-fill me-1"></i>
            <small>Gmail</small>
         </a>
         <a href="{{route('outlook_mail')}}" class="list-group-item list-group-item-action list-anchor {{ (request()->is('outlook_mail') || request()->is('outlook_list')) ? 'active' : '' }}">
            <i class="bi bi-chat-square-dots-fill me-1"></i>
            <small>Outlook</small>
         </a> -->
         {{-- <br><br>
         <span class="badge bg-theme" role="button" tabindex="0" onclick="topup('topup')">TOPUP</span> --}}

         <div class="list-group-item bg-dark px-2 ">
            @php
            $userplan = App\Models\SubscriptionPlan::where('id',Auth::user()->plan)->first();
            $plan = ($userplan) ? $userplan->credits : 0;
            $price = ($userplan) ? $userplan->price : 0;
            $type = 'No Active Plan';
            if(isset($userplan) && $userplan->plan_type =='1'){
            $type ="Monthly";
            }
            if(isset($userplan) && $userplan->plan_type =='2'){
            $type ="Yearly";
            }
            if(isset($userplan) && $userplan->plan_type =='3'){
            $type ="Free";
            }

            @endphp
            <div class="d-flex w-100 align-items-center justify-content-between mb-2">
               <div>
                  <small class="text-muted fw-500">Credits Left
                     <span class="text-white">
                        <span class="text-white">{{ Auth::user()->credits }}</span>
                     </span>
                  </small>
               </div>
               <div class="ms-auto">
                  @if(!empty(Auth::user()->plan) )
                  <span class="badge bg-warning rounded-1" role="button" tabindex="0" onclick="buyCredits()">Buy
                     More</span>
                  @endif
               </div>
            </div>
            <hr class="text-light mt-2 mb-2">
            
            <div class="d-flex flex-column">
               <p class="text-white mb-0" style="font-size: 12px;">
                  <small>Next credit topup on: 12-02-2023</small>
               </p>
            </div>

            <hr class="text-light mt-2 mb-2">
            
            <div class="text-center">
               <a href="{{ route('grab-free-credits') }}" class="btn btn-green btn-sm fw-500 shadow-none">
                  Grab free credits
               </a>
            </div>
            
            <!-- <div class="col-12">
               <div class="progress" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0"
                     aria-valuemax="100"></div>
               </div>
               <hr class="text-muted mb-1">
               <div class="text-center">

                  <small class="fw-500 text-muted">Active Plan :
                    
                     <span class="text-white">
                        {{($userplan) ? $userplan->name : null }}  
                     </span>
                    
                  </small>

                    @if(in_array(auth()->user()->plan,['1','2','4']))   
                  <a href="{{ route('pricing') }}"><span class="badge bg-warning rounded-1">Upgrade </span></a>
                  @endif
               </div>
            </div> -->
         </div>

         {{-- <a href="#" class="list-group-item bg-dark px-2" style="pointer-events: none;">
            <?php
              $userplan = App\Models\SubscriptionPlan::where('id',Auth::user()->plan)->first();
              $plan = ($userplan) ? $userplan->credits : 0;
              $price = ($userplan) ? $userplan->price : 0;
              $type = 'Free';
              if(isset($userplan) && $userplan->plan_type =='1'){
               $type  ="Monthly";
              }
              if(isset($userplan) && $userplan->plan_type =='3'){
               $type  ="Free";
              }
              if(isset($userplan) && $userplan->plan_type =='2'){
               $type  ="Yearly";
              }
            ?>
            <div class="d-flex w-100 align-items-center justify-content-between">
               <small class="mb-2 text-muted fw-500">Credits Left
                  @if(Auth::user()->plan!='')
                  <span class="text-white">{{Auth::user()->credits}}</span>
                  @endif
               </small>
            </div>
            <div class="col-12 mb-1">

               <div class="progress" style="height: 5px;">
                  @if(Auth::user()->plan!='')
                  <div class="progress-bar" role="progressbar"
                     style="width: {{ Helper::credits_used(Auth::user()->credits, $plan) }}%;"
                     aria-valuenow="{{ Helper::credits_used(Auth::user()->credits, $plan) }}" aria-valuemin="0"
                     aria-valuemax="100"></div>
                  @endif

               </div>
               <hr class="text-muted mb-1">
               <div class="text-center">
                  <small class="fw-500 text-muted">Active Plan : <span class="text-white">{{
                        ($userplan) ? $userplan->name : null }} ({{ $type }})</span></small>
                  @if(auth()->user()->plan !='5') <a href="{{ route('pricing') }}"><span
                        class="badge bg-primary rounded-0">Upgrade Plan</span></a>@endif
               </div>
            </div>
         </a> --}}

      </div>
   </div>
</nav>

<!-- <div class="modal fade" tabindex="-1" role="dialog" id="buy-credits">
   <div class="modal-dialog" role="document">
      <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0">
            <h4 class="fw-bold mb-0">Credits Topup</h4>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2 pb-0">
            <div class="input-group input-group-lg mt-3 mb-3 w-50">
               <span class="input-group-text" role="button" tabindex="0"><i class="bi bi-dash-lg"></i></span>
               <input type="text" class="form-control shadow-none" value="100">
               <span class="input-group-text" role="button" tabindex="0"><i class="bi bi-plus-lg"></i></span>
            </div>
            <div>
               <h6 class="fw-500">Total: $100.00</h6>

               <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="recurring" checked>
                  <label class="form-check-label" for="recurring">
                     Recurring payment $200.00 when reaching  <span class="fw-bold"> 20000 </span> credits
                  </label>
               </div>
               <div class="alert alert-warning mt-3" role="alert">
                  Datalists allow you to create a group of that can be accessed (and autocompleted) from within an. These are similar to elements, but come with more menu styling limitations and differences.
                  <p class="fw-500 mt-2 mb-0">To cancel a recurring payment, please <a href="#">contact us</a></p>
               </div>
            </div>
            <button class="btn btn-success px-4 fw-500 mb-4">Pay Now</button>
         </div>
      </div>
   </div>
</div> -->

<div class="modal fade" tabindex="-1" role="dialog" id="topup-modal">
   <div class="modal-dialog" role="document">
      <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0">
            <h3 class="fw-bold mb-0">Credits Topup</h3>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2 pb-0">
            <p class="fw-500 text-muted">Purchase Credit <span class="text-dark" id="credit_cost">{{
                  ($userplan) ? $userplan->credits : 0 }} credits</span>?</p>
            <div class="row">
               <div class="col-md-12">
                  Current Package: <label class="form" for="dont-show">{{
                     ($userplan) ? $userplan->name : null }} ({{ $type }})</label>
               </div>
               <div class="col-md-12">

                  Credits: <label class="form" for="dont-show">{{
                     ($userplan) ? $userplan->credits : 0 }} </label>
               </div>
               <div class="col-md-12">

                  Price: <label class="form" for="dont-show">${{
                     ($userplan) ? $userplan->price : 0 }} </label>
               </div>
            </div>

         </div>
         <div class="modal-footer" id="topup-action">
            <button class="btn bg-white border-dark rounded-4 shadow-none" data-bs-dismiss="modal">No,
               Cancel</button><button class="btn btn-primary rounded-4" type="submit"
               onclick="purchaseCredits('{{$price}}','{{$plan}}')">Yes, Proceed</button>
         </div>
      </div>
   </div>
</div>

<!--   buy credit model -->

<div class="modal fade" tabindex="-1" role="dialog" id="buy-credits">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
          <form id="create-campaign" action="{{ route('purchase-credit-model') }}" method="POST" enctype="multipart/form-data"
         class="form-meet" name="form" autocomplete="off" role="form">
          <input type="hidden" name="credit_price" id="credit_price" value="10">
       @csrf
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">
               Buy more credits
            </h4>

            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2 pb-2 position-relative">
            <label class="form-label mt-3 fw-600">
               <small>Credits</small>
            </label>
            <div class="input-group mb-3" style="width: 35%;">
                <span class="input-group-prepend">
                    <button type="button" class="btn btn-outline-secondary btn-number minus"  data-type="minus" data-field="credits">
                         <i class="bi bi-dash"></i>
                    </button>
                </span>
                <input type="text" name="credits"  id="credits" class="form-control input-number" value="1000" min="1000" max="50000000000000000"  >
                <span class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary btn-number plus" data-type="plus" data-field="credits">
                        <i class="bi bi-plus"></i>
                    </button>
                </span>
            </div>

           

            <hr>

            <div>
               <div class="d-flex mb-3">
                  <div>
                     <h6 class="mb-0">Total: &nbsp;</h6>
                  </div>
                  <div>
                     <h6 class="mb-0 fw-bold" id="price"> $10</h6>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0" id="topup-action">
            <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red fw-600" data-bs-dismiss="modal">No, Cancel</button>
            <button type="submit" class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover fw-600" >Buy Now</button>
           
         </div>
         </form>
      </div>
   
   </div>
</div>


{{-- Connect email modal --}}
<div class="modal modal-alert fade" tabindex="-1" role="dialog" id="email_connect_model">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable " role="document">
      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
         <div class="modal-body p-4 pt-2 pb-2 position-relative">
            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="d-flex justify-content-center">
                  <i class="bi bi-lock-fill fs-1 text-red"></i>
            </div>
            <p class="fw-semibold mb-3 mt-3 lh-base fw-500">
               You must have atleast one email address actively connected with your account to create and launch an outreach campaign
            </p>
         </div>
         <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
            <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red" data-bs-dismiss="modal">Will do later</button>
            <a href="{{ route('settings') }}" type="button" class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover">Connect my email</a>
        </div>
      </div>
   </div>
</div>

{{-- Upgrade pop up --}}
   <div class="modal fade" tabindex="-1" role="dialog" id="upgrade-modal">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
          <div class="modal-content shadow border-0 bg-light"
              style="border-radius: 0.75rem!important;">
              <div class="modal-body p-4 pt-2 pb-2 position-relative">
                 <!--  <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
                      data-bs-dismiss="modal" aria-label="Close"></button> -->

                  <div class="d-flex justify-content-center">
                      <i class="bi bi-lock-fill fs-1 text-red"></i>
                  </div>
                  <p class="fw-semibold mb-3 mt-3 lh-base text-center" id="title_plan">
                      To access this feature, you need to upgrade your plan.
                  </p>
              </div>
              <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
               <a href="{{ route('pricing') }}" type="button" class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover fw-600">Upgrade now</a>
                  <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red fw-600" data-bs-dismiss="modal">Will do later</button>
                  
              </div>
          </div>
      </div>
   </div>