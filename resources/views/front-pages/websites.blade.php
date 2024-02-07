<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="theme-color" content="#0f172a">
   <!-- Bootstrap CSS -->
   <link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet"
      >
   <!-- Bootstrap icons -->
   <link rel="stylesheet" href="/css/bootstrap-icons.css">
   <!-- Manual CSS -->
   <link rel="stylesheet" href="{{asset('css/custom.css')}}">
   <link rel="stylesheet" href="{{asset('css/style.css')}}">
   <!-- favicon -->
   <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <title>Search - Reachomation</title>
</head>

<body class="app__front-search">
   <nav class="navbar navbar-expand-lg navbar-dark py-0 sticky-top bg-body">
      <div class="container">
         <a class="navbar-brand fw-bold fs-4 me-5 pb-0" href="{{  url('/') }}">
            <img src="{{asset('images/reachomation_white_logo1.png')}}" class="img-fluid" width="180" alt="logo">
         </a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="d-flex" action="/search">
               @csrf
               <div class="input-group shadow-sm">
                  <input type="search" class="form-control shadow-none searchInput rounded-0" id="searchInput" placeholder="Search keyword like 'Tech'" value="{{ $_GET['q']}}" aria-describedby="button-addon2" size="35px" aria-label="Search" name="keyword">
                  <button class="btn btn-green shadow-none rounded-0" type="submit" onclick="checkWebSite()" id="button-addon2"><i class="bi bi-search text-white"></i></button>
               </div>
            </form>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
               {{-- <li class="nav-item me-3">
                  <a class="nav-link {{ (request()->is('pricing')) ? 'active' : '' }}" aria-current="page"
                     href="{{  url('pricing') }}">Pricing</a>
               </li> --}}
               {{-- <li class="nav-item me-3">
                  <a class="nav-link {{ (request()->is('blog')) ? 'active' : '' }}" href="#">Blog</a>
               </li>
               <li class="nav-item me-3">
                  <a class="nav-link {{ (request()->is('reviews')) ? 'active' : '' }}"
                     href="{{  url('reviews') }}">Reviews</a>
               </li> --}}
               <li class="nav-item me-3">
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
   
   @if(count($websites)>0)
   <section class="bg-light">
      <div class="container pb-4">
         <div class="card shadow-sm rounded-0 border-0">
            <div class="card-body">
               <div class="hstack gap-3 mb-3 border-bottom pb-2">
                  <h5 class="fw-semibold mb-0">
                     <i class="bi bi-funnel-fill"></i> Filters
                  </h5>
               </div>
               <form class="row g-3">
                  @csrf
                  <div class="col-md-3">
                     <select class="form-select form-select-sm shadow-none" id="da" onchange="filter(this.id)">
                        <option value="" selected>Domain Authority</option>
                        <option value="10">10+</option>
                        <option value="20">20+</option>
                        <option value="30">30+</option>
                        <option value="40">40+</option>
                        <option value="50">50+</option>
                        <option value="60">60+</option>
                        <option value="70">70+</option>
                        <option value="80">80+</option>
                     </select>
                  </div>
                  <div class="col-md-3">
                     <select class="form-select form-select-sm shadow-none" id="tf" onchange="filter(this.id)">
                        <option selected>Trust Flow</option>
                        <option value="5">5+</option>
                        <option value="10">10+</option>
                        <option value="15">15+</option>
                        <option value="20">20+</option>
                        <option value="25">25+</option>
                        <option value="30">30+</option>
                        <option value="35">35+</option>
                        <option value="40">40+</option>
                        <option value="45">45+</option>
                        <option value="50">50+</option>
                        <option value="55">55+</option>
                        <option value="60">60+</option>
                        <option value="65">65+</option>
                        <option value="70">70+</option>
                        <option value="75">75+</option>
                        <option value="80">80+</option>
                     </select>
                  </div>
                  <div class="col-md-3">
                     <select class="form-select form-select-sm shadow-none" id="country" onchange="filter(this.id)">
                        <option selected>COUNTRY OF ORIGIN</option>
                        <option value="us">United States</option>
                        <option value="uk">United Kingdom</option>
                        <option value="ca">Canada</option>
                        <option value="au">Australia</option>
                        <option value="za">South Africa</option>
                        <option value="in">India</option>
                     </select>
                  </div>
                  <div class="col-md-3">
                     <select class="form-select form-select-sm shadow-none" id="sot" onchange="filter(this.id)">
                        <option selected>SEMRUSH TRAFFIC (US)</option>
                        <option value="100">100+</option>
                        <option value="250">250+</option>
                        <option value="500">500+</option>
                        <option value="1000">1000+</option>
                        <option value="2500">2500+</option>
                        <option value="5000">5K+</option>
                        <option value="10000">10K+</option>
                        <option value="15000">15K+</option>
                        <option value="25000">25K+</option>
                        <option value="50000">50K+</option>
                        <option value="100000">100K+</option>
                     </select>
                  </div>
                  <div class="col-12">
                     <label for="semrush" class="form-label"><small class="fw-500">UPDATION FREQUENCY</small></label>
                     <div class="row">
                        <div class="col-sm-1">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="updation" id="inlineRadio1"
                                 value="0" onchange="filter()">
                              <label class="form-check-label" for="inlineRadio1">All</label>
                           </div>
                        </div>
                        <div class="col-sm-2">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="updation" id="inlineRadio2"
                                 value="6" onchange="filter()">
                              <label class="form-check-label" for="inlineRadio2">Last 6 months</label>
                           </div>
                        </div>
                        <div class="col-sm-2">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="updation" id="inlineRadio3"
                                 value="3" onchange="filter()">
                              <label class="form-check-label" for="inlineRadio3">Last 3 months</label>
                           </div>
                        </div>
                        <div class="col-sm-2">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="updation" id="inlineRadio4"
                                 value="1" onchange="filter()">
                              <label class="form-check-label" for="inlineRadio4">Last month</label>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-12">
                     <label for="semrush" class="form-label"><small class="fw-500">TOP LEVEL DOMAIN</small></label>
                     <div class="row">
                        <div class="col-sm-1">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="com" value=".com" onchange="filter()">
                              <label class="form-check-label" for="com">.com</label>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="uk" value=".uk" onchange="filter()">
                              <label class="form-check-label" for="uk">.uk</label>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="net" value=".net" onchange="filter()">
                              <label class="form-check-label" for="net">.net</label>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="ca" value=".ca" onchange="filter()">
                              <label class="form-check-label" for="ca">.ca</label>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="au" value=".au" onchange="filter()">
                              <label class="form-check-label" for="au">.au</label>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="za" value=".za" onchange="filter()">
                              <label class="form-check-label" for="za">.za</label>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="in" value=".in" onchange="filter()">
                              <label class="form-check-label" for="in">.in</label>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>

   <section class="bg-light">
      <div class="container pb-4">
         @foreach($websites as $website)
         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1">
               <div class="row">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="https://d1ppzyob8tkz8r.cloudfront.net/{{$website->website}}.png" id="img{{$website->domain_id}}" alt="{{$website->website}}" class="img-fluid" width="140">
                           <div class="text-center mt-3">
                              <span class="badge rounded-pill bg-info text-dark px-3"><i class="bi bi-info-circle-fill"></i> Sponsored</span>
                           </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">{{ ucwords(substr($website->title,0,45) ?? '') }}</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <a class="text-decoration-none" href="https://{{$website->website}}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$website->website}}">{{ $website->website }}</a>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark px-3">Last updated: {!! date('M Y',strtotime($website->activity_date)) !!}</span>
                           </h6>
                           <div>
                              <small class="font-weight-bold text-muted">
                                 <span>{{ ucwords(substr($website->description,0,160) ?? '') }}</span><br><small class="fw-500"><a href="javascript:void(0)">SHOW MORE</a></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <a href="https://facebook.com/techplutoMedia" target="_blank">
                                    <i class="bi bi-facebook fb" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Facebook" aria-label="Facebook"></i>
                                 </a>
                              </li>  
                                                                                          <li class="list-inline-item">
                                 <a href="https://twitter.com/techpluto" target="_blank">
                                    <i class="bi bi-twitter twitter" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Twitter" aria-label="Twitter"></i>
                                 </a>
                              </li>  
                                                                                          <li class="list-inline-item">
                                 <a href="https://youtube.com/channel/UCk5ynzr2QJtdM-RuW9dj2Gw" target="_blank">
                                    <i class="bi bi-youtube youtube" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="YouTube" aria-label="YouTube"></i>
                                 </a>
                              </li>  
                                                                                           <li class="list-inline-item">
                                 <a href="https://in.linkedin.com/company/techpluto" target="_blank">
                                    <i class="bi bi-linkedin linkedin" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Linkedin" aria-label="Linkedin"></i>
                                 </a>
                              </li>  
                           </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">{{$website->da}}</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">{{$website->tf}}</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">{{$website->sot}}</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">{{$website->sok}}</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; {{
                   \Carbon\Carbon::parse(@$website->creation_date)->age }} Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white" role="button" onclick="infoPopup()">Verified(5)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top" title="Send Mail" role="button" tabindex="0" onclick="infoPopup()">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Favourites" role="button" tabindex="0" onclick="infoPopup()">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to list" role="button" tabindex="0" onclick="infoPopup()">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-bar-chart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top" title="Traffic History" role="button" tabindex="0" onclick="infoPopup()">
                              <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
                           </svg>
                           {{-- <i class="icon-statistics" style="font-size: 1.5em;" data-bs-toggle="tooltip" data-bs-placement="top" title="Traffic History" role="button" tabindex="0" onclick="infoPopup()"></i> --}}
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Hidden" role="button" tabindex="0" onclick="infoPopup()">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div> 
         @endforeach

         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1 position-relative">
               <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001;">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <button type="button" class="btn btn-green btn-sm fw-500 px-4 shadow-none" onclick="infoPopup()">Reveal</button>
                  </div>
               </div>
               <div class="row" style="filter: blur(4px);">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="{{asset('images/logo_thumb.png')}}" alt="..." class="img-fluid" width="140">
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">Xxxxxxxxx | Xxxxxxxx Xx Xxxxxxxx Xxxxxxxxxx X</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <span class="text-primary text-uppercase" href="javascript:void(0)">xxxxxxxxxxxxx</span>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark">Last updated: XXX XXXX</span>
                           </h6>
                           <div>
                              <small class="text-muted">
                                 <span>Xxxxxxxxx Xxxxxx Xxxxxx Xxxx Xxx Xxxxxx Xxxx Xxx Xxxxx Xx Xxxxxxxx Xxx Xxxx. Xxxxxxxxxxxxx Xxxxxx Xxxxx Xxxxxxxx Xxx Xxxxxx Xxx Xxxxxxxx. Xxxxxxx Xxxxxxxx Xx Xx</span><br><small class="fw-500 text-primary"><span>SHOW MORE</span></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <i class="bi bi-facebook fb"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-twitter twitter"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-youtube youtube"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-instagram instagram"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-linkedin linkedin"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-pinterest pinterest"></i>
                              </li>
                            </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; XX
                                 Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white">Verified(XX)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <i class="icon-statistics" style="font-size: 1.5em;"></i>
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div> 

         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1 position-relative">
               <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <button type="button" class="btn btn-green btn-sm fw-500 px-4 shadow-none" onclick="infoPopup()">Reveal</button>
                  </div>
               </div>
               <div class="row" style="filter: blur(4px);">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="{{asset('images/logo_thumb.png')}}" alt="..." class="img-fluid" width="140">
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">Xxxxxxxxx | Xxxxxxxx Xx Xxxxxxxx Xxxxxxxxxx X</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <span class="text-primary text-uppercase" href="javascript:void(0)">xxxxxxxxxxxxx</span>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark">Last updated: XXX XXXX</span>
                           </h6>
                           <div>
                              <small class="text-muted">
                                 <span>Xxxxxxxxx Xxxxxx Xxxxxx Xxxx Xxx Xxxxxx Xxxx Xxx Xxxxx Xx Xxxxxxxx Xxx Xxxx. Xxxxxxxxxxxxx Xxxxxx Xxxxx Xxxxxxxx Xxx Xxxxxx Xxx Xxxxxxxx. Xxxxxxx Xxxxxxxx Xx Xx</span><br><small class="fw-500 text-primary"><span>SHOW MORE</span></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <i class="bi bi-facebook fb"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-twitter twitter"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-youtube youtube"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-instagram instagram"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-linkedin linkedin"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-pinterest pinterest"></i>
                              </li>
                            </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; XX
                                 Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white">Verified(XX)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <i class="icon-statistics" style="font-size: 1.5em;"></i>
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1 position-relative">
               <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <button type="button" class="btn btn-green btn-sm fw-500 px-4 shadow-none" onclick="infoPopup()">Reveal</button>
                  </div>
               </div>
               <div class="row" style="filter: blur(4px);">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="{{asset('images/logo_thumb.png')}}" alt="..." class="img-fluid" width="140">
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">Xxxxxxxxx | Xxxxxxxx Xx Xxxxxxxx Xxxxxxxxxx X</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <span class="text-primary text-uppercase" href="javascript:void(0)">xxxxxxxxxxxxx</span>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark">Last updated: XXX XXXX</span>
                           </h6>
                           <div>
                              <small class="text-muted">
                                 <span>Xxxxxxxxx Xxxxxx Xxxxxx Xxxx Xxx Xxxxxx Xxxx Xxx Xxxxx Xx Xxxxxxxx Xxx Xxxx. Xxxxxxxxxxxxx Xxxxxx Xxxxx Xxxxxxxx Xxx Xxxxxx Xxx Xxxxxxxx. Xxxxxxx Xxxxxxxx Xx Xx</span><br><small class="fw-500 text-primary"><span>SHOW MORE</span></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <i class="bi bi-facebook fb"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-twitter twitter"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-youtube youtube"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-instagram instagram"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-linkedin linkedin"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-pinterest pinterest"></i>
                              </li>
                            </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; XX
                                 Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white">Verified(XX)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <i class="icon-statistics" style="font-size: 1.5em;"></i>
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1 position-relative">
               <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <button type="button" class="btn btn-green btn-sm fw-500 px-4 shadow-none" onclick="infoPopup()">Reveal</button>
                  </div>
               </div>
               <div class="row" style="filter: blur(4px);">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="{{asset('images/logo_thumb.png')}}" alt="..." class="img-fluid" width="140">
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">Xxxxxxxxx | Xxxxxxxx Xx Xxxxxxxx Xxxxxxxxxx X</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <span class="text-primary text-uppercase" href="javascript:void(0)">xxxxxxxxxxxxx</span>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark">Last updated: XXX XXXX</span>
                           </h6>
                           <div>
                              <small class="text-muted">
                                 <span>Xxxxxxxxx Xxxxxx Xxxxxx Xxxx Xxx Xxxxxx Xxxx Xxx Xxxxx Xx Xxxxxxxx Xxx Xxxx. Xxxxxxxxxxxxx Xxxxxx Xxxxx Xxxxxxxx Xxx Xxxxxx Xxx Xxxxxxxx. Xxxxxxx Xxxxxxxx Xx Xx</span><br><small class="fw-500 text-primary"><span>SHOW MORE</span></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <i class="bi bi-facebook fb"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-twitter twitter"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-youtube youtube"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-instagram instagram"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-linkedin linkedin"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-pinterest pinterest"></i>
                              </li>
                            </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; XX
                                 Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white">Verified(XX)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <i class="icon-statistics" style="font-size: 1.5em;"></i>
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1 position-relative">
               <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <button type="button" class="btn btn-green btn-sm fw-500 px-4 shadow-none" onclick="infoPopup()">Reveal</button>
                  </div>
               </div>
               <div class="row" style="filter: blur(4px);">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="{{asset('images/logo_thumb.png')}}" alt="..." class="img-fluid" width="140">
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">Xxxxxxxxx | Xxxxxxxx Xx Xxxxxxxx Xxxxxxxxxx X</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <span class="text-primary text-uppercase" href="javascript:void(0)">xxxxxxxxxxxxx</span>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark">Last updated: XXX XXXX</span>
                           </h6>
                           <div>
                              <small class="text-muted">
                                 <span>Xxxxxxxxx Xxxxxx Xxxxxx Xxxx Xxx Xxxxxx Xxxx Xxx Xxxxx Xx Xxxxxxxx Xxx Xxxx. Xxxxxxxxxxxxx Xxxxxx Xxxxx Xxxxxxxx Xxx Xxxxxx Xxx Xxxxxxxx. Xxxxxxx Xxxxxxxx Xx Xx</span><br><small class="fw-500 text-primary"><span>SHOW MORE</span></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <i class="bi bi-facebook fb"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-twitter twitter"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-youtube youtube"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-instagram instagram"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-linkedin linkedin"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-pinterest pinterest"></i>
                              </li>
                            </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; XX
                                 Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white">Verified(XX)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <i class="icon-statistics" style="font-size: 1.5em;"></i>
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1 position-relative">
               <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <button type="button" class="btn btn-green btn-sm fw-500 px-4 shadow-none" onclick="infoPopup()">Reveal</button>
                  </div>
               </div>
               <div class="row" style="filter: blur(4px);">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="{{asset('images/logo_thumb.png')}}" alt="..." class="img-fluid" width="140">
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">Xxxxxxxxx | Xxxxxxxx Xx Xxxxxxxx Xxxxxxxxxx X</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <span class="text-primary text-uppercase" href="javascript:void(0)">xxxxxxxxxxxxx</span>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark">Last updated: XXX XXXX</span>
                           </h6>
                           <div>
                              <small class="text-muted">
                                 <span>Xxxxxxxxx Xxxxxx Xxxxxx Xxxx Xxx Xxxxxx Xxxx Xxx Xxxxx Xx Xxxxxxxx Xxx Xxxx. Xxxxxxxxxxxxx Xxxxxx Xxxxx Xxxxxxxx Xxx Xxxxxx Xxx Xxxxxxxx. Xxxxxxx Xxxxxxxx Xx Xx</span><br><small class="fw-500 text-primary"><span>SHOW MORE</span></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <i class="bi bi-facebook fb"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-twitter twitter"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-youtube youtube"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-instagram instagram"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-linkedin linkedin"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-pinterest pinterest"></i>
                              </li>
                            </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; XX
                                 Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white">Verified(XX)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <i class="icon-statistics" style="font-size: 1.5em;"></i>
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1 position-relative">
               <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <button type="button" class="btn btn-green btn-sm fw-500 px-4 shadow-none" onclick="infoPopup()">Reveal</button>
                  </div>
               </div>
               <div class="row" style="filter: blur(4px);">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="{{asset('images/logo_thumb.png')}}" alt="..." class="img-fluid" width="140">
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">Xxxxxxxxx | Xxxxxxxx Xx Xxxxxxxx Xxxxxxxxxx X</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <span class="text-primary text-uppercase" href="javascript:void(0)">xxxxxxxxxxxxx</span>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark">Last updated: XXX XXXX</span>
                           </h6>
                           <div>
                              <small class="text-muted">
                                 <span>Xxxxxxxxx Xxxxxx Xxxxxx Xxxx Xxx Xxxxxx Xxxx Xxx Xxxxx Xx Xxxxxxxx Xxx Xxxx. Xxxxxxxxxxxxx Xxxxxx Xxxxx Xxxxxxxx Xxx Xxxxxx Xxx Xxxxxxxx. Xxxxxxx Xxxxxxxx Xx Xx</span><br><small class="fw-500 text-primary"><span>SHOW MORE</span></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <i class="bi bi-facebook fb"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-twitter twitter"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-youtube youtube"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-instagram instagram"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-linkedin linkedin"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-pinterest pinterest"></i>
                              </li>
                            </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; XX
                                 Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white">Verified(XX)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <i class="icon-statistics" style="font-size: 1.5em;"></i>
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1 position-relative">
               <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <button type="button" class="btn btn-green btn-sm fw-500 px-4 shadow-none" onclick="infoPopup()">Reveal</button>
                  </div>
               </div>
               <div class="row" style="filter: blur(4px);">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="{{asset('images/logo_thumb.png')}}" alt="..." class="img-fluid" width="140">
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">Xxxxxxxxx | Xxxxxxxx Xx Xxxxxxxx Xxxxxxxxxx X</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <span class="text-primary text-uppercase" href="javascript:void(0)">xxxxxxxxxxxxx</span>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark">Last updated: XXX XXXX</span>
                           </h6>
                           <div>
                              <small class="text-muted">
                                 <span>Xxxxxxxxx Xxxxxx Xxxxxx Xxxx Xxx Xxxxxx Xxxx Xxx Xxxxx Xx Xxxxxxxx Xxx Xxxx. Xxxxxxxxxxxxx Xxxxxx Xxxxx Xxxxxxxx Xxx Xxxxxx Xxx Xxxxxxxx. Xxxxxxx Xxxxxxxx Xx Xx</span><br><small class="fw-500 text-primary"><span>SHOW MORE</span></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <i class="bi bi-facebook fb"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-twitter twitter"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-youtube youtube"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-instagram instagram"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-linkedin linkedin"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-pinterest pinterest"></i>
                              </li>
                            </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; XX
                                 Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white">Verified(XX)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <i class="icon-statistics" style="font-size: 1.5em;"></i>
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="card shadow-sm mb-3 rounded-0 border-0">
            <div class="card-body pb-1 position-relative">
               <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1001">
                  <i class="bi bi-lock-fill fs-1 text-danger"></i>
                  <div class="mt-2">
                     <button type="button" class="btn btn-green btn-sm fw-500 px-4 shadow-none" onclick="infoPopup()" onclick="infoPopup()">Reveal</button>
                  </div>
               </div>
               <div class="row" style="filter: blur(4px);">
                  <div class="col-sm-8">
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                           <img src="{{asset('images/logo_thumb.png')}}" alt="..." class="img-fluid" width="140">
                        </div>
                        <div class="flex-grow-1 ms-3">
                           <h5 class="mt-0 mb-0">Xxxxxxxxx | Xxxxxxxx Xx Xxxxxxxx Xxxxxxxxxx X</h5>
                           <h6 class="mt-2 text-dark">
                              <span>
                                 <span class="text-primary text-uppercase" href="javascript:void(0)">xxxxxxxxxxxxx</span>
                              </span>
                              <span class="badge rounded-pill ms-2 lastDate text-dark">Last updated: XXX XXXX</span>
                           </h6>
                           <div>
                              <small class="text-muted">
                                 <span>Xxxxxxxxx Xxxxxx Xxxxxx Xxxx Xxx Xxxxxx Xxxx Xxx Xxxxx Xx Xxxxxxxx Xxx Xxxx. Xxxxxxxxxxxxx Xxxxxx Xxxxx Xxxxxxxx Xxx Xxxxxx Xxx Xxxxxxxx. Xxxxxxx Xxxxxxxx Xx Xx</span><br><small class="fw-500 text-primary"><span>SHOW MORE</span></small> </small>
                           </div>
                           <ul class="list-inline mt-2">
                              <li class="list-inline-item">
                                 <i class="bi bi-facebook fb"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-twitter twitter"></i>
                              </li>  
                              <li class="list-inline-item">
                                 <i class="bi bi-youtube youtube"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-instagram instagram"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-linkedin linkedin"></i>
                              </li>
                              <li class="list-inline-item">
                                 <i class="bi bi-pinterest pinterest"></i>
                              </li>
                            </ul>
                        </div>

                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="flex-column">
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Trust Flow: <span class="text-dark">XX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">XXXX</span></span>
                        </div>
                        <div class="text-nowrap mb-1">
                           <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; XX
                                 Years</span></span>
                        </div>
                        <div class="text-nowrap">
                           <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white">Verified(XX)</span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <div class="d-flex flex-column float-end">
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                              <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </div>
                        <div class="mb-2">
                           <i class="icon-statistics" style="font-size: 1.5em;"></i>
                        </div>
                        <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                              <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                              <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <nav>
            <ul class="pagination justify-content-end">
               <li class="page-item active shadow-sm" aria-current="page">
                  <span class="page-link rounded-0">&nbsp;1&nbsp;</span>
               </li>
               <li class="page-item shadow-sm">
                  <a class="page-link shadow-none" href="javascript:void(0)" onclick="infoPopup()"><i class="bi bi-lock-fill text-danger"></i></a>
               </li>
               <li class="page-item shadow-sm">
                  <a class="page-link shadow-none" href="javascript:void(0)" onclick="infoPopup()"><i class="bi bi-lock-fill text-danger"></i></a>
               </li>
               <li class="page-item shadow-sm">
                  <a class="page-link shadow-none" href="javascript:void(0)" onclick="infoPopup()"><i class="bi bi-lock-fill text-danger"></i></a>
               </li>
               <li class="page-item shadow-sm">
                  <a class="page-link shadow-none" href="javascript:void(0)" onclick="infoPopup()"><i class="bi bi-lock-fill text-danger"></i></a>
               </li>
               <li class="page-item shadow-sm">
                  <a class="page-link shadow-none" href="javascript:void(0)" onclick="infoPopup()"><i class="bi bi-lock-fill text-danger"></i></a>
               </li>
               <li class="page-item shadow-sm">
                  <a class="page-link shadow-none" href="javascript:void(0)" onclick="infoPopup()"><i class="bi bi-lock-fill text-danger"></i></a>
               </li>
               <li class="page-item shadow-sm">
                  <a class="page-link shadow-none" href="javascript:void(0)" onclick="infoPopup()"><i class="bi bi-lock-fill text-danger"></i></a>
               </li>
               <li class="page-item shadow-sm">
                  <a class="page-link shadow-none" href="javascript:void(0)" onclick="infoPopup()"><i class="bi bi-lock-fill text-danger"></i></a>
               </li>
               <li class="page-item shadow-sm">
                  <a class="page-link shadow-none rounded-0" href="javascript:void(0)" onclick="infoPopup()"><i class="bi bi-lock-fill text-danger"></i></a>
               </li>
            </ul>
         </nav>

      </div>
   </section>
   @else
       
   @endif

   @include('sections.new-footer')

   {{-- Edit list modal start --}}
   <div class="modal fade " tabindex="-1" role="dialog" id="infoPopup">
      <div class="modal-dialog" role="document">
         @if (Auth::check())
         <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
            <div class="modal-header p-4 pb-2 border-bottom-0">
               <h4 class="fw-bold mb-0 ms-auto">Go to dashboard</h4>
               <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
               <div class="mt-4">
                  <img src="{{asset('images/portfolio_website.svg')}}" class="img-fluid" alt="...">
                  <p class="text-center fw-500 mt-3">Go to dashboard to access 100k+ blogs and business sites.</p>
                  <div class="text-center">
                     <a href="{{route('dashboard')}}" class="w-50 mb-2 btn rounded-pill btn-primary" >Dashboard</a>
                  </div>
               </div>
            </div>
         </div>
         @else
          <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
            <div class="modal-header p-4 pb-2 border-bottom-0">
               <h4 class="fw-bold mb-0">Sign up now to access & outreach 100k+ blogs and business sites.</h4>
               <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
               <div class="mt-4">
                  <img src="{{asset('images/portfolio_website.svg')}}" class="img-fluid" alt="...">
                  <div class="text-center mt-4">
                     <a href="{{route('register')}}" class="w-50 mb-2 btn rounded-pill btn-green shadow-none" type="submit">Sign up for free</a>
                  </div>
               </div>
            </div>
         </div>
         @endif
      </div>
   </div>
   {{-- Edit list modal end --}}

   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

   <script>
      // intialize tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      })

      const infoPopup = () =>{
         $("#infoPopup").modal('show');
      }

      const filter = (id) =>{
         $("#"+id).addClass('bg-primary text-white');
         $("#infoPopup").modal('show');
      }
   </script>
</body>

</html>
