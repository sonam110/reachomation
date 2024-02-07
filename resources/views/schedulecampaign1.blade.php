<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="theme-color" content="#0f172a">
   <!-- Bootstrap CSS -->
   <link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet" >
   <!-- Bootstrap icons -->
   <link rel="stylesheet" href="/css/bootstrap-icons.css">
   <!-- Manual CSS -->
   <link rel="stylesheet" href="{{asset('css/custom.css')}}">
   <!-- favicon -->
   <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <!-- JQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <!-- Summernote css/js -->
   <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js" integrity="sha512-zMfrMAZYAlNClPKjN+JMuslK/B6sPM09BGvrWlW+cymmPmsUT1xJF3P4kxI3lOh9zypakSgWaTpY6vDJY/3Dig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="/js/ckeditor.js"></script>
   {!! Html::script('js/ckeditor/adapters/jquery.js') !!}
   {!! Html::script('js/ckeditor/ckeditor.js') !!}


   <title>Create new Campaign - Reachomation</title>
</head>

<body class="bg-light">
   @include('sections.navbar')

   <div class="container-fluid">
      <div class="row g-0">
         @include('sections.sidebar')
         <main class="col-md-9 ms-sm-auto col-lg-10">
            <div class="py-3">
               <div class="hstack border-bottom gap-3 mb-3 pb-3">
                  <div>
                     <h3 class="fw-bold mb-0">
                        Create Campaign
                     </h3>
                  </div>
               </div>
{{-- Recepients section start --}}
<div class="card my-3 shadow-sm">
   <div class="card-body pb-0">
      <div class="form-floating mb-3">
         <input type="text" class="form-control rounded-4 shadow-none" id="campaign-name" placeholder="name@example.com" autocomplete="off">
         <label for="campaign-name">Campaign Name*</label>
      </div>
      <hr>
      <div class="mb-3">
         <div class="hstack gap-3 mb-3">
            <h5 class="fw-bold">
               Whom are you targeting?
            </h5>
         </div>
         <input type="checkbox" class="btn-check" name="target" id="target" value="1" autocomplete="off">
         <label class="btn btn-outline-success btn-lg me-4 shadow-sm" for="target" style="font-size: 16px;">Blogs/Webmasters/Publications</label>

         <input type="radio" class="btn-check" name="target" id="option2" autocomplete="off" disabled>
         <label class="btn btn-outline-success btn-lg me-4 shadow-sm" for="option2" style="font-size: 16px;">Companies/Professionals 
            <span style="font-size: 14px;">
               <small>(Coming Soon)</small>
            </span>
         </label>
      </div>
      <hr>
      <div class="mb-3">
         <div class="hstack gap-3 mb-3">
            <h5 class="fw-bold">
               Recipients
            </h5>
         </div>
         <div class="row">
            <div class="col-sm-6 boder-end border-secondary">
               <label for="exampleFormControlInput1" class="form-label text-muted mb-3 fw-500">Select list from created lists</label>
               <select class="form-select form-select-lg shadow-sm shadow-none" id="created-list" style="font-size: 14px;height:50px;" onchange="loadList()">
                  <option value="" selected>Select</option>
                  @foreach ($collections as $collection)
                  <option value="{{$collection->id}}">{{ucfirst($collection->name)}}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-sm-6 border-start border-secondary">
               <div class="hstack gap-3 mb-3">
                  <div>
                     <label for="exampleFormControlInput1" class="form-label text-muted mb-0"
                        style="font-weight: 600;">Upload websites (file extension must be 
                        <span class="text-dark">.csv</span>)
                     </label>
                  </div>
                  <div class="ms-auto">
                     <a href="{{ url('downloadfile') }}" class="text-decoration-none"><i class="bi bi-download"></i> sample.csv</a>
                  </div>
               </div>
               <label class="label-file">Choose file
                  <input type="file" size="60" id="csv-file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
               </label>
            </div>

            <div class="col-sm-12 d-none" id="recipientsdata">
               <hr>
               <div class="hstack mb-2">
                  <div>
                     <h6 class="text-muted fw-500 mt-1">
                        List Name : 
                        <span class="text-dark" id="list-name"></span>
                     </h6>
                  </div>
                  <div class="ms-auto">
                     <span class="badge bg-primary rounded-0 shadow-sm p-2">Total Websites : 
                        <span id="domain-count"></span></span>
                  </div>
               </div>
               <div class="table-responsive" style="max-height: 350px; overflow: scroll;">
                  <table class="table table-hover table-bordered table-sm">
                     <thead class="thead-dark">
                        <tr>
                           <th scope="col">S. No.</th>
                           <th scope="col">Author</th>
                           <th scope="col">Website</th>
                           <th scope="col">Email</th>
                           <th scope="col">Tags</th>
                        </tr>
                     </thead>
                     <tbody id="domain-list">

                     </tbody>
                  </table>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>
{{-- Recepients section end --}}

