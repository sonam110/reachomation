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
      <link rel="stylesheet" href="{{asset('css/views/front-pages/pricing-new.css')}}">

   <!-- Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <!-- Title -->
   <title>Pricing</title>

</head>

<body>

   <!-- Main start -->
   <main id="main">

      <!-- Header Start -->
      @include('sections.new-front-header')
      <!--Header End -->

      <!-- Plan start -->
      <section class="app__plan pb-5">
         <svg viewBox="0 0 220 192" width="220" height="192" fill="none"
            class="position-absolute text-white-50 bottom-0 start-0 d-none d-sm-block">
            <defs class="ng-tns-c213-0">
               <pattern id="837c3e70-6c3a-44e6-8854-cc48c737b659" x="0" y="0" width="20" height="20"
                  patternUnits="userSpaceOnUse" class="ng-tns-c213-0">
                  <rect x="0" y="0" width="4" height="4" fill="currentColor" class="ng-tns-c213-0"></rect>
               </pattern>
            </defs>
            <rect width="220" height="192" fill="url(#837c3e70-6c3a-44e6-8854-cc48c737b659)" class="ng-tns-c213-0">
            </rect>
         </svg>

         <svg viewBox="0 0 960 540" width="100%" height="100%" preserveAspectRatio="xMidYMax slice"
            xmlns="http://www.w3.org/2000/svg"
            class="position-absolute inset-0 pointer-events-none ng-tns-c213-0 text-light d-none d-sm-block">
            <g fill="none" stroke="currentColor" strokeWidth="80"
               class="text-gray-700 text-white-50 opacity-25 ng-tns-c213-0">
               <circle r="234" cx="196" cy="23" class="ng-tns-c213-0"></circle>
               <circle r="234" cx="890" cy="491" class="ng-tns-c213-0"></circle>
            </g>
         </svg>

         <div class="container-fluid pricingTable pt-90">
            <div class="container">
               <div class="row justify-content-center text-center mb-5">
                  <div class="col-lg-12 aos-init aos-animate" data-aos="fade-up">
                     <h2 class="mb-3 fw-bold text-white mt-5">
                        Before you move ahead, please choose your plan
                     </h2>
                     <hr class="hr-short mt-4 text-white mb-lg-4">

                     <figure class="position-absolute top-100 start-50 translate-middle mt-5 pt-5 d-none d-lg-block"
                        style="margin-left: 12rem;">
                        <svg>
                           <path class="fill-white"
                              d="m181.6 6.7c-0.1 0-0.2-0.1-0.3 0-2.5-0.3-4.9-1-7.3-1.4-2.7-0.4-5.5-0.7-8.2-0.8-1.4-0.1-2.8-0.1-4.1-0.1-0.5 0-0.9-0.1-1.4-0.2-0.9-0.3-1.9-0.1-2.8-0.1-5.4 0.2-10.8 0.6-16.1 1.4-2.7 0.3-5.3 0.8-7.9 1.3-0.6 0.1-1.1 0.3-1.8 0.3-0.4 0-0.7-0.1-1.1-0.1-1.5 0-3 0.7-4.3 1.2-3 1-6 2.4-8.8 3.9-2.1 1.1-4 2.4-5.9 3.9-1 0.7-1.8 1.5-2.7 2.2-0.5 0.4-1.1 0.5-1.5 0.9s-0.7 0.8-1.1 1.2c-1 1-1.9 2-2.9 2.9-0.4 0.3-0.8 0.5-1.2 0.5-1.3-0.1-2.7-0.4-3.9-0.6-0.7-0.1-1.2 0-1.8 0-3.1 0-6.4-0.1-9.5 0.4-1.7 0.3-3.4 0.5-5.1 0.7-5.3 0.7-10.7 1.4-15.8 3.1-4.6 1.6-8.9 3.8-13.1 6.3-2.1 1.2-4.2 2.5-6.2 3.9-0.9 0.6-1.7 0.9-2.6 1.2s-1.7 1-2.5 1.6c-1.5 1.1-3 2.1-4.6 3.2-1.2 0.9-2.7 1.7-3.9 2.7-1 0.8-2.2 1.5-3.2 2.2-1.1 0.7-2.2 1.5-3.3 2.3-0.8 0.5-1.7 0.9-2.5 1.5-0.9 0.8-1.9 1.5-2.9 2.2 0.1-0.6 0.3-1.2 0.4-1.9 0.3-1.7 0.2-3.6 0-5.3-0.1-0.9-0.3-1.7-0.8-2.4s-1.5-1.1-2.3-0.8c-0.2 0-0.3 0.1-0.4 0.3s-0.1 0.4-0.1 0.6c0.3 3.6 0.2 7.2-0.7 10.7-0.5 2.2-1.5 4.5-2.7 6.4-0.6 0.9-1.4 1.7-2 2.6s-1.5 1.6-2.3 2.3c-0.2 0.2-0.5 0.4-0.6 0.7s0 0.7 0.1 1.1c0.2 0.8 0.6 1.6 1.3 1.8 0.5 0.1 0.9-0.1 1.3-0.3 0.9-0.4 1.8-0.8 2.7-1.2 0.4-0.2 0.7-0.3 1.1-0.6 1.8-1 3.8-1.7 5.8-2.3 4.3-1.1 9-1.1 13.3 0.1 0.2 0.1 0.4 0.1 0.6 0.1 0.7-0.1 0.9-1 0.6-1.6-0.4-0.6-1-0.9-1.7-1.2-2.5-1.1-4.9-2.1-7.5-2.7-0.6-0.2-1.3-0.3-2-0.4-0.3-0.1-0.5 0-0.8-0.1s-0.9 0-1.1-0.1-0.3 0-0.3-0.2c0-0.4 0.7-0.7 1-0.8 0.5-0.3 1-0.7 1.5-1l5.4-3.6c0.4-0.2 0.6-0.6 1-0.9 1.2-0.9 2.8-1.3 4-2.2 0.4-0.3 0.9-0.6 1.3-0.9l2.7-1.8c1-0.6 2.2-1.2 3.2-1.8 0.9-0.5 1.9-0.8 2.7-1.6 0.9-0.8 2.2-1.4 3.2-2 1.2-0.7 2.3-1.4 3.5-2.1 4.1-2.5 8.2-4.9 12.7-6.6 5.2-1.9 10.6-3.4 16.2-4 5.4-0.6 10.8-0.3 16.2-0.5h0.5c1.4-0.1 2.3-0.1 1.7 1.7-1.4 4.5 1.3 7.5 4.3 10 3.4 2.9 7 5.7 11.3 7.1 4.8 1.6 9.6 3.8 14.9 2.7 3-0.6 6.5-4 6.8-6.4 0.2-1.7 0.1-3.3-0.3-4.9-0.4-1.4-1-3-2.2-3.9-0.9-0.6-1.6-1.6-2.4-2.4-0.9-0.8-1.9-1.7-2.9-2.3-2.1-1.4-4.2-2.6-6.5-3.5-3.2-1.3-6.6-2.2-10-3-0.8-0.2-1.6-0.4-2.5-0.5-0.2 0-1.3-0.1-1.3-0.3-0.1-0.2 0.3-0.4 0.5-0.6 0.9-0.8 1.8-1.5 2.7-2.2 1.9-1.4 3.8-2.8 5.8-3.9 2.1-1.2 4.3-2.3 6.6-3.2 1.2-0.4 2.3-0.8 3.6-1 0.6-0.2 1.2-0.2 1.8-0.4 0.4-0.1 0.7-0.3 1.1-0.5 1.2-0.5 2.7-0.5 3.9-0.8 1.3-0.2 2.7-0.4 4.1-0.7 2.7-0.4 5.5-0.8 8.2-1.1 3.3-0.4 6.7-0.7 10-1 7.7-0.6 15.3-0.3 23 1.3 4.2 0.9 8.3 1.9 12.3 3.6 1.2 0.5 2.3 1.1 3.5 1.5 0.7 0.2 1.3 0.7 1.8 1.1 0.7 0.6 1.5 1.1 2.3 1.7 0.2 0.2 0.6 0.3 0.8 0.2 0.1-0.1 0.1-0.2 0.2-0.4 0.1-0.9-0.2-1.7-0.7-2.4-0.4-0.6-1-1.4-1.6-1.9-0.8-0.7-2-1.1-2.9-1.6-1-0.5-2-0.9-3.1-1.3-2.5-1.1-5.2-2-7.8-2.8-1-0.8-2.4-1.2-3.7-1.4zm-64.4 25.8c4.7 1.3 10.3 3.3 14.6 7.9 0.9 1 2.4 1.8 1.8 3.5-0.6 1.6-2.2 1.5-3.6 1.7-4.9 0.8-9.4-1.2-13.6-2.9-4.5-1.7-8.8-4.3-11.9-8.3-0.5-0.6-1-1.4-1.1-2.2 0-0.3 0-0.6-0.1-0.9s-0.2-0.6 0.1-0.9c0.2-0.2 0.5-0.2 0.8-0.2 2.3-0.1 4.7 0 7.1 0.4 0.9 0.1 1.6 0.6 2.5 0.8 1.1 0.4 2.3 0.8 3.4 1.1z">
                           </path>
                        </svg>
                     </figure>
                  </div>
               </div>

               <div class="row">
                  <div class="col-12">
                     <div class="inner d-flex tabsBtnHolder mt-lg-5">
                        <ul>
                           <li onclick="fetchPlan('1')">
                              <p id="monthly" class="active">Monthly</p>
                           </li>
                           <li onclick="fetchPlan('2')">
                              <p id="yearly" class="">Yearly <small>(20% off)</small></p>
                           </li>

                           <li class="indicator"></li>
                        </ul>
                     </div>
                  </div>
               </div>

               <div class="table-responsive">
                  <table class="table table-striped table-bordered text-center">
                     <thead>
                        <tr class="bg-blue text-white align-middle">
                           <th scope="col" style="width: 40%;">FEATURES</th>
                           <th scope="col">
                              FREE FOREVER
                              <div class="price mt-2">
                                 <h2 class="mb-0"><b>$0</b><span> / mo</span></h2>
                              </div>
                              <div class="">
                                 <button class="btn btn-green shadow-none rounded-pill px-5">
                                    Subscribe <i class="bi bi-chevron-double-right"></i>
                                 </button>
                              </div>
                           </th>
                           <th scope="col">
                              STARTER
                              <div class="price mt-2">
                                 <h2 class="mb-0"><b>$99</b><span> / mo</span></h2>
                              </div>
                              <div class="">
                                 <button class="btn btn-green shadow-none rounded-pill px-5">
                                    Subscribe <i class="bi bi-chevron-double-right"></i>
                                 </button>
                              </div>
                           </th>
                           <th scope="col">
                              PREMIUM
                              <div class="price mt-2">
                                 <h2 class="mb-0"><b>$199</b><span> / mo</span></h2>
                              </div>
                              <div class="">
                                 <button class="btn btn-green shadow-none rounded-pill px-5">
                                    Subscribe <i class="bi bi-chevron-double-right"></i>
                                 </button>
                              </div>
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <th scope="row" class="text-white">
                              Parallel users
                           </th>
                           <td class="text-white">1</td>
                           <td class="text-white">2</td>
                           <td class="text-white">Unlimited</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              List size limit
                           </th>
                           <td class="text-white">50</td>
                           <td class="text-white">10000</td>
                           <td class="text-white">10000</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              Monthly credits
                           </th>
                           <td class="text-white">250</td>
                           <td class="text-white">15000</td>
                           <td class="text-white">35000</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              Site Metrics + Quality filters
                           </th>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              One-to-one outreach
                           </th>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              Automated Outreach
                           </th>
                           <td class="text-white">NA</td>
                           <td class="text-white">Yes (upto 2 emails)</td>
                           <td class="text-white">Yes (upto 4 emails)</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              Automated followups
                           </th>
                           <td class="text-white">NA</td>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              Site traffic history
                           </th>
                           <td class="text-white">NA</td>
                           <td class="text-white">NA</td>
                           <td class="text-white">Yes</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              Mail personalization
                           </th>
                           <td class="text-white">NA</td>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              Pre-defined templates 
                           </th>
                           <td class="text-white">NA</td>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              'Import sites' feature
                           </th>
                           <td class="text-white">NA</td>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              'Export data' option
                           </th>
                           <td class="text-white">NA</td>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                        </tr>
                        <tr>
                           <th scope="row" class="text-white">
                              Skype & chat support
                           </th>
                           <td class="text-white">NA</td>
                           <td class="text-white">Yes</td>
                           <td class="text-white">Yes</td>
                        </tr>
                     </tbody>
                  </table>
               </div>

            </div>
         </div>
      </section>
      <!-- Plan end -->

      <!-- FAQ start -->
      <section class="app__faq bg-light">
         <div class="container py-lg-5 py-3">
            <div class="row justify-content-center text-center mb-5">
               <div class="col-lg-8 aos-init aos-animate" data-aos="fade-up">
                  <h2 class="mb-3 fw-bold">
                     FREQUENTLY ASKED QUESTIONS
                  </h2>
                  <hr class="hr-short mt-4">
               </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 g-lg-4 g-3 px-lg-5 justify-content-center">
               <div class="col">
                  <div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
                     data-aos="flip-down">
                     <div class="card-body">
                        <h5 class="card-title fw-bold">
                           Can I have a discounted monthly billing?
                        </h5>
                        <div class="w-25 border-3 border-dark border-bottom"></div>
                        <hr class="hr-short mt-3 text-light">
                        <div class="py-3">
                           <p class="mb-0" style="text-align: justify;">
                              Sorry, discounted plans are only available for annual billing.
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col">
                  <div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
                     data-aos="flip-down">
                     <div class="card-body">
                        <h5 class="card-title fw-bold">
                           Do you have a discount coupon?
                        </h5>
                        <div class="w-25 border-3 border-dark border-bottom"></div>
                        <hr class="hr-short mt-3 text-light">
                        <div class="py-3">
                           <p class="mb-0" style="text-align: justify;">
                              We don't run any discount offers or coupons. Buying an annual subscription already gives a
                              20% discount. So unless it's Black Friday, there's no way getting to this incredible tool
                              with some flashy and hollow discount offers.
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col">
                  <div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
                     data-aos="flip-down">
                     <div class="card-body">
                        <h5 class="card-title fw-bold">
                           What's your cancellation policy?
                        </h5>
                        <div class="w-25 border-3 border-dark border-bottom"></div>
                        <hr class="hr-short mt-3 text-light">
                        <div class="py-3">
                           <p class="mb-0" style="text-align: justify;">
                              You may cancel your subscription at any time and your account will be downgraded to 'Free
                              forever' plan after your pre-paid subscription period runs out. You will not be billed any
                              further. We will confirm the cancellation of your subscription within 3 business days
                              after receipt of your cancellation request.
                           </p>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="col">
                  <div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
                     data-aos="flip-down">
                     <div class="card-body">
                        <h5 class="card-title fw-bold">
                           Do you offer a refund?
                        </h5>
                        <div class="w-25 border-3 border-dark border-bottom"></div>
                        <hr class="hr-short mt-3 text-light">
                        <div class="py-3">
                           <p class="mb-0" style="text-align: justify;">
                              At Reachomation, we offer a 3-day money back guarantee. If you cancel your subscription in
                              accordance with this Policy within 3 calendar days of placing your subscription order, we
                              will, upon your written request, refund your prepaid fees within 30 calendar days. The
                              refund will be processed through the same method as the original payment. Any bank fees
                              and charges shall be borne solely by you.
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- FAQ end -->

      <!-- Footer start -->
      @include('sections.new-footer')
      <!-- Footer end -->

      <!-- Back to top start -->
      <a href="#main" class="back-top back-top-show">
         <div class="scroll-line"></div>
         <span class="scoll-text text-white">Go Up</span>
      </a>
      <!-- Bac to top end -->

   </main>
   <!-- Main end -->

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script src="/js/aos.js"></script>
   <script src="{{asset('js/index.js')}}"></script>
   <script>
      AOS.init();

      /***** for pricing table (tabs | monthly & yearly) ******/
      $(document).ready(function () {
         $("#monthly").click(function () {
            $(this).addClass('active');
            $("#yearly").removeClass('active')

            $("#starter-price").html("$99");
            $("#premium-price").html("$199");

            $(".indicator").css("left", "2px");
         })

         $("#yearly").click(function () {
            $(this).addClass('active');
            $("#monthly").removeClass('active');

            $("#starter-price").html("$79");
            $("#premium-price").html("$159");

            $(".indicator").css("left", "163px");
         })
      })
      /***** for pricing table (tabs | monthly & yearly) ******/

      $(document).ready ( function(){
         fetchPlan('1');
      });
      function fetchPlan(plan){
      $.ajax({
            url: "{{ route('fetch-plan')}}",
            type: 'POST',
            data:{plan:plan},  
            success:function(data){
               // console.log(data);
               $(".monthlyPriceList").html(data);
            }
         });
      }

      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
      const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
   </script>
</body>

</html>
