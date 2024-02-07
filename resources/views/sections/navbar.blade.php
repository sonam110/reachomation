@php
$userplan = App\Models\SubscriptionPlan::where('id',Auth::user()->plan)->first();
@endphp
<nav class="navbar navbar-expand-lg navbar-dark py-0 sticky-top bg-body">
   <div class="container-fluid">
      <a class="navbar-brand fw-bold fs-4 me-5 pb-0" href="{{ url('dashboard') }}">
         <img src="{{asset('images/reachomation_white_logo1.png')}}" class="img-fluid" width="180" alt="Reachomation"
            loading="lazy">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
         aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <form class="d-flex" action="{{route('search')}}">
            @csrf
            <div class="input-group shadow-sm">
               <input type="search" class="form-control shadow-none searchInput rounded-0"
                  placeholder="Search keyword like 'Tech'" value="" aria-describedby="button-addon2" size="35px"
                  aria-label="Search" name="keyword">
               <button class="btn btn-green shadow-none rounded-0" type="submit" id="button-addon2"><i
                     class="bi bi-search text-white"></i></button>
            </div>
         </form>

         <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
            <li class="nav-item dropdown me-3">
               <a class="nav-link dropdown-toggle text-white" href="#" id="navbarScrollingDropdown" role="button"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  Marketing Tools
               </a>
               <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                  <li>
                     <a class="dropdown-item" href="{{route('ai-blog-finder')}}">
                        <i class="bi bi-search me-1"></i>
                        <small>AI Blog Finder</small>
                     </a>
                  </li>
                  <li>
                     <a class="dropdown-item" href="{{route('email-extractor')}}">
                        <i class="bi bi-envelope-fill me-1"></i>
                        <small>Email Extractor</small>
                     </a>
                  </li>
                  <li>
                     <a class="dropdown-item" href="{{route('author-finder')}}">
                        <i class="bi bi-person-bounding-box me-1"></i>
                        <small>Author Finder</small>
                     </a>
                  </li>
                  <li>
                     <a class="dropdown-item" href="{{route('email-validator')}}">
                        <i class="bi bi-envelope-fill me-1"></i>
                        <small>Email Validator</small>
                     </a>
                  </li>
                  <li>
                     <a class="dropdown-item" href="{{route('da-semrush-finder')}}">
                        <i class="bi bi-bar-chart-fill me-1"></i>
                        <small>DA & SEMrush Finder</small>
                     </a>
                  </li>
               </ul>
            </li>

            <li class="nav-item active-plan-div">
               @if(auth()->user()->plan !='')
               <small class="fw-500 text-muted">Active Plan : <span class="text-white">{{
                     ($userplan) ? $userplan->name : null }} </span></small>@endif
               @if(in_array(auth()->user()->plan,['1','2','4']))
               <a href="{{ route('pricing') }}"><span class="badge bg-warning rounded-1">Upgrade</span></a>
               @endif

            </li>
            <li class="nav-item me-3">
               <div class="dropdown">
                  <a href="#"
                     class="d-flex align-items-center text-white text-decoration-none dropdown-toggle  dropdown-toggle-not mt-1 markUnreadMessage"
                     id="dropdownUser2" data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false"><i
                        class="bi bi-bell-fill"></i>
                     @if(Auth::user()->unreadNotifications->count()>0)
                     <span
                        class=" nav-unread badge badge-warning  badge-pill messageCount">{{Auth::user()->unreadNotifications->count()}}</span>
                     @endif
                  </a>
                  @if(Auth::check())
                  @if(Auth::user()->notifications->count()>0)
                  <ul class="navbar-dpd dropdown-menu dropdown-menu-dark text-small shadow dropdown-menu-end mt-1 user-message-list"
                     aria-labelledby="dropdownUser2">
                     @if(Auth::user()->unreadNotifications->count()>0)
                     <a href="{{route('read-all-notification')}}"
                        class="dropdown-item text-center new-notification">{{count(Auth::user()->unreadNotifications)}}
                        New Notifications</a>
                     @else

                     <a href="{{route('read-all-notification')}}" class="dropdown-item text-center"> Notifications</a>
                     @endif

                     <li>
                        <hr class="dropdown-divider">
                     </li>
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

                        $name = \Illuminate\Support\Str::limit(@$msg['data']['notificationdata']['name'], 80, $end='...');
                        $fname =  (@$msg['data']['notificationdata']['is_parent']==NULL)  ? 'outreach'  :'followup' ; 
                        $message = 'The Attempt '.@$msg['data']['notificationdata']['attemp']. ' of '.$fname.' '.@$name. '  has been '.$status.'';


                           if(@$msg['data']['notificationdata']['status'] == '7' && @$msg['data']['notificationdata']['is_file_read'] == 'Y'){
                              $message = 'Data Processing and Validation of '.@$name. ' has been completed';
                           }
                           if(@$msg['data']['notificationdata']['status'] == '7' && @$msg['data']['notificationdata']['is_file_read'] == 'N'){
                              $message = 'Data for your campaign '.@$name. ' is under processing';
                           }
                        
                         ?>
                     <li class="">
                        <a class="dropdown-item noti-message" href="#">
                           @if(@$msg['data']['notificationdata']['from_email'] !='')
                           {{ $message }}
                           @endif
                           @if(@$msg['data']['notificationdata']['comment'] !='')
                           {{ @$msg['data']['notificationdata']['comment'] }}
                           @endif
                           @if(@$msg['data']['notificationdata']['stripe_id'] !='')
                           @if(@$msg['data']['notificationdata']['plan_id'] !='1')
                           Your Plan have been upgraded to {{ @$msg['data']['notificationdata']['name'] }}
                           @else
                           Your Plan {{ @$msg['data']['notificationdata']['name'] }} has been successfully activated
                           @endif
                           @endif

                           <div class="small text-muted">{{($notification->created_at)->diffForHumans()}}</div>
                        </a>

                     </li>
                     @endforeach
                     <li>
                        <hr class="dropdown-divider">
                     </li>

                  </ul>
                  @endif
                  @endif
               </div>
            </li>

            <li class="nav-item">
               <div class="dropdown">
                  <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle mt-1"
                     id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                     <span class="navbar-dpd2 badge bg-theme text-white"
                        >{{
                        substr(ucfirst(Auth::user()->name),0,1) }}</span>
                     {{-- <img src="{{asset('images/team-1.jpg')}}" alt="" width="32" height="32"
                        class="rounded-circle me-2"> --}}
                  </a>

                  <ul class="navbar-dpd3 dropdown-menu dropdown-menu-dark text-small shadow dropdown-menu-end mt-1"
                     aria-labelledby="dropdownUser1">
                     <li>
                        <a href="{{url('settings')}}"
                           class="d-flex align-items-center text-white text-decoration-none dropdown-item">
                           <span class="navbar-dpd4 badge bg-theme text-white me-2"
                              >{{
                              substr(ucfirst(Auth::user()->name),0,1) }}</span>
                           <span class="fw-500">{{ Auth::user()->name }}</span>
                        </a>
                     </li>
                     <li>
                        <hr class="dropdown-divider">
                     </li>
                     {{-- <li><a class="dropdown-item" href="{{ url('templates') }}"><i class="bi bi-layers"></i>
                           Templates</a></li> --}}

                     {{-- <li><a class="dropdown-item" href="{{url('sendbox')}}"><i
                              class="bi bi-chat-square-dots-fill"></i> Inbox</a></li> --}}

                     {{-- <li><a class="dropdown-item" href="{{url('campaigns')}}"><i class="bi bi-stack"></i>
                           Campaigns</a></li> --}}
                     <li><a class="dropdown-item" href="{{route('transaction-history')}}"><i
                              class="bi bi-file me-1"></i> Credit Ledger</a></li>

                     <li><a class="dropdown-item" href="{{ route('payment-billing')}}"><i class="bi bi-credit-card"></i>
                           Payment & Billing</a></li>
                     <li><a class="dropdown-item" href="{{url('settings')}}"><i class="bi bi-gear"></i> Settings</a>
                     </li>
                     <li>
                        <hr class="dropdown-divider">
                     </li>
                     <li>
                        <div class="list-group-item bg-dark px-2">
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
                                 @if(!empty(Auth::user()->plan) && Auth::user()->plan!='1' )
                                 <span class="badge bg-warning rounded-1" role="button" tabindex="0"
                                    onclick="buyCredits()">Buy
                                    More</span>
                                 @endif

                                 {{-- <span class="badge bg-primary rounded-0" role="button" tabindex="0"
                                    onclick="topup('topup')">Buy
                                    More</span> --}}
                              </div>
                           </div>
                           <div class="col-12">
                              <div class="progress navbar-dpd5">
                                 <div class="progress-bar navbar-dpd6" role="progressbar" aria-valuenow="20"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <hr class="text-muted mb-1">
                              <div class="text-center">

                                 <small class="fw-500 text-muted">Active Plan :

                                    <span class="text-white">
                                       {{($userplan) ? $userplan->name : null }}
                                    </span>

                                 </small>

                                 <div class="text-center mt-1">

                                    @if(in_array(auth()->user()->plan,['1','2','4']))
                                    <a href="{{ route('pricing') }}" class="btn btn-green fw-500 btn-sm">Upgrade</a>
                                    @endif
                                 </div>

                              </div>
                           </div>
                        </div>
                     </li>
                     {{-- @if(auth()->user()->plan !='5')
                     <li>
                        <div class="text-center">
                           <a href="{{ route('pricing') }}" class="btn btn-green fw-500 btn-sm">Upgrade</a>
                        </div>
                     </li>
                     @endif --}}
                     <li>
                        <hr class="dropdown-divider">
                     </li>
                     <li>
                        <form method="POST" action="{{ route('logout.auth') }}">
                           @csrf
                           <a class="dropdown-item dropdown-item-danger" href="{{route('logout.auth')}}" onclick="event.preventDefault();
                           this.closest('form').submit();"><i class="bi bi-box-arrow-right"></i> Sign out</a>
                        </form>
                     </li>
                  </ul>
               </div>
            </li>
         </ul>
      </div>
   </div>
</nav>
<script src="/js/views/sections/navbar.js"></script>
<link rel="stylesheet" href="/css/views/sections/navbar.css"/>
