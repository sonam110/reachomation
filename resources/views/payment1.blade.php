<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="theme-color" content="#af0000">
   <!-- Bootstrap CSS -->
   <link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet"
      >
   <!-- Bootstrap icons -->
   <link rel="stylesheet" href="/css/bootstrap-icons.css">
   <!-- Manual CSS -->
   <link rel="stylesheet" href="{{asset('css/custom.css')}}">
   <!-- favicon -->
   <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}" />
   <!-- JQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/3.0.0/jquery.payment.min.js"></script>
   <title>Billing - Reachomation</title>
</head>

<body class="bg-body">
   @php
      $stripe_key = env('STRIPE_KEY');

      $amount = 99;
   @endphp
   @include('sections.front-navbar')

   <section class="position-relative">
      <svg viewBox="0 0 220 192" width="220" height="192" fill="none"
         class="position-absolute text-muted bottom-0 start-0">
         <defs class="ng-tns-c213-0">
            <pattern id="837c3e70-6c3a-44e6-8854-cc48c737b659" x="0" y="0" width="20" height="20"
               patternUnits="userSpaceOnUse" class="ng-tns-c213-0">
               <rect x="0" y="0" width="4" height="4" fill="currentColor" class="ng-tns-c213-0"></rect>
            </pattern>
         </defs>
         <rect width="220" height="192" fill="url(#837c3e70-6c3a-44e6-8854-cc48c737b659)" class="ng-tns-c213-0">
         </rect>
      </svg>
      <div class="h-100 position-relative">
         <div class="container py-4 px-5">
            <div class="card shadow mb-3 bg-light py-3 px-5">
               <div class="card-body">
                  <div class="row">

<div class="col-sm-6">
   <h5 class="card-title fw-500 mb-3">Plan</h5>
   <div class="card shadow-sm" style="border-radius: 0.50rem!important;">
      <div class="card-body">
         <div class="hstack gap-3">
            <div>
               @if(isset($_GET['plan']))
                  <h5 class="mb-0">{{ ucfirst($_GET['plan']) }}</h5> 
               @endif
            </div>
            <div class="ms-auto">
               @if(isset($_GET['plan']))
                  @if($_GET['duration']=='monthly')
                     @if ($_GET['plan']=='standard')
                        <h5 class="mb-0">$79/mo</h5>
                     @endif
                     @if ($_GET['plan']=='premium')
                        <h5 class="mb-0">$159/mo</h5>
                     @endif  
                  @endif
               @endif
            </div>
         </div>
         <ul class="list-group mt-3">
            <li class="list-group-item">
               <span class="fw-500">Credits: 45000</span>
            </li>
            <li class="list-group-item">
               <span class="fw-500">Campaigns: 2</span>
            </li>
         </ul>
      </div>
   </div>
</div>

