@php
   $fav = array();
   foreach ($favourites as $favourite){
      $fav[$favourite->domain_id] = true;
   }

   $hide = array();
   foreach($hiddens as $hidden) {
      $hide[$hidden->domain_id] = true;
   }
@endphp
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
   <!-- favicon -->
   <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert"></script>
   <!-- Chart JS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
   <title>Revealeds - Reachomation</title>
</head>

<body class="bg-light">
   @include('sections.navbar')

   <div class="container-fluid">
      <div class="row g-0">
         @include('sections.sidebar')
         <main class="col-md-9 ms-sm-auto col-lg-10">
            <div class="py-3">
               <div class="hstack border-bottom gap-3 mb-3 pb-3">
                  <div>
                     <h3 class="fw-bold mb-0">
                        Revealeds
                     </h3>
                  </div>
               </div>

@if(count($sites)>0)
@foreach ($sites as $site)
   @if(isset($hide[$site->domain_id]))
   <div class="card shadow-sm mb-3">
      <div class="card-body pb-2" id="domain_body_{{$site->domain_id}}">
         <div class="hstack mb-2">
            <div>
               <h6 class="text-dark mb-0">
                  <span>
                     <a class="text-decoration-none" href="https://{{$site->website}}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{$site->website}}">{{$site->website}}</a>
                  </span>
               </h6>
            </div>
            <div class="ms-auto">
               <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="red" class="bi bi-eye-slash-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Remove" role="button" tabindex="0" onclick="unhide('{{$site->domain_id}}','{{$site->website}}')"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/></svg>
            </div>
         </div>
      </div>
   </div>    
   @else
   <div class="card shadow-sm mb-3">
      <div class="card-body pb-2" id="domain_body_{{$site->domain_id}}">
         <div class="row">
            <div class="col-sm-8">
               <div class="d-flex">
                  <div class="flex-shrink-0">
                     <img src="https://d1ppzyob8tkz8r.cloudfront.net/{{$site->website}}.png" id="img{{$site->domain_id}}" onerror="javascript: document.getElementById('img{{$site->domain_id}}').src='{{asset('images/logo_thumb.png')}}'" alt="{{$site->website}}" class="img-fluid" width="140">
                  </div>
                  <div class="flex-grow-1 ms-3">
                     <h6 class="mt-2 text-dark">
                        <span>
                           <a class="text-decoration-none" href="https://{{$site->website}}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$site->website}}">{{ $site->website }}</a>
                        </span>
                        <span class="badge rounded-pill ms-2 lastDate text-dark px-3">Business</span>
                        <span class="badge rounded-pill ms-2 lastDate text-dark px-3">Lifestyle</span>
                     </h6>
                     <h5 class="mt-0 mb-0">{{ ucwords(substr($site->title,0,45) ?? '') }}</h5>
                     <div>
                        <small class="font-weight-bold text-muted">
                           <span id="show-more_{{$site->domain_id}}" class="d-none">{{ ucwords($site->description) }}</span>
                           <span id="show-less_{{$site->domain_id}}">{{ ucwords(substr($site->description,0,140)) }}</span>
                           &nbsp;
                           @if (strlen($site->description)>140)
                           <small class="fw-500"><a href="javascript:void(0)" id="show-btn_{{$site->domain_id}}" onclick="showMore('{{$site->domain_id}}')">SHOW MORE</a></small>
                           @endif
                        </small>
                     </div>
                     <div class="mt-1">
                        <span class="badge bg-theme rounded-pill me-2 px-3">Business</span>
                        <span class="badge bg-theme rounded-pill me-2 px-3">Lifestyle</span>
                        <span class="badge bg-theme rounded-pill me-2 px-3">Lifestyle</span>
                     </div>
                     <ul class="list-inline mt-2">
                        @if($site->facebook_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->facebook_url}}" target="_blank">
                              <i class="bi bi-facebook fb" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Facebook" aria-label="Facebook"></i>
                           </a>
                        </li>
                        @endif
                        @if($site->twitter_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->twitter_url}}" target="_blank">
                              <i class="bi bi-twitter twitter" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Twitter" aria-label="Twitter"></i>
                           </a>
                        </li>  
                        @endif
                        @if($site->youtube_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->youtube_url}}" target="_blank">
                              <i class="bi bi-youtube youtube" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="YouTube" aria-label="YouTube"></i>
                           </a>
                        </li>  
                        @endif
                        @if($site->instagram_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->instagram_url}}" target="_blank">
                              <i class="bi bi-instagram instagram" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Instagram" aria-label="Instagram"></i>
                           </a>
                        </li> 
                        @endif
                        @if($site->linkedin_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->linkedin_url}}" target="_blank">
                              <i class="bi bi-linkedin linkedin" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Linkedin" aria-label="Linkedin"></i>
                           </a>
                        </li>  
                        @endif
                        @if($site->pinterest_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->pinterest_url}}" target="_blank">
                              <i class="bi bi-pinterest pinterest" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Pinterest" aria-label="Pinterest"></i>
                           </a>
                        </li>  
                        @endif
                     </ul>
                  </div>
   
               </div>
            </div>
            <div class="col-sm-3">
               <div class="flex-column">
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">Domain Authority: <span class="text-dark">{{$site->da}}</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">SEMrush Traffic: <span class="text-dark">{{$site->sot}}</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">SEMrush Keyword: <span class="text-dark">{{$site->sok}}</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; {{
                   \Carbon\Carbon::parse($site->creation_date)->age }} Years</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     @php
                     $arr = json_decode($site->emails, true);
                     @endphp
                     <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-theme" role="button" tabindex="0" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-html="true" data-bs-trigger="hover" data-bs-content='<div class="d-flex flex-column">{{ Helper::getEmail($arr) }}</div>'>Verified ({{ Helper::countEmail($arr) }})</span></span>
                  </div>
                  <div class="text-nowrap">
                     <span class="fw-500 text-muted">Last updated: <span class="text-dark">{!! date('M Y',strtotime($site->activity_date)) !!}</span></span>
                  </div>
               </div>
            </div>
            <div class="col-sm-1">
               <div class="d-flex flex-column float-end">
                  <div class="mb-2">
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Send Mail" role="button" tabindex="0" onclick="openMail('{{$site->domain_id}}')">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                     </svg>
                  </div>
                  <div class="mb-2">
                     @if(isset($fav[$site->domain_id]))
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="red" class="bi bi-suit-heart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Added" role="button" tabindex="0" onclick="removetoFavourites('{{ $site->domain_id }}','{{ $site->website }}')" id="fav_{{$site->domain_id}}">
                        <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                     </svg>
                     @else
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Favourites" role="button" tabindex="0" onclick="addtoFavourites('{{ $site->domain_id }}','{{ $site->website }}')" id="fav_{{$site->domain_id}}">
                        <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                     </svg> 
                     @endif
                  </div>
                  <div class="mb-2">
                     @php
                        $j=0;
                     @endphp
                     @foreach($collections as $collection)
                        @php
                           $class = false;
                        @endphp
                        @foreach($collectionsData as $collectionData)
                           @if($collectionData->domain_id==$site->domain_id)
                           @if($collection->id==$collectionData->collection_id)
                           @php
                              $j++;
                              $class = true;
                           @endphp
                           @endif  
                           @endif
                        @endforeach
                     @endforeach
                     <div class="dropdown list-dropdown">
                        <a href="#" class="dropdown-toggle text-decoration-none text-dark position-relative" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                           @if ($j>0)
                           <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                              {{$j}}
                           </span>
                           @endif
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to list" role="button" tabindex="0">
                              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </a>
                        <ul class="dropdown-menu mx-0 shadow py-0 dropdown-menu-end" id="list-dropdown" aria-labelledby="dropdownMenuButton1" style="min-width:250px;">
                           @if ($list_count>0)
                           <form class="p-2 bg-light border-bottom">
                              @csrf
                              <input type="search" class="form-control shadow-none" autocomplete="false" placeholder="Search" id="filter-input_{{$site->domain_id}}" onkeyup="filterFunction('{{$site->domain_id}}')">
                           </form> 
                           @endif
                           <div id="autocom-box_{{$site->domain_id}}" style="max-height: 160px;overflow:auto;">
                              @foreach($collections as $collection)
                              <li>
                                 @php
                                    $class = false;
                                 @endphp
                                 @foreach($collectionsData as $collectionData)
                                    @if($collectionData->domain_id==$site->domain_id)
                                    @if($collection->id==$collectionData->collection_id)
                                    
                                    @php
                                       $class = true;
                                    @endphp
                                    @endif  
                                    @endif
                                 @endforeach
                                 <a class="dropdown-item d-flex gap-2 align-items-center" href="javascript:void(0)" id="collection_{{$collection->id}}"  onclick="<?=($class ? 'removetoList' : 'addtoList');?>('{{$collection->id}}','{{$site->domain_id}}','{{$site->website}}')">
                                    <span class="<?=($class ? 'badge bg-theme' : 'badge border border-dark text-dark');?>" id="badge_{{$collection->id}}">{{ substr(ucfirst($collection->name), 0,1) }}</span>
                                    {{ucfirst($collection->name)}}
                                 </a>
                              </li>
                              @endforeach
                           </div>
                           <li><hr class="dropdown-divider my-0"></li>
                           <li class="bg-light py-1"><a class="dropdown-item fw-500" href="javascript:void(0)" onclick="opencreateModal()"><i class="bi bi-plus-lg"></i> Create List</a></li>
                           <li><hr class="dropdown-divider my-0"></li>
                           <li class="bg-light py-1"><a class="dropdown-item fw-500" href="{{route('lists')}}"><i class="bi bi-list"></i> Manage List</a></li>
                        </ul>
                     </div>
                  </div>
                  <div class="mb-2">
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-bar-chart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Traffic History" role="button" tabindex="0" onclick="openTraffic('{{$site->domain_id}}')">
                        <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
                     </svg>
                  </div>
                  <div>
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Hidden" role="button" tabindex="0" onclick="hide('{{$site->domain_id}}','{{$site->website}}')">
                        <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                        <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                     </svg>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>  
   @endif
