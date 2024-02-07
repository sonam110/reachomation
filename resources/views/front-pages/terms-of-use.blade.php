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
   <title>Terms of use</title>

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

      <!-- Terms start -->
      <section class="pt-5">
         <div class="container pt-3 pb-5">
            <div class="row">
               <div class="col-md-12">
                  <h2 class="pb-4 mb-4 border-bottom border-2 fw-bold">
                     Terms of Use
                  </h2>
                  <div>
                     <p>
                        Reachomation is a content marketing platform for agencies and professionals which helps them
                        fulfill their content marketing needs.
                     </p>
                     <p>
                        We have mentioned the terms and conditions you must agree to use our content marketing platform.
                     </p>
                     <p>
                        By accessing and signing up, you're agreeing to our terms of use. If you don't agree with our
                        terms and use, we strongly recommend you to not pursue ahead with sign up or email to our
                        support team for further query.
                     </p>
                     <p>
                        There are various terminologies used on our terms of use and privacy policy:
                     </p>
                     <ul>
                        <li>
                           “You”, “Client”, “Your” refers to you, the person who is signing up or visiting our website
                           and compliant to our company's terms of use.
                        </li>
                        <li>
                           “We”, “Our”, “Us”, “Company”, “Product”, “Platform” refers to our company.
                        </li>
                     </ul>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>

                     <h4 class="fw-bold">Services</h4>
                     <p>
                        Our service consists of a content marketing platform for marketing agencies and professionals,
                        which includes services related to guest posting, blogger outreach. The user can search,
                        explore, evaluate and place order on various websites and blogs through our platform.
                     </p>
                     <p>
                        Reachomation reserves the right to change or alter the terms and conditions of our services,
                        which are either or not by issuing prior notice to the users, by changing the text of this
                        written agreement or changing the information on the terms of use policy page. If the terms and
                        conditions are not acceptable to you, you may terminate your account or raise the concern with
                        our support team.
                     </p>
                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>
                     <h4 class="fw-bold">
                        Registration and Account Usage
                     </h4>
                     <h5 class="mt-4">
                        Your use of the Services is subject to the following additional restrictions:
                     </h5>
                     <p>
                        You represent, warrant, and agree that you will not contribute any Content or User Submission
                        (each of those terms is defined below) or otherwise use the Services or interact with the
                        Services in a manner that:
                     </p>
                     <ol>
                        <li class="mb-2">
                           Infringes or violates the intellectual property rights or any other rights of anyone else
                           (including Reachomation);
                        </li>
                        <li class="mb-2">
                           Violates any law or regulation, including, without limitation, any applicable export control
                           laws;
                        </li>
                        <li class="mb-2">
                           Is harmful, fraudulent, deceptive, threatening, harassing, defamatory, obscene, or otherwise
                           objectionable;
                        </li>
                        <li class="mb-2">
                           Jeopardizes the security of your Reachomation account or anyone else's (such as allowing
                           someone else to log in to the Services as you);
                        </li>
                        <li class="mb-2">
                           Attempts, in any manner, to obtain the password, account, or other security information from
                           any other user;
                        </li>
                        <li class="mb-2">
                           Violates the security of any computer network, or cracks any passwords or security encryption
                           codes;
                        </li>
                        <li class="mb-2">
                           Runs Maillist, Listserv, any form of auto-responder or “spam” on the Services, or any
                           processes that run or are activated while you are not logged into the Services, or that
                           otherwise interfere with the proper working of the Services (including by placing an
                           unreasonable load on the Service's infrastructure);
                        </li>
                        <li class="mb-2">
                           “Crawls,” “scrapes,” or “spiders” any page, data, or portion of or relating to the Services
                           or Content (through use of manual or automated means);
                        </li>
                        <li class="mb-2">
                           Copies or stores any significant portion of the Content;
                        </li>
                        <li class="mb-2">
                           Decompiles, reverse engineers, or otherwise attempts to obtain the source code or underlying
                           ideas or information of or relating to the Services.
                        </li>
                     </ol>
                     <p>
                        A violation of any of the foregoing is grounds for termination of your right to use or access
                        the Services.
                     </p>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>

                     <h4 class="fw-bold">
                        How We Use the Collected Information?
                     </h4>
                     <p>
                        We use the collected information to provide you safe, secure and trusted platform which complies
                        with our legal obligations.
                     </p>
                     <ol>
                        <li class="fw-bold">
                           To Use Our Platform
                        </li>
                        <ul>
                           <li>
                              Enable you the access to use our platform
                           </li>
                           <li>
                              Provide you the support service
                           </li>
                           <li>
                              Send you service updates, order updates, and transactional and promotional messages
                           </li>
                        </ul>
                        <li class="fw-bold">
                           User Representation
                        </li>
                        <ul>
                           <li>
                              To maintain a trusted and safer platform
                           </li>
                           <li>
                              Detection and prevention from spam, abuse, fraud and other harmful activity.
                           </li>
                           <li>
                              Verification of information provided by you
                           </li>
                           <li>
                              Comply with our terms of use and legal obligations.
                           </li>
                           <li>
                              Resolve any disputes and enforce our agreements with third parties.
                           </li>
                        </ul>
                     </ol>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>

                     <h4 class="fw-bold">
                        Disclosure of Data
                     </h4>
                     <p>
                        We do not trade or sell your data to any third party companies or outside our parent company
                        unless we provide you any advance notice.
                     </p>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>

                     <h4 class="fw-bold">
                        Links to Other Sites
                     </h4>
                     <p>
                        Our platform contains links to other sites that are not owned or operated by us. These websites
                        have their own terms and privacy policies. We are not responsible or liable for the content and
                        activities of the other websites.
                     </p>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>

                     <h4 class="fw-bold">
                        Privacy Policy
                     </h4>
                     <p>
                        Your use of service and account are governed by our privacy policy. You can learn more about our
                        privacy policy here.
                     </p>
                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Terms end -->

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