<div class="col-sm-6">
   <p class="fw-bold mb-3">Payment Details</p>
   @if (Session::has('success'))
      <div class="alert alert-success text-center">
         <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
         <p>{{ Session::get('success') }}</p>
      </div>
   @endif
   <form action="{{route('payment')}}"  method="post" id="payment-form">
      @csrf
      <div class="form-floating mb-3">
         <input type="text" class="form-control rounded-4 shadow-none" id="card_name" autocomplete="off" name="card_name" placeholder="xxxxxxxxxx">
         <label for="card_name">Name on Card*</label>
      </div>
      <div class="form-floating mb-3">
         <input id="cc-number" name="card-number" type="tel" class="form-control cc-number rounded-4 shadow-none" autocomplete="cc-number" placeholder="xxxxxxxxxxxxx" required>
         <label for="cc-number">Card Number*</label>
      </div>

      <div class="row g-2">
         <div class="col-md">
            <div class="form-floating mb-3">
               <input id="cc-exp" name="expiry" type="tel" class="form-control cc-exp rounded-4 shadow-none" autocomplete="cc-exp" placeholder="xxxxxx" required>
               <label for="expire">MM/YYYY</label>
            </div>
         </div>
         <div class="col-md">
            <div class="form-floating mb-3">
               <input id="cc-cvc" name="cvv" type="tel" class="form-control cc-cvc rounded-4 shadow-none" autocomplete="off" placeholder="••••" required>
               <label for="cvv">CVV</label>
            </div>
         </div>
      </div>

      <div class="address">
         <p class="fw-bold mb-3">Billing address</p> 
         <div class="form-floating mb-3">
            <textarea class="form-control shadow-none" placeholder="Leave a comment here" id="floatingTextarea" style="resize: none;" name="address"></textarea>
            <label for="floatingTextarea">Address</label>
         </div>
         <div class="form-floating mb-3">
            <select class="form-select shadow-none" id="floatingSelect" aria-label="Floating label select example" name="country">
               <option value="1">One</option>
               <option value="2">Two</option>
               <option value="3">Three</option>
            </select>
            <label for="floatingSelect">Country</label>
         </div>
         <div class="row g-2">
            <div class="col-md">
               <div class="form-floating">
                  <input type="text" class="form-control rounded-4 shadow-none" id="city" autocomplete="off" name="city" placeholder="xxxxxxxxxx">
                  <label for="city">City</label>
               </div>
            </div>
            <div class="col-md">
               <div class="form-floating">
                  <input type="text" class="form-control rounded-4 shadow-none" id="postal" autocomplete="off" name="postal" placeholder="xxxxxxxxxx">
                  <label for="postal">Zip</label>
               </div>
            </div>
         </div>
         
         <hr>
         @php
            if(isset($_GET['plan'])){
               if($_GET['duration']=='monthly'){
                  $amount = 79;
               }
               if($_GET['duration']=='premium'){
                  $amount = 159;
               }
            }
         @endphp
         <input type="hidden" name="amount" value="{{$amount}}">
         <div class="d-flex flex-column mt-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
               <p class="fw-bold">Total</p>
               <p class="fw-bold"><span class="fas fa-dollar-sign"></span><i class="bi bi-currency-dollar"></i>{{$amount}}</p>
            </div>
            <div class="d-grid">
               <button id="card-button" class="btn btn-primary mt-1 fw-500" type="submit" data-secret=""> Pay <i class="bi bi-currency-dollar"></i>{{$amount}} </button>
            </div>
         </div>
      </div>
   </form>
</div>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   @include('sections.footer')

   <!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script src="https://js.stripe.com/v3/"></script>
   <script>
      $(function($) {
         $('[data-numeric]').payment('restrictNumeric');
         $('.cc-number').payment('formatCardNumber');
         $('.cc-exp').payment('formatCardExpiry');
         $('.cc-cvc').payment('formatCardCVC');
      });

      $(function() {
   
      var $form = $(".require-validation");
   
      $('form.require-validation').bind('submit', function(e) {
         var $form = $(".require-validation"),
         inputSelector = ['input[type=text]', 'input[type=tel]'].join(', '),
         $inputs = $form.find('.required').find(inputSelector),
         $errorMessage = $form.find('div.error'),
         valid = true;
         $errorMessage.addClass('hide');
   
         $('.has-error').removeClass('has-error');
         $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
            $input.parent().addClass('has-error');
            $errorMessage.removeClass('hide');
            e.preventDefault();
            }
         });
   
         if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
         }
   
   });
   
   function stripeResponseHandler(status, response) {
      if (response.error) {
         $('.error')
         .removeClass('hide')
         .find('.alert')
         .text(response.error.message);
      } else {
         /* token contains id, last4, and card type */
         var token = response['id'];
               
         $form.find('input[type=text]').empty();
         $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
         $form.get(0).submit();
      }
   }
   
   });

   const stripe = Stripe('{{ $stripe_key }}', { locale: 'en' });
   const clientSecret = cardButton.dataset.secret;

   // Handle form submission.
   var form = document.getElementById('payment-form');
        
   form.addEventListener('submit', function(event) {
      event.preventDefault();
      
      stripe.handleCardPayment(clientSecret, cardElement, {
            payment_method_data: {
               //billing_details: { name: cardHolderName.value }
            }
      })
      .then(function(result) {
            // console.log(result);
            if (result.error) {
               // Inform the user if there was an error.
               var errorElement = document.getElementById('card-errors');
               errorElement.textContent = result.error.message;
            } else {
               // console.log(result);
               form.submit();
            }
      });
   });
   </script>
</body>
</html>
