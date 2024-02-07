@extends('layouts.master')
@section('content')
@section('extracss')
<link href="/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('css/views/campaigns.css')}}">
@endsection
<div class="py-3">
   <div class="hstack border-bottom gap-3 mb-3 pb-3">
      <div>
         <h3 class="fw-bold mb-0">
            Your Campaigns
         </h3>
      </div>
      <div class="ms-auto">
         <a href="{{route('schedulecampaign')}}" type="button" class="btn btn-green shadow-sm"
            style="font-weight: 500;"><i class="bi bi-plus-lg"></i> Create New Campaign</a>
      </div>
   </div>

   <div class="row row-cols-1 row-cols-md-4 g-1 mb-4">
      <div class="col">
         <div class="card h-100 shadow-sm border-0 rounded-1">
            <div class="card-body py-2">
               <div class="hstack gap-3">
                  <div>
                     <p class="mb-0 text-muted fw-600">
                        <small>Campaigns created</small>
                     </p>
                     <h4 class="mb-0 fw-bold">
                        {{ userCampiagn(auth()->user()->id)}}
                     </h4>
                  </div>
                  <div class="ms-auto">
                     <i class="bi bi-stack fs-2"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col">
         <div class="card h-100 shadow-sm border-0 rounded-1">
            <div class="card-body py-2">
               <div class="hstack gap-3">
                  <div>
                     <p class="mb-0 text-muted fw-600">
                        <small>Total mails sent</small>
                     </p>
                     <h4 class="mb-0 fw-bold"> {{ totalSendMail(auth()->user()->id)}}</h4>
                  </div>
                  <div class="ms-auto">
                     <i class="bi bi-cursor-fill fs-2"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col">
         <div class="card h-100 shadow-sm border-0 rounded-1">
            <div class="card-body py-2">
               <div class="hstack gap-3">
                  <div>
                     <p class="mb-0 text-muted fw-600">
                        <small>Total responses received</small>
                     </p>
                     <h4 class="mb-0 fw-bold">{{ totalReplyMail(auth()->user()->id)}}</h4>
                  </div>
                  <div class="ms-auto">
                     <i class="bi bi-reply-all-fill fs-2"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col">
         <div class="card h-100 shadow-sm border-0 rounded-1">
            <div class="card-body py-2">
               <div class="hstack gap-3">
                  <div>
                     <p class="mb-0 text-muted fw-600">
                        <small>Total lists</small>
                     </p>
                     <h4 class="mb-0 fw-bold">
                        {{ userList(auth()->user()->id)}}
                     </h4>
                  </div>
                  <div class="ms-auto">
                     <i class="bi bi-list fs-2"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-12">
         <div class="card shadow-sm border-0 rounded-1">
            <div class="card-body position-relative">
               @if((userCampiagn(auth()->user()->id)) <= 0 ) <div
                  class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001;">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <h6 class="mb-0 text-center">
                        Your campaign reporting will be displayed here after you setup and launch your outreach
                        campaigns
                     </h6>
                  </div>
            </div>
            @endif
            @if((userCampiagn(auth()->user()->id)) > 0 )
            <canvas id="myChart" width="1507" height="436"></canvas>
            @else
            <div class="blur">
               <div class="chartjs-size-monitor">
                  <div class="chartjs-size-monitor-expand">
                     <div class=""></div>
                  </div>
                  <div class="chartjs-size-monitor-shrink">
                     <div class=""></div>
                  </div>
               </div>
               <canvas class="w-100 chartjs-render-monitor" id="myChart" width="1507" height="336"
                  style="display: block; height: 424px; width: 1005px;"></canvas>
            </div>

            @endif

         </div>
      </div>
   </div>

   @if(count($campaigns)>0)
   <div class="card rounded-0 border-0 shadow-sm d-none">
      <div class="card-body">
         <div class="row">
            @php
            $count=0;
            @endphp
            <div class="col-sm-12">
               <div class="row row-cols-1 row-cols-md-4 g-4">
                  @foreach($campaigns as $campaign)
                  @php
                  $count++;
                  @endphp
                  <div class="col-md-4">
                     <div class="card rounded-0 border-0 h-100 text-center shadow-sm">
                        <div class="card-header">
                           <div class="hstack">
                              <div>
                                 <h6 class="fw-500 mb-0">
                                    {{$campaign->name}}
                                 </h6>
                              </div>
                              <div class="ms-auto">
                                 <div class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle text-dark"
                                       id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false"><i class="icon-cog2"></i></a>
                                    <div class="dropdown-menu dropdown-menu-dark text-small shadow dropdown-menu-end"
                                       aria-labelledby="dropdownMenuButton">
                                       <a class="dropdown-item" href="#">Edit Schedule</a>
                                       <div class="dropdown-divider"></div>
                                       <a class="dropdown-item" href="javascript:void(0)">Stop Campaign</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="card-body p-2">
                           <div class="row">
                              <div class="col-md-4">
                                 <ul class="list-unstyled">
                                    <li><small class="fw-bold">{{date('d-m-Y',
                                          strtotime($campaign->start_date))}}</small></li>
                                    <li><small class="fw-500 text-muted text-nowrap">Start Date</small></li>
                                 </ul>
                              </div>
                              <div class="col-md-4">
                                 <ul class="list-unstyled">
                                    <li>
                                       <small class="fw-bold">
                                          {{$campaign->list_name}}
                                       </small>
                                    </li>
                                    <li>
                                       <small class="fw-500 text-muted text-nowrap">List Name</small>
                                    </li>
                                 </ul>
                              </div>
                              <div class="col-md-4">
                                 <ul class="list-unstyled">
                                    <li>
                                       <span class="badge bg-secondary">Scheduled</span>
                                    </li>
                                    <li>
                                       <small class="fw-500 text-muted text-nowrap">Status</small>
                                    </li>
                                 </ul>
                              </div>
                              <div class="col-md-4">
                                 <ul class="list-unstyled">
                                    <li>
                                       <span class="badge bg-primary">-</span>
                                    </li>
                                    <li>
                                       <small class="fw-500 text-muted text-nowrap">Mail Sent</small>
                                    </li>
                                 </ul>
                              </div>
                              <div class="col-md-4">
                                 <ul class="list-unstyled">
                                    <li>
                                       <span class="badge bg-warning">-</span>
                                    </li>
                                    <li>
                                       <small class="fw-500 text-muted text-nowrap">Responses</small>
                                    </li>
                                 </ul>
                              </div>
                              <div class="col-md-4">
                                 <ul class="list-unstyled">
                                    <li>
                                       <span class="badge bg-success">-</span>
                                    </li>
                                    <li>
                                       <small class="fw-500 text-muted text-nowrap">Success</small>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                           <canvas id="myChart{{$count}}" height="228"></canvas>
                        </div>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
         </div>
      </div>
   </div>
   @else
   <div class="card d-none">
      <div class="card-body text-center">
         <img src="{{asset('images/empty.svg')}}" class="mb-3 mx-auto" width="360" alt="...">
         <h5 class="mt-3">You're yet to create your first campaign</h5>
         {{-- <a href="{{route('schedulecampaign')}}" type="button"
            class="btn btn-primary btn-lg shadow-sm fw-500 mt-3"><i class="bi bi-plus-lg"></i> Create New Campaign</a>
         --}}
      </div>
   </div>
   @endif
   {{-- Card section start --}}
   @if(count($campaigns)>0)
   <div class="card rounded-0 border-0">
      <div class="card-body">
         <div class="hstack border-bottom pb-2 mb-3">
            <div>

               <div class="input-group shadow-sm">
                  <input type="text" class="form-control shadow-none" name="keyword" id="keyword"
                     placeholder="Search & Hit Enter " aria-describedby="button-addon2" size="25px" aria-label="Search">
                  <!-- <button class="btn btn-success shadow-none" type="submit" id="button-addon2"><i class="bi bi-search text-white"></i></button> -->
               </div>

            </div>
            <div class="ms-auto">
               <div class="d-flex">
                  <select class="form-select shadow-none" name="from_email" id="from_email">
                     <option value="" selected>All Users</option>
                     @foreach($allUsers as $user)
                     <option value="{{ $user->email }}">{{ ($user->name!='') ? $user->name :$user->email }}</option>
                     @endforeach
                  </select>
                  <select class="form-select shadow-none" name="mystatus" id="mystatus">
                     <option value="" selected>Status</option>
                     <option value="6">Draft</option>
                     <option value="0">To begin</option>
                     <option value="3">In Progress</option>
                     <option value="4">In Process</option>
                     <option value="5">Paused</option>
                     <option value="8">Finished</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="">
            <table class="table mb-0 table-striped" id="datatable">
               <thead>
                  <tr>
                     <th scope="col"></th>
                     <th scope="col" width="50%">Campaign</th>
                     <th scope="col"  width="15%" >List Size</th>
                     <th scope="col" width="15%">Status</th>
                     <th scope="col" width="15%">Opens</th>
                     <th scope="col" width="15%">Clicks</th>
                     <th scope="col" width="15%">Replies</th>
                     <th scope="col" width="50%">Sender</th>
                     <th scope="col">Action</th> 
                  </tr>
               </thead>
               <tbody>



               </tbody>
            </table>
         </div>

      </div>
   </div>
   {{-- Card section end --}}

   @else
   <div class="card rounded-0 border-0 mt-4">
      <div class="card-body text-center">
         <img src="{{asset('images/empty.svg')}}" class="mb-3 mx-auto" width="360" alt="...">
         <h5 class="mt-3">You're yet to create your first campaign</h5>
         {{-- <a href="{{route('schedulecampaign')}}" type="button"
            class="btn btn-primary btn-lg shadow-sm fw-500 mt-3"><i class="bi bi-plus-lg"></i> Create New Campaign</a>
         --}}
      </div>
   </div>
   @endif

