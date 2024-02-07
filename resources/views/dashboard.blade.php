@extends('layouts.master')
@section('content')
@php
$fav = array();
foreach ($favourites as $favourite){
$fav[$favourite->domain_id] = true;
}
$hide = array();
foreach($hiddens as $hidden) {
$hide[$hidden->domain_id] = true;
}
$reveal = array();
foreach($revealeds as $revealed) {
$reveal[$revealed->domain_id] = true;
}
@endphp
<div class="pt-3">
   <div class="hstack border-bottom border-1 gap-3 mb-3 pb-3">
      <div>
         <h3 class="fw-bold mb-0 text-capitalize">
            Welcome {{ Helper::split_name(Auth::user()->name) }},
         </h3>
      </div>
      <div class="ms-auto">
         <a href="{{ url('/schedulecampaign') }}" type="button" class="btn btn-green shadow-sm fw-500">
            <i class="bi bi-plus-lg"></i> Create New Campaign
         </a>
      </div>
   </div>
   <div class="card shadow-sm border-0 rounded-1 bg-light">
      <div class="card-body">
         <h4 class="text-center fw-bold mb-3 text-green">Browse 200000+ sites across 16 Categories</h4>
         <div class="row row-cols-1 row-cols-md-4 g-2">
            <div class="col">
               <a href="{{ url('search?keyword=tech') }}" class="text-decoration-none">
                  <div class="card h-100 shadow-sm border-0 rounded-1">
                     <div class="card-body py-2">
                        <div class="hstack gap-3">
                           <div>
                              <p class="mb-0 text-muted fw-600">
                                 <small>Technology</small>
                              </p>
                              <h5 class="mb-0 fw-bold">
                                 00
                              </h5>
                           </div>
                           {{-- <div class="ms-auto">
                              <i class="bi bi-stack fs-2"></i>
                           </div> --}}
                        </div>
                     </div>
                  </div>
               </a>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Startups</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Business</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Marketing</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Real Estate</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Finance</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Automobile</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Gaming</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Pets</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Health</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Food</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Lifestyle</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Travel</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>News</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Education</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>

            <div class="col">
               <div class="card h-100 shadow-sm border-0 rounded-1">
                  <div class="card-body py-2">
                     <div class="hstack gap-3">
                        <div>
                           <p class="mb-0 text-muted fw-600">
                              <small>Home Interior</small>
                           </p>
                           <h5 class="mb-0 fw-bold">
                              00
                           </h5>
                        </div>
                        {{-- <div class="ms-auto">
                           <i class="bi bi-stack fs-2"></i>
                        </div> --}}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>
<div class="mt-4">
   <div class="hstack gap-3 mb-3">
      <div>
         <h4 class="fw-bold mb-0">Recommended for you</h4>
      </div>
   </div>
   @foreach($nicheData as $category => $sites)
   @foreach($sites as $site)
   @if(isset($reveal[$site->domain_id]))
   @if(isset($hide[$site->domain_id]))
   <div class="card shadow-sm mb-3 rounded-0 border-0">
      <div class="card-body pb-2" id="domain_body_{{$site->domain_id}}">
         <div class="hstack mb-2">
            <div>
               <h6 class="text-dark mb-0">
                  <span>
                     <a class="text-decoration-none" href="https://{{$site->website}}" target="_blank"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-original-title="{{$site->website}}">{{$site->website}}</a>
                  </span>
               </h6>
            </div>
            <div class="ms-auto">
               {{-- <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="red" class="bi bi-eye-slash-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left" title="Remove" id="hide-unhide" role="button" tabindex="0" data-domain-id="{{$site->domain_id}}" data-website="{{$site->website}}">
                  <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                  <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
               </svg> --}}
               <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="red"
                  class="bi bi-eye-slash-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left"
                  title="Remove" id="hide-unhide" role="button" tabindex="0"
                  onclick="unhide('{{$site->domain_id}}','{{$site->website}}')">
                  <path
                     d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                  <path
                     d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
               </svg>
            </div>
         </div>
      </div>
   </div>
   @else
   <div class="card shadow-sm mb-3 rounded-0 border-0 position-relative">
      <div class="position-absolute bottom-0 start-0">
         <span class="badge bg-secondary rounded-0 text-dark px-2 text-white fw-normal" role="button"
            onclick="report()"><i class="bi bi-flag-fill"></i> Report</span>
      </div>
      <div class="card-body pb-2" id="domain_body_{{$site->domain_id}}">
         <div class="row">
            <div class="col-sm-8">
               <div class="d-flex">
                  <div class="flex-shrink-0">
                     @if(isRevealed($site->domain_id)== true)
                     <img src="https://d1ppzyob8tkz8r.cloudfront.net/{{$site->website}}.png"
                        id="img{{$site->domain_id}}"
                        onerror="javascript: document.getElementById('img{{$site->domain_id}}').src='{{asset('images/blurr.jpg')}}'"
                        alt="{{$site->website}}" class="img-fluid" width="140">
                     <div class="d-flex flex-column mt-2 text-center">
                        <div>
                           <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                           <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                           <i class="bi bi-star-fill text-warning" style="font-size: 14px;"></i>
                           <i class="bi bi-star-half text-warning" style="font-size: 14px;"></i>
                           <i class="bi bi-star text-warning" style="font-size: 14px;"></i>
                        </div>
                        <div>
                           <p class="text-primary" role="button" style="font-size: 14px;" onclick="ratings()">4,305
                              ratings</p>
                        </div>
                     </div>
                     @else
                     <img src="{{asset('images/
