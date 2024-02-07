s<!DOCTYPE html>
<html lang="en">

<head>
   <!-- Meta tags -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta name="author" content="Reachomation">
   <meta name="description" content="">
   <meta name="theme-color" content="#0f172a" />
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <!-- Favicon -->
   <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
   <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/apple-touch-icon.png') }}">
   <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/apple-touch-icon.png') }}">

   <!-- CSS -->
   <link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="/css/bootstrap-icons.css">
   <link href="/css/aos.css" rel="stylesheet">
   <link rel="stylesheet" href="{{asset('css/style.css')}}">
   <link rel="stylesheet" href="{{asset('css/views/front-pages/contact.css')}}">

   <!-- Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <!-- Title -->
   <title>Contact us</title>
</head>

<body>

   <!-- Main start -->
   <main id="main">

      <!-- Header Start -->
      @include('sections.new-front-header')
      <!--Header End -->

      <!-- Banner start -->
      <section class="app__banner position-relative d-flex align-items-end">
         <img src="{{ asset('images/elements/contact-us.svg') }}" class="img-fluid position-absolute bottom-0 start-0"
            alt="Reachomation" width="860">

         <svg viewBox="0 0 960 540" width="100%" height="100%" preserveAspectRatio="xMidYMax slice"
            xmlns="http://www.w3.org/2000/svg"
            class="position-absolute inset-0 pointer-events-none ng-tns-c213-0 text-light">
            <g fill="none" stroke="currentColor" strokeWidth="80"
               class="text-gray-700 text-white-50 opacity-25 ng-tns-c213-0">
               <circle r="234" cx="196" cy="23" class="ng-tns-c213-0"></circle>
               <circle r="234" cx="890" cy="491" class="ng-tns-c213-0"></circle>
            </g>
         </svg>

         <div class="container-fluid">
            <div class="row">
               <div class="col-lg-8 text-center">
               </div>
               <div class="col-sm-4">
                   @include('sections.message')
                  <div class="card shadow-sm rounded-1 mb-3 me-lg-4 bg-light">
                     <div class="card-body text-left py-4">
                        <h4 class="fw-bold">Our support at your reach</h4>
                        <p class="fw-normal mb-3">Our experts are available to resolve your queries in all time
                           zones.</p>
                         <form id="contact-form" action="{{ route('save.contact') }}" method="POST" enctype="multipart/form-data"name="form" autocomplete="off" role="form">
                           @csrf
                           <div class="form-row">
                              <div class="form-group col-md-12 mb-3">
                                 <input type="text" class="form-control shadow-none rounded-1" placeholder="Name*"
                                    autocomplete="off" name="name">
                              </div>
                           </div>
                           <div class="form-row">
                              <div class="form-group col-md-12 mb-3">
                                 <input type="email" class="form-control shadow-none rounded-1"
                                    placeholder="Email Address*" name="email">
                              </div>
                           </div>
                           <div class="form-row">
                              <div class="form-group col-md-12 mb-3">
                                 <input type="text" class="form-control shadow-none rounded-1"
                                    placeholder="Skype/Whatsapp* " name="skypewhatsapp">
                              </div>
                           </div>
                           <div class="form-row">
                              <div class="form-group col-md-12 mb-3">
                                 <textarea class="form-control shadow-none rounded-1" rows="3"
                                    placeholder="Your message*" style="resize:none;" name="message"></textarea>
                              </div>
                           </div>
                           <div class="form-row ">
                              <div class="form-row col-md-12 mb-3 {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                {!! app('captcha')->display() !!}
                               @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                           </div>
                        
                        
                        <div class="d-grid">
                           <button type="submit" class="btn btn-green rounded-pill shadow-none">Send message</button>
                        </div>
                        </form>
                     </div>
                  </div>

               </div>
            </div>
         </div>
      </section>
      <!-- Banner end -->

      <!-- Help start -->
      <section class="app__help bg-light">
			<div class="container py-lg-5 py-3 g-0">
				<div class="row justify-content-center text-center mb-5">
					<div class="col-lg-8 aos-init aos-animate" data-aos="fade-up">
						<h2 class="mb-3 fw-bold">
							WE'RE HERE TO HELP!
						</h2>
						<hr class="hr-short mt-4">
					</div>
				</div>

				<div class="row row-cols-1 row-cols-md-4 g-lg-5 g-3 justify-content-center">
					<div class="col">
						<div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3" data-aos="flip-down">
							<div
								class="icon-lg text-white rounded-circle position-absolute top-0 end-0 translate-middle" style="background-color: #00aff0;">
								<i class="bi bi-skype"></i>
							</div>
							<h5 class="fw-bold">Skype</h5>
							<p class="mb-0">
								<a href="skype:accuratemedia?chat" class="h6 mb-0 fw-normal text-decoration-none">
									accuratemedia
								</a>
							</p>
						</div>
					</div>
					<div class="col">
						<div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3" data-aos="flip-down">
							<div
								class="icon-lg bg-warning text-white rounded-circle position-absolute top-0 end-0 translate-middle">
								<i class="bi bi-envelope-fill"></i>
							</div>
							<h5 class="fw-bold">Email</h5>
							<p class="mb-0">
								<a href="mailto:support@reachomation.com" class="h6 mb-0 fw-normal text-decoration-none">
									support@reachomation.com
								</a>
							</p>
						</div>
					</div>
					<div class="col">
						<div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3" data-aos="flip-down">
							<div
								class="icon-lg bg-success text-white rounded-circle position-absolute top-0 end-0 translate-middle">
								<i class="bi bi-whatsapp"></i>
							</div>
							<h5 class="fw-bold">WhatsApp</h5>
							<p class="mb-0">
								<a href="https://api.whatsapp.com/send?phone=+918979724976&text=Hi" class="h6 mb-0 fw-normal text-decoration-none">
									+918979724976
								</a>
							</p>
						</div>
					</div>
					<div class="col">
						<div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3" data-aos="flip-down">
							<div
								class="icon-lg bg-primary text-white rounded-circle position-absolute top-0 end-0 translate-middle">
								<i class="bi bi-telephone-fill"></i>
							</div>
							<h5 class="fw-bold">Phone</h5>
							<p class="mb-0">
								<a href="tel:+919871272058" class="h6 mb-0 fw-normal text-decoration-none">
									+919871272058
								</a>
							</p>
						</div>
					</div>

				</div>
			</div>
		</section>
      <!-- Help end -->

      <!-- Map start -->
      <section class="app__map mb-3">
         <div class="ratio ratio-21x9">
            <iframe
               src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3506.449586781716!2d77.40016841492012!3d28.496118282471766!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce88243f538c1%3A0xc6b04fb7568682f4!2sAccunite%20Solutions%20Pvt%20Ltd.!5e0!3m2!1sen!2sin!4v1628592655888!5m2!1sen!2sin"
               allowfullscreen loading="lazy" height="350"></iframe>
         </div>
      </section>
      <!-- Map end -->

      <!-- Footer start -->
      @include('sections.new-footer')
      <!-- Footer end -->

      <!-- Back to top start -->
      <a href="#main" class="back-top back-top-show">
         <div class="scroll-line"></div>
         <span class="scoll-text text-white">Go Up</span>
      </a>
      <!-- Bac to top end -->

   </main>
   <!-- Main end -->

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

   <script src="/js/aos.js"></script>
   <script src="{{asset('js/index.js')}}"></script>
   {!! Html::script('js/jquery.validate.js') !!}
   {!! NoCaptcha::renderJs() !!}
   
   <script>
      AOS.init();
      $( document ).ready(function() {
         $('#contact-form').each(function () {
        $(this).validate({
            rules: {
                name : {
                  required: true,
                },
                email : {
                  required: true,
                  email: true
                },
                skype_whatsapp : {
                  required: true,
                },
                
                message:{
                  required: true,
                },
                
            },
          
           
            messages: {
               "name": {
                  required: 'Please enter your full name',
               },
               "email": {
                  required: 'Please enter email address',
                  email: 'Please enter valid email address',
               },
               "skypewhatsapp": {
                   require_from_group: 'Please Enter skype/Whats app detail',
               },
               "message": {
                   require_from_group: 'Please enter message',
               },
               
            },
            
           
            submitHandler : function(form) {
                //do something here
                form.submit();
            },

            

        });
   });
      });
   </script>
</body>

</html>