</div>

{{-- Modal for alert start --}}
<div class="modal modal-alert" tabindex="-1" role="dialog" id="copyCampaign">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content rounded-4 shadow">
         <form action="{{ route('copy-campaign')}}" method="post">
            @csrf
            <input type="hidden" name="camp_id" id="camp_id">
            <div class="modal-body p-4 text-center">
               <h5 class="mb-2">Are you sure?</h5>
               <p class="mb-0">You want to copy this! ?.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
               <button type="submit"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-danger shadow-none"
                  id="copy-btn"><strong>Yes, Copy it</strong></button>
               <button type="button"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start text-dark shadow-none"
                  data-bs-dismiss="modal">No thanks</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- Modal for alert end --}}

{{--Schedule Campaign Modal for alert start --}}
<div class="modal fade" tabindex="-1" role="dialog" id="scheduleCampaign">
   <form action="{{ route('schedule-campaign')}}" method="post" class="scheduleClass" id="scheduleClass">
      @csrf
      <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content rounded-4 shadow">
            <div class="modal-header pd-x-20">
               <h6 class="modal-title"> Set Sending Schedule</h6>
               <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" name="camp_id" id="camp_sid">
            <div class="modal-body p-4 text-center">
               <div class="row">
                  <div class="col-md-12 mb-4">
                     <div class="row">
                        <div class="col-md-6">
                           <label class="form-label mb-1 fw-500">
                           <small>
                           Start Date
                           </small>
                           </label>
                           <input type="date" class="form-control shadow-none form-control-lg datePicker rounded-0"
                              value="{{ date('Y-m-d') }}" name="start_date" id="start-date" required>
                        </div>
                        <div class="col-md-6">
                           <label class="form-label mb-1 fw-500">
                           <small>
                           Timezone
                           </small>
                           </label>
                           <select class="form-control form-control-lg shadow-none selectTimeZone rounded-0"
                              name="timezone" id="timezone">
                           @foreach(generate_timezone_list() as $key =>$timezone)
                           <option value="{{ $key  }}" {{ ($key=='Asia/Kolkata' ) ? 'selected' : '' }}>
                           {{ $timezone }}
                           </option>
                           @endforeach
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 mb-4">
                     <div class="row">
                        <div class="col-md-6">
                           <label class="form-label mb-1 fw-500">
                           <small>
                           Send Between
                           </small>
                           </label>
                           <div class="d-flex align-items-center timesetting">
                              <div>
                                 <input type="time" class="form-control shadow-none form-control-lg rounded-0"
                                    value="10:00" name="starttime" id="starttime" value="" required>
                              </div>
                              <div>
                                 <p class="mb-0 fw-bold px-2">to</p>
                              </div>
                              <div>
                                 <input type="time" class="form-control shadow-none form-control-lg rounded-0"
                                    value="18:00" name="endtime" id="endtime" value="" required>
                              </div>
                           </div>
                           <div class="form-check mt-1">
                              <input class="form-check-input rounded-0 shadow-none notStopCamp" type="checkbox"  name="non_stop"  value="1" id="flexCheckDefault">
                              <label class="form-check-label" for="flexCheckDefault">
                              <small>Non stop</small>
                              </label>
                           </div>
                        </div>
                        <div class="col-md-6 timesetting">
                           <label class="form-label mb-1 fw-500">
                           <small>
                           Cooling Period 
                           </small>
                           </label>
                           <select class="form-select shadow-none rounded-0" name="cooling_period" id="cooling_period" style="height: 42px;">
                              <option value="24" selected>24 Hours</option>
                              <option value="36">36 Hours</option>
                              <option value="48">48 Hours</option>
                           </select>
                           
                           <small class="text-red" style="font-size: 10px;">
                           *Timegap between diff stages, and min cooling period to be 24 hrs
                           </small>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-8">
                     <div class="alert alert-info d-flex align-items-center rounded-0 py-2" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                           class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                           <path
                              d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                        </svg>
                        <div>
                           <div class="d-flex">
                              <small>&nbsp;Interval between two emails will be <span class="fw-500">60-90</span> seconds
                              </small>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer flex-nowrap p-0">
               <button class="btn bg-white border-dark rounded-4 shadow-none cancel-tools" data-bs-dismiss="modal">No,
                  Cancel</button><button class="btn btn-primary rounded-4 buy-credit d-none" id="schedulebtn"
                  type="submit">Schedule</button>
               <button class="btn btn-primary rounded-4 proceed-btn">Yes, Proceed</button>
            </div>

         </div>
      </div>
   </form>
