<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="theme-color" content="#af0000">
   <!-- Bootstrap CSS -->
   <link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet"
      >
   <!-- Bootstrap icons -->
   <link rel="stylesheet" href="/css/bootstrap-icons.css">
   <!-- Manual CSS -->
   <link rel="stylesheet" href="{{asset('css/custom.css')}}">
   <!-- favicon -->
   <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <title>Pricing - Reachomation</title>
</head>

<body class="bg-body">
   @include('sections.front-navbar')
   @include('sections.message')

   <section class="position-relative">
      <svg viewBox="0 0 220 192" width="220" height="192" fill="none"
         class="position-absolute text-muted bottom-0 start-0">
         <defs class="ng-tns-c213-0">
            <pattern id="837c3e70-6c3a-44e6-8854-cc48c737b659" x="0" y="0" width="20" height="20"
               patternUnits="userSpaceOnUse" class="ng-tns-c213-0">
               <rect x="0" y="0" width="4" height="4" fill="currentColor" class="ng-tns-c213-0"></rect>
            </pattern>
         </defs>
         <rect width="220" height="192" fill="url(#837c3e70-6c3a-44e6-8854-cc48c737b659)" class="ng-tns-c213-0">
         </rect>
      </svg>
      <div class="h-100 position-relative">
         <div class="container py-4 px-3">
            <div class="card shadow mb-3">
               <div class="card-body">
                  <h4 class="card-title fw-bold text-center">Plans & Pricing</h4>
                  <div class="text-center">
                     <div class="btn-group mt-2 mb-3" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" value="1" onchange="fetchPlans('1')" checked>
                        <label class="btn btn-outline-success shadow-none" for="btnradio1" style="font-weight: 600;">Billed monthly</label>
                      
                        <input type="radio" class="btn-check" name="btnradio" onchange="fetchPlans('2')" id="btnradio3" value="2" autocomplete="off">
                        <label class="btn btn-outline-success shadow-none" for="btnradio3" style="font-weight: 600;">Billed annually <small>(20% off)</small></label>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <div id="plan-table" ></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="bg-white">
      <div class="h-100 rounded-0">
         <div class="container p-5">
            <div class="row">
               <div class="col-sm-12">
                  <h1 class="fw-bold text-center mb-5">Reachomation is loved by users worldwide</h1>
               </div>
               <div class="col-sm-12">
                  <div class="row row-cols-1 row-cols-md-3 g-4">
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm ">
                           <div class="card-body">
                              <img src="{{ asset('images/team-5.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Melissa Frank</h5>
                              <p class="card-text">The whole process of exploring websites and securing
                                 content
                                 placements is pretty smooth. Dedicated support team comes handy to quickly
                                 resolve any
                                 issues.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-4.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Shir Lapidot</h5>
                              <p class="card-text">The whole process of exploring websites and securing
                                 content
                                 placements is pretty smooth. Dedicated support team comes handy to quickly
                                 resolve any
                                 issues.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-3.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Bernd Garnot</h5>
                              <p class="card-text">It's a breeze working with the support team as they are
                                 always
                                 available to resolve any queries we may have. Their execution and output is
                                 precise and
                                 matches our expectations.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-2.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Ashton Popescu</h5>
                              <p class="card-text">The whole process of exploring websites and securing
                                 content
                                 placements is pretty smooth. Dedicated support team comes handy to quickly
                                 resolve any
                                 issues.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star text-info"></i>
                                 <i class="bi bi-star text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-1.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Robert Marshall</h5>
                              <p class="card-text">It's a breeze working with the support team as they are
                                 always
                                 available to resolve any queries we may have. Their execution and output is
                                 precise and
                                 matches our expectations.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-4.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Alex Smith</h5>
                              <p class="card-text">Our requirements aren't easy to match but these guys do a
                                 fairly
                                 decent job in getting us what we want. Their creative and content abilities
                                 makes them
                                 a service provider of our choice.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="bg-light">
      <div class="h-100 rounded-0">
         <div class="container p-5">
            <div class="row">
               <div class="col-sm-12">
                  <h1 class="fw-bold text-center">FAQ</h1>
                  <h5 class="text-center mb-5" style="font-weight: 500;">Frequently Asked Questions</h5>
               </div>
               <div class="col-sm-12">
                  <div class="row row-cols-1 row-cols-md-2 g-4">
                     <div class="col">
                        <div class="card h-100 shadow-sm">
                           <div class="card-body">
                              <h5>Will my credit card be instantly billed?</h5>
                              <p class="mb-0">No. Your credit card will only be charged when your trial ends or if you
                                 upgrade your
                                 account manually.</p>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 shadow-sm">
                           <div class="card-body">
                              <h5>Will my credit card be billed during the trial period?</h5>
                              <p class="mb-0">No. Your credit card will not be billed if you cancel your subscription
                                 before your
                                 trial period ends. You will only be automatically billed after that time.</p>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 shadow-sm">
                           <div class="card-body">
                              <h5>Can I pay with a monthly plan?</h5>
                              <p class="mb-0">Yes, we have monthly plans as well, but they are not discounted.</p>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 shadow-sm">
                           <div class="card-body">
                              <h5>Can I pay with PayPal?</h5>
                              <p class="mb-0">Yes, contact us if you cannot pay with a credit card.</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   @include('sections.footer')

   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script>
      $(document).ready ( function(){
         fetchPlans('1');
      });
      function fetchPlans(plan){
      $.ajax({
          url: "{{ route('fetch-plans')}}",
          type: 'POST',
          data:{plan:plan},  
          success:function(data){
            $("#plan-table").html(data);
         }
        });
   }

   /************ add to card ********/
$(document).on("click", "#subscribe", function () {
  var plan_id = $(this).data('plan_id');
  var stripe_plan_id = $(this).data('stripe_plan_id');
  var plan_type = $(this).data('plan_type');
  $.ajax({
    url: "{{ route('subscribe')}}",
    type: 'POST',
    data:{plan_id:plan_id,stripe_plan_id:stripe_plan_id,plan_type:plan_type},  
    success:function(info){
      if(info['data']=='3')
      {
        window.location.href = '{{ route('dashboard')}}';
      }
      else
      {
       window.location.href = "subscription/"+info['slug'];
      }
    }
  });
  });
   </script>
</body>

</html>

  
