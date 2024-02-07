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
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <!-- Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Title -->
    <title>Forgot Password</title>

</head>

<body>

    <!-- Main start -->
    <main id="main">

        <!-- Login start -->
        <section class="app__login">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 col-lg-8 d-md-block custom-sidebar collapse bg-blue">
                        <svg viewBox="0 0 220 192" width="220" height="192" fill="none"
                            class="position-absolute text-white-50 top-0 end-0 mt-3">
                            <defs class="ng-tns-c213-0">
                                <pattern id="837c3e70-6c3a-44e6-8854-cc48c737b659" x="0" y="0" width="20" height="20"
                                    patternUnits="userSpaceOnUse" class="ng-tns-c213-0">
                                    <rect x="0" y="0" width="4" height="4" fill="currentColor" class="ng-tns-c213-0">
                                    </rect>
                                </pattern>
                            </defs>
                            <rect width="220" height="192" fill="url(#837c3e70-6c3a-44e6-8854-cc48c737b659)"
                                class="ng-tns-c213-0">
                            </rect>
                        </svg>

                        <svg viewBox="0 0 960 540" width="100%" height="100%" preserveAspectRatio="xMidYMax slice"
                            xmlns="http://www.w3.org/2000/svg"
                            class="position-absolute inset-0 pointer-events-none ng-tns-c213-0 text-white-50">
                            <g fill="none" stroke="currentColor" strokeWidth="80"
                                class="text-gray-700 text-white-50 opacity-25 ng-tns-c213-0">
                                <circle r="234" cx="196" cy="23" class="ng-tns-c213-0"></circle>
                                <circle r="234" cx="890" cy="491" class="ng-tns-c213-0"></circle>
                            </g>
                        </svg>
                        <img src="{{ asset('images/elements/forgot-password.svg') }}"
                            class="img-fluid position-absolute bottom-0 start-0" alt="Reachomation" width="620">
                    </div>
                    <div class="col-md-4 ms-sm-auto col-lg-4 px-md-4">
                        <div class="card rounded-0 border-0">
                            <div class="card-body text-center">
                                <a href="{{  url('/') }}">
                                    <img src="{{ asset('images/reachomation-black.png') }}" class="img-fluid mt-3" width="180"
                                        alt="logo">
                                </a>

                                <div class="alert alert-info rounded-1 mt-5" role="alert">
                                    <p class="text-justify fw-normal text-start mb-0">
                                        <span class="fw-bold">Forgot your password?</span> No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                                    </p>
                                </div>
                                @include('sections.message')
                                <form method="POST" action="{{ url('forgotpassword') }}" class="mt-4">
                                    @csrf
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control rounded-1 shadow-none" id="email"
                                            placeholder="name@example.com" autocomplete="off" name="email">
                                        <label for="email">Email address*</label>
                                    </div>
                                    <div class="mb-4">
                                        <div class="d-grid">
                                            <button type="submit" name="action"
                                                class="btn btn-green rounded-pill shadow-none">
                                                Email Password Reset Link
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Login end -->

    </main>
    <!-- Main end -->

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>
