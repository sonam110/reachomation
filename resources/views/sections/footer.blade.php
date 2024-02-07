
<footer class="site-footer">
   <div class="container">
      <div class="row">
         <div class="col-md-12 col-sm-12 text-center">
            <a class="navbar-brand fw-bold fs-4 me-5 text-white" href="/">
               <img src="{{asset('images/reachomation_white_logo.png')}}" class="img-fluid" width="180" alt="logo">
            </a>
            <p class="text-justify px-3">We help SEO Agencies and Professionals with their most critical task – Highly relevant Content Marketing without spending countless hours and money on exploring, negotiating and publishing content on genuine and worthy sites with strong authority and genuine traffic.</p>
         </div>
         @if (!\Auth::check())
         <div class="col-md-4 col-6 text-center">
            <h6 class="mb-3">IMPORTANT LINKS</h6>
            <ul class="footer-links">
               <li><a href="{{ url('about') }}">About</a></li>
               <li><a href="{{ url('terms-of-use') }}">Terms Of Use</a></li>
              <!--  <li><a href="{{ url('reviews') }}">Reviews</a></li> -->
               <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
            </ul>
         </div>
         <div class="col-md-4 col-6 text-center">
            <h6 class="mb-3">Sister Services</h6>
            <ul class="footer-links">
               <li><a href="{{ url('guest-post-services') }}">Guest Post Services</a></li>
               <li><a href="{{ url('blogger-outreach-services') }}">Blogger Outreach Services</a></li>
               <li><a href="{{ url('link-building-services') }}">Link Building Services</a></li>
               <li><a href="{{ url('contextual-link-building-services') }}">Contextual Link Building</a></li>
               <li><a href="{{ url('outsourcing-link-building') }}">Outsourcing Link Building</a></li>
            </ul>
         </div>
         <div class="col-md-4 col-6 text-center">
            <h6 class="mb-3">Other Links</h6>
            <ul class="footer-links">
               <!--<li><a href="{{ url('blog') }}">Blog</a></li>-->
               <li><a href="{{ url('contact') }}">Contact</a></li>
               <li><a href="{{ url('pricing') }}">Pricing</a></li>
            </ul>
         </div>
         @endif
      </div>
      <hr>
   </div>
   <div class="container">
      <div class="row">
         <div class="col-md-8 col-sm-6 col-12">
            <p class="copyright-text">Copyright © 2023 All Rights Reserved by<a class="text-decoration-none" target="_blank" href="{{ url('/') }}"><strong class="text-white"> Reachomation</strong></a>.</p>
         </div>
         <div class="col-md-4 col-sm-6 col-12">
            <ul class="social-icons">
               <li><a target="_blank" href="/https://facebook.com"><i class="bi bi-facebook"></i></a></li>
               <li><a target="_blank" href="/https://linkedin.com"><i class="bi bi-twitter"></i></a></li>
               <li><a target="_blank" href="/https://instagram.com"><i class="bi bi-google"></i></a></li>
               <li><a target="_blank" href="/https://instagram.com"><i class="bi bi-instagram"></i></a></li>
               <li><a target="_blank" href="/https://instagram.com"><i class="bi bi-linkedin"></i></a></li>
            </ul>
         </div>
      </div>
   </div>
</footer>

