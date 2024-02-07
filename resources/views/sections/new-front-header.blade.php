<header>
   <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-0">
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