</div>
{{-- Modal for alert end --}}

{{-- Modal for alert start --}}
<div class="modal modal-alert" tabindex="-1" role="dialog" id="deleteCampaign">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content rounded-4 shadow">
         <form action="{{ route('delete-campaign')}}" method="post">
            @csrf
            <input type="hidden" name="campid" id="campid">
            <div class="modal-body p-4 text-center">
               <h5 class="mb-2">Are you sure?</h5>
               <p class="mb-0">Once deleted, you will not be able to recover this Campaign.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
               <button type="submit"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-danger shadow-none"
                  id="delete-btn"><strong>Yes, delete it</strong></button>
               <button type="button"
                  class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start text-dark shadow-none"
                  data-bs-dismiss="modal">No thanks</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- Modal for alert end --}}

{{-- Hidden input tags start --}}
<input type="hidden" id="thirty-days" value='@php echo getLast30Days(); @endphp'>

<input type="hidden" id="thirty-days-sent-mails" value='@php echo  getLast30DaysSentMails(auth() -> user() -> id); @endphp'>

<input type="hidden" id="thirty-days-delivered-mails" value='@php echo getLast30DaysDeliveredMails(auth() -> user() -> id); @endphp'>

<input type="hidden" id="thirty-days-reply-mails" value='@php echo getLast30DaysReplyMails(auth() -> user() -> id); @endphp'>

<input type="hidden" id="campaign-list-url" value='{{ route('api.campaign-list') }}'>

<input type="hidden" id="csrf-token" value='{{ csrf_token() }}'>
{{-- Hidden input tags end --}}

@endsection
@section('extrajs')
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
{!! Html::script('js/list.js') !!}
{!! Html::script('js/jquery.validate.js') !!}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('js/views/campaigns.js')}}"></script>
@endsection
