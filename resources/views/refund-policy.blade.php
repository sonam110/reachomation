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
   <link rel="stylesheet" href="{{asset('css/style.css')}}">

   <!-- Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <!-- Title -->
   <title>Refund Policy</title>

</head>

<body>

   <!-- Main start -->
   <main id="main">

      <!-- Header Start -->
      <header class="mb-5">
         <nav class="navbar navbar-expand-lg navbar-dark bg-blue fixed-top py-0">
            <div class="container-fluid px-lg-5">
               <a class="navbar-brand" href="{{  url('/') }}">
                  <img class="img-fluid" src="{{ asset('images/reachomation-white.png') }}" alt="Reachomation"
                     width="180">
               </a>
               <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                  aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                     <li class="nav-item me-lg-3">
                        <a class="nav-link {{ (request()->is('contact')) ? 'active' : '' }}"
                           href="{{  url('contact') }}">Contact</a>
                     </li>
                     @if (Auth::check())
                     <li class="nav-item me-lg-3">
                        <a class="nav-link {{ (request()->is('dashboard')) ? 'active' : '' }}"
                           href="{{  url('dashboard') }}">Dashboard</a>
                     </li>
                     @else
                     <li class="nav-item me-lg-3">
                        <a class="nav-link {{ (request()->is('login')) ? 'active' : '' }}"
                           href="{{  url('login') }}">Login</a>
                     </li>
                     <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-button w-nav-link" style="max-width: 940px;">Sign
                           up</a>
                     </li>
                     @endif

                  </ul>
               </div>
            </div>
         </nav>
      </header>
      <!--Header End -->

      <!-- Policy start -->
      <section class="pt-5">
         <div class="container pt-3 pb-5">
            <div class="row">
               <div class="col-md-12">
                  <h2 class="fw-bold">
                     Refund Policy
                  </h2>
                  <p class="pb-4 mb-4 border-bottom border-2">
                     <span class="fw-bold">Last updated date:</span> September 10, 2022
                  </p>
                  <div>
                     <h5 class="fw-bold">1. GENERAL</h5>
                     <ol type="A">
                        <li class="mb-2">
                           This website with the URL of <a href="https://reachomation.com/"><strong>https://reachomation.com/</strong></a> (“Website/Site”) is operated by <strong>Accunite Solutions Private Limited</strong> (“We/Our/Us”). "
                        </li>
                        <li class="mb-2">
                           We are committed to providing Our customers with the highest quality Services. However, on rare occasions, Services may be found to be deficient. In such cases, We offer refund in accordance with this Refund Policy (“Policy”)."
                        </li>
                        <li class="mb-2">
                           You are advised to read Our Terms and Conditions along with this Policy at the following webpage: <a href="https://reachomation.com/"><strong>https://reachomation.com/terms-of-use</strong></a>.
                        </li>
                        <li class="mb-2">
                           By using this website, you agree to be bound by the terms contained in this Policy without modification. If You do not agree to the terms contained in this Policy, You are advised not to transact on this website.
                        </li>
                        <li class="mb-2">
                           We offer a 3 day refund Policy for the eligible Services.
                        </li>
                        <li class="mb-2">
                           Please read this Policy before availing service on this Website, so that You understand Your rights as well as what You can expect from Us if You are not happy with Your purchase.
                        </li>
                     </ol>

                     <h5 class="fw-bold mt-4">2. DEFINITIONS</h5>
                     <ol type="A">
                        <li class="mb-2">
                           “Business Days” - means a day that is not a Saturday, Sunday, public holiday, or bank holiday in India or in the state where our office is located."
                        </li>
                        <li class="mb-2">
                           “Customer” - means a person who avails services for consideration and does not include commercial purchases."
                        </li>
                        <li class="mb-2">
                           “Date of Transaction” - means the date of invoice of the service, which includes the date of renewal processed in accordance with the terms and conditions of the applicable service agreement."
                        </li>
                        <li class="mb-2">
                           “Website” - means this website with the URL: <a href="https://reachomation.com/"><strong>https://reachomation.com/</strong></a>."
                        </li>
                     </ol>

                     <h5 class="fw-bold mt-4">3. REFUNDS RULES</h5>
                     <ol type="A">
                        <li class="mb-2">
                           Every effort is made so as to service the orders placed, as per the specifications and timelines mentioned with respect to a Services. If due to any unforeseen circumstances or limitations from Our side, the service is not provided then such order stands cancelled and the amount paid by You is refunded.
                        </li>
                        <li class="mb-2">
                           When you make a qualifying refund request. We may refund the full amount, less any additional cost incurred by Us in providing such Services.
                        </li>
                        <li class="mb-2">
                           Once qualified, the refunds are applied to the original payment option.
                        </li>
                        <li class="mb-2">
                           The request for a refund of Services can be made in the following manner:
                        </li>
                     </ol>

                     <p class="fw-bold">
                        Click on Billing Section, you will find cancel subscription button there. Your account will be downgraded.
                     </p>

                     <h5 class="fw-bold mt-4">4. ORDER NOT CONFIRMED BUT AMOUNT DEDUCTED</h5>
                     <ol type="A">
                        <li class="mb-2">
                           If the amount has been deducted and the order is not confirmed, please do contact Your respective bank. It takes 7 (seven) Business Days to reverse back the amount by the respective bank.
                        </li>
                        <li class="mb-2">
                           If the issue has not been resolved within 7 (seven) Business Days, you can contact Our customer care support as follows: <a href="mailto:support@reachomation.com" class="fw-bold">support@reachomation.com</a>.
                        </li>
                     </ol>

                     <h5 class="fw-bold mt-4">5. EXEMPTIONS</h5>
                     <ol type="A">
                        <li class="mb-2">
                           Notwithstanding the other provisions of this Policy, We may refuse to provide a refund for a service if:
                        </li>
                        <ol type="I">
                           <li class="mb-2">
                              You knew or were made aware of the problem(s) with the service before you availed it.
                           </li>
                           <li class="mb-2">
                              Free Services.
                           </li>
                           <li class="mb-2">
                              Refund requests are placed after the refund window is closed.
                           </li>
                        </ol>
                     </ol>

                     <h5 class="fw-bold mt-4">6. YOUR DATA</h5>
                     <p>
                        The privacy of your data supplied to Us during the return and refund procedure is also governed by our privacy policy, which can be accessed under the following link: <a href="https://reachomation.com/privacy-policy"><strong>https://reachomation.com/privacy-policy</strong></a>.
                     </p>

                     <h5 class="fw-bold mt-4">7. RESPONSE TIME</h5>
                     <ol type="A">
                        <li class="mb-2">
                           Refunds are normally processed within 30 Business Days after checking the veracity of the refund request.
                        </li>
                        <li class="mb-2">
                           The period of refund may also depend on various banking and payment channels, and We will not be liable for any errors or delays in a refund due to banks or third-party service providers.
                        </li>
                     </ol>

                     <h5 class="fw-bold mt-4">8. CANCELLATION OF RETURN REQUEST</h5>
                     <p>
                        A request for return or refund once made can be cancelled by contacting customer care at <a href="mailto:support@reachomation.com" class="fw-bold">support@reachomation.com</a>.
                     </p>

                     <h5 class="fw-bold mt-4">9. REFUSAL OF RETURN OR REFUND REQUEST</h5>
                     <p>
                        We reserve the right to refuse any request for a refund if such request is not in compliance with this Policy or applicable laws.
                     </p>

                     <h5 class="fw-bold mt-4">10. CONTACT US</h5>
                     <p>
                        For any feedback, concern, or query, You may please reach out to Us on the contact details below.
                     </p>
                     <h6 class="fw-bold">
                        Customer care: <a href="mailto:support@reachomation.com" class="fw-bold">support@reachomation.com</a>
                     </h6>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>

                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Policy end -->

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
   <script>
      // Back to top button start
      $(window).scroll(function () {
         if ($(this).scrollTop() > 400) {
            $('.back-top').fadeIn('slow');
         } else {
            $('.back-top').fadeOut('slow');
         }
      });
   </script>
</body>

</html>