{{-- From email section start --}}
<div class="card shadow-sm mb-3">
   <div class="card-body">
      <div class="row">
         <div class="col-sm-12">
            <div class="hstack gap-3">
               <div>
                  <h5 class="fw-bold mb-0">
                     From Email
                  </h5>
               </div>
               <div class="ms-auto">
                  <button type="button" class="btn btn-primary fw-500"><i class="bi bi-plus-lg"></i> Add Email Account</button>
               </div>
            </div>
            <div class="mt-3">
               

               <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off">
               <label class="btn bg-white border border-warning shadow-sm rounded-0 text-start me-3" for="option1">
                  <div class="hstack mb-2">
                     <div class="me-3">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="2rem" class="LgbsSe-Bz112c">
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
                     </div>
                     <div class="">
                        <p class="mb-0 text-muted fw-500"><small>Pallav Raj</small>
                        </p>
                        <h6 class="mb-0 fw-bold">
                           <small>pallav@prchitects.com</small>
                        </h6>
                     </div>
                  </div>
               </label>

               <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off">
               <label class="btn bg-white border border-warning shadow-sm rounded-0 text-start me-3" for="option1">
                  <div class="hstack mb-2">
                     <div class="me-3">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="2rem" class="LgbsSe-Bz112c">
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
                     </div>
                     <div class="">
                        <p class="mb-0 text-muted fw-500"><small>Pallav Raj</small>
                        </p>
                        <h6 class="mb-0 fw-bold">
                           <small>pallav@prchitects.com</small>
                        </h6>
                     </div>
                  </div>
               </label>
            </div>
         </div>
      </div>
   </div>
</div>
{{-- From email section end --}}

{{-- Outreach section start --}}
<div class="card shadow-sm mb-3">
   <div class="card-body">
      <div class="row">
         <div class="col-sm-8">
            <div class="hstack gap-3 mb-3">
               <div>
                  <h5 class="fw-bold">
                     Outreach Template
                  </h5>
               </div>
               <div class="ms-auto">
                  <button type="button" class="btn btn-outline-dark btn-sm fw-500" onClick="showTemplateList(1)" id="open-template-1">
                     <i class="bi bi-plus-lg"></i> Insert Template</button>
               </div>
            </div>
            <div class="form-floating mb-3">
               <textarea class="form-control rounded-4 shadow-none form-control-sm  subject"  name="subject[]" data-rid="1" id="subject-1" rows="2" autocomplete="off"></textarea>
               
            </div>
            <textarea id="summernote-1" name="message[]" class="form-control message summernote" data-rid="1" ></textarea>
            <input type="hidden" name="temp_id[]" id="tid-1">
            <input type="hidden"  name="is_followup[]" value="0"  id="is_followup-1">
            <div class="hstack gap-3 mt-3">
               <input class="form-control form-control-sm me-auto shadow-none" type="text" placeholder="Send test mail"  name="test-mail[]" id="test-mail-1" value="pallav@prchitects.com">
               <button type="button" class="btn btn-primary text-nowrap shadow-sm btn-sm">Send test mail</button>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="card shadow-sm h-100">
               <div class="card-header fw-500">
                  Personalization tags
               </div>
               <div class="card-body">
                  <div class=" alert-info p-2 shadow-sm" >
                     <p class="mb-0"><i class="bi bi-info-circle-fill"></i> Use the merge tags to personalize your campaigns and avoid spam filters:</p>
                  </div>

                  <div class="row mb-3">
                     <div class="col-sm-8">
                        <div class=" alert-light border-dark py-1 mb-0 rounded-0 " >
                           <span class="fw-500"><small>Author</small></span>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <button type="button" class="btn btn-outline-success btn-sm fw-500"><i class="bi bi-dash"></i></button>
                        <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element elementMsg"  data-pid="1" id="custom-ele-1"  data-customval="Author"><i class="bi bi-plus-lg"></i></button>
                     </div>
                  </div>

                  <div class="row mb-3">
                     <div class="col-sm-8">
                        <div class=" alert-light border-dark py-1 mb-0 rounded-0" >
                           <span class="fw-500"><small>Website</small></span>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <button type="button" class="btn btn-outline-success btn-sm fw-500"><i class="bi bi-dash"></i></button>
                        <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500 element  elementMsg"   data-pid="1"  id="custom-ele-1" data-customval="Website" ><i class="bi bi-plus-lg"></i></button>
                     </div>
                  </div>

                  <div class="row mb-3">
                     <div class="col-sm-8">
                        <div class=" alert-light border-dark py-1 mb-0 rounded-0" >
                           <span class="fw-500"><small>Title</small></span>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <button type="button" class="btn btn-outline-success btn-sm fw-500"><i class="bi bi-dash"></i></button>
                        <button type="button" class="btn btn-outline-success btn-sm fw-500  element elementMsg" data-pid="1"  id="custom-ele-1" data-customval="Title" ><i class="bi bi-plus-lg"></i></button>
                     </div>
                  </div>
               </div>
               {{-- <div class="card-footer text-muted">
                  <small>If you’d prefer not to receive email from me, <a href="#">unsubscribe</a>
                     here.</small>
               </div> --}}
            </div>
         </div>
      </div>
   </div>
