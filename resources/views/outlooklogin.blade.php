<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="theme-color" content="#0f172a">
   <!-- Bootstrap CSS -->
   <link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet" >
   <!-- Bootstrap icons -->
   <link rel="stylesheet" href="/css/bootstrap-icons.css">
   <!-- Manual CSS -->
   <link rel="stylesheet" href="{{asset('css/custom.css')}}">
   <!-- favicon -->
   <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}"/>
   <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}"/>
   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert"></script>
   <title>Outlook - Reachomation</title>
</head>

<body class="bg-light">
   @include('sections.navbar')
   <?php 
   //echo "<PRE>";print_R($data);die;

   ?>
   <div class="container-fluid">
      <div class="row g-0">
         @include('sections.sidebar')
         <main class="col-md-9 ms-sm-auto col-lg-10">
            <div class="py-3">
               <div class="hstack border-bottom gap-3 mb-3 pb-3">
                  <div>
                     <h3 class="fw-bold mb-0">
                        Outlook Login
                     </h3>
                  </div>
               </div>
            </div>
            <div class="py-3">
               <div class="col-md-12 text-center">
                  <a type="button" href="<?php echo $data['authurl']; ?>" class="btn btn-primary">Login With Outlook</a>
               </div>
            </div>
            @include('sections.footer')
         </main>
      </div>
   </div>


   <!-- Bootstrap Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script src="js/list.js"></script>
   <script>
      // intialize toast
      var toastLiveExample = document.getElementById('liveToast');

      // intialize tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      })
   </script>
</body>
</html>
