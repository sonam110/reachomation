<!DOCTYPE html>
<html lang="en">

<head>
   <!-- Meta tags -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta name="author" content="Reachomation">
   <meta name="description" content="">
   <meta name="theme-color" content="#0f172a" />
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <!-- Favicon -->
   <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
   <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/apple-touch-icon.png') }}">
   <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/apple-touch-icon.png') }}">

   <!-- CSS -->
   <link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet"
      >
   <link rel="stylesheet" href="/css/bootstrap-icons.css">
   <link href="/css/aos.css" rel="stylesheet">
   <link rel="stylesheet" href="{{asset('css/style.css')}}">
   <link rel="stylesheet" href="{{asset('css/views/to-many-account.css')}}">
   <!-- Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <!-- Title -->
   <title>Contact us</title>
</head>

<body>

   <!-- Main start -->
   <main id="main">

      <!-- Header Start -->
      @include('sections.new-front-header')
        
      <!--Header End -->

      <!-- Banner start -->
      
      <section class="app__banner position-relative d-flex align-items-end">
         <img src="{{ asset('images/elements/contact-us.svg') }}" class="img-fluid position-absolute bottom-0 start-0"
            alt="Reachomation" width="860">

         <svg viewBox="0 0 960 540" width="100%" height="100%" preserveAspectRatio="xMidYMax slice"
            xmlns="http://www.w3.org/2000/svg"
            class="position-absolute inset-0 pointer-events-none ng-tns-c213-0 text-light">
            <g fill="none" stroke="currentColor" strokeWidth="80"
               class="text-gray-700 text-white-50 opacity-25 ng-tns-c213-0">
               <circle r="234" cx="196" cy="23" class="ng-tns-c213-0"></circle>
               <circle r="234" cx="890" cy="491" class="ng-tns-c213-0"></circle>
            </g>
         </svg>

         <div class="container-fluid">
            <div class="row">
               <div class="col-lg-4 text-center">
               </div>
               <div class="col-sm-8">
                 @include('sections.message')
                  <div class="card shadow-sm rounded-1 mb-3 me-lg-4 bg-light">
                     
                     <div class="card-body text-left py-4">
                        <h4 class="fw-bold">Too many devices logged into one account</h4>
                        <p class="fw-normal mb-3">Our experts are available to resolve your queries in all time
                           zones.</p>
                        <button type="button" data-toggle="modal" data-target="#pause-other-session" id="show-model-pause" class="btn btn-green rounded-pill shadow-none">Continue Working</button> <p class="fw-normal mb-3">and pause one active session.</p>
                     </div>
                      
                  </div>

               </div>
            </div>
         </div>
      </section>
      <!-- Banner end -->



      <!-- Footer start -->
     <!--  @include('sections.new-footer') -->
      <!-- Footer end -->

      <!-- Back to top start -->
      <a href="#main" class="back-top back-top-show">
         <div class="scroll-line"></div>
         <span class="scoll-text text-white">Go Up</span>
      </a>
      <!-- Bac to top end -->

<div class="modal fade" tabindex="-1" role="dialog" id="pause-other-session">
   <div class="modal-dialog  modal-md" role="document">
      <form id="contact-form" action="{{ route('pause.session') }}" method="POST" enctype="multipart/form-data"name="form" autocomplete="off" role="form">
         @csrf
      <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0">
            <h3 class="fw-bold mb-0"></h3>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-3 pt-0">
            <div class="mt-4" id="add-list-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="mobile" class="form-label">Password <span
                           class="requiredLabel"></span></label>
                        {!! Form::password('password',array('id'=>'password','class'=>
                        $errors->has('password') ? 'form-control is-invalid state-invalid' : 'form-control',
                        'placeholder'=>'Enter Your password ', 'autocomplete'=>'off','required'=>'required')) !!}
                     </div>
                  </div>
                  
               </div>
            </div>
         </div>
         <div class="modal-footer">
            {!! Form::submit('Continue', array('class'=>'btn btn-green rounded-pill shadow-none')) !!}
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
         </div>
      </div>
      </form>
   </div>
</div>
    
   </main>
   <!-- Main end -->

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

   <script src="/js/aos.js"></script>
   <script src="{{asset('js/index.js')}}"></script>
   <script type="text/javascript">
      
       $(document).on("click", "#show-model-pause", function(event){
       
         $("#pause-other-session").modal('show');
      });
   </script>

</body>

</html>
