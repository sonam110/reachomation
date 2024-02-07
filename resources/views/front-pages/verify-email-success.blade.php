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
    <link  rel="stylesheet" href="/css/bootstrap.min.css" >
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <!-- Title -->
    <title>Reachomation</title>

</head>

<body>

    <!-- Main start -->
    <main id="main">

        <!-- Banner start -->
        <section class="app__banner d-flex align-items-center justify-content-center position-relative">
            <svg viewBox="0 0 220 192" width="220" height="192" fill="none"
                class="position-absolute text-white-50 bottom-0 start-0 d-none d-sm-block">
                <defs class="ng-tns-c213-0">
                    <pattern id="837c3e70-6c3a-44e6-8854-cc48c737b659" x="0" y="0" width="20" height="20"
                        patternUnits="userSpaceOnUse" class="ng-tns-c213-0">
                        <rect x="0" y="0" width="4" height="4" fill="currentColor" class="ng-tns-c213-0"></rect>
                    </pattern>
                </defs>
                <rect width="220" height="192" fill="url(#837c3e70-6c3a-44e6-8854-cc48c737b659)" class="ng-tns-c213-0">
                </rect>
            </svg>

            <svg viewBox="0 0 960 540" width="100%" height="100%" preserveAspectRatio="xMidYMax slice"
                xmlns="http://www.w3.org/2000/svg"
                class="position-absolute inset-0 pointer-events-none ng-tns-c213-0 text-light d-none d-sm-block">
                <g fill="none" stroke="currentColor" strokeWidth="80"
                    class="text-gray-700 text-white-50 opacity-25 ng-tns-c213-0">
                    <circle r="234" cx="196" cy="23" class="ng-tns-c213-0"></circle>
                    <circle r="234" cx="890" cy="491" class="ng-tns-c213-0"></circle>
                </g>
            </svg>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="mb-4">
                        <img class="img-fluid d-block mx-auto" src="{{ asset('images/reachomation-white.png') }}" alt="Reachomation" width="240">
                    </div>
                    <div class="card p-4 rounded-0 border-0 shadow-sm">
                        <div class="card-body text-center">

                            @include('sections.message')

                            <h4 class="fw-bold">Thank you!</h4>
                            <p>Your email has been successfully verified.</p>
                            <a href="{{ route('login')}}" class="w-50 mt-2 btn rounded-pill btn-green shadow-none" type="submit">Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
            <img src="{{ asset('images/elements/thank-you.svg') }}" class="img-fluid position-absolute bottom-0 end-0" alt="Reachomation" width="420">
        </section>
        <!-- Banner end -->

    </main>
    <!-- Main end -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>