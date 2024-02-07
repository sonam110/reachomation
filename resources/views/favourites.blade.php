
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
@endphp
<div class="py-3">
   <div class="hstack border-bottom gap-3 mb-3 pb-3">
      <div>
         <h3 class="fw-bold mb-0">
            @if($totalCount <=0)
            Favourites  
            @else
            Favourites ({{ $totalCount }})
            @endif
            
         </h3>
         
      </div>
       @if(count($sites)>0)
   <div class="btn-toolbar mb-2 mb-md-0">
      <button type="button" class="btn btn-green shadow-sm fw-500" onclick="openaddtoList()"><i
            class="bi bi-plus-lg"></i> Outreach Results</button>
   </div>
   @endif
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
                           <span id="show-more_{{$site->domain_id}}" class="d-none">{{ ucwords($site->description) }}</span>
                           <span id="show-less_{{$site->domain_id}}">{{ ucwords(substr($site->description,0,140)) }}</span>
                           &nbsp;
                           @if (strlen($site->description)>140)
                           <small class="fw-500"><a href="javascript:void(0)" id="show-btn_{{$site->domain_id}}" onclick="showMore('{{$site->domain_id}}')">SHOW MORE</a></small>
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
                     <span class="fw-500 text-muted">Domain Age: <span class="text-dark">&gt;{{
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
<div class="card">
      <div class="card-body text-center">
         <img src="{{asset('images/empty.svg')}}" class="mb-3 mx-auto" width="360" alt="...">
         <h5 class="mt-3">You've not marked any site as favourite yet</h5>
        
      </div>
   </div>


@endif
<div class="d-flex justify-content-center mt-3">
   @if(count($sites)>0)
      {{ $sites->links() }}
   @endif
</div>

</div>


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

 
    {{-- Modal for fallback text --}}
      <div class="modal fade" tabindex="-1" role="dialog" id="fallback-modal">
         <div class="modal-dialog  modal-md" role="document">
            <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
               
               <div class="modal-header p-4 pb-2 border-bottom-0">
                  <h3 class="fw-bold mb-0">Merge tag</h3>
                  <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body p-3 pt-0">
                  <div class="mt-4" id="add-list-body">
                     <div class="row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label for="mobile" class="form-label">Recipient field <span class="requiredLabel"></span></label>
                                 {!! Form::text('recipient_feild','',array('id'=>'recipient_feild','class'=> $errors->has('recipient_feild') ? 'form-control is-invalid state-invalid' : 'form-control', 'placeholder'=>'Recipient field ', 'autocomplete'=>'off','readonly'=>'readonly')) !!}
                                 
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label for="mobile" class="form-label">Fallback text <span class="requiredLabel">*</span></label>
                                 {!! Form::text('fallback_txt','',array('id'=>'fallback_txt','class'=> $errors->has('fallback_txt') ? 'form-control is-invalid state-invalid' : 'form-control', 'placeholder'=>'', 'autocomplete'=>'off')) !!}
                                 
                              </div>
                           </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  {!! Form::button('Save', array('class'=>'btn btn-primary saveFallbackTxt')) !!} 
                  <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
               </div>
             
            </div>
         </div>
      </div>
   {{-- Modal for fallBack --}}
   {{-- Insert template modal end --}}

   {{-- Add to list modal start --}}
<div class="modal fade" tabindex="-1" role="dialog" id="add-list-modal">
   <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
         <div class="modal-header p-4 pb-2 border-bottom-0 position-relative">
            <h4 class="fw-bold mb-0 mx-auto">Outreach Search Results</h4>


            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0 mt-1 me-1"
               data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
        

         <div class="modal-body p-3 pt-0">
            <div class="mt-4 add-list-body" id="add-list-body">
               <h6 class="mb-3 mt-3">
                  To setup outreach automation, you must add your target sites to
                  a list by revealing them using your credit balance.
               </h6>
               <br>
                <input type="hidden" name="list_type" id="list_type" value="2">
               <div class="row">
                  <div class="col-sm-8">
                     <div class="mb-3">
                        <h6>Choose your  list</h6>
                     <div class="d-flex align-items-center">
                        <div>
                           <select class="form-select rounded-0 shadow-none" id="created-list" onchange="createdlist()" style="width: 230px;">
                              <option value="" selected>Select Existing</option>
                              @if($collections !='')
                              @foreach ($collections as $list)
                              <option value="{{$list->id}}">{{$list->name}}({{ ($list->website_count!='') ? $list->website_count:'0' }})</option>
                              @endforeach
                              @endif
                           </select>
                        </div>
                        <div>
                           <p class="mb-0 fw-bold px-2">
                              or
                           </p>
                        </div>
                        <div>
                           <input type="text" id="new-list-name"
                           class="form-control rounded-0 shadow-none"
                           placeholder="Add New" autocomplete="off"
                           onkeyup="newlistname()" style="width: 230px;">
                        </div>
                     </div>
                     </div>

                     <div class="mb-3">
                        <h6>How many results you want to add to this list?</h6>
                        <div class="btn-group w-100" role="group"
                           aria-label="Basic radio toggle button group">
                           <input type="radio" class="btn-check listSize" name="list-size" id="btnradio1"
                              autocomplete="off" value="50" checked>
                           <label class="btn btn-outline-success rounded-0 shadow-none fw-500"
                              for="btnradio1">Top 50
                              Results</label>

                           <input type="radio" class="btn-check listSize" name="list-size" id="btnradio2"
                              autocomplete="off" value="500">
                           <label class="btn btn-outline-success rounded-0 shadow-none fw-500"
                              for="btnradio2">Top 500
                              Results</label>

                           <input type="radio" class="btn-check listSize" name="list-size" id="btnradio3"
                              autocomplete="off" value="1500">
                           <label class="btn btn-outline-success rounded-0 shadow-none fw-500"
                              for="btnradio3">Top 1500
                              Results</label>
                        </div>
                     </div>

                     <div>
                        <div class="form-check form-check-inline">
                           <input type="checkbox" class="form-check-input shadow-none rounded-0 checkReveal" 
                             name="reveal_or_not" id="reveal_or_not" value="1" >
                           <label class="form-check-label " for="checkbtn1">
                              <small>
                                 Favourites sites only
                              </small>
                           </label>
                        </div>
                     </div>
                     <div>
                        <div class="form-check form-check-inline">
                           <input type="checkbox" class="form-check-input shadow-none rounded-0 checkReveal" 
                             name="reveal_or_not"    id="reveal_or_not" value="2" >
                           <label class="form-check-label " for="checkbtn2">
                              <small>
                                 Sites that aren't revealed yet
                              </small>
                           </label>
                        </div>
                     </div>

                  </div>
                  <div class="col-sm-4" >
                     <div class="card rounded-0 border-0 shadow-sm mb-3 bg-light">
                        <div class="card-body py-2">
                           <div>
                              <p class="mb-0 text-muted fw-600">
                                 <small>Sites to add</small>
                              </p>
                              <h4 class="mb-0 fw-bold total_sites_list" id="total_sites_list">
                                 0
                              </h4>
                           </div>
                        </div>
                     </div>

                     <div class="card rounded-0 border-0 shadow-sm mb-3 bg-light">
                        <div class="card-body py-2">
                           <div>
                              <p class="mb-0 text-muted fw-600">
                                 <small>Credits required</small>
                              </p>
                              <h4 class="mb-0 fw-bold total_credits_list" id="total_credits_list">
                                 0
                              </h4>
                           </div>
                        </div>
                     </div>

                     <div class="card rounded-0 border-0 shadow-sm bg-light">
                        <div class="card-body py-2">
                           <div>
                              <p class="mb-0 text-muted fw-600">
                                 <small>Credits available</small>
                              </p>
                              <h4 class="mb-0 fw-bold">
                                 {{ auth()->user()->credits }}
                              </h4>
                           </div>
                        </div>
                     </div>

                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12">
                  <div id="domain-lists">
                     
                  </div>
                     
                  </div>
               </div>
               
            </div>
         </div>
         <div class="modal-footer p-0">
            <div class="d-grid mx-auto" id="add-list-action">
               <button class="btn btn-green rounded-1 px-5" type="submit"
                  onclick="addSitesToList()">Submit</button>
            </div>
         </div>
      </div>
   </div>
</div>
{{-- Add to list modal end --}}

      {{-- toast start --}}
   <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
      <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
         <div class="d-flex">
            <div class="toast-body toast-msg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
      </div>
   </div>
   {{-- toast end --}}

@endsection
@section('extrajs')

   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  {!! Html::script('js/dashboard.js') !!}
   <script>

      // function for open add to list modal
       

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

        $(document).ready(function(){

          $(".subject").summernote(
              {
                 toolbar: [],
                  
                  placeholder: `Subject`,
                  //followingToolbar: false   ,
                 // focus: true,
                  disableResizeEditor: true ,
                  width: 600,
                  height: '50px',
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

      $("#sidebarMenu").niceScroll(); 
   </script>
@endsection
