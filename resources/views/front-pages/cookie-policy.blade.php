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
   <title>Cookie Policy</title>

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
                     Cookies Policy
                  </h2>
                  <p class="pb-4 mb-4 border-bottom border-2">
                     <span class="fw-bold">Last updated date:</span> September 05, 2022
                  </p>
                  <div>
                     <p>
                        This Cookies Policy explains what Cookies are and how We use them. You should read this policy so You can understand what type of cookies We use, or the information We collect using Cookies and how that information is used. This Cookies Policy has been created with the help of the Cookies Policy Generator.
                     </p>
                     <p>
                        Cookies do not typically contain any information that personally identifies a user, but personal information that we store about You may be linked to the information stored in and obtained from Cookies. For further information on how We use, store and keep your personal data secure, see our Privacy Policy.
                     </p>
                     <p>
                        We do not store sensitive personal information, such as mailing addresses, account passwords, etc. in the Cookies We use.
                     </p>
                     <h3 class="fw-bold text-blue mt-4 border-bottom border-2 pb-2 mb-4">Interpretation and Definitions</h3>

                     <h5 class="fw-bold">Interpretation</h5>
                     <p>
                        The words of which the initial letter is capitalized have meanings defined under the following conditions. The following definitions shall have the same meaning regardless of whether they appear in singular or in plural.
                     </p>

                     <h5 class="fw-bold">Definitions</h5>
                     <p>
                        For the purposes of this Cookies Policy:
                     </p>
                     <ul>
                        <li class="mb-2">
                           <span class="fw-bold">Company</span> (referred to as either "the Company", "We", "Us" or "Our" in this Cookies Policy) refers to Accunite Solutions Private Limited, 115, Tower 1, Assotech Business Cresterra, Sector-135, Noida, IN.
                        </li>
                        <li class="mb-2">
                           <span class="fw-bold">Cookies</span> means small files that are placed on Your computer, mobile device or any other device by a website, containing details of your browsing history on that website among its many uses.
                        </li>
                        <li class="mb-2">
                           <span class="fw-bold">Website</span> refers to Reachomation, accessible from <a href="https://reachomation.com/" class="fw-bold">https://reachomation.com/</a>
                        </li>
                        <li class="mb-2">
                           <span class="fw-bold">You</span> means the individual accessing or using the Website, or a company, or any legal entity on behalf of which such individual is accessing or using the Website, as applicable.
                        </li>
                     </ul>

                     <h3 class="fw-bold text-blue mt-5 border-bottom border-2 pb-2 mb-4">The use of the Cookies</h3>

                     <h5 class="fw-bold">Type of Cookies We Use</h5>
                     <p>
                        Cookies can be "Persistent" or "Session" Cookies. Persistent Cookies remain on your personal computer or mobile device when You go offline, while Session Cookies are deleted as soon as You close your web browser.
                     </p>

                     <p>
                        We use both session and persistent Cookies for the purposes set out below:
                     </p>

                     <h6 class="fw-bold">Necessary / Essential Cookies</h6>
                     <ul>
                        <li class="mb-2">
                           <span class="fw-bold">Type:</span> Session Cookies
                        </li>
                        <li class="mb-2">
                           <span class="fw-bold">Administered by: </span> Us
                        </li>
                        <li class="mb-2">
                           <span class="fw-bold">Purpose:</span> These Cookies are essential to provide You with services available through the Website and to enable You to use some of its features. They help to authenticate users and prevent fraudulent use of user accounts. Without these Cookies, the services that You have asked for cannot be provided, and We only use these Cookies to provide You with those services.
                        </li>
                     </ul>

                     <h6 class="fw-bold">Functionality Cookies</h6>
                     <ul>
                        <li class="mb-2">
                           <span class="fw-bold">Type:</span> Persistent Cookies
                        </li>
                        <li class="mb-2">
                           <span class="fw-bold">Administered by: </span> Us
                        </li>
                        <li class="mb-2">
                           <span class="fw-bold">Purpose:</span> These Cookies allow us to remember choices You make when You use the Website, such as remembering your login details or language preference. The purpose of these Cookies is to provide You with a more personal experience and to avoid You having to re-enter your preferences every time You use the Website.
                        </li>
                     </ul>

                     <h5 class="fw-bold">Your Choices Regarding Cookies</h5>
                     <p>
                        If You prefer to avoid the use of Cookies on the Website, first You must disable the use of Cookies in your browser and then delete the Cookies saved in your browser associated with this website. You may use this option for preventing the use of Cookies at any time.
                     </p>
                     <p>
                        If You do not accept Our Cookies, You may experience some inconvenience in your use of the Website and some features may not function properly.
                     </p>
                     <p>
                        If You'd like to delete Cookies or instruct your web browser to delete or refuse Cookies, please visit the help pages of your web browser.
                     </p>
                     <ul>
                        <li class="mb-2">
                           For the Chrome web browser, please visit this page from Google: <a href="https://support.google.com/accounts/answer/32050" class="fw-bold">https://support.google.com/accounts/answer/32050</a>
                        </li>
                        <li class="mb-2">
                           For the Internet Explorer web browser, please visit this page from Microsoft: <a href="http://support.microsoft.com/kb/278835" class="fw-bold">http://support.microsoft.com/kb/278835</a>
                        </li>
                        <li class="mb-2">
                           For the Firefox web browser, please visit this page from Mozilla: <a href="https://support.mozilla.org/en-US/kb/delete-cookies-remove-info-websites-stored" class="fw-bold">https://support.mozilla.org/en-US/kb/delete-cookies-remove-info-websites-stored</a>
                        </li>
                        <li class="mb-2">
                           For the Safari web browser, please visit this page from Apple: <a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac" class="fw-bold">https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac</a>
                        </li>
                     </ul>

                     <p>
                        For any other web browser, please visit your web browser's official web pages.
                     </p>

                     <h5 class="fw-bold">More Information about Cookies</h5>
                     <p>
                        You can learn more about cookies: What Are Cookies?.
                     </p>

                     <h5 class="fw-bold">Contact Us</h5>
                     <p>
                        If you have any questions about this Cookies Policy, You can contact us:
                     </p>
                     <ul>
                        <li>
                           By visiting this page on our website: <a href="https://reachomation.com/contact" class="fw-bold">https://reachomation.com/contact</a>
                        </li>
                     </ul>

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
