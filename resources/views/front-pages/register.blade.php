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
    <title>Registration</title>

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
                        <img src="{{ asset('images/elements/login.svg') }}" class="img-fluid position-absolute bottom-0 start-0"
                            alt="Reachomation" width="460">
                    </div>
                    <div class="col-md-4 ms-sm-auto col-lg-4 px-md-4">
                        <div class="card rounded-0 border-0">
                            <div class="card-body text-center">
                                <a href="{{  url('/') }}">
                                    <img src="{{ asset('images/reachomation-black.png') }}" class="img-fluid mt-3" width="180"
                                        alt="logo">
                                </a>
                                <div class="d-grid mt-4 mb-4">
                                    <a type="button" class="btn btn-outline-dark rounded-pill shadow-none" href="{{ url('/google') }}">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"
                                            class="LgbsSe-Bz112c google" width="18">
                                            <g>
                                                <path fill="#EA4335"
                                                    d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                                                </path>
                                                <path fill="#4285F4"
                                                    d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                                                </path>
                                                <path fill="#FBBC05"
                                                    d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z">
                                                </path>
                                                <path fill="#34A853"
                                                    d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                                                </path>
                                                <path fill="none" d="M0 0h48v48H0z"></path>
                                            </g>
                                        </svg>
                                        &nbsp;Sign up with Google
                                    </a>
                                </div>

                                <div class="border-3 border-top position-relative mb-4">
                                    <p
                                        class="mb-0 fw-bold position-absolute top-0 start-50 translate-middle bg-white px-3">
                                        OR</p>
                                </div>
                                @include('sections.message')
                                <form method="POST" action="{{ route('register') }}" class="mt-4">
                                    @csrf
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control rounded-1 shadow-none" id="name"
                                            autocomplete="off" name="name" placeholder="xxxxxxxxxx">
                                        <label for="name">Full Name*</label>
                                    </div>

                                    <!-- <div class="form-floating mb-3">
                                        <input type="text" id="phone" name="phone" class="form-control rounded-1 shadow-none"
                                            placeholder="xxxxxxxxxx">
                                        <label for="phone">Phone</label>
                                    </div> -->

                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control rounded-1 shadow-none" id="email"
                                            placeholder="name@example.com" autocomplete="off" name="email">
                                        <label for="email">Email address*</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control rounded-1 shadow-none" id="password"
                                            name="password" placeholder="xxxxxxxxxx" autocomplete="false">
                                        <label for="password">Password*</label>
                                    </div>

                                    <div class="mb-4">
                                        <div class="d-grid">
                                            <button type="submit" name="action"
                                                class="btn btn-green rounded-pill shadow-none">
                                                Sign up
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="border-3 border-top position-relative mb-4">
                                    <p
                                        class="mb-0 text-secondary position-absolute top-0 start-50 translate-middle bg-white px-3 text-nowrap">
                                        Already have an account?</p>
                                </div>

                                <div class="d-grid mb-2">
                                    <a type="button" class="btn btn-outline-dark rounded-pill shadow-none" href="{{ url('login') }}">
                                        Login
                                    </a>
                                </div>

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