@endforeach
@else
<div class="card mb-4">
   <div class="card-body">
      <div class="row">
         <div class="col-sm-6 text-center">
            <img src="{{asset('images/empty.svg')}}" width="480" alt="empty">
         </div>
         <div class="col-sm-6">
            <h5 class="ml-3">You're yet to reveal website.</h5>
            <ol>
               <li>If you searched for a particular website, you must enter site URL with domain
                  extension (com, net, org etc)</li>
               <li>Make sure that all words are spelled correctly.</li>
               <li>Try different keywords.</li>
               <li>Try more general keywords.</li>
               <li>Try exploring or searching via 'niches'</li>
            </ol>
            <div class="ml-3">
               <h5><small class="font-weight-bold">Niches suggestions</small></h5>
               <div class="d-flex flex-wrap">
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=astrology" class="text-decoration-none">Astrology</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=casino" class="text-decoration-none">Casino</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=dating" class="text-decoration-none">Dating</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=finance" class="text-decoration-none">Finance</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=entertainment" class="text-decoration-none">Entertainment</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=homedecoration" class="text-decoration-none">Homedecoration</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=tech" class="text-decoration-none">Tech</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=realstate" class="text-decoration-none">Realstate</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=design" class="text-decoration-none">Design</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=game" class="text-decoration-none">Game</a>
                  </div>
                  <div class="alert alert-primary py-1 px-2 rounded-0 shadow-sm me-2">
                     <a href="{{route('search')}}?keyword=fashion" class="text-decoration-none">Fashion</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endif
