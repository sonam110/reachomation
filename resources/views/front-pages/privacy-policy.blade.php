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
   <title>Privacy Policy</title>

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
                  <h2 class="pb-4 mb-4 border-bottom border-2 fw-bold">
                     Privacy Policy
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

                     <h4 class="fw-bold">When do you collect information?</h4>
                     <ul>
                        <li>
                           On Sign Up
                        </li>
                        <li>
                           On Subscribing to Our
                        </li>
                        <li>
                           Newsletter
                        </li>
                        <li>
                           Fill Out Our Form
                        </li>
                        <li>
                           Live Chat
                        </li>
                        <li>
                           Enter Information On Our Site
                        </li>
                        <li>
                           Alternate Address provided by you
                        </li>
                     </ul>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>

                     <h4 class="fw-bold">
                        Other Information Which We Collect From Your Usage Of Our Services
                     </h4>
                     <ul>
                        <li>
                           Payment Transaction Details
                        </li>
                        <li>
                           Cookies
                        </li>
                        <li>
                           Device & Connection Information
                        </li>
                        <li>
                           Third Party Details : Skype Details
                        </li>
                        <li>
                           Google Authorization
                        </li>
                     </ul>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>

                     <h4 class="fw-bold">
                        How We Use the Collected Information?
                     </h4>
                     <p>
                        We use the collected information to provide you safe, secure and trusted platform which complies
                        with our legal obligations.
                     </p>
                     <ol>
                        <li class="fw-bold mb-2">
                           To Use Our Platform
                        </li>
                        <ul class="mb-2">
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
                        <li class="fw-bold mb-2">
                           User Representation
                        </li>
                        <ul class="mb-2">
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

                        <li class="fw-bold mb-2">
                           Gmail
                        </li>
                        <p>
                           For your better understanding, this is the detail of the use of the Gmail permissions by
                           Reachomation:
                        </p>
                        <ul class="mb-2">
                           <li class="mb-2">
                              <span class="fw-bold">Basic permissions:</span> Read your name, email address, aliases,
                              and profile picture.
                           </li>
                           <li class="mb-2">
                              <span class="fw-bold">Read your emails:</span> View your email metadata such as labels,
                              headers, email recipients and the email body. The Service only reads those emails which
                              are sent using Reachomation email tracking service. It is useful in displaying email
                              preview in the web application, to track replies of the recipient to provide engagement
                              information.
                           </li>
                           <li class="mb-2">
                              <span class="fw-bold">Compose new emails:</span> We only compose emails in your mailbox
                              when you send emails using Reachomation tool using your email account authorization. We do
                              not compose emails without your consent.
                           </li>
                           <li class="mb-2">
                              <span class="fw-bold">Send emails for you:</span> No one from Accunite Solutions will send
                              emails on your behalf and will never share the content of your emails without your
                              consent. The Service only send emails which are scheduled with Reachomation tracking
                              service.
                           </li>
                           <li class="mb-2">
                              <span class="fw-bold">Delete your email:</span> The Service does not use this permission,
                              but unfortunately, it can not be removed separately.
                           </li>
                           <li class="mb-2">
                              <span class="fw-bold">Create, change or delete your email labels:</span> The Service
                              requires to add/modify your email labels based on the engagement data of your email
                              (tracked with Reachomation).
                           </li>
                           <li class="mb-2">
                              <span class="fw-bold">Get notified when certain kinds of emails appear in your Gmail
                                 inbox, like a travel confirmation:</span> The Service notify you regarding the changes
                              in your emails (sent with Reachomation tracking) to keep you updated regarding the
                              engagement activities.
                           </li>
                        </ul>

                        <p>
                           <span class="fw-bold">Email content:</span> Content of Customer's email subject lines,
                           recipient email address, email content (only when emails are sent using the Reachomation Web
                           App) and Templates.
                        </p>

                        <ul class="mb-3">
                           <li>
                              <span class="fw-bold">Purpose:</span> We process this type of Personal Data in order to
                              provide you our Services.
                           </li>
                        </ul>

                        <p>
                           <span class="fw-bold">Legal Grounds:</span> We process this type of Personal Data for our
                           legitimate interests in providing the Services.
                        </p>
                        <p>
                           Cookies or other anonymous identifiers may be sent to your devices and may be used when you
                           interact with our site or service.
                        </p>
                     </ol>

                     <p>
                        Once using our Services, we may communicate with you if you've provided us the means to do so.
                        For example, we may send you promotional email offers, or email you about your use of the
                        Services. Also, we may receive a confirmation when you open an email from us. This confirmation
                        helps us make our communications with you more interesting and improve our services. If you do
                        not want to receive communications from us, feel free to unsubscribe or change your
                        communication preferences.
                     </p>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>

                     <h4 class="fw-bold">
                        Disclosure of Data
                     </h4>
                     <p>
                        We do not trade or sell your data to any third party companies or outside our parent company
                        unless we provide you any advance notice.
                     </p>

                     <div class="py-1 border border-2 border-start-0 border-end-0 mt-4 mb-4"></div>
                      <p>Reachomation’s use and transfer to any other app of information received from Google APIs will adhere to <a href="https://developers.google.com/terms/api-services-user-data-policy#additional_requirements_for_specific_api_scopes" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://developers.google.com/terms/api-services-user-data-policy%23additional_requirements_for_specific_api_scopes&amp;source=gmail&amp;ust=1676715124893000&amp;usg=AOvVaw0HfgDXoqAs8Rjsd7ddc5pe">Google API Services User Data Policy</a>, including the Limited Use requirements.&nbsp;<br></div></p>

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