</div>
{{-- Outreach section end --}}
{{-- Auto followup section start --}}
<div class="numberBox">
      <div class="blockNumber">
   </div>
</div>
<div class="card shadow-sm mb-3">
   <div class="card-body">
      <div class="row">
         <div class="col-sm-12">
            <div class="hstack gap-3 mb-3">
               <div>
                  <h5 class="fw-bold">
                     Auto Followup Template
                  </h5>
               </div>
            </div>
            <span class="input-group-append">
                   <button class="btn btn-primary addMorenumber" data-toggle="tooltip" type="button" data-placement="top" title="Add more Followup"><i class="bi bi-plus-lg"></i></button>
            </span>
         </div>
      </div>
   </div>
</div>


{{-- Auto followup section end --}}

{{-- Auto followup action button start --}}
{{-- <div class="card shadow-sm mb-3">
   <div class="card-body">
      <div class="row">
         <div class="col-sm-12">
            <div class="hstack gap-3">
               <div>
                  <h5 class="fw-bold mb-0">
                     Auto Followup Template
                  </h5>
               </div>
               <div class="ms-auto">
                  <button type="button" class="btn btn-primary fw-500" onclick="addAutofollowup()" id="auto-followup-btn"><i class="bi bi-plus-lg"></i> Add</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div> --}}
{{-- Auto followup action button end --}}

{{-- Schedule section start --}}
<div class="card shadow-sm">
   <div class="card-body">
      <div class="hstack gap-3 border-bottom mb-3">
         <div>
            <h5 class="fw-bold">
               Set Schedule
            </h5>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-8 mb-3">
            <label class="form-label text-muted mb-2 fw-500">Start Date</label>
            <input type="date" class="form-control shadow-none form-control-lg" id="start-date">
         </div>
         <div class="col-sm-8 mb-3">
            <label class="form-label text-muted mb-2 fw-500">Send Between</label>
            <div class="row">
               <div class="col-sm-3">
                  <input type="time" class="form-control shadow-none form-control-lg" id="start-time"
                     value="10:30">
               </div>
               <div class="col-sm-3">
                  <input type="time" class="form-control shadow-none form-control-lg" id="end-time"
                     value="23:30">
               </div>
               <div class="col-sm-6">
                  <select class="form-control form-control-lg shadow-none">
                     <option>Time zone in India (GMT+5:30)</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="col-sm-8">
            <div class="alert alert-primary d-flex align-items-center" role="alert">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                </svg>
               <div>
                  <div class="d-flex">
                     &nbsp;Interval between two emails (in seconds)&nbsp;
                     <span class="badge bg-white text-dark border border-dark me-2">60</span>
                     to
                     <span class="badge bg-white text-dark border border-dark ms-2">90</span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
{{-- Schedule section end --}}

<div class="hstack gap-3">
   <div class="ms-auto">
      <button class="btn btn-primary btn-lg mt-3 fw-500 d-none" type="button" id="wait-btn" disabled>
         <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         <span>Wait</span>
      </button>
      <button type="button" class="btn btn-outline-dark btn-lg shadow-sm fw-500 mt-3 rounded-0 me-2"><small>Save as Draft</small></button>
      <button type="button" class="btn btn-primary btn-lg shadow-sm fw-500 mt-3 rounded-0" onclick="createCampaign()"><small>Launch Campaign</small></button>
   </div>
</div>

            </div>
            @include('sections.footer')
         </main>
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

   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   {!! Html::script('js/schedulecampaign.js') !!}

   <script>
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
      
      $("#sidebarMenu").niceScroll(); 

    




   </script>
   <script type="text/javascript">
    $(document).ready(function() {
       $('.subject').ckeditor();
       $('.message').ckeditor();
    });
</script>
</body>
</html>
