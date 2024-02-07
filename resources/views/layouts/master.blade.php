<!DOCTYPE html>
<html lang="en">

<head>
   @include('sections.head')
   <script type="text/javascript">
      var appurl = '{{url("/")}}/';
      var usercredit ='{{ auth()->user()->credits }}';
      var userplan ='{{ auth()->user()->plan }}';
   </script>
   @yield('extracss')
</head>
<body class="bg-light">
   <div class="loader-fullscreen">
      <div class="inner-loader">
         <div class="progressbar">
            <span class="meter" style="width: 0%;"></span>
         </div>
      </div>
      <div id="form-submit-loading">
         <div class="close-loader">
            <i class="fa fa-times"></i>
         </div>
      </div>
   </div>

   @include('sections.navbar')
   <div class="container-fluid">
      <div class="row">
         @include('sections.sidebar')
         <main class="col-md-9 ms-sm-auto col-lg-10">
            @include('sections.message')
            @yield('content')

            @include('sections.new-footer')
         </main>
      </div>
   </div>
   <!--  {{-- toast start --}}
      <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
         <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
               <div class="toast-body toast-msg"></div>
               <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
         </div>
      </div> -->

   <!-- Purchase credit model  -->
   <div id="purchase-credit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg" role="document">
         <div class="text-center">
            <div class="spinner4">
               <div class="bounce1"></div>
               <div class="bounce2"></div>
               <div class="bounce3"></div>
            </div>
         </div>
         <div class="modal-content" id="purchase-credit-section">

         </div>
      </div>
   </div>

   {{-- Create email modal start --}}
   <div class="modal fade" id="mail-modal" tabindex="-1">
      <div class="modal-dialog modal-fullscreen">
         <div class="modal-content">
            <div class="modal-header py-2">
               <h5 class="modal-title fw-bold" id="modal-title"></h5>
               <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-8">
                     <div class="row mb-3">
                        <div class="col-sm-6">
                           <input type="hidden" name="fallback_text" class="fallback_text">

                           <div class="input-group">
                              <label class="input-group-text" for="from_email">From</label>
                              <select class="form-select shadow-none" id="from_email" name="from_email">
                                 @foreach(emailCollections() as $email)
                                 <option value="{{ $email->email }}" {{($email->is_default == '1') ? 'selected' :''}}>{{ $email->name }} {{'<'}}{{
                                    $email->email }}{{'>'}}</option>
                                 @endforeach
                              </select>
                           </div>

                           
                        </div>
                        <div class="col-sm-6">

                           <div class="input-group">
                              <label class="input-group-text" for="site-email">To</label>
                              <select class="form-select shadow-none" id="site-email">
                                 <option value="" selected>Select Email</option>
                              </select>
                           </div>

                          
                        </div>

                     </div>
                     <div class="row mb-3">
                        <div class="col-sm-9">
                           <div class="form-floating mb-3">
                              <textarea class="form-control rounded-4 shadow-none form-control-sm subject"
                                 placeholder="name@example.com" autocomplete="off" id="subject" name="subject"
                                 data-rid="1" id="subject"
                                 rows="2">{!! (!empty(defaultTemplate())) ? defaultTemplate()->subject :'' !!}</textarea>
                           </div>
                        </div>
                        <div class="col-sm-3">
                           <div class="d-grid gap-2">
                              <button type="button" class="btn btn-outline-dark btn-sm fw-500 shadow-none" onclick="openTemplate()"
                                 style="padding: 11px 0;">
                                 <i class="bi bi-plus-lg"></i> Insert Template</button>
                           </div>
                        </div>
                     </div>


                     <textarea id="summernote" name="htmlckeditor" class="form-control message summernote"
                        data-rid="1">{{ (!empty(defaultTemplate())) ? defaultTemplate()->body :'' }}</textarea>

                     <div class="hstack gap-3 mt-3">
                        <input class="form-control form-control-sm me-auto shadow-none" type="text"
                           placeholder="Send test mail" id="test-email" value="{{Auth::user()->email}}">
                        <button class="btn btn-primary btn-sm text-nowrap shadow-sm fw-500 d-none" type="button"
                           disabled id="wait-test-btn">
                           <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                           <span class="">Sending...</span>
                        </button>
                        <button type="button" class="btn btn-green btn-sm text-nowrap shadow-sm fw-500"
                           id="test-btn">Send test mail</button>
                     </div>
                  </div>
                  <div class="col-sm-4 customTag" id="customTag-1">
                     <div class="card shadow-sm mb-3 rounded-0 border-0 h-100">
                        <div class="card-header fw-500">
                           Personalization tags
                        </div>
                        <div class="card-body">
                           <div class=" alert-info p-2 shadow-sm mb-3">
                              <p class="mb-0"><i class="bi bi-info-circle-fill"></i> <small>Use the merge tags to personalize
                                 your campaigns and avoid spam filters:</small></p>
                           </div>

                           <div class="row mb-3">
                              <div class="col-sm-8">
                                 <div class=" alert-light border-dark py-1 mb-0 rounded-0 ">
                                    <span class="fw-500"><small>Name</small></span>
                                 </div>
                              </div>
                              <div class="col-sm-4">

                                 <button type="button"
                                    class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg fallback-Name"
                                    data-pid="1" id="custom-ele-1" data-customval="Name"><i
                                       class="bi bi-plus-lg"></i></button>
                                 <button type="button"
                                    class="btn btn-outline-success btn-sm ms-auto fw-500 addFallbackAll" data-pid="1"
                                    id="custom-ele-1" data-customval="Name"><i class="bi bi-pencil"></i></button>
                              </div>
                           </div>

                           <div class="row mb-3">
                              <div class="col-sm-8">
                                 <div class=" alert-light border-dark py-1 mb-0 rounded-0">
                                    <span class="fw-500"><small>Website</small></span>
                                 </div>
                              </div>
                              <div class="col-sm-4">

                                 <button type="button"
                                    class="btn btn-outline-success btn-sm ms-auto fw-500 element  elementMsg fallback-Website"
                                    data-pid="1" id="custom-ele-1" data-customval="Website"><i
                                       class="bi bi-plus-lg"></i></button>
                                 <button type="button"
                                    class="btn btn-outline-success btn-sm ms-auto fw-500 addFallbackAll" data-pid="1"
                                    id="custom-ele-1" data-customval="Website"><i class="bi bi-pencil"></i></button>
                              </div>
                           </div>

                           <!-- <div class="row mb-3">
                                 <div class="col-sm-8">
                                    <div class=" alert-light border-dark py-1 mb-0 rounded-0" >
                                       <span class="fw-500"><small>Title</small></span>
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                   
                                    <button type="button" class="btn btn-outline-success btn-sm fw-500  element elementMsg fallback-Title" data-pid="1"  id="custom-ele-1" data-customval="Title" ><i class="bi bi-plus-lg"></i></button>
                                    <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 addFallbackAll"  data-pid="1"  id="custom-ele-1"  data-customval="Title"><i class="bi bi-pencil"></i></button>
                                 </div>
                              </div> -->
                        </div>
                        <!--  <div class="card-footer text-muted">
                              <button type="button" class="btn btn-outline-dark btn-sm fw-500" onClick="unSubscribeTag(1)" id="unsubscribe-tag-1" data-pid="1">
                                 <i class="bi bi-plus-lg"></i> Use Unsubscribe tag</button>
                           </div> -->
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer py-2" id="mail-action-section"></div>
         </div>
      </div>
   </div>
   {{-- Create email modal end --}}


   {{-- Modal for fallback text --}}
   <div class="modal fade" tabindex="-1" role="dialog" id="fallback-modal-all">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
         <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">

            <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
               <h4 class="fw-bold mb-0 mx-auto">
                  Merge tag
               </h4>
   
               <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
                  data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3 pt-0">
               <div class="mt-4" id="add-list-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group mb-3">
                           <label for="mobile" class="form-label">Recipient field <span
                                 class="requiredLabel"></span></label>
                           {!! Form::text('recipient_feild','',array('id'=>'recipient_feild_all','class'=>
                           $errors->has('recipient_feild') ? 'form-control is-invalid state-invalid' : 'form-control shadow-none',
                           'placeholder'=>'Recipient field ', 'autocomplete'=>'off','readonly'=>'readonly')) !!}

                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="mobile" class="form-label">Fallback text <span
                                 class="requiredLabel">*</span></label>
                           {!! Form::text('fallback_txt','',array('id'=>'fallback_txt_all','class'=>
                           $errors->has('fallback_txt') ? 'form-control is-invalid state-invalid' : 'form-control shadow-none',
                           'placeholder'=>'', 'autocomplete'=>'off')) !!}

                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
               <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-red" data-bs-dismiss="modal">Cancel</button>
               {!! Form::button('Save', array('class'=>'btn btn-lg btn-green fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover saveFallbackTxtAll')) !!}
            </div>

         </div>
      </div>
   </div>
   {{-- Modal for fallBack --}}


    {{-- Insert template modal start --}}
   <div class="modal fade " tabindex="-1" role="dialog" id="insert-template">
      <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
         <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
            <div class="modal-header p-4 pb-2 border-bottom-0">
               <h3 class="fw-bold mb-0">Insert Template</h3>
               <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 pt-3">
               <div class="row">
                  <div class="col-sm-3">
                     <h6 class="pb-2 mb-2 border-bottom">
                        <i class="bi bi-folder2-open"></i> Groups
                     </h6>
                     <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white">
                        <div class="list-group list-group-flush border-bottom scrollarea">
                           @foreach(group() as $group)
                           <div
                           class="list-group-item list-group-item-action py-2 lh-tight" role="button" tabindex="0" onclick="templateGroups('{{$group->name}}')">
                              <div class="d-flex w-100 align-items-center justify-content-between">
                                 <h6 class="mb-0"><i class="bi bi-folder2"></i> {{$group->name}}</h6>
                                 <small><span class="badge bg-theme text-white">{{$group->total}}</span></small>
                              </div>
                           </div>
                           @endforeach
                        </div>
                     </div>
                  </div>
                  <div class="col-md-9">
                     @if(count(template())>0)
                     <div class="hstack pb-2 mb-4 border-bottom">
                        <div>
                           <h6 class="mb-0" id="">
                              All Templates
                           </h6>
                        </div>
                     </div>
                     <div id="group-template">
                        @foreach(template() as $template)
                     <div class="row">
                        <div class="col-md-9">
                           <h6 class="mb-0">
                              {!! $template->subject !!} 
                           </h6>
                            <!-- <div class="form-check form-check-inline mt-2">
                              

                              <label class="form-check-label set_as_default" for="{{$template->id}}"><input class="form-check-input" type="checkbox" name="set_as_default" onclick="setDefault('{{$template->id}}')" value="{{$template->id}}" {{ (auth()->user()->default_tid == $template->id ) ?'checked' :'' }}>
                             Set as default</label> 
                           </div>-->
                        </div>
                        <div class="col-md-3 text-center">
                           <ul class="list-inline mt-2 mb-0">
                              
                              <li class="list-inline-item">
                                 @if((auth()->user()->default_tid == $template->id ))
                                  <i class="bi bi-star-fill  text-warning" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Default" ></i>
                                  @endif
                                 <span class="badge bg-success rounded-0 px-3 fw-500 text-white" role="button" tabindex="0" onclick="insertTemplate('{{$template->id}}')">Select</span>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <hr>  
                     @endforeach
                     </div>
                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   {{-- Insert template modal end --}}
   <!-- Insert Temp model -->
  


   {{-- Modal for balance low alert start --}}