blurr.jpg')}}" alt="..." class="img-fluid" width="140">
                     <span class='badge rounded-pill ms-2 lastDate text-dark px-3 text-red credit-show-div'>{{
                        $site->credit_cost }} credits</span>
                     @endif
                     <div class="text-center mt-3">
                     </div>
                  </div>

                  <div class="flex-grow-1 ms-3">
                     <h6 class="mt-2 text-dark">
                        <span>
                           <a class="text-decoration-none" href="https://{{$site->website}}" target="_blank"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="{{$site->website}}">{{
                              $site->website }}</a>
                        </span>
                        @php
                        $cat = explode(',', $site->tag_category);
                        $categories = array_slice($cat, 0, 2);
                        foreach ($categories as $category) {
                        echo "<span class='badge rounded-pill ms-2 lastDate text-dark px-3'>$category</span>";
                        }
                        @endphp

                     </h6>

                     <h5 class="mt-0 mb-0">{{ ucwords(substr($site->title,0,45) ?? '') }}</h5>
                     <div>
                        <small class="font-weight-bold text-muted">
                           <span id="show-more_{{$site->domain_id}}" class="d-none">{{ ucwords($site->description)
                              }}</span>
                           <span id="show-less_{{$site->domain_id}}">{{ ucwords(substr($site->description,0,140))
                              }}</span>
                           &nbsp;
                           @if (strlen($site->description)>140)
                           {{-- <small class="fw-500"><a href="javascript:void(0)" id="show-btn_{{$site->domain_id}}" data-domain-id="{{$site->domain_id}}">SHOW MORE</a></small> --}}
                           <small class="fw-500"><a href="javascript:void(0)" id="show-btn_{{$site->domain_id}}"
                                 onclick="showMore('{{$site->domain_id}}')">SHOW MORE</a></small>
                           @endif
                        </small>
                     </div>
                     <div class="mt-1">
                        @php
                        $tags_arr = json_decode($site->tags, true);
                        $i =0;
                        @endphp
                        @if (is_array($tags_arr))
                        @foreach ($tags_arr as $key => $value)
                        @php
                        $i++;
                        @endphp
                        @if ($i<=3) <span class="badge bg-theme rounded-pill me-2 px-3">{{ucfirst($key)}}</span>
                           @endif
                           @endforeach
                           @endif
                     </div>
                     <ul class="list-inline mt-2">
                        @if($site->facebook_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->facebook_url}}" target="_blank">
                              <i class="bi bi-facebook fb" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                 data-bs-original-title="Facebook" aria-label="Facebook"></i>
                           </a>
                        </li>
                        @endif
                        @if($site->twitter_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->twitter_url}}" target="_blank">
                              <i class="bi bi-twitter twitter" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                 data-bs-original-title="Twitter" aria-label="Twitter"></i>
                           </a>
                        </li>
                        @endif
                        @if($site->youtube_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->youtube_url}}" target="_blank">
                              <i class="bi bi-youtube youtube" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                 data-bs-original-title="YouTube" aria-label="YouTube"></i>
                           </a>
                        </li>
                        @endif
                        @if($site->instagram_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->instagram_url}}" target="_blank">
                              <i class="bi bi-instagram instagram" data-bs-toggle="tooltip" data-bs-placement="top"
                                 title="" data-bs-original-title="Instagram" aria-label="Instagram"></i>
                           </a>
                        </li>
                        @endif
                        @if($site->linkedin_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->linkedin_url}}" target="_blank">
                              <i class="bi bi-linkedin linkedin" data-bs-toggle="tooltip" data-bs-placement="top"
                                 title="" data-bs-original-title="Linkedin" aria-label="Linkedin"></i>
                           </a>
                        </li>
                        @endif
                        @if($site->pinterest_url!='')
                        <li class="list-inline-item">
                           <a href="https://{{$site->pinterest_url}}" target="_blank">
                              <i class="bi bi-pinterest pinterest" data-bs-toggle="tooltip" data-bs-placement="top"
                                 title="" data-bs-original-title="Pinterest" aria-label="Pinterest"></i>
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
                     <span class="fw-500 text-muted">Domain Authority: <span
                           class="text-dark">{{$site->da}}</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">SEMrush Traffic(US): <span
                           class="text-dark">{{$site->sot}}</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">SEMrush Keyword(US): <span
                           class="text-dark">{{$site->sok}}</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; {{
                   \Carbon\Carbon::parse($site->creation_date)->age }} Years</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     @php
                     $arr = json_decode($site->emails, true);
                     @endphp
                     <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white"
                           role="button" tabindex="0" data-bs-container="body" data-bs-toggle="popover"
                           data-bs-placement="top" data-bs-html="true" data-bs-trigger="hover"
                           data-bs-content='<div class="d-flex flex-column">{{ Helper::getEmail($arr) }}</div>'>Verified
                           ({{ Helper::countEmail($arr) }})</span></span>
                  </div>
                  <div class="text-nowrap">
                     <span class="fw-500 text-muted">Last updated: <span class="text-dark">{!! date('M
                           Y',strtotime($site->activity_date)) !!}</span></span>
                  </div>
               </div>
            </div>
            <div class="col-sm-1">
               <div class="d-flex flex-column float-end">
                  <div class="mb-2">
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor"
                        class="bi bi-envelope-fill mail-id" viewBox="0 0 16 16" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Send Mail" role="button" tabindex="0"
                        onclick="openMail('{{$site->domain_id}}')">
                        <path
                           d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z" />
                     </svg>
                  </div>
                  <div class="mb-2">
                     @if(isset($fav[$site->domain_id]))
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="red"
                        class="bi bi-suit-heart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Added" role="button" tabindex="0"
                        onclick="removetoFavourites('{{ $site->domain_id }}','{{ $site->website }}')"
                        id="fav_{{$site->domain_id}}">
                        <path
                           d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z" />
                     </svg>
                     @else
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor"
                        class="bi bi-suit-heart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Add to Favourites" role="button" tabindex="0"
                        onclick="addtoFavourites('{{ $site->domain_id }}','{{ $site->website }}')"
                        id="fav_{{$site->domain_id}}">
                        <path
                           d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z" />
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
                        <a href="#" class="dropdown-toggle text-decoration-none text-dark position-relative"
                           id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                           @if ($j>0)
                           <span
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success text-white">
                              {{$j}}
                           </span>
                           @endif
                           <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor"
                              class="bi bi-list" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="left"
                              title="Add to list" role="button" tabindex="0">
                              <path fill-rule="evenodd"
                                 d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                           </svg>
                        </a>
                        <ul class="dropdown-menu mx-0 shadow py-0 dropdown-menu-end" id="list-dropdown"
                           aria-labelledby="dropdownMenuButton1" style="min-width:250px;">
                           @if ($list_count>0)
                           <form class="p-2 bg-light border-bottom">
                              @csrf
                              <input type="search" class="form-control shadow-none" autocomplete="false"
                                 placeholder="Search" id="filter-input_{{$site->domain_id}}"
                                 onkeyup="filterFunction('{{$site->domain_id}}')">
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
                                 <a class="dropdown-item d-flex gap-2 align-items-center" href="javascript:void(0)"
                                    id="collection_{{$collection->id}}"
                                    onclick="<?=($class ? 'removetoList' : 'addtoList');?>('{{$collection->id}}','{{$site->domain_id}}','{{$site->website}}')">
                                    <span
                                       class="<?=($class ? 'badge bg-theme text-white' : 'badge border border-dark text-dark');?>"
                                       id="badge_{{$collection->id}}">{{ substr(ucfirst($collection->name), 0,1)
                                       }}</span>
                                    {{ucfirst($collection->name)}}
                                 </a>
                              </li>
                              @endforeach
                           </div>
                           <li>
                              <hr class="dropdown-divider my-0">
                           </li>
                           <li class="bg-light py-1"><a class="dropdown-item fw-500" href="javascript:void(0)"
                                 onclick="opencreateModal()"><i class="bi bi-plus-lg"></i> Create List</a></li>
                           <li>
                              <hr class="dropdown-divider my-0">
                           </li>
                           <li class="bg-light py-1"><a class="dropdown-item fw-500" href="{{route('lists')}}"><i
                                    class="bi bi-list"></i> Manage List</a></li>
                        </ul>
                     </div>
                  </div>
                  <div class="mb-2">
                     @if(isRevealed($site->domain_id)== true)
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor"
                        class="bi bi-bar-chart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Traffic History" role="button" tabindex="0"
                        onclick="openTraffic('{{$site->domain_id}}')">
                        <path
                           d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z" />
                     </svg>
                     @else
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor"
                        class="bi bi-bar-chart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Traffic History" role="button" tabindex="0"
                        onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')">
                        <path
                           d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z" />
                     </svg>

                     @endif
                  </div>
                  <div>
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor"
                        class="bi bi-eye-slash-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Add to Hidden" role="button" tabindex="0"
                        onclick="hide('{{$site->domain_id}}','{{$site->website}}')">
                        <path
                           d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                        <path
                           d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                     </svg>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   @endif
   @else
   <div class="card shadow-sm mb-3 rounded-0 border-0">
      <div class="card-body pb-1">
         <div class="row">
            <div class="col-sm-8">
               <div class="d-flex">
                  <div class="flex-shrink-0">
                     @if(isRevealed($site->domain_id)== true)
                     <img src="https://d1ppzyob8tkz8r.cloudfront.net/{{$site->website}}.png"
                        id="img{{$site->domain_id}}"
                        onerror="javascript: document.getElementById('img{{$site->domain_id}}').src='{{asset('images/blurr.jpg')}}'"
                        alt="{{$site->website}}" class="img-fluid" width="140">

                     @else
                     <img src="{{asset('images/
blurr.jpg')}}" alt="..." class="img-fluid" width="140">
                     <span class='badge rounded-pill ms-2 lastDate text-dark px-3 text-red credit-show-div'>{{
                        $site->credit_cost }} credits</span>
                     @endif

                     <div class="text-center mt-3">
                        @if (Auth::user()->credits<$site->credit_cost)
                           <span class="badge rounded-pill bg-warning text-dark px-4" role="button" tabindex="0"
                              onclick="balancelow('{{$site->credit_cost}}')">Reveal</span>
                           @else
                           @if (Auth::user()->dont_show==1)
                           <span class="badge rounded-pill bg-warning text-dark px-4" role="button" tabindex="0"
                              onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')">Reveal</span>
                           @else
                           <span class="badge rounded-pill bg-warning text-dark px-4" role="button" tabindex="0"
                              onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')">Reveal</span>
                           @endif
                           @endif
                     </div>
                  </div>
                  <div class="flex-grow-1 ms-3">
                     <h6 class="mt-2 text-dark">
                        @php
                        $extension = pathinfo($site->website, PATHINFO_EXTENSION);
                        $domain = substr($site->website, 0, strrpos($site->website, '.'));
                        $length = strlen($domain);
                        $firsttwochar = substr($domain,0,2);
                        $lastchar = substr($domain, -1);
                        $star = '';
                        for ($i=0; $i <= $length ; $i++) { $star .='*' ; } @endphp <span>
                           <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                              title="{{ucfirst($firsttwochar).$star.$lastchar.'.'.$extension}}">{{ucfirst($firsttwochar).$star.$lastchar.'.'.$extension}}</span>
                           </span>
                           @php
                           $cat = explode(',', $site->tag_category);
                           $categories = array_slice($cat, 0, 2);
                           foreach ($categories as $category) {
                           echo "<span class='badge rounded-pill ms-2 lastDate text-dark px-3'>$category</span>";
                           }
                           @endphp
                     </h6>
                     <div class="placeholder-glow position-relative">
                        {{-- <div class="position-absolute top-50 start-50 translate-middle text-center"
                           style="z-index: 1001;">
                           <h5 class="mb-0">Site Description</h5>
                        </div> --}}
                        <span class="placeholder col-12"></span>
                        <span class="placeholder col-12 placeholder-sm"></span>
                        <span class="placeholder col-12 placeholder-sm"></span>
                     </div>
                     <div class="mt-1">
                        @php
                        $tags_arr = json_decode($site->tags, true);
                        $i =0;
                        @endphp
                        @if (is_array($tags_arr))
                        @foreach ($tags_arr as $key => $value)
                        @php
                        $i++;
                        @endphp
                        @if ($i<=3) <span class="badge bg-theme rounded-pill me-2 px-3">{{ucfirst($key)}}</span>
                           @endif
                           @endforeach
                           @endif
                     </div>
                     <ul class="list-inline mt-2">
                        @if($site->facebook_url!='')
                        <li class="list-inline-item">
                           <i class="bi bi-facebook fb" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                              data-bs-original-title="Facebook" aria-label="Facebook" role="button" tabindex="0"
                              onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')"></i>
                        </li>
                        @endif
                        @if($site->twitter_url!='')
                        <li class="list-inline-item">
                           <i class="bi bi-twitter twitter" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                              data-bs-original-title="Twitter" aria-label="Twitter" role="button" tabindex="0"
                              onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')"></i>
                        </li>
                        @endif
                        @if($site->youtube_url!='')
                        <li class="list-inline-item">
                           <i class="bi bi-youtube youtube" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                              data-bs-original-title="YouTube" aria-label="YouTube" role="button" tabindex="0"
                              onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')"></i>
                        </li>
                        @endif
                        @if($site->instagram_url!='')
                        <li class="list-inline-item">
                           <i class="bi bi-instagram instagram" data-bs-toggle="tooltip" data-bs-placement="top"
                              title="" data-bs-original-title="Instagram" aria-label="Instagram" role="button"
                              tabindex="0" onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')"></i>
                        </li>
                        @endif
                        @if($site->linkedin_url!='')
                        <li class="list-inline-item">
                           <i class="bi bi-linkedin linkedin" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                              data-bs-original-title="Linkedin" aria-label="Linkedin" role="button" tabindex="0"
                              onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')"></i>
                        </li>
                        @endif
                        @if($site->pinterest_url!='')
                        <li class="list-inline-item">
                           <i class="bi bi-pinterest pinterest" data-bs-toggle="tooltip" data-bs-placement="top"
                              title="" data-bs-original-title="Pinterest" aria-label="Pinterest" role="button"
                              tabindex="0" onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')"></i>
                        </li>
                        @endif
                     </ul>
                  </div>
               </div>
            </div>
            <div class="col-sm-3">
               <div class="flex-column">
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">Domain Authority: <span
                           class="text-dark">{{$site->da}}</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">SEMrush Traffic(US): <span
                           class="text-dark">{{$site->sot}}</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">SEMrush Keyword(US): <span
                           class="text-dark">{{$site->sok}}</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt; {{
                   \Carbon\Carbon::parse($site->creation_date)->age }} Years</span></span>
                  </div>
                  <div class="text-nowrap mb-1">
                     @php
                     $arr = json_decode($site->emails, true);
                     @endphp
                     @if (Auth::user()->credits<$site->credit_cost)
                        <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white"
                              role="button" tabindex="0" onclick="balancelow('{{$site->credit_cost}}')">Verified ({{
                              Helper::countEmail($arr) }})</span></span>
                        @else
                        @if (Auth::user()->dont_show==1)
                        <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white"
                              role="button" tabindex="0"
                              onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')">Verified ({{
                              Helper::countEmail($arr) }})</span></span>
                        @else
                        <span class="fw-500 text-muted">Found Contacts: <span class="badge bg-blue text-white"
                              role="button" tabindex="0"
                              onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')">Verified ({{
                              Helper::countEmail($arr) }})</span></span>
                        @endif
                        @endif
                  </div>
                  <div class="text-nowrap">
                     <span class="fw-500 text-muted">Last updated: <span class="text-dark">{!! date('M
                           Y',strtotime($site->activity_date)) !!}</span></span>
                  </div>
               </div>
            </div>
            <div class="col-sm-1">
               <div class="d-flex flex-column float-end">
                  <div class="mb-2">
                     @if(isRevealed($site->domain_id)== true)
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor"
                        class="bi bi-bar-chart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Traffic History" role="button" tabindex="0"
                        onclick="openTraffic('{{$site->domain_id}}')">
                        <path
                           d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z" />
                     </svg>
                     @else
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor"
                        class="bi bi-bar-chart-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Traffic History" role="button" tabindex="0"
                        onclick="reveal('{{$site->domain_id}}','{{$site->credit_cost}}')">
                        <path
                           d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z" />
                     </svg>

                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>

   </div>

   @endif
   @endforeach
   @endforeach
</div>
@php

if(auth()->user()->niches =='') {
echo "<script>
   window.onload = function(){ $('#welcome-modal').modal('show'); } 
</script>";

}
@endphp
{{-- Welcome Modal start --}}
<!-- <div class="modal fade " tabindex="-1" role="dialog" id="welcome-niches">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
<div class="modal-content shadow" style="border-radius: 0.75rem!important;">

   <div class="modal-header p-4 pb-2 border-bottom-0">
      <h3 class="fw-bold mb-0 mx-auto">Hey {{ auth()->user()->name }} ,Welcome aboard!</h3>
      <p>Before you get started, please let us know...</p>

      <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
   </div>
   <div class="modal-body p-4 pt-2">
      <h6 class="fw-bold mb-2 mt-3">What are you interested in?</h6>
      <p class="fw-500 text-muted">Select some categories you're interested in to help personalize your Reachomation experience, starting with finding Domains, Blogs to invite.</p>
      <div class="d-flex flex-wrap">
         @foreach ($niches as $niche)
         <div class="me-2 mb-2">
            <label class="border border-secondary bg-white btn-sm shadow-none">
            <input class="form-check-input niches" name="niches" type="checkbox" value="{{$niche->niche}}" id="niches">
            {{ucfirst($niche->niche)}}
            </label>
         </div>
         @endforeach
      </div>
   </div>
   <div class="modal-footer">
      <button class="btn btn-primary rounded-4" type="submit" onclick="updateNiches()">Submit</button>
   </div>
</div>
</div>
</div> -->

<div class="modal fade" id="welcome" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-green" style="background-image: url('{{asset('images/elements/blur-film.png')}}');">
         <div class="d-flex">
            <div class="ms-auto">
               <button type="button" class="btn-close btn-lg shadow-none" data-bs-dismiss="modal"
                  aria-label="Close"></button>
            </div>
         </div>
         <div class="modal-body p-4 text-center">
            <img src="{{asset('images/elements/thank-you.svg') }}" class="img-fluid" alt="thank-you" width="280">
            <h3 class="mt-5">Thank You!</h3>
            <p class="lead text-dark">Thank you for submitting your business setup inquiry to Shuraa India. Our <span
                  class="fw-bold text-primary">Business Advisor</span> would get in touch with you soon…</p>
         </div>
      </div>
   </div>
</div>

<div class="modal fade " tabindex="-1" role="dialog" id="welcome-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow"
         style="border-radius: 0.75rem!important;background-image: url('{{asset('images/elements/blur-film.png')}}');">
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">Hey {{ Helper::split_name(Auth::user()->name) }}, Welcome aboard!</h4>


            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2">
            <p class="text-center">Before you get started, please let us know...</p>


            <h6 class="fw-bold mb-3 mt-3">What's the best way to provide you 24x7 chat support?</h6>
            <div class="mb-2 d-flex justify-content-center">
               <select class="form-select py-2 shadow-none shadow-sm w-50" aria-label="Default select example"
                  name="way_of_support" id="way_of_support">
                  <option value="" selected>Select</option>
                  <option value="skype Id">Skype</option>
                  <option value="whatsapp No">Whatsapp</option>
                  <option value="Telegram">Telegram</option>
                  <option value="email">Email</option>
               </select>

            </div>
            <div class="mb-2 d-flex justify-content-center">
               <input type="text" name="skype" id="skypeIn" placeholder="" class="form-control  w-50  skypeIn"
                  style="display: none;">
            </div>
            <h6 class="fw-bold mb-2 mt-3">Which categories are you most interested in?</h6>
            <div class="d-flex flex-wrap">
               @php
               $i = 0;
               @endphp
               @foreach ($niches as $niche)
               @php
               $i++;
               @endphp
               <div class="me-2 mb-2">
                  <input type="checkbox" class="btn-check niches" id="niches{{$i}}" value="{{$niche->niche}}"
                     autocomplete="off" name="niches">
                  <label class="btn btn-outline-dark shadow-none btn-sm "
                     for="niches{{$i}}">{{ucfirst($niche->niche)}}</label>
               </div>
               {{-- <div class="me-2 mb-2 d-none">
                  <label class="border border-secondary bg-white btn-sm shadow-none  nichesCheck">
                     <input class="form-check-input niches" name="niches" type="checkbox" value="{{$niche->niche}}"
                        id="niches">
                     {{ucfirst($niche->niche)}}
                  </label>
               </div> --}}
               @endforeach
            </div>
         </div>
         <div class="modal-footer mx-auto">
            <button class="btn btn-green rounded-4 px-5" type="submit" onclick="updateUserInterest()">Submit</button>
         </div>
      </div>
   </div>
</div>
{{-- Welcome modal end --}}


{{-- Traffic History Modal Start --}}
<div class="modal fade" id="traffic-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-body">
            <canvas class="w-100 chartjs-render-monitor" id="myChart_traffic" width="1507" height="636"
               style="display: block; height: 724px; width: 1005px;"></canvas>
         </div>
         <div class="modal-footer py-1">
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
{{-- Traffic History Modal End --}}

{{-- toast start --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99999">
   <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive"
      aria-atomic="true">
      <div class="d-flex">
         <div class="toast-body toast-msg"></div>
         <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
            aria-label="Close"></button>
      </div>
   </div>
</div>
{{-- toast end --}}

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
                  <input type="text" id="list-name" class="form-control rounded-4 shadow-none"
                     placeholder="List Name (eg. Blogs)" autocomplete="off" onkeyup="listName()">
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

{{-- Create reveal modal start --}}
<div class="modal fade" tabindex="-1" role="dialog" id="reveal-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow border-0 bg-light" style="border-radius: 0.75rem!important;">
         <div class="modal-body p-4 pt-2 pb-2 position-relative">
            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
            <p class="fw-semibold mb-3 mt-3 lh-base">
               This action requires the website to be revealed first. Are you sure you want to reveal this website using
               <span class="fw-bold fs-5 reveal-credit-cost" id="credit_cost"> credits</span>?
            </p>

            <div class="form-check form-check-inline">
               <input class="form-check-input shadow-none rounded-0" type="checkbox" name="dont-show" id="dont-show"
                  value="1">
               <label class="form-check-label" for="dont-show"><small>Don't show again</small></label>
            </div>
         </div>
         <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0" id="reveal-action">

         </div>
      </div>
   </div>
</div>
{{-- Create reveal modal end --}}


{{-- Modal for balance low alert start --}}
<div class="modal modal-alert fade" tabindex="-1" role="dialog" id="balance-low-modal">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable " role="document">
      <div class="modal-content shadow border-0" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">
               You're low on credits
            </h4>

            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-none"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body p-4 pt-2 pb-2 position-relative" id="title_txt">
            <p class="fw-semibold mb-3 mt-3 lh-base fw-500">
               You don't have enough credits to reveal this website
            </p>

            <div class="d-flex mb-3">
               <div class="border border-2 border-secondary py-2 w-100 text-center">
                  <p class="fw-semibold mb-0">
                     Credit required: <span class="fs-6 fw-bold mb-0 text-success fw-bold" id="credit-cost"></span>
                  </p>
               </div>
               <div class="border border-2 border-secondary py-2 w-100 text-center">
                  <p class="fw-semibold mb-0">
                     Your Balance: <span class="fs-6 mb fw-bold text-danger fw-bold">{{Auth::user()->credits}}</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="modal-footer flex-nowrap p-0 border-start-0 border-end-0 border-bottom-0">
            <a href="javascript:void(0)" type="button"
               class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end shadow-none text-green-hover fw-600"
               onclick="buyCredits()">Buy Credits</a>
            <a href="{{ route('pricing') }}" type="button"
               class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start shadow-none text-green-hover fw-600">Upgrade
               Plan</a>
         </div>
      </div>
   </div>
</div>
{{-- Modal for balance low alert end --}}




@endsection
@section('extrajs')
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
{!! Html::script('js/dashboard.js') !!}
<script>
   $(document).ready(function(){

          $(".subject").summernote(
              {
                 toolbar: [],
                  
                  placeholder: `Subject`,
                  //followingToolbar: false   ,
                 // focus: true,
                  disableResizeEditor: true ,
                  width: 600,
                  // height: '50px',
                  minHeight: 0,
                 maxHeight: null,
                  callbacks: {
                    onKeyup: function (contents, $editable) {
                       var rid = $(this).data('rid');
                        $(".element").addClass('elementSub');
                        $(".element").removeClass('elementMsg');

                        $(".resetBt").addClass('resetBtsub');
                        $(".resetBt").removeClass('resetBtnmsg');
                    }

                  }
              }
          );
           $(".message").summernote(
              {
                  toolbar: [ ["style", ["style"]], ["font", ["bold", "italic", "underline", "fontname", "clear"]], ["color", ["color"]], ["paragraph", ["ul", "ol", "paragraph"]], ["table", ["table"]], ["insert", ["link", "picture", "video"]], ["view", ["codeview", "fullscreen"]], ],
                  placeholder: `Hi there,<br><br>I saw your site and liked the content. I am keen to explore advertising opportunities with you.<br>Could I know your charges for publishing a guest post on . We pay via PayPal.<br><br>Looking forward to hearing from you. `,
                  tabsize: 2,
                  height: 200,
                  callbacks: {
                    onKeyup: function (contents, $editable) {
                       var rid = $(this).data('rid');
                        $(".element").removeClass('elementSub');
                        $(".element").addClass('elementMsg');
                        $(".resetBt").removeClass('resetBtsub');
                        $(".resetBt").addClass('resetBtnmsg');
                    }
                  }
              }
          );

      });

   $('.note-statusbar').hide();
   // $('.summernote').summernote('removeFormat');
   
   
   // chart
   var ctx = document.getElementById('myChart_traffic').getContext('2d');
   var myChart = new Chart(ctx, {
      type: 'line',
      data: {
      labels: [
        @php echo getLast30Days(); @endphp
      ],
      datasets: [{
         label: 'Mails sent',
         data: [
           @php echo  getLast30DaysSentMails(auth()->user()->id);    @endphp,
         ],
         
         lineTension: 0,
         backgroundColor: 'transparent',
         borderColor: '#007bff',
         borderWidth: 4,
         pointBackgroundColor: '#007bff'
      },
      {
         label: 'Delivered ',
         data: [
           @php echo getLast30DaysDeliveredMails(auth()->user()->id); @endphp
         ],
         
         lineTension: 0,
         backgroundColor: 'transparent',
         borderColor: '#39ac8a',
         borderWidth: 4,
         pointBackgroundColor: '#39ac8a'
      },
      
      {
         label: 'Replies received',
         data: [
           @php echo getLast30DaysReplyMails(auth()->user()->id); @endphp
         ],
         
         lineTension: 0,
         backgroundColor: 'transparent',
         borderColor: '#ffc107',
         borderWidth: 4,
         pointBackgroundColor: '#ffc107'
      }]
   },
   options: {
      title: {
         display: true,
         text: 'CAMPAIGN ACTIVITY',
         fontSize: 16
      },
      scales: {
         yAxes: [{
            scaleLabel: {
               display: true,
               labelString: 'Total Sent/Delivered/Replied'
            },
            ticks: {
               beginAtZero: false
            }
         }],
         xAxes: [{
            scaleLabel: {
               display: true,
               labelString: 'Date'
            },
            ticks: {
               beginAtZero: false
            }
         }]
      },
      legend: {
         display: false
      }
   }
   });
   // chart
   
   $(document).ready(function() {
   $('.summernote').summernote();
   });
   $("#sidebarMenu").niceScroll();

   // intialize popover
   var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
         return new bootstrap.Popover(popoverTriggerEl)
      })
</script>
@endsection
