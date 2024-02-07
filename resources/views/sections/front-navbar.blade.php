<nav class="navbar navbar-expand-lg navbar-dark py-0">
   <div class="container">
      <a class="navbar-brand fw-bold fs-4 me-5" href="{{  url('/') }}">
         <img src="{{asset('images/reachomation_white_logo.png')}}" class="img-fluid" width="180" alt="logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            {{-- <li class="nav-item me-3">
               <a class="nav-link {{ (request()->is('pricing')) ? 'active' : '' }}" aria-current="page"
                  href="{{  route('pricing') }}">Pricing</a>
            </li> --}}
<!--            <li class="nav-item me-3">
               <a class="nav-link {{ (request()->is('blog')) ? 'active' : '' }}" href="{{  url('blog') }}">Blog</a>
            </li>-->
            <!-- <li class="nav-item me-3">
               <a class="nav-link {{ (request()->is('reviews')) ? 'active' : '' }}"
                  href="{{  url('reviews') }}">Reviews</a>
            </li> -->
            <li class="nav-item me-3">
               <a class="nav-link {{ (request()->is('contact')) ? 'active' : '' }}"
                  href="{{  url('contact') }}">Contact</a>
            </li>
            <li class="nav-item">

               @if (Auth::check())
              
               <a class="nav-link {{ (request()->is('dashboard')) ? 'active' : '' }}"
                  href="{{  url('dashboard') }}">Dashboard</a>
               @else
               
                <a class="nav-link {{ (request()->is('login')) ? 'active' : '' }}"
                  href="{{  url('login') }}">Login</a>
               @endif
            </li>
         </ul>
      </div>
   </div>
</nav>