<div class="modal modal-alert fade" tabindex="-1" role="dialog" id="balance-low-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable " role="document">
      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">
               You're low on credits
            </h4>

            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2 pb-2 position-relative">
            <p class="fw-semibold mb-3 mt-3 lh-base fw-500 title_txt" id="title_txt">
               You don't have enough credits to reveal this website
            </p>

            <div class="d-flex mb-3">
               <div class="border border-2 border-secondary py-2 w-100 text-center">
                  <p class="fw-semibold mb-0">
                     Credit required: <span class="fs-6 fw-bold mb-0 text-success fw-bold" id="credit-cost"></span>
                  </p>
               </div>
               <div class="border border-2 border-secondary py-2 w-100 text-center">
                  <p class="fw-semibold mb-0">
                     Your Balance: <span class="fs-6 mb fw-bold text-danger fw-bold">{{Auth::user()->credits}}</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
            <a href="javascript:void(0)" type="button"
               class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-green-hover fw-600"
               onclick="buyCredits()">Buy Credits</a>
            <a href="{{ route('pricing') }}" type="button"
               class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover fw-600">Upgrade
               Plan</a>
         </div>
      </div>
   </div>
</div>
{{-- Modal for balance low alert end --}}

{{-- pen account type --}}
<div class="modal fade" tabindex="-1" role="dialog" id="connect-email-modal">
   <div class="modal-dialog  modal-lg" role="document">
      <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0">
            <h3 class="fw-bold mb-0">Choose your email provider</h3>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>

         <div class="modal-body p-3 pt-0">
            <div class="mt-4" id="add-list-body">
               <div class="row mb-3">
                  <div class="col-sm-12">
                     <div class="mt-3">
                        <a class="navbar-brand fw-bold fs-4 me-5 pb-0" href="javascript:;" onclick="googleLogin();">
                                    <img src="{{asset('images/google.png')}}" class="img-fluid" width="180" alt="Reachomation"
                                       loading="lazy">
                                 </a>
                           <input type="radio" class="btn-check" name="account_type" id="account_type" value="outlook"
                              autocomplete="off">
                           <label
                              class="btn bg-white border  shadow-sm rounded-0 text-start me-3  accountOption"
                              for="option1" data-type="outlook" id="redirectToAccount">
                              <div class="hstack mb-2">
                                 <div class="me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#00A4EF" class="bi bi-microsoft" viewBox="0 0 16 16">
                              <path d="M7.462 0H0v7.19h7.462V0zM16 0H8.538v7.19H16V0zM7.462 8.211H0V16h7.462V8.211zm8.538 0H8.538V16H16V8.211z"/>
                            </svg>

                                 </div>
                                 <div class="">
                                    <p class="mb-0 text-muted fw-500"><small>Sign In With Microsoft</small>
                                    </p>
                                   <!--  <h6 class="mb-0 fw-bold">
                                       <small>Outlook</small>
                                    </h6> -->
                                 </div>
                              </div>
                           </label> 

                     </div>
                    <!--  <div class="mt-3">
                        <input type="hidden" name="accounttype" id="accounttype">
                        <input type="radio" class="btn-check" name="account_type" id="account_type" value="gmail"
                           autocomplete="off">
                        <label
                           class="btn bg-white border border-warning shadow-sm rounded-0 text-start me-3 radio-alert accountOption"
                           data-type="gmail" for="option1">
                           <div class="hstack mb-2">
                              <div class="me-3">
                                 <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"
                                    width="3rem" class="LgbsSe-Bz112c">
                                    <g>
                                       <path fill="#EA4335"
                                          d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                                       </path>
                                       <path fill="#4285F4"
                                          d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                                       </path>
                                       <path fill="#FBBC05"
                                          d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z">
                                       </path>
                                       <path fill="#34A853"
                                          d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                                       </path>
                                       <path fill="none" d="M0 0h48v48H0z"></path>
                                    </g>
                                 </svg>
                              </div>
                              <div class="">
                                 <p class="mb-0 text-muted fw-500"><small>Sign in with Google</small>
                                 </p>
                                 <h6 class="mb-0 fw-bold">
                                    <small>Gmail</small>
                                 </h6>
                              </div>
                           </div>
                        </label>

                       <input type="radio" class="btn-check" name="account_type" id="account_type" value="outlook"
                           autocomplete="off">
                        <label
                           class="btn bg-white border border-warning shadow-sm rounded-0 text-start me-3 radio-alert accountOption"
                           for="option1" data-type="outlook">
                           <div class="hstack mb-2">
                              <div class="me-3">
                                 <svg width="50" height="50">
                                    <rect x="0" y="0" width="42" height="42" fill="#F25022" />
                                    <rect x="0" y="25" width="42" height="42" fill="#00A4EF" />
                                    <rect x="25" y="0" width="42" height="42" fill="#7FBA00" />
                                    <rect x="25" y="25" width="42" height="42" fill="#FFB900" />
                                 </svg>

                              </div>
                              <div class="">
                                 <p class="mb-0 text-muted fw-500"><small>Microsoft Account</small>
                                 </p>
                                 <h6 class="mb-0 fw-bold">
                                    <small>Outlook</small>
                                 </h6>
                              </div>
                           </div>
                        </label> 
                     </div> -->
                  </div>

               </div>
              <!--  <div id="add-list-action">
                  <button class=" mb-2 btn btn-lg rounded-4 btn-primary" type="button" id="redirectToAccount"
                     disabled>Continue</button>
                  <button class="btn btn-success btn-lg rounded-4" onclick="beforeProceed()">Before Proceed</button>
               </div> -->
            </div>
         </div>
      </div>
   </div>
