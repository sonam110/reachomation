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
   <title>Reviews - Reachomation</title>
</head>

<body class="bg-body">
   @include('sections.front-navbar')

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

      <svg viewBox="0 0 960 540" width="100%" height="100%" preserveAspectRatio="xMidYMax slice"
         xmlns="http://www.w3.org/2000/svg"
         class="position-absolute inset-0 pointer-events-none ng-tns-c213-0 text-muted">
         <g fill="none" stroke="currentColor" strokeWidth="80"
            class="text-gray-700 text-muted opacity-25 ng-tns-c213-0">
            <circle r="234" cx="196" cy="23" class="ng-tns-c213-0"></circle>
            <circle r="234" cx="890" cy="491" class="ng-tns-c213-0"></circle>
         </g>
      </svg>
      <div class="h-100 text-center position-relative">
         <div class="container p-5">
            <div class="row">
               <div class="col-lg-10 offset-lg-1">
                  <h1 class="fw-bold mt-2 text-white">Reviews & Testimonials</h1>
                  <h5 class="mt-5 mb-4 text-white px-4" style="font-weight: 500;">Check Out Real Reviews &
                     Testimonials about our platform.</h5>
                  <h5 class="mb-4 text-white px-4" style="font-weight: 500;">We have morphed client faces as we
                     have quoted their feedback/opinions <br>“without asking for the same”</h5>
               </div>
               <div class="col-sm-6 offset-sm-3">
                  <div class="d-flex justify-content-center mt-5">
                     <img src="{{asset('images/team-5.jpg')}}" alt="" width="52" height="52"
                        class="rounded-circle img-thumbnail collase" />
                     <img src="{{asset('images/team-4.jpg')}}" alt="" width="52" height="52"
                        class="rounded-circle img-thumbnail collase" />
                     <img src="{{asset('images/team-3.jpg')}}" alt="" width="52" height="52"
                        class="rounded-circle img-thumbnail collase" />
                     <img src="{{asset('images/team-2.jpg')}}" alt="" width="52" height="52"
                        class="rounded-circle img-thumbnail collase" />
                     <img src="{{asset('images/team-1.jpg')}}" alt="" width="52" height="52"
                        class="rounded-circle img-thumbnail collase" />
                  </div>
                  <h6 class="mt-2 mb-4 text-white-50">Trusted by 100+ SEO Agencies & Professionals</h6>
                  <div class="d-flex justify-content-center">
                     <a href="/pricing" class="btn btn-outline-light btn-lg font-weight-bold"
                        style="padding: 0.5rem 2.8rem;padding-top:8px;">Get Started <i
                           class="bi bi-chevron-double-right"></i></a>
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
                  <h1 class="fw-bold text-center mb-5">Word From Our Clients</h1>
               </div>
               <div class="col-sm-12">
                  <div class="row row-cols-1 row-cols-md-3 g-4">
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm ">
                           <div class="card-body">
                              <img src="{{asset('images/team-5.jpg')}}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);" />
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
                              <img src="{{asset('images/team-4.jpg')}}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);" />
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
                              <img src="{{asset('images/team-3.jpg')}}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);" />
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
                              <img src="{{asset('images/team-2.jpg')}}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);" />
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
                              <img src="{{asset('images/team-1.jpg')}}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);" />
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
                              <img src="{{asset('images/team-4.jpg')}}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);" />
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
   <section class="bg-white">
      <div class="container py-5">
         <div class="row">
            <div class="col-lg-12">
               <h1 class="fw-bold text-center">Contact For More Information</h1>
            </div>
            <div class="col-sm-8 offset-sm-2 mt-4 text-center">
               <h5 style="font-weight: 500;">If you have any concerns about our Privacy Policy or have any queries you
                  can</h5>
               <h5 class="mt-3" style="font-weight: 500;">connect with us over our Email: <a
                     href="mailto:support@reachomation.com" class="fw-bold">support@reachomation.com</a></h5>
               <hr class="mt-4">
               <h3 class="fw-bold text-center">Talk To Our Support Team</h3>
               <button class="btn btn-outline-dark btn-lg mt-4 shadow-sm"
                  style="padding: 0.5rem 2.8rem;padding-top:8px;font-weight:600;">Start the Conversation <i
                     class="bi bi-chevron-double-right"></i></button>
            </div>
         </div>
      </div>
   </section>

   @include('sections.footer')

   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>
