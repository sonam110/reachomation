<!DOCTYPE html>
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
	<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
	<link rel="stylesheet" href="{{asset('css/style.css')}}">

	<!-- Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<!-- Title -->
	<title>About Us</title>

</head>

<body>

	<!-- Main start -->
	<main id="main">

		<!-- Header Start -->
		@include('sections.new-front-header')
		<!--Header End -->

		<!-- Banner start -->
		<section class="app__about">
			<div class="container">
				<div class="row">
					<div class="col-md-8 mx-auto position-absolute top-50 start-50 translate-middle text-center">
						<span class="fw-bold display-2 text-green d-inline-block px-3">About Us</span>
						<hr class="hr-short mt-3 mb-5 text-white">
						{{-- <h3 class="fw-normal text-white">
							Prospecting and Outreach Automation Tool
						</h3>
						<a href="{{ url('register') }}" class="btn btn-outline-light fw-bold py-3 rounded-1 mt-4"
							style="padding: 0.5rem 2.8rem;padding-top:8px;">
							Sign up for Free <i class="bi bi-chevron-double-right"></i>
						</a> --}}
					</div>
				</div>
			</div>
			<a class="home_arrow d-none d-sm-block" href="#target-down">
				<div class="home_arrow_inner">
					<div class="home_arrow_in"></div>
					<div class="home_arrow_move"></div>
				</div>
			</a>
		</section>
		<!-- Banner end -->

		<!-- why us start -->
		<section class="app__prospecting-made-simpler">
			<div class="container py-lg-5 py-3">
				<div class="row py-lg-4">
					<div class="col-sm-5">
						<div class="">
							<img src="{{ asset('images/elements/why-us.jpg') }}" class="img-fluid" data-aos="fade-right" width="380"
								alt="reachomation">
						</div>
					</div>
					<div class="col-sm-7 aos-init aos-animate" data-aos="fade-up">

						<p class="lead fw-normal">
							Having our background of running a Digital Marketing Agency, we always struggled to find a
							single point solution, that would aid in research as well as outreach efforts for content
							collaborations. So we decided to build one and that's how Reachomation was born.
						</p>

						<p class="lead fw-normal mt-4">
							At Reachomation, we help marketers and sales professionals with their most painful task:
							Prospecting and precision targeting for potential collaborations and sales, without wasting
							tons of time in manual research and outreach.
						</p>
					</div>
				</div>
			</div>
		</section>
		<!-- why us end -->

		<!-- Strength start -->
		<section class="app__strength bg-light py-5" id="target-down">
			<div class="container py-lg-5 py-3">
				<div class="row justify-content-center text-center mb-5">
					<div class="col-lg-8 aos-init aos-animate" data-aos="fade-up">
						<h2 class="mb-3 fw-bold">OUR STRENGTH IN NUMBERS</h2>
						<hr class="hr-short mt-4">
					</div>
				</div>

				<div class="row row-cols-1 row-cols-md-3 g-lg-5 g-3 px-lg-5 justify-content-center">
					<div class="col">
						<div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
							data-aos="flip-down" style="background-image: linear-gradient(to bottom,#fc8822,#ed851b);">
							<div class="card-body">
								<h5 class="card-title text-center fw-bold text-white">
									NETWORK STRENGTH
								</h5>
								<hr class="hr-short mt-3 text-light">
								<div class="text-center py-3">
									<h1 class="fw-bold display-3 text-white">
										100K<span>+</span>
									</h1>
								</div>
								<div class="d-flex justify-content-center">
									<div class="border border-2 border-white-50 p-2 w-50 text-center">
										<span class="mb-0 text-white fw-normal">
											WEBSITES
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
							data-aos="flip-down" style="    background-color: #0aa8e5;">
							<div class="card-body">
								<h5 class="card-title text-center fw-bold text-white">
									VERTICLES COVERED
								</h5>
								<hr class="hr-short mt-3 text-light">
								<div class="text-center py-3">
									<h1 class="fw-bold display-3 text-white">
										26<span>+</span>
									</h1>
								</div>
								<div class="d-flex justify-content-center">
									<div class="border border-2 border-white-50 p-2 w-50 text-center">
										<span class="mb-0 text-white fw-normal">
											NICHES
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col d-none">
						<div class="card rounded-0 border-0 shadow-sm h-100 aos-init aos-animate p-3 card-shadow"
							data-aos="flip-down" style="background-color: #a67fcd;">
							<div class="card-body">
								<h5 class="card-title text-center fw-bold text-white">
									ACTIVE USAGE
								</h5>
								<hr class="hr-short mt-3 text-light">
								<div class="text-center py-3">
									<h1 class="fw-bold display-3 text-white">
										30<span>+</span>
									</h1>
								</div>
								<div class="d-flex justify-content-center">
									<div class="border border-2 border-white-50 p-2 w-75 text-center">
										<span class="mb-0 text-white fw-normal">
											ACTIVE CLIENTS
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</section>
		<!-- Strength end -->

		<!-- Network start -->
		<section class="app__about-network bg-blue">
			<div class="container py-lg-5 py-3">
				<div class="row">
					<div class="col-sm-7 d-flex align-items-center">
						<h1 class="fw-bold text-white display-6">
							We don't just gather data. We put it through <span class="text-green">multiple quality
								checks</span> so it becomes usable for <span class="text-green">meaningful
								prospecting</span> and <span class="text-green">result-driven</span> outreach.
						</h1>
					</div>
					<div class="col-sm-5">
						<img class="img-fluid d-block mx-auto" src="{{ asset('images/netzwerk.png') }}"
							alt="Reachomation">
					</div>
				</div>
			</div>
		</section>
		<!-- Network end -->

		<!-- Team start -->
		<section class="app__about-team py-lg-5 d-none">
			<div class="container py-lg-5 py-3">
				<div class="row justify-content-center text-center mb-5">
					<div class="col-lg-8 aos-init aos-animate" data-aos="fade-up">
						<h2 class="mb-3 fw-bold">
							MEET OUR TEAM
						</h2>
						<hr class="hr-short mt-4">
					</div>
				</div>

				<div class="carousel" data-flickity='{ "groupCells": true }'>
					<div class="carousel-cell">
						<div class="card text-bg-dark rounded-0 border-0 shadow-sm">
							<img src="{{ asset('images/prashant.png') }}" class="card-img rounded-0" alt="Reachomation">
							<div class="card-img-overlay">
								<div class="card-img-overlay">
									<div class="position-absolute bottom-0">
										<h5 class="card-title">Prashant Sharma</h5>
										<p class="fw-normal">Founder & CEO</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-cell">
						<div class="card text-bg-dark rounded-0 border-0 shadow-sm">
							<img src="{{ asset('images/garima.png') }}" class="card-img rounded-0" alt="Reachomation">
							<div class="card-img-overlay">
								<div class="position-absolute bottom-0">
									<h5 class="card-title">Garima Parashar</h5>
									<p class="fw-normal">Head, Influencer Relations</p>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-cell">
						<div class="card text-bg-dark rounded-0 border-0 shadow-sm">
							<img src="{{ asset('images/ankitkr.png') }}" class="card-img rounded-0" alt="Reachomation" height="260">
							<div class="card-img-overlay">
								<div class="position-absolute bottom-0">
									<h5 class="card-title">Ankit Kumar</h5>
									<p class="fw-normal">SEM Specialist</p>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-cell">
						<div class="card text-bg-dark rounded-0 border-0 shadow-sm">
							<img src="{{ asset('images/kapil.png') }}" class="card-img rounded-0" alt="Reachomation">
							<div class="card-img-overlay">
								<div class="position-absolute bottom-0">
									<h5 class="card-title">Kapil Sharma</h5>
									<p class="fw-normal">Outreach Specialist</p>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-cell">
						<div class="card text-bg-dark rounded-0 border-0 shadow-sm">
							<img src="{{ asset('images/majeet.png') }}" class="card-img rounded-0" alt="Reachomation">
							<div class="card-img-overlay">
								<div class="position-absolute bottom-0">
									<h5 class="card-title">Manjeet Kumar</h5>
									<p class="fw-normal">Outreach Specialist</p>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-cell">
						<div class="card text-bg-dark rounded-0 border-0 shadow-sm">
							<img src="{{ asset('images/pawan.jpg') }}" class="card-img rounded-0" alt="Reachomation" height="260">
							<div class="card-img-overlay">
								<div class="position-absolute bottom-0">
									<h5 class="card-title">Pawan Jadam</h5>
									<p class="fw-normal">Web Developer</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Team end -->

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

		@include('sections.new-footer')

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
	<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
	<script src="{{asset('js/index.js')}}"></script>
	<script>
		AOS.init();
	</script>
</body>

</html>