</div>

{{-- Modal for before-proceed-modal start --}}
<div class="modal fade" tabindex="-1" role="dialog" id="before-proceed-modal">
   <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable " role="document">
      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">
               Before you proceed... 
            </h4>

            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2 pb-2 position-relative">
            <p class="fw-semibold mb-3 mt-3 lh-base fw-500">
               As we've recently launched our services and our Gmail/Gsuite app integration is undergoing verification (and approval takes its own sweet time) and therefore, Google will highlight that <b>our app isn't verified yet</b>.
            </p>

            <p class="fw-semibold mb-3 mt-3 lh-base fw-500">
               Till our Gmail/Gsuite app status in <b>unverified</b>, we encourage our users to use a completely new email account for associating with Reachomation. However, using your regular email account for outreach purposes should work perfectly as well.
            </p>

            <p class="fw-semibold mb-3 mt-3 lh-base fw-500">
               If you proceed further with Gmail/Gsuite<br> <span class="mt-2">Select your Gmail/Gsuite account > click <b>Advanced Go to <a class="{{ url('/') }}">reachomation.com</a> (unsafe)</b></span>
            </p>

            <div class="d-flex">
               <div class="mb-3">
                  <input type="hidden" name="accounttype" id="accounttype">
                  <input type="radio" class="btn-check" name="account_type" id="account_type" value="gmail"
                     autocomplete="off">
                  <label
                     class="btn bg-white border border-warning shadow-sm rounded-0 text-start me-3 radio-alert accountOption"
                     data-type="gmail" for="option1">
                     <div class="hstack mb-2">
                        <div class="me-3">
                           <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"
                              width="3rem" class="LgbsSe-Bz112c">
                              <g>
                                 <path fill="#EA4335"
                                    d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                                 </path>
                                 <path fill="#4285F4"
                                    d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                                 </path>
                                 <path fill="#FBBC05"
                                    d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z">
                                 </path>
                                 <path fill="#34A853"
                                    d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                                 </path>
                                 <path fill="none" d="M0 0h48v48H0z"></path>
                              </g>
                           </svg>
                        </div>
                        <div class="">
                           <p class="mb-0 text-muted fw-500"><small>Google Account</small>
                           </p>
                           <h6 class="mb-0 fw-bold">
                              <small>Gmail</small>
                           </h6>
                        </div>
                     </div>
                  </label>

                  <input type="radio" class="btn-check" name="account_type" id="account_type" value="outlook"
                     autocomplete="off">
                  <label
                     class="btn bg-white border border-warning shadow-sm rounded-0 text-start me-3 radio-alert accountOption"
                     for="option1" data-type="outlook">
                     <div class="hstack mb-2">
                        <div class="me-3">
                           <svg width="50" height="50">
                              <rect x="0" y="0" width="42" height="42" fill="#F25022" />
                              <rect x="0" y="25" width="42" height="42" fill="#00A4EF" />
                              <rect x="25" y="0" width="42" height="42" fill="#7FBA00" />
                              <rect x="25" y="25" width="42" height="42" fill="#FFB900" />
                           </svg>

                        </div>
                        <div class="">
                           <p class="mb-0 text-muted fw-500"><small>Microsoft Account</small>
                           </p>
                           <h6 class="mb-0 fw-bold">
                              <small>Outlook</small>
                           </h6>
                        </div>
                     </div>
                  </label>
               </div>
               <div>
                  <button class="btn btn-white border-warning rounded-0 px-5 py-3 fs-4 fw-bold shadow-sm" type="submit">
                     Talk to support
                  </button>
               </div>
            </div>

         </div>

         <div class="modal-footer mx-auto w-100">
            <button class="btn btn-green rounded-4 px-5" type="submit">Submit</button>
         </div>
      </div>
   </div>