<div class="d-flex justify-content-center mt-3">
   @if(count($sites)>0)
      {{ $sites->links() }}
   @endif
</div>

            </div>

            @include('sections.footer')
         </main>
      </div>
   </div>

   {{-- toast start --}}
   <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99999">
      <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert"
         aria-live="assertive" aria-atomic="true">
         <div class="d-flex">
            <div class="toast-body toast-msg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
               aria-label="Close"></button>
         </div>
      </div>
   </div>
   {{-- toast end --}}

   {{-- Create email modal start --}}
   <div class="modal fade" id="mail-modal" tabindex="-1">
      <div class="modal-dialog modal-fullscreen">
         <div class="modal-content">
            <div class="modal-header py-2">
               <h5 class="modal-title fw-bold" id="modal-title"></h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-8">
                     <div class="row mb-3">
                        <div class="col-sm-9">
                           <select class="form-select form-select-sm shadow-none" id="site-email">
                              <option value="" selected>Select Email</option>
                           </select>
                        </div>
                        <div class="col-sm-3">
                           <div class="d-grid gap-2">
                              <button type="button" class="btn btn-outline-dark btn-sm fw-500" onclick="openTemplate()">
                                 <i class="bi bi-plus-lg"></i> Insert Template</button>
                           </div>
                        </div>
                     </div>
                     @if (Auth::user()->default_tid != 0)
                        @foreach ($templates as $template)
                           @if ($template->id == Auth::user()->default_tid)
                           <div class="form-floating mb-3">
                              <input type="text" class="form-control rounded-4 shadow-none form-control-sm" value="{{$template->subject}}" placeholder="name@example.com" autocomplete="off" id="subject" onkeyup="subject()">
                              <label for="subject">Subject</label>
                           </div>
                           <textarea id="summernote" name="htmlckeditor" class="form-control">{!! strip_tags(substr($template->body,0,140))!!}</textarea> 
                           @endif 
                        @endforeach
                     @else
                     <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-4 shadow-none form-control-sm" placeholder="name@example.com" autocomplete="off" id="subject" onkeyup="subject()">
                        <label for="subject">Subject</label>
                     </div>
                     <textarea id="summernote" name="htmlckeditor" class="form-control"></textarea>
                     @endif
                     <div class="hstack gap-3 mt-3">
                        <input class="form-control form-control-sm me-auto shadow-none" type="text" placeholder="Send test mail" id="test-email" value="{{Auth::user()->email}}" onkeyup="testEmail()">
                        <button class="btn btn-primary btn-sm text-nowrap shadow-sm fw-500 d-none" type="button" disabled id="wait-test-btn">
                           <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                           <span class="">Wait...</span>
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm text-nowrap shadow-sm fw-500" id="test-btn">Send test mail</button>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="card shadow-sm h-100">
                        <div class="card-header fw-500">
                           Personalization tags
                        </div>
                        <div class="card-body">
                           <div class="alert alert-info p-2 shadow-sm" role="alert">
                              <p class="mb-0"><i class="bi bi-info-circle-fill"></i> Use the merge tags to personalize your campaigns and avoid spam filters:</p>
                           </div>

                           <div class="row mb-3">
                              <div class="col-sm-8">
                                 <div class="alert alert-light border-dark py-1 mb-0 rounded-0" role="alert">
                                    <span class="fw-500"><small>Author</small></span>
                                 </div>
                              </div>
                              <div class="col-sm-4">
                                 <button type="button" class="btn btn-outline-success btn-sm fw-500"><i class="bi bi-dash"></i></button>
                                 <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500" onclick="personalizeAuthor()"><i class="bi bi-plus-lg"></i></button>
                              </div>
                           </div>
   
                           <div class="row mb-3">
                              <div class="col-sm-8">
                                 <div class="alert alert-light border-dark py-1 mb-0 rounded-0" role="alert">
                                    <span class="fw-500"><small>Website</small></span>
                                 </div>
                              </div>
                              <div class="col-sm-4">
                                 <button type="button" class="btn btn-outline-success btn-sm fw-500"><i class="bi bi-dash"></i></button>
                                 <button type="button" class="btn btn-outline-success btn-sm ms-auto fw-500" onclick="personalizeURL()"><i class="bi bi-plus-lg"></i></button>
                              </div>
                           </div>
   
                           <div class="row mb-3">
                              <div class="col-sm-8">
                                 <div class="alert alert-light border-dark py-1 mb-0 rounded-0" role="alert">
                                    <span class="fw-500"><small>Title</small></span>
                                 </div>
                              </div>
                              <div class="col-sm-4">
                                 <button type="button" class="btn btn-outline-success btn-sm fw-500"><i class="bi bi-dash"></i></button>
                                 <button type="button" class="btn btn-outline-success btn-sm fw-500" onclick="personalizeTitle()"><i class="bi bi-plus-lg"></i></button>
                              </div>
                           </div>
                        </div>
                        <div class="card-footer text-muted">
                           <small>If you’d prefer not to receive email from me, <a href="#">unsubscribe</a>
                              here.</small>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer py-2" id="mail-action-section"></div>
         </div>
      </div>
   </div>
   {{-- Create email modal end --}}

   {{-- Traffic History Modal Start --}}
   <div class="modal fade" id="traffic-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-body">
               <canvas class="w-100 chartjs-render-monitor" id="myChart_traffic" width="1507" height="636" style="display: block; height: 724px; width: 1005px;"></canvas>
            </div>
            <div class="modal-footer py-1">
               <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
   {{-- Traffic History Modal End --}}

   {{-- Create list modal start --}}
   <div class="modal fade" tabindex="-1" role="dialog" id="list-modal">
      <div class="modal-dialog" role="document">
         <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
            <div class="modal-header p-4 pb-2 border-bottom-0">
               <h3 class="fw-bold mb-0" id="list-title"></h3>
               <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 pt-0">
               <div class="mt-4">
                  <div class="form-floating mb-3">
                     <input type="text" id="list-name" class="form-control rounded-4 shadow-none" placeholder="List Name (eg. Blogs)" autocomplete="off" onkeyup="listName()">
                     <label for="create-name">List Name (eg. Blogs)</label>
                  </div>
                  <div id="list-action">
                     
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   {{-- Create list modal end --}}



   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script src="js/dashboard.js"></script>
   <script>
      // intialize toast
      var toastLiveExample = document.getElementById('liveToast');

      // intialize tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      })

      // intialize popover
      var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
         return new bootstrap.Popover(popoverTriggerEl)
      })

      $('#summernote').summernote({
         toolbar: [ ["style", ["style"]], ["font", ["bold", "italic", "underline", "fontname", "clear"]], ["color", ["color"]], ["paragraph", ["ul", "ol", "paragraph"]], ["table", ["table"]], ["insert", ["link", "picture", "video"]], ["view", ["codeview", "fullscreen"]], ],
         placeholder: `Hi there,<br><br>I saw your site and liked the content. I am keen to explore advertising opportunities with you.<br>Could I know your charges for publishing a guest post on . We pay via PayPal.<br><br>Looking forward to hearing from you. `,
         tabsize: 2,
         height: 200
      })
   </script>
</body>
</html>
