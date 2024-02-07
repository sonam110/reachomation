@extends('layouts.master')
@section('content')
@section('extracss')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
   .select2-container .select2-selection--single {
   height: 38px;
   }
   .progress-bar {
   background-color: #60A396;
   }
</style>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 21px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.switch-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.switch-slider:before {
  position: absolute;
  content: "";
  height: 14px;
  width: 15px;
  left: 2px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .switch-slider {
  background-color: #2196F3;
}

input:focus + .switch-slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .switch-slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.switch-slider.round {
  border-radius: 34px;
}

.switch-slider.round:before {
  border-radius: 50%;
}
</style>
@endsection
<form id="create-campaign" action="{{ route('create.campaign') }}" method="POST" enctype="multipart/form-data"
   class="form-meet create-campaign" name="form" autocomplete="off" role="form">
   @csrf
   <input type="hidden" name="id" id="edit_id" value="">
   <input type="hidden" name="target" id="target" value="1">
   <input type="hidden" name="is_same_thread" id="is_same_thread" value="0">
   <input type="hidden" name="validcontact" id="validcontact" value="">
   <input type="hidden" name="is_file_change" id="is_file_change" value="0">
   <input type="hidden" name="including_non_blog" id="including_non_blog" value="0">
   <input type="hidden" name="usercredit" id="usercredit" value="{{ auth()->user()->credits }}">
   <input type="hidden" name="current_time" id="current_time" value="{{ date('H:i:s') }}">
   <div class="py-3">
      <div class="hstack border-bottom gap-3 mb-3 pb-3">
         <div>
            <h3 class="fw-bold mb-0">
               Create Campaign
            </h3>
         </div>
      </div>

      {{-- Recepients section start --}}
      <div id="divErrorContainer"></div>
      <!-- Campaign name start -->
      <section class="app__name mb-3">
         <div class="card rounded-0 border-0 shadow-sm">
            <div class="card-body">
               <h5 class="card-title fw-bold">
                  Step 1: Name your campaign
               </h5>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3 mt-4">
                        <input type="text"
                           class="form-control form-control-lg rounded-0 border border-3 border-top-0 border-start-0 border-end-0 shadow-none fs-6"
                           name="campaign_name" id="campaign-name" placeholder="Name your campaign" value="{!! $camp_name !!}" autocomplete="off"
                           required>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Campaign name end -->
      <!-- Target Start -->
     <!--  <section class="app__name mb-3">
         <div class="card rounded-0 border-0 shadow-sm">
            <div class="card-body">
               <h5 class="card-title fw-bold">
                  Step 2: Select your target group
               </h5>
               <div class="mb-3 mt-4">
                  <input type="checkbox" class="btn-check" name="target" id="target" value="1" autocomplete="off"
                     checked  >
                  <label class="btn btn-outline-success btn-lg me-4 rounded-1 py-3 shadow-sm" for="target" id="target"
                     style="font-size: 16px; pointer-events:none;">Blogs/Webmasters/Publications</label>
                  <input type="radio" class="btn-check" name="target" id="option2" value="2" autocomplete="off"
                     disabled>
                  <label class="btn btn-outline-success btn-lg me-4 rounded-1 py-3 shadow-sm" for="option2" id="target"
                     style="font-size: 16px;">Companies/Professionals
                  <span style="font-size: 14px;">
                  <small>(Coming Soon)</small>
                  </span>
                  </label>
               </div>
            </div>
         </div>
      </section> -->
      <!-- Target end -->
      <!-- List start -->
      <section class="app__list mb-3">
         <div class="card rounded-0 border-0 shadow-sm">
            <div class="card-body">
               <h5 class="card-title fw-bold">
                  Step 2: Choose your list
               </h5>
               <div class="row mt-4 mb-3">
                  <div class="col-md-6">
                     <div>
                        <select class="form-select form-select-lg rounded-1 shadow-none rec_type" name="rec_type"
                           id="rec_type" style="font-size: 14px;height:50px;">
                           <option value="" selected>Select</option>
                           <option value="1" {{ (!empty($list_id)) ? 'selected':''
                        }}>Select from existing lists</option>
                           <option value="3">Import from other email campaign(s)</option>
                           <option value="2">Upload a new list (CSV files only)</option>
                        </select>
                     </div>
                  </div>
                  <div class="vr w-0"></div>
                  <div class="col-md-5 listType" style="visibility: hidden; height: 100%;display: none">
                     <select class="form-select form-select-lg rounded-1 shadow-none customTagType mygroup"
                        name="list_id" data-type="1" id="created-list" style="font-size: 14px;height:50px;"
                        onchange="loadList()">
                        <option value="" selected>Select</option>
                        @foreach ($collections as $collection)
                        @php $collectionSize =
                        App\Models\DomainCollection::where('collection_id',$collection->id)->count(); @endphp
                        <option value="{{$collection->id}}" data-size="{{ $collectionSize }}" {{ ($collection->id == $list_id) ? 'selected':''
                        }}>{{ucfirst($collection->name)}} ({{ $collectionSize }})</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-5 campaignType" style="visibility: hidden; height: 0;display: none">
                     <select class="form-select form-select-lg shadow-sm shadow-none customTagType mygroup"
                        name="camp_id" data-type="3" id="camp_id" style="font-size: 14px;height:50px;"
                        onchange="loadCampData()">
                        <option value="" selected>Select</option>
                        @foreach ($allOldCampign as $camp)
                        <?php  
                        $name  =  \Illuminate\Support\Str::limit($camp->name, 70, $end='...') ;
                        ?>
                        <option value="{{$camp->id}}"  data-size="{{ $camp->import_contact }}" >{{ $name }}  ({{ $camp->import_contact }})</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-sm-5 csvType" style="visibility: hidden; height: 0;display: none">
                     <label class="label-file">Choose file
                     <input type="file"  class="customTagType mygroup" data-type="2" name="csv_file" id="file-csv"   />
                     </label>
                     <div class="d-flex">
                        <div>
                           <label for="exampleFormControlInput1" class="form-label text-danger mb-0 fw-500"><small>File extension must be
                           <span class="fw-bold">.csv</span></small>
                           </label>
                        </div>
                        <div class="ms-auto me-5">
                           <a href="{{ url('downloadfile') }}" class="text-decoration-none"><i
                              class="bi bi-download"></i> sample.csv</a>
                        </div>
                     </div>
                     <small><span id="file_name" class="mb-2"></span></small><br>
                     <div class="alert alert-info d-flex align-items-center rounded-0 py-1 mt-2 mb-0" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                           class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                           <path
                              d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                        </svg>
                        <div class="d-flex align-items-center">
                           <small>
                           &nbsp;Max contacts limit is {{ $size_limit }} per campaign
                           </small>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="show-features"></div>
               <div class="col-sm-10" id="recipientsdata">
               </div>
            </div>
         </div>
      </section>
      <!-- List end -->
      <!-- Sender Start -->
      <section class="app__sender mb-3">
         <div class="card rounded-0 border-0 shadow-sm">
            <div class="card-body">
               <div class="d-flex align-items-center">
                  <div>
                     <h5 class="card-title fw-bold mb-0">
                        Step 3: Sender Email
                     </h5>
                  </div>
                  <div class="ms-auto">
                     <a href="{{ route('settings') }}" type="button"
                        class="btn btn-green shadow-sm fw-500 fw-semibold rounded-1">
                     <i class="bi bi-plus-lg"></i> Add Email Account
                     </a>
                  </div>
               </div>
               <div class="mt-3">
                  <?php $fromEmail = ($default_email) ? $default_email->email :'';
                     ?>
                  <input type ="hidden" name="from_email" id="from_email" value="{{ $fromEmail }}">
                  @if(count(emailCollections()) >0)
                  @foreach(emailCollections() as $conEmail)
                  <input type="radio" class="btn-check" name="connect-email" id="connect-email"  value="{{ $conEmail->email }}"  autocomplete="off" {{ ($fromEmail == $conEmail->email) ? 'checked' :'' }}>
                  <label class="btn bg-white border border-warning shadow-sm rounded-0 text-start me-3 radio-alert {{ ($fromEmail == $conEmail->email) ? 'radio-active' :'' }}"  data-email="{{ $conEmail->email }}"  data-atype="{{ $conEmail->account_type }}" for="option1">
                     <div class="hstack mb-2">
                        <div class="me-3">
                           @if($conEmail->account_type=='2')
                           <svg width="50" height="50">
                              <rect x="0" y="0" width="42" height="42" fill="#F25022" />
                              <rect x="0" y="25" width="42" height="42" fill="#00A4EF" />
                              <rect x="25" y="0" width="42" height="42" fill="#7FBA00" />
                              <rect x="25" y="25" width="42" height="42" fill="#FFB900" />
                           </svg>
                           @else
                           <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="3rem" class="LgbsSe-Bz112c">
                              <g>
                                 <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                                 </path>
                                 <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                                 </path>
                                 <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z">
                                 </path>
                                 <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                                 </path>
                                 <path fill="none" d="M0 0h48v48H0z"></path>
                              </g>
                           </svg>
                           @endif
                        </div>
                        <div class="">
                           <p class="mb-0 text-muted fw-500"><small>{{ $conEmail->name }}</small>
                           </p>
                           <h6 class="mb-0 fw-bold">
                              <small>{{ $conEmail->email }}</small><br>
                              <small>Daily Limit : {{ $conEmail->daily_limit }}  Reset :24hrs</small><br>
                           </h6>
                        </div>
                     </div>
                  </label>
                  @endforeach
                  @endif
               </div>
               <br>
               <!-- <div class="row">
                     <div class="col-md-4">
                           
                           <label class="switch me-3">
                             <input type="checkbox"  name="same_thread" id="same_thread"   >
                             <span class="switch-slider round"></span>
                           </label><small>
                                    &nbsp;Send followup email in the same thread  
                                    </small>
                     </div>

                    
                      <div class="col-md-4">
                           <label class="switch me-3">
                             <input type="checkbox"  name="open_tracking" id="open_tracking" checked  disabled >
                             <span class="switch-slider round"></span>
                           </label><small>
                                    &nbsp;Open tracking
                                    </small>
                     </div>
                     <div class="col-md-4">
                           <label class="switch me-3">
                             <input type="checkbox" name="link_tracking" id="link_tracking" checked disabled>
                             <span class="switch-slider round"></span>
                           </label><small>
                                    &nbsp;Link tracking
                                    </small>
                     </div>
                     
                  </div> -->
            </div>
         </div>
     
      </section>
        

      </section>
      <!-- Sender End -->
      <section class="mb-3">
         <div class="card rounded-0 border-0 shadow-sm">
            <div class="card-body">
               <div class="hstack border-bottom gap-3 mb-3 pb-3">
                  <div>
                     <h4 class="fw-bold mb-0">
                        Step 4: Define Mailing Templates & Sequence
                     </h4>
                  </div>
               </div>
               <!-- Stage 1 start -->
               <input type="hidden" name="stage-count" id="stage-count" class="stage-count" value="1">
               <input type="hidden" name="totalSum" id="totalSum" class="totalSum" value="0">
               <input type="hidden" name="customtype" id="customtype" class="customtype" value="">
               <input type="hidden" name="account_type" id="account_type" class="account_type"
                  value="{{ ($default_email) ? $default_email->account_type :'' }}">
               <input type="hidden" name="btn_type" id="btn_type" class="btn_type">
               <input type="hidden" name="credit_deduct" id="credit_deduct" class="credit_deduct">
               <input type="hidden" name="features" id="features" class="features">
               <input type="hidden" name="is_feature" id="is_feature" class="is_feature">
               <input type="hidden" name="domain_authority" id="domain_authority" class="domain_authority">
               <input type="hidden" name="semrus_traffic" id="semrus_traffic" class="semrus_traffic">
               <input type="hidden" id="follow-id">
               <section class="app__stage_one mb-3">
                  <div class="card rounded-0 border-0 shadow-sm">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-8">
                              <div class="d-flex align-items-center mb-4">
                                 <div>
                                    <h5 class="card-title fw-bold mb-0 template-title">
                                       Outreach Template
                                    </h5>
                                 </div>
                                 <div class="ms-auto">
                                    <button type="button" class="btn btn-outline-dark shadow-sm fw-500 fw-semibold rounded-1"
                                       onClick="showTemplateList(1)" id="open-template-1">
                                    <i class="bi bi-plus-lg"></i> Insert Template
                                    </button>
                                 </div>
                              </div>
                              <div>
                                 <div class="mb-3 aa">
                                    <textarea class="form-control rounded-1 shadow-none form-control-sm  subject"
                                       name="subject[]" data-rid="1" id="subject-1" rows="2"
                                       autocomplete="off">{!! (!empty($default_template)) ? $default_template->subject :'' !!}</textarea>
                                 </div>
                                 <div class="mb-3">
                                    <textarea id="summernote-1" name="message[]" class="form-control message summernote"
                                       data-rid="1">{!! (!empty($default_template)) ? $default_template->body :'' !!}</textarea>
                                    <input type="text" value="Note: Unsubscribe option will be auto appended to all your outbound e-mails" class="form-control" readonly>
                                    <input type="hidden" name="temp_id[]" id="tid-1" class="tid">
                                    <input type="hidden" name="fallback_text" class="fallback_text">
                                    <input type="hidden" name="is_followup[]" value="0" id="is_followup-1" class="is_followup">
                                    <input type="hidden" name="followup_cond[]" value="3" id="followup_cond-1">
                                 </div>
                              </div>
                              <div class="hstack gap-3 mt-3">
                                 <input type="hidden" name="fallback_text"  class="fallback_text"> 
                                 <div class="input-group" >
                                    <label class="input-group-text fw-600" for="from_email">From</label>
                                    <select class="form-select form-select-sm shadow-none accountEmails" id="from" name="from">
                                       <option value="">Select</option>
                                       @foreach(emailCollections() as $email)
                                       <option value="{{ $email->email}}" selected>{{ $email->email}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                                 <div class="input-group">
                                    <span class="input-group-text" id="to">To</span>
                                    <input class="form-control form-control-sm me-auto shadow-none test-mail " type="text"
                                       placeholder="Send test mail" name="test-mail[]" id="test-mail-1"
                                       value="{{ auth()->user()->email }}">
                                 </div>
                                 <button class="btn btn-green btn-sm text-nowrap shadow-sm fw-500 d-none rounded-1" type="button"
                                    disabled id="wait-test-btn-1">
                                 <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                 <span class="">Sending...</span>
                                 </button>
                                 <button type="button" class="btn btn-green text-nowrap shadow-sm btn-sm SendMailCamp rounded-1"
                                    onClick="SendMailCamp(1)" data-rNum="1" id="send-mail-1">Send test email</button>
                              </div>
                           </div>
                           <div class="col-md-4" id="customTag-1">
                              <div class="card rounded-0 shadow-sm border-0 h-100">
                                 <div class="card-header fw-500 rounded-0">
                                    Personalization tags
                                 </div>
                                 <div class="card-body">
                                    <p class="leads">
                                       <i class="bi bi-info-circle-fill text-info"></i> Improve your open and reply rates with
                                       email personalization
                                    </p>
                                    <p class="leads">
                                       <i class="bi bi-info-circle-fill text-info"></i> Please select or import a list for using
                                       personalized tags in your outreach and follow-up emails
                                    </p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </section>
               <!-- Stage 1 end -->
               <!-- Autofollowup templates start -->
               <section class="mb-3 cloneDiv newClone-1" id="cloneDiv">
                  <div class="blockNumbers">
                  </div>
               </section>
               <!-- Autofollowup templates end -->
               <!-- Autofollowup start -->
               <section class="app__autofollowup">
                  <div class="card rounded-0 border-0 shadow-sm">
                     <div class="card-body">
                        <div class="d-flex align-items-center">
                           <div>
                              <h5 class="card-title fw-bold mb-0">
                                 Auto Followup Template
                              </h5>
                           </div>
                           <div class="ms-auto">
                              <button class="btn btn-green addMorenumber fw-500 shadow-sm" data-toggle="tooltip" type="button"
                                 data-placement="top" title="Add more Followup"><i class="bi bi-plus-lg"></i> Add</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </section>
               <!-- Autofollowup end -->
            </div>
         </div>
      </section>
      <!-- Schedule start -->
      <section class="app__schedule mb-3 " >
         <div class="card rounded-0 border-0 shadow-sm">
            <div class="card-body schedule-div" id="schedule-div">
               <h5 class="card-title fw-bold">
                  Step 5: Set Sending Schedule
               </h5>
               <div class="row mt-5">
                  <div class="col-md-12 mb-4">
                     <div class="row">
                        <div class="col-md-4">
                           <label class="form-label mb-1 fw-500">
                           <small>
                           Start Date
                           </small>
                           </label>
                           <input type="date" class="form-control shadow-none form-control-lg datePicker rounded-0"
                              value="{{ date('Y-m-d') }}" name="start_date" id="start-date" required>
                        </div>
                        <div class="col-md-4">
                           <label class="form-label mb-1 fw-500">
                           <small>
                           Timezone
                           </small>
                           </label>
                           <select class="form-control form-control-lg shadow-none selectTimeZone rounded-0"
                              name="timezone" id="timezone">
                           @foreach(generate_timezone_list() as $key =>$timezone)
                           <option value="{{ $key  }}" {{ ($key=='America/New_York' ) ? 'selected' : '' }}>
                           {{ $timezone }}
                           </option>
                           @endforeach
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 mb-4">
                     <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-4 timesetting">
                           <label class="form-label mb-1 fw-500">
                           <small>
                           Cooling Period 
                           </small>
                           </label>
                           <select class="form-select shadow-none rounded-0" name="cooling_period" id="cooling_period" style="height: 42px;">
                              <option value="24" selected>24 Hours</option>
                              <option value="36">36 Hours</option>
                              <option value="48">48 Hours</option>
                              <option value="6">6 Hours</option>
                              <option value="3">3 Hours</option>
                           </select>
                           {{-- <input type="number" min="24" max="48" value="24"
                              class="form-control shadow-none form-control-lg rounded-0" name="cooling_period"
                              id="cooling_period" required> --}}
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
            <div class="card rounded-0 border-0 d-none" id="schedule-note-div">
               <div class="card-body text-center">
                  <img src="{{asset('images/schedule.png')}}" class="mb-3 mx-auto" height="360" alt="...">
                  <div class="alert alert-primary d-flex align-items-center rounded-0 py-3 mb-0" role="alert">
                     <small><span class="fw-bold">Note: </span> It will take some time to process the data as per your selection and hence, campaign scheduling options will be available only after data processing gets completed. You will be notified via email regarding the same.</small>
                  </div>
               </div>
            </div>
            <div class="card-footer text-muted border-0 d-grid gap-2 d-md-flex justify-content-md-end">
               <button class="btn btn-success rounded-0 px-3 fw-500 d-none" type="button" id="wait-btn" disabled>
               <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
               <span>Wait</span>
               </button>
               <button class="btn btn-outline-dark rounded-0 px-3 fw-500 shadow-sm shadow-none" type="button"
                  id="draft-btn" name="Draft">Save as Draft</button>
               <button class="btn btn-success rounded-0 px-3 fw-500 shadow-sm shadow-none" type="button"
                  id="preview-btn" name="preview">Preview</button>
            </div>
         </div>
      </section>
      <!-- Schedule end -->
      {{-- Schedule section end --}}
   </div>
</form>
<!-- Preview Campaign -->
<div id="preview-campaign" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
   aria-hidden="true" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="text-center">
         <div class="spinner4">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
         </div>
      </div>
      <div class="modal-content shadow" style="border-radius: 0.75rem!important;" id="preview-campaign-section">
      </div>
   </div>
</div>
{{-- No list yet --}}
<div class="modal fade" tabindex="-1" role="dialog" id="not-list-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow border-0 bg-light"
         style="border-radius: 0.75rem!important;">
         <div class="modal-body p-4 pt-2 pb-2 position-relative">
            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="d-flex justify-content-center">
               <i class="bi bi-lock-fill fs-1 text-red"></i>
            </div>
            <p class="fw-semibold mb-3 mt-3 lh-base text-center" >
               You're yet to create any lists
            </p>
         </div>
         <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
            <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red fw-600" data-bs-dismiss="modal">Will do later</button>
            <a href="{{ route('lists') }}" type="button" class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover fw-600">Create List</a>
         </div>
      </div>
   </div>
</div>
{{-- No campaign yet --}}
<div class="modal fade" tabindex="-1" role="dialog" id="not-campiagn-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow border-0 bg-light"
         style="border-radius: 0.75rem!important;">
         <div class="modal-body p-4 pt-2 pb-2 position-relative">
            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="d-flex justify-content-center">
               <i class="bi bi-lock-fill fs-1 text-red"></i>
            </div>
            <p class="fw-semibold mb-3 mt-3 lh-base text-center" >
               You're yet to create any email campaigns.
            </p>
         </div>
         <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
            <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red fw-600" data-bs-dismiss="modal">Will do later</button>
            <a href="{{ route('schedulecampaign') }}" type="button" class="btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover fw-600">Create Campiagn</a>
         </div>
      </div>
   </div>
</div>
<!--  credit model  -->
<div id="credit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
   data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="text-center">
         <div class="spinner4">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
         </div>
      </div>
      <div class="modal-content shadow" style="border-radius: 0.75rem!important;" id="credit-section">
      </div>
   </div>
</div>
{{-- Modal for fallback text --}}
<div class="modal fade" tabindex="-1" role="dialog" id="fallback-modal">
   <div class="modal-dialog  modal-md" role="document">
      <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0">
            <h3 class="fw-bold mb-0">Merge tag</h3>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-3 pt-0">
            <div class="mt-4" id="add-list-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="mobile" class="form-label">Recipient field <span
                           class="requiredLabel"></span></label>
                        {!! Form::text('recipient_feild','',array('id'=>'recipient_feild','class'=>
                        $errors->has('recipient_feild') ? 'form-control is-invalid state-invalid' : 'form-control',
                        'placeholder'=>'Recipient field ', 'autocomplete'=>'off','readonly'=>'readonly')) !!}
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="mobile" class="form-label">Fallback text <span
                           class="requiredLabel">*</span></label>
                        {!! Form::text('fallback_txt','',array('id'=>'fallback_txt','class'=>
                        $errors->has('fallback_txt') ? 'form-control is-invalid state-invalid' : 'form-control',
                        'placeholder'=>'', 'autocomplete'=>'off')) !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            {!! Form::button('Save', array('class'=>'btn btn-primary saveFallbackTxt')) !!}
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
         </div>
      </div>
   </div>
</div>
{{-- Modal for fallBack --}}
{{-- toast start --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
   <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive"
      aria-atomic="true">
      <div class="d-flex">
         <div class="toast-body toast-msg"></div>
         <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
            aria-label="Close"></button>
      </div>
   </div>
</div>
@php
if(account_email() == NULL) {
echo "<script>
   window.onload = function(){ $('#email_connect_model').modal('show'); } 
</script>";
}
@endphp
@endsection
@section('extrajs')
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
   integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
{!! Html::script('js/schedulecampaign.js') !!}
{!! Html::script('js/jquery.validate.js') !!}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> -->
<script>
   function formatSelection(val) {
          return val.id;
        }
   
        $(function() {
            $('.selectTimeZone').select2({
               placeholder: "Select Timezone",
              width: '100%'
            });
           
            
        
        });
     $(document).ready(loadList);
   
   

      // intialize tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      })
   
      // intialize toast
      var toastLiveExample = document.getElementById('liveToast');
   
      // intialize popover
      var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
         return new bootstrap.Popover(popoverTriggerEl)
      })
     $(document).ready(function(){
   
   
   
      document.querySelector(".subject").spellcheck = false;
      document.querySelector(".message").spellcheck = false;
   
          $(".subject").summernote(
              {
                 toolbar: [],
                  
                  placeholder: `Subject`,
                  //followingToolbar: false   ,
                 // focus: true,
                 minHeight: 0,
               //   height:'46px',
                 maxHeight: null,
                  disableResizeEditor: true ,
                  spellcheck: false ,
                  callbacks: {
                    onKeyup: function (contents, $editable) {
                       var rid = $(this).data('rid');
                        //var subject = $('#subject-'rid).summernote('code');
                        $(".element").addClass('elementSub');
                        $(".element").removeClass('elementMsg');
   
                        $(".resetBt").addClass('resetBtsub');
                        $(".resetBt").removeClass('resetBtnmsg');
                        var is_same_thread = $('#is_same_thread').val();
                        var subject = $('#subject-1').summernote('code');
                        if(is_same_thread== 1){

                           //$('.followup-subject').summernote('code',subject);
                           //$('.followup-subject').next().find(".note-editable").attr("contenteditable", false);

                        } else{
                          // $('.followup-subject').next().find(".note-editable").attr("contenteditable", false);
                        }

                    }
   
                  }
              }
          );
           $("textarea.message").summernote(
              {
                  toolbar: [ ["style", ["style"]], ["font", ["bold", "italic", "underline", "fontname", "clear"]], ["color", ["color"]], ["paragraph", ["ul", "ol", "paragraph"]], ["table", ["table"]], ["insert", ["link", "picture"]], ["view", ["codeview"]], ],
                  placeholder: `Hi there,<br><br>I saw your site and liked the content. I am keen to explore advertising opportunities with you.<br>Could I know your charges for publishing a guest post on . We pay via PayPal.<br><br>Looking forward to hearing from you. `,
                  tabsize: 2,
                  height:300,
                  spellcheck: false,
                  callbacks: {
                    onKeyup: function (contents, $editable) {
                       var rid = $(this).data('rid');
                        $(".element").removeClass('elementSub');
                        $(".element").addClass('elementMsg');
                        $(".resetBt").removeClass('resetBtsub');
                        $(".resetBt").addClass('resetBtnmsg');
                        
                        
                    }
                  }
              }
          );
   
      });
        
      $("#sidebarMenu").niceScroll(); 
    
</script>
@endsection