</div>
{{-- Modal for before-proceed-modal end --}}

{{-- Modal for ratings start --}}
<div class="modal fade" tabindex="-1" role="dialog" id="ratings-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">
               Ratings
            </h4>

            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2 pb-2">
            <div class="d-flex flex-column">
               <div class="mb-3">
                  <p class="d-flex align-items-center text-decoration-none mb-1">
                     <span class="badge bg-theme text-white me-2" style="font-weight: 600!important;font-size: 1rem!important;">P</span>
                     <span class="fw-500">Pallav Raj</span>
                  </p>
                  <div class="d-flex">
                     <div>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-half text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star text-warning" style="font-size: 14px;"></i>
                     </div>
                  </div>
                  <small>
                     Get Current Local News  Crime  Politics  Weather  Sports  Entertainment  Arts  Features  Obituaries  Real Estate And All Other Stories Relevant To Residents Of 100 Mile House  BC  Canada. Your News Source!
                  </small>
               </div>

               <div class="mb-3">
                  <p class="d-flex align-items-center text-decoration-none mb-1">
                     <span class="badge bg-theme text-white me-2" style="font-weight: 600!important;font-size: 1rem!important;">P</span>
                     <span class="fw-500">Pallav Raj</span>
                  </p>
                  <div class="d-flex">
                     <div>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-half text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star text-warning" style="font-size: 14px;"></i>
                     </div>
                  </div>
                  <small>
                     Get Current Local News  Crime  Politics  Weather  Sports  Entertainment  Arts  Features  Obituaries  Real Estate And All Other Stories Relevant To Residents Of 100 Mile House  BC  Canada. Your News Source!
                  </small>
               </div>

               <div class="mb-3">
                  <p class="d-flex align-items-center text-decoration-none mb-1">
                     <span class="badge bg-theme text-white me-2" style="font-weight: 600!important;font-size: 1rem!important;">P</span>
                     <span class="fw-500">Pallav Raj</span>
                  </p>
                  <div class="d-flex">
                     <div>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-half text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star text-warning" style="font-size: 14px;"></i>
                     </div>
                  </div>
                  <small>
                     Get Current Local News  Crime  Politics  Weather  Sports  Entertainment  Arts  Features  Obituaries  Real Estate And All Other Stories Relevant To Residents Of 100 Mile House  BC  Canada. Your News Source!
                  </small>
               </div>

               <div class="mb-3">
                  <p class="d-flex align-items-center text-decoration-none mb-1">
                     <span class="badge bg-theme text-white me-2" style="font-weight: 600!important;font-size: 1rem!important;">P</span>
                     <span class="fw-500">Pallav Raj</span>
                  </p>
                  <div class="d-flex">
                     <div>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-half text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star text-warning" style="font-size: 14px;"></i>
                     </div>
                  </div>
                  <small>
                     Get Current Local News  Crime  Politics  Weather  Sports  Entertainment  Arts  Features  Obituaries  Real Estate And All Other Stories Relevant To Residents Of 100 Mile House  BC  Canada. Your News Source!
                  </small>
               </div>

               <div class="mb-3">
                  <p class="d-flex align-items-center text-decoration-none mb-1">
                     <span class="badge bg-theme text-white me-2" style="font-weight: 600!important;font-size: 1rem!important;">P</span>
                     <span class="fw-500">Pallav Raj</span>
                  </p>
                  <div class="d-flex">
                     <div>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star-half text-warning" style="font-size: 14px;"></i>
                        <i class="bi bi-star text-warning" style="font-size: 14px;"></i>
                     </div>
                  </div>
                  <small>
                     Get Current Local News  Crime  Politics  Weather  Sports  Entertainment  Arts  Features  Obituaries  Real Estate And All Other Stories Relevant To Residents Of 100 Mile House  BC  Canada. Your News Source!
                  </small>
               </div>
            </div>
         </div>
         <div class="modal-footer w-100 bg-light">
            <div class="row">
               <div class="col-12">
                  <textarea class="form-control shadow-none w-100" rows="2" placeholder="Enter your comment or feedback"></textarea>
               </div>
               <div class="col-12">
                  <div class="d-flex align-items-center">
                     <div>
                        <p class="lh-base mt-2 fw-500">Rate this website:</p>
                     </div>
                     <div class="rate">
                        <input type="radio" id="star5" name="rate" value="5" />
                        <label for="star5" title="text">5 stars</label>
                        <input type="radio" id="star4" name="rate" value="4" />
                        <label for="star4" title="text">4 stars</label>
                        <input type="radio" id="star3" name="rate" value="3" />
                        <label for="star3" title="text">3 stars</label>
                        <input type="radio" id="star2" name="rate" value="2" />
                        <label for="star2" title="text">2 stars</label>
                        <input type="radio" id="star1" name="rate" value="1" />
                        <label for="star1" title="text">1 star</label>
                     </div>
                  </div>
               </div>
               <div class="col-12 text-center">
                  <button class="btn btn-green rounded-4 px-5" type="submit">Submit</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
{{-- Modal for ratings end --}}

