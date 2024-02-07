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

   <!-- Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <!-- Title -->
   <title>Welcome to Reachomation</title>

</head>

<body>

   <!-- Main start -->
   <main id="main">

      <!-- Header Start -->
      @include('sections.new-front-header')
      <!--Header End -->

      <!-- Banner start -->
      <section class="app__banner d-flex align-items-center justify-content-center position-relative">
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

         <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
               <div class="text-center">
                  <h1 class="fw-bold mt-2 text-white display-5">
                     Meet World's Simplest
                  </h1>
                  <hr class="hr-short mt-2 text-white">
                  <h1 class="fw-bold text-white mt-3">
                     Prospecting and Outreach Automation Tool
                  </h1>
                  <p class="mt-3 mb-3 text-white-50 lead fw-bold">
                     Research, Shortlist and Outreach Bloggers & Companies within Minutes
                  </p>
               </div>
            </div>
            <div class="col-sm-6 offset-sm-3 px-lg-5 px-5">
               <form method="GET" action="{{ url('websites') }}">
                  @csrf
                  <div class="input-group input-group-lg shadow-sm mt-4">
                     <input type="text" class="form-control shadow-none rounded-0 dropdown-toggle" name="q" id="q"
                        placeholder="Search keyword like 'Tech'" aria-describedby="button-addon2" />
                     <button class="btn btn-green rounded-0 shadow-none" type="submit" id="button-addon2"><i
                           class="bi bi-search text-white"></i></button>
                  </div>
               </form>

               <div class="d-flex justify-content-center mt-4">

                  <a href="{{ route('register') }}" class="btn btn-outline-light fw-bold py-3 rounded-1 position-absolute"
                     style="padding: 0.5rem 2.8rem;padding-top:8px;">Sign up for Free <i
                        class="bi bi-chevron-double-right"></i></a>
               </div>
            </div>
         </div>

         <a class="home_arrow d-none d-sm-block" href="#target-down">
            <div class="home_arrow_inner">
               <div class="home_arrow_in"></div>
               <div class="home_arrow_move"></div>
            </div>
         </a>
      </section>
      <!-- Banner end -->

      <!-- Strength start -->
      <section class="app__strength bg-light py-5" id="target-down">
         <div class="container py-lg-5 py-3">
            <div class="row justify-content-center text-center mb-5">
               <div class="col-lg-8 aos-init aos-animate" data-aos="fade-up">
                  <h2 class="mb-3 fw-bold">OUR STRENGTH IN NUMBERS</h2>
                  <hr class="hr-short mt-4">
               </div>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-lg-5 g-3 px-lg-5 justify-content-center">
               <div class="col">
                  <div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
                     data-aos="flip-down" style="background-image: linear-gradient(to bottom,#fc8822,#ed851b);">
                     <div class="card-body">
                        <h5 class="card-title text-center fw-bold text-white">
                           NETWORK STRENGTH
                        </h5>
                        <hr class="hr-short mt-3 text-light">
                        <div class="text-center py-3">
                           <h1 class="fw-bold display-3 text-white">
                              100K<span>+</span>
                           </h1>
                        </div>
                        <div class="d-flex justify-content-center">
                           <div class="border border-2 border-white-50 p-2 w-50 text-center">
                              <span class="mb-0 text-white fw-normal">
                                 WEBSITES
                              </span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col">
                  <div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
                     data-aos="flip-down" style="    background-color: #0aa8e5;">
                     <div class="card-body">
                        <h5 class="card-title text-center fw-bold text-white">
                           VERTICLES COVERED
                        </h5>
                        <hr class="hr-short mt-3 text-light">
                        <div class="text-center py-3">
                           <h1 class="fw-bold display-3 text-white">
                              26<span>+</span>
                           </h1>
                        </div>
                        <div class="d-flex justify-content-center">
                           <div class="border border-2 border-white-50 p-2 w-50 text-center">
                              <span class="mb-0 text-white fw-normal">
                                 NICHES
                              </span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col d-none">
                  <div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
                     data-aos="flip-down" style="background-color: #a67fcd;">
                     <div class="card-body">
                        <h5 class="card-title text-center fw-bold text-white">
                           ACTIVE USAGE
                        </h5>
                        <hr class="hr-short mt-3 text-light">
                        <div class="text-center py-3">
                           <h1 class="fw-bold display-3 text-white">
                              30<span>+</span>
                           </h1>
                        </div>
                        <div class="d-flex justify-content-center">
                           <div class="border border-2 border-white-50 p-2 w-75 text-center">
                              <span class="mb-0 text-white fw-normal">
                                 ACTIVE CLIENTS
                              </span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </section>
      <!-- Strength end -->

      <!-- Prospecting made simpler start -->
      <section class="app__prospecting-made-simpler">
         <div class="container py-lg-5 py-3">
            <div class="row py-lg-4">
               <div class="col-sm-5">
                  <img src="{{ asset('images/elements/mantra-design-purpose.png') }}" class="img-fluid" data-aos="fade-right"
                     width="380" alt="">
               </div>
               <div class="col-sm-7 aos-init aos-animate" data-aos="fade-up">
                  <h3 class="mb-3 fw-bold section-header-text">Prospecting Made Simpler</h3>
                  <div class="w-25 border-3 border-dark border-bottom"></div>

                  <p class="lead fw-normal mt-4">
                     Research bloggers and webmasters with our refined data index for your content
                     collaborations. For sales prospecting, large index of companies is provided with all
                     relevant data
                  </p>
                  <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg shadow-none rounded-0 mt-3"
                     style="padding: 0.5rem 2.8rem;padding-top:8px;">Sign up for Free <i
                        class="bi bi-chevron-double-right"></i></a>
               </div>
            </div>
         </div>
      </section>
      <!-- Prospecting made simpler end -->

      <!-- Outreach on Autopilot start -->
      <section class="app__outreach-on-autopilot bg-light">
         <div class="container py-lg-5 py-3">
            <div class="row py-lg-4">
               <div class="col-sm-7 aos-init aos-animate" data-aos="fade-up">
                  <h3 class="mb-3 fw-bold section-header-text">
                     Outreach on Autopilot
                  </h3>
                  <div class="w-25 border-3 border-dark border-bottom"></div>

                  <p class="lead fw-normal mt-4">
                     Our tool helps you save valuable time by enabling fully automated and personalised outreach,
                     complemented by auto-followups to maximise responses.
                  </p>
                  <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg shadow-none rounded-0 mt-3"
                     style="padding: 0.5rem 2.8rem;padding-top:8px;">Sign up for Free <i
                        class="bi bi-chevron-double-right"></i></a>
               </div>
               <div class="col-sm-5">
                  <img src="{{ asset('images/elements/mantra-purpose.png') }}" class="img-fluid" data-aos="fade-right"
                     width="380" alt="">
               </div>
            </div>
         </div>
      </section>
      <!-- Outreach on Autopilot end -->

      <!-- Latest data and metrics start -->
      <section class="app__prospecting-made-simpler">
         <div class="container py-lg-5 py-3">
            <div class="row py-lg-4">
               <div class="col-sm-5">
                  <img src="{{ asset('images/elements/mantra-ai-purpose.png') }}" class="img-fluid" data-aos="fade-right"
                     width="380" alt="">
               </div>
               <div class="col-sm-7 aos-init aos-animate" data-aos="fade-up">
                  <h3 class="mb-3 fw-bold section-header-text">
                     Latest data and metrics
                  </h3>
                  <div class="w-25 border-3 border-dark border-bottom"></div>

                  <p class="lead fw-normal mt-4">
                     You will find all necessary details of websites to help you shortlist and target your
                     prospects with precision. From 'Site traffic' to 'Authority indicators', we have it all.
                  </p>
                  <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg shadow-none rounded-0 mt-3"
                     style="padding: 0.5rem 2.8rem;padding-top:8px;">Sign up for Free <i
                        class="bi bi-chevron-double-right"></i></a>
               </div>
            </div>
         </div>
      </section>
      <!-- Latest data and metrics end -->

      <!-- How We Trump Our Competitors start -->
      <section class="app__how-we-trump-our-competitors bg-light">
         <div class="container py-lg-5 py-3">
            <div class="row justify-content-center text-center mb-5">
               <div class="col-lg-8 aos-init aos-animate" data-aos="fade-up">
                  <h2 class="mb-3 fw-bold">
                     HOW WE TRUMP OUR COMPETITORS
                  </h2>
                  <hr class="hr-short mt-4">
               </div>
            </div>

            <div class="row row-cols-1 row-cols-md-4 g-4 mt-5">
               <div class="col mt-5">
                  <div
                     class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate position-relative card-shadow"
                     data-aos="flip-down">
                     <div class="card position-absolute top-0 start-50 translate-middle p-1 rounded-0 border-0 shadow">
                        <img src="{{ asset('images/elements/storage-capacity.png') }}" class="img-fluid rounded-0 " width="80"
                           alt="Largest Dataset">
                     </div>
                     <div class="card-body">
                        <h4 class="card-title mt-5 text-center fw-bold">
                           Largest Dataset
                        </h4>
                     </div>
                  </div>
               </div>
               <div class="col mt-5">
                  <div
                     class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate position-relative card-shadow"
                     data-aos="flip-down">
                     <div class="card position-absolute top-0 start-50 translate-middle p-1 rounded-0 border-0 shadow">
                        <img src="{{ asset('images/elements/automation.png') }}" class="img-fluid rounded-0 " width="80"
                           alt="Automated Outreach">
                     </div>
                     <div class="card-body">
                        <h4 class="card-title mt-5 text-center fw-bold">
                           Automated Outreach
                        </h4>
                     </div>
                  </div>
               </div>
               <div class="col mt-5">
                  <div
                     class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate position-relative card-shadow"
                     data-aos="flip-down">
                     <div class="card position-absolute top-0 start-50 translate-middle p-1 rounded-0 border-0 shadow">
                        <img src="{{ asset('images/elements/statistics.png') }}" class="img-fluid rounded-0 " width="80"
                           alt="Shuraa Business Setup">
                     </div>
                     <div class="card-body">
                        <h4 class="card-title mt-5 text-center fw-bold">
                           Latest Metrics
                        </h4>
                     </div>
                  </div>
               </div>
               <div class="col mt-5">
                  <div
                     class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate position-relative card-shadow"
                     data-aos="flip-down">
                     <div class="card position-absolute top-0 start-50 translate-middle p-1 rounded-0 border-0 shadow">
                        <img src="{{ asset('images/elements/lowest-price.png') }}" class="img-fluid rounded-0 " width="80"
                           alt="Shuraa Business Setup">
                     </div>
                     <div class="card-body">
                        <h4 class="card-title mt-5 text-center fw-bold">
                           Lowest Price
                        </h4>
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </section>
      <!-- How We Trump Our Competitors end -->

      <!-- Trusted by 100+ digital agencies and brands start -->
      <section class="app__how-we-trump-our-competitors d-none">
         <div class="container py-lg-5 py-3">
            <div class="row justify-content-center text-center mb-5">
               <div class="col-lg-8 aos-init aos-animate" data-aos="fade-up">
                  <h2 class="mb-3 fw-bold">
                     TRUSTED BY 100+ DIGITAL AGENCIES AND BRANDS
                  </h2>
                  <hr class="hr-short mt-4">
               </div>
            </div>

            <div class="row">
               <div class="col-lg-12">
                  <img src="{{ asset('images/brands.png') }}" class="img-fluid mt-3"
                     data-aos="fade-down" alt="Reachomation clients">
               </div>
            </div>
         </div>
      </section>
      <!-- Trusted by 100+ digital agencies and brands end -->

      <!-- Free trial start -->
      <section class="app__free-trial">
         <div class="container py-lg-5 py-3">
            <div class="row justify-content-center text-center mb-5">
               <div class="col-lg-8 aos-init aos-animate" data-aos="fade-up">
                  <h2 class="mb-3 fw-bold text-white">
                     READY TO START?
                  </h2>
                  <hr class="hr-short mt-4 text-white">
               </div>
            </div>

            <div class="row">
               <div class="col-lg-12 mt-3">
                  <div class="d-flex">
                     <div class="ms-auto">
                        <img src="{{ asset('images/elements/arrow.png') }}" class="img-fluid ms-2 me-2" width="52" height="52"
                           alt="arrow">
                     </div>
                  </div>
                  <div class="d-flex">
                     <div>
                        <h1 class="text-white display-5 fw-bold">
                           Start with a Free forever account
                        </h1>
                     </div>
                     <div class="ms-auto position-relative">
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg fw-bold ms-auto py-2 rounded-1"
                           style="padding: 0.5rem 2.8rem;padding-top:8px;">Sign up <i
                              class="bi bi-chevron-double-right"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Free trial end -->

      <!-- FAQ start -->
      <section class="app__faq bg-green">
         <div class="container py-lg-5 py-3">
            <div class="row justify-content-center text-center mb-5">
               <div class="col-lg-8 aos-init aos-animate" data-aos="fade-up">
                  <h2 class="mb-3 fw-bold text-white">
                     FREQUENTLY ASKED QUESTIONS
                  </h2>
                  <hr class="hr-short mt-4 text-white">
               </div>
            </div>

            <div class="row mt-3">
               <div class="col-lg-6">
                  <div class="accordion accordion-flush" id="accordionFlushExample">
                     <!-- First start -->
                     <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="flush-headingOne">
                           <button class="accordion-button collapsed shadow-none fs-5 mb-0" type="button"
                              data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false"
                              aria-controls="flush-collapseOne">
                              What is Reachomation all about?
                           </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                           aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                           <div class="accordion-body bg-light">
                              <p class="fw-normal text-justify">
                                 Simply put, Reachomation is the world's biggest and highly curated blog (and soon for companies) search engine, which doubles up as an outreach automation tool as well.
                              </p>
                           </div>
                        </div>
                     </div>
                     <!-- First end -->

                     <!-- Second start -->
                     <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="flush-headingTwo">
                           <button class="accordion-button collapsed shadow-none fs-5 mb-0" type="button"
                              data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false"
                              aria-controls="flush-collapseTwo">
                              How will outreach automation save my time?
                           </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                           aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                           <div class="accordion-body bg-light">
                              <p class="fw-normal text-justify">
                                 Not only will you be able to send personalized emails at scale, you will be able to automate an entire email sequence (unlimited auto-follow ups, controlled by certain behavior of recipient(s)) and most importantly, you will be able to target the same prospect with multiple emails(if available) during the same outreach campaign.
                              </p>
                           </div>
                        </div>
                     </div>
                     <!-- Second end -->

                     <!-- Third start -->
                     <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="flush-headingThree">
                           <button class="accordion-button collapsed shadow-none fs-5 mb-0" type="button"
                              data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false"
                              aria-controls="flush-collapseThree">
                              Can I upload my own data for outreach automation?
                           </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse"
                           aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                           <div class="accordion-body bg-light">
                              <p class="fw-normal text-justify">
                                 Yes, you can upload your data. Simply import your data in CSV format and you're set
                              </p>
                           </div>
                        </div>
                     </div>
                     <!-- Third end -->

                     <!-- Seven start -->
                     <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="flush-headingSeven">
                           <button class="accordion-button collapsed shadow-none fs-5 mb-0" type="button"
                              data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false"
                              aria-controls="flush-collapseSeven">
                              Is there any free trial?
                           </button>
                        </h2>
                        <div id="flush-collapseSeven" class="accordion-collapse collapse"
                           aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample">
                           <div class="accordion-body bg-light">
                              <p class="fw-normal text-justify">
                                 Forget trial, Reachomation offers a <span class="fw-bold">“Free forever”</span> plan alongside paid plans so stick with <span class="fw-bold">Free forever</span> till you feel the need to upgrade.
                              </p>
                           </div>
                        </div>
                     </div>
                     <!-- Seven end -->

                     <!-- Nine start -->
                     <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="flush-headingNine">
                           <button class="accordion-button collapsed shadow-none fs-5 mb-0" type="button"
                              data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false"
                              aria-controls="flush-collapseNine">
                              Does Reachomation offer chat support?
                           </button>
                        </h2>
                        <div id="flush-collapseNine" class="accordion-collapse collapse"
                           aria-labelledby="flush-headingNine" data-bs-parent="#accordionFlushExample">
                           <div class="accordion-body bg-light">
                              <p class="fw-normal text-justify">
                                 We offer 24x7 support over Skype and Email. We are always listening, even to our 'Free forever' users.
                              </p>
                           </div>
                        </div>
                     </div>
                     <!-- Seven end -->

                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="accordion accordion-flush" id="accordianTwo">

                     <!-- Fourth start -->
                     <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="flush-headingFour">
                           <button class="accordion-button collapsed shadow-none fs-5 mb-0" type="button"
                              data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false"
                              aria-controls="flush-collapseFour">
                              Whom will this tool help the most?
                           </button>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse"
                           aria-labelledby="flush-headingFour" data-bs-parent="#accordianTwo">
                           <div class="accordion-body bg-light">
                              <p class="fw-normal text-justify">
                                 Reachomation is meant for SEO marketers/Affiliate professionals/Media buyers, who are looking to prospect blogs and webmasters for content marketing, advertising and affiliate collaborations.
                              </p>
                           </div>
                        </div>
                     </div>
                     <!-- Fourth end -->

                     <!-- Fifth start -->
                     <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="flush-headingFive">
                           <button class="accordion-button collapsed shadow-none fs-5 mb-0" type="button"
                              data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false"
                              aria-controls="flush-collapseFive">
                              How will mail bounces and unsubscribes be handled?
                           </button>
                        </h2>
                        <div id="flush-collapseFive" class="accordion-collapse collapse"
                           aria-labelledby="flush-headingFive" data-bs-parent="#accordianTwo">
                           <div class="accordion-body bg-light">
                              <p class="fw-normal text-justify">
                                 We provide data refinement options such as 'email validation' so you never encounter a situation of having too many bounces in a given campaign. In case you skip the in-built mail validation and we track unusual bounce rate, we will auto-pause your campaign till you restart it after investigating the cause.
                              </p>
                           </div>
                        </div>
                     </div>
                     <!-- Fifth end -->

                     <!-- Sixth start -->
                     <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="flush-headingSix">
                           <button class="accordion-button collapsed shadow-none fs-5 mb-0" type="button"
                              data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false"
                              aria-controls="flush-collapseSix">
                              What makes your tool different from other prospecting tools?
                           </button>
                        </h2>
                        <div id="flush-collapseSix" class="accordion-collapse collapse"
                           aria-labelledby="flush-headingSix" data-bs-parent="#accordianTwo">
                           <div class="accordion-body bg-light">
                              <p class="fw-normal">
                                 We are very particular about the quality(read 'accuracy') of our data and that makes us better than most other blogger prospecting tools out there like Pitchbox, NinjaOutreach and so on. Of course, it's also the cost factor since we are the most affordable and 'Value for money' tool out of all. Their present customers are our future customers!
                              </p>
                           </div>
                        </div>
                     </div>
                     <!-- Sixth end -->

                     <!-- Eight start -->
                     <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="flush-headingEight">
                           <button class="accordion-button collapsed shadow-none fs-5 mb-0" type="button"
                              data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false"
                              aria-controls="flush-collapseEight">
                              How do I get started?
                           </button>
                        </h2>
                        <div id="flush-collapseEight" class="accordion-collapse collapse"
                           aria-labelledby="flush-headingEight" data-bs-parent="#accordianTwo">
                           <div class="accordion-body bg-light">
                              <p class="fw-normal">
                                 <a href="{{ url('register') }}">Just sign up</a> with your email, choose your plan type(go for 'free forever') and voila! You can begin prospecting blogs and setting up your outreach campaigns.
                              </p>
                           </div>
                        </div>
                     </div>
                     <!-- Eight end -->

                  </div>
               </div>
               {{-- <div class="col-lg-8 offset-lg-2 mt-3"></div> --}}
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
   </script>
</body>

</html>
