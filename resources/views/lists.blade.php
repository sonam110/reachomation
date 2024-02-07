@extends('layouts.master')
@section('content')
         <div class="py-3">
            <div class="hstack border-bottom gap-3 mb-3 pb-3">
               <div>
                  <h3 class="fw-bold mb-0">
                     Your Lists
                  </h3>
               </div>
               <div class="ms-auto">
                  <a href="{{ route('download-unsubscribed-list')}}" class="btn btn-danger fw-500"  title="Download Unsubscribe  List" data-bs-original-title="Download Unsubscribe List" >
                     <i class="bi bi-download"></i> Unsubscribes
                  </a> 
                  <button type="button" class="btn btn-primary shadow-sm fw-500" onclick="opencreateModal()"><i class="bi bi-plus-lg"></i> Create New List</button> 
               </div>
            </div>

            @if(count($collections)>0)
            <ul class="list-group shadow-sm rounded-1">
               <li class="list-group-item bg-light fw-500 py-3">
                  <div class="row">
                     <div class="col-sm-5">List Name</div>
                     <div class="col-sm-2">List Size</div>
                     <div class="col-sm-2">Campaign History</div>
                     <div class="col-sm-3">Action</div>
                  </div>
               </li>
               @foreach($collections as $collection)
               <?php
               $is_show = true;
               if($collection->name=='Revealed'){
                  $is_show = false;
               }
               if($collection->name=='Favourites'){
                  $is_show = false;
               }
               $is_lunch = true;
               if($collection->website_count<=0){
                  $is_lunch = false;
               }
               if($collection->website_count >= @$plan->size_limit){
                  $is_lunch = false;
               }

               ?>
               <li class="list-group-item list-group-item-action">
                  <div class="row">
                     <div class="col-sm-5">
                        {{ucfirst($collection->name)}}  <span class="badge bg-dark text-white badge-pill default-badge">{{
                                             ($is_show == false) ? 'Default' :'' }}</span>
                     </div>
                     <div class="col-sm-2">
                        @if($collection->website_count==0)
                           0
                        @else
                           {{$collection->website_count}}
                        @endif
                     </div>
                     <div class="col-sm-2">{{ $collection->campHistoryCount }}</div>
                     <div class="col-sm-3">
                        <ul class="list-inline ms-auto">

                           @if($collection->website_count>0)
                           <li class="list-inline-item">
                              <a href="{{route('search')}}?list={{$collection->id}}">
                                 <i class="bi bi-eye-fill" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View"></i>
                                 {{-- <i class="bi bi-view-list" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View"></i> --}}
                              </a>
                           </li>
                           @endif
                           @if($is_show == true)
                           <li class="list-inline-item">
                              <i class="bi bi-pencil-square" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit" onclick="openeditModal('{{$collection->id}}','{{$collection->name}}')"></i>
                           </li>
                           <li class="list-inline-item">
                              <i class="bi bi-trash text-danger" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" onclick="deleteAlert('{{$collection->id}}')"></i>
                           </li>
                           @endif
                            @if($is_lunch == true)
                           <li class="list-inline-item">
                              <a href="{{ route('schedulecampaign',$collection->id) }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Launch Campaign">
                                 <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
                              </svg></a>
                           </li>
                           @endif
                           <li class="list-inline-item">
                              <i class="bi bi-download text-primary" role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Download"></i>
                           </li>
                        </ul>
                     </div>
                  </div>
               </li>
               @endforeach
            </ul>
            @else
            <div class="card">
               <div class="card-body text-center">
                  <img src="{{asset('images/empty.svg')}}" class="mb-3 mx-auto" width="320" alt="...">
                  <h5 class="mt-3">You're yet to create your first list</h5>
                  <button type="button" class="btn btn-primary btn-lg shadow-sm fw-500 mt-3" onclick="opencreateModal()"><i class="bi bi-plus-lg"></i> Create New List</button>
               </div>
            </div> 
            @endif
            
            <div class="d-flex justify-content-end mt-3">
               @if(count($collections)>0)
                  {{ $collections->links() }}
               @endif
            </div>

         </div>


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

   {{-- Edit list modal start --}}
   <div class="modal fade " tabindex="-1" role="dialog" id="edit-modal">
      <div class="modal-dialog" role="document">
         <div class="modal-content shadow" style="border-radius: 0.75rem!important;">
            <div class="modal-header p-4 pb-2 border-bottom-0">
               <h3 class="fw-bold mb-0">Edit List</h3>
               <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 pt-0">
               <div class="mt-4">
                  <div class="form-floating mb-3">
                     <input type="text" id="edit-name" class="form-control rounded-4 shadow-none" placeholder="List Name (eg. Blogs)" autocomplete="off" onkeyup="editName()">
                     <label for="edit-name">List Name (eg. Blogs)</label>
                  </div>
                  <div id="edit-btn">
                     <button class="w-100 mb-2 btn btn-lg rounded-4 btn-primary" type="submit">Submit</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   {{-- Edit list modal end --}}

   {{-- Modal for alert start --}}
   <div class="modal  deleteAlert" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content rounded-4 shadow">
            <div class="modal-body p-4 text-center">
               <h5 class="mb-2">Are you sure?</h5>
               <p class="mb-0">Once deleted, you will not be able to recover this list.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
               <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-danger shadow-none" id="delete-btn"><strong>Yes, delete it</strong></button>
               <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-start text-dark shadow-none" data-bs-dismiss="modal">No thanks</button>
            </div>
         </div>
      </div>
   </div>
   {{-- Modal for alert end --}}
@endsection
@section('extrajs')
   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  {!! Html::script('js/list.js') !!}
   <script>
      // intialize toast
      var toastLiveExample = document.getElementById('liveToast');

      // intialize tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      })
      $("#sidebarMenu").niceScroll(); 
   </script>
@endsection