{{-- Modal for report start --}}
<div class="modal fade" tabindex="-1" role="dialog" id="report-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">
               Report issues with site
            </h4>

            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2 pb-2">
            <div class="mt-2">
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="report-one">
                  <label class="form-check-label" for="report-one">
                     Site not working
                  </label>
               </div>
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="report-two">
                  <label class="form-check-label" for="report-two">
                     Wrong email
                  </label>
               </div>
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="report-three">
                  <label class="form-check-label" for="report-three">
                     Incorrect stats
                  </label>
               </div>
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="report-four">
                  <label class="form-check-label" for="report-four">
                     Wrong category
                  </label>
               </div>
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="report-five">
                  <label class="form-check-label" for="report-five" onclick="reportOther()">
                     Other
                  </label>
               </div>
               <textarea class="form-control shadow-none d-none" id="report-textbox" rows="3"></textarea>
            </div>
         </div>
         <div class="modal-footer mx-auto w-100">
            <button class="btn btn-green rounded-4 px-5" type="submit">Submit</button>
         </div>
      </div>
   </div>
</div>
{{-- Modal for report end --}}

{{-- toast end --}}{{-- Upload error modal start --}}
<div class="modal modal-alert fade" tabindex="-1" role="dialog" id="upload-error-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable " role="document">
      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">
               Upload Error
            </h4>

            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2 pb-2 position-relative">
            <p class="fw-semibold mb-3 mt-3 lh-base fw-500 text-red">
               No contacts found in CSV <a href="{{ url('downloadfile') }}" class="text-decoration-none"><i
                              class="bi bi-download"></i> sample.csv</a>
            </p>
            <hr>
            <p class="fw-semibold mb-3 mt-3 lh-base fw-500">
               Here are best practices
            </p>
            <ul>
               <li>Please use <span class="fw-500">CSV</span> file format</li>
               <li>First row will be treated as <span class="fw-500">merge tag</span></li>
               <li>File must have one column with <span class="fw-500">Email</span></li>
               <li>Have no more than <span class="fw-500">20 columns</span></li>
               <li>File size not more than <span class="fw-500">20 MB</span></li>
               
            </ul>
         </div>
         <div class="modal-footer mx-auto">
            <button class="btn btn-green rounded-4 px-5" type="button " data-bs-dismiss="modal" aria-label="Close">Okay, I Understand</button>
         </div>
      </div>
   </div>
</div>
{{-- Upload error modal end --}}
   
   {{-- toast end --}}



   @include('sections.footer_script')
   @notify_render

   @yield('extrajs')
   
   <script src="/js/views/layouts/master.js"></script>
</body>

</html>
