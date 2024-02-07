@extends('layouts.master')
@section('content')
<div class="py-3">
   <div class="hstack border-bottom gap-3 mb-3 pb-3">
      <div>
         <h3 class="fw-bold mb-0">
            Grab Free credits
         </h3>
      </div>
   </div>

   <div class="card rounded-0 border-0 shadow-sm mb-3">
      <div class="card-body">
         <div class="row">
            <div class="col-md-6">
               <img src="{{ asset('images/digital.svg') }}" class="img-fluid" alt="digital">
            </div>
            <div class="col-md-5 ms-4">
               <div class="card rounded-0 shadow-sm">
                  <div class="card-body">
                     <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                           <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-1-circle-fill text-blue" viewBox="0 0 16 16">
                              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM9.283 4.002H7.971L6.072 5.385v1.271l1.834-1.318h.065V12h1.312V4.002Z"/>
                            </svg>
                        </div>
                        <div class="flex-grow-1 ms-3">
                           Install our add-on for <b>250</b> free bonus credits.
                           <button class="btn btn-green rounded-4 px-5 mt-3" type="submit">Install</button>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="card rounded-0 shadow-sm">
                  <div class="card-body">
                     <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                           <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-2-circle-fill" viewBox="0 0 16 16">
                              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM6.646 6.24c0-.691.493-1.306 1.336-1.306.756 0 1.313.492 1.313 1.236 0 .697-.469 1.23-.902 1.705l-2.971 3.293V12h5.344v-1.107H7.268v-.077l1.974-2.22.096-.107c.688-.763 1.287-1.428 1.287-2.43 0-1.266-1.031-2.215-2.613-2.215-1.758 0-2.637 1.19-2.637 2.402v.065h1.271v-.07Z"/>
                           </svg>
                        </div>
                        <div class="flex-grow-1 ms-3">
                           Tweet about us to get <b>250</b> free credits.
                           <div class="mt-2">
                              <input type="email" class="form-control form-control-lg shadow-none" placeholder="@username">
                           </div>
                           <button class="btn btn-green rounded-4 px-5 mt-3" type="submit">Tweet</button>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="card rounded-0 shadow-sm">
                  <div class="card-body">
                     <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                           <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-3-circle-fill" viewBox="0 0 16 16">
                              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0Zm-8.082.414c.92 0 1.535.54 1.541 1.318.012.791-.615 1.36-1.588 1.354-.861-.006-1.482-.469-1.54-1.066H5.104c.047 1.177 1.05 2.144 2.754 2.144 1.653 0 2.954-.937 2.93-2.396-.023-1.278-1.031-1.846-1.734-1.916v-.07c.597-.1 1.505-.739 1.482-1.876-.03-1.177-1.043-2.074-2.637-2.062-1.675.006-2.59.984-2.625 2.12h1.248c.036-.556.557-1.054 1.348-1.054.785 0 1.348.486 1.348 1.195.006.715-.563 1.237-1.342 1.237h-.838v1.072h.879Z"/>
                           </svg>
                        </div>
                        <div class="flex-grow-1 ms-3">
                           Report a bug and get <b>1000</b> credits.
                           <button class="btn btn-green rounded-4 px-5 mt-3" type="submit">Submit</button>
                        </div>
                     </div>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </div>

</div>

@endsection
@section('extrajs')
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@endsection