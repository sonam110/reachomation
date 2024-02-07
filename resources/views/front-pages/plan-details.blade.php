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
    <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">-->
   <link rel="stylesheet" href="{{asset('css/views/front-pages/plan-details.css')}}">
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <title>Pricing - Reachomation</title>
</head>

<body class="bg-body">
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
        <?php       
          $plan_type = ($plan->plan_type =='1') ? 'month' :'Year';


         ?>
        <div class="container py-4 px-3">
            <div class="card shadow mb-3">
               <div class="card-body">
                  <h4 class="card-title fw-bold text-center">Plans Detail</h4>
                  <div class="text-center">
                     <div class="btn-group mt-2 mb-3" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" value="1" checked>
                        <label class="btn btn-outline-success shadow-none" for="btnradio1" style="font-weight: 600;">{{ $plan->name }} (${{$plan->price}}/{{$plan_type}})</label>
                      
                      
                     </div>
                  </div>
                   <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                   @include('sections.message')

                                   <div class="wrapper">
                                        <div class="container">
                                            <form action="{{ route('subscription.create') }}" method="post" id="subscribe-form">
                                              @csrf 
                                                <h1>
                                                    <i class="fas fa-shipping-fast"></i>
                                                    Shipping Details
                                                </h1>
                                                <div class="name">
                                                    <div>
                                                        <label for="f-name">Name</label>
                                                        <input type="text" name="f-name">
                                                    </div>
                                                    
                                                </div>
                                                <div class="street">
                                                    <label for="name">Country</label>
                                                    <input type="text" name="country">
                                                </div>
                                                <div class="address-info">
                                                    <div>
                                                        <label for="city">City</label>
                                                        <input type="text" name="city">
                                                    </div>
                                                    <div>
                                                        <label for="state">State</label>
                                                        <input type="text" name="state">
                                                    </div>
                                                    <div>
                                                        <label for="zip">Postal Code</label>
                                                        <input type="text" name="postal_code">
                                                    </div>
                                                </div>
                                                <h1>
                                                <i class="far fa-credit-card"></i> Payment Information
                                                </h1>
                                                <div class="cc-num">
                                                   <div id="card-element" class="form-control">
                                                </div>
                                               
                                                <div class="btns">
                                                    <button id="card-button" data-secret="{{ $intent->client_secret }}">PAY(${{$plan->price}})</button>
                                                    <button>Back to Plan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                  <!-- <form action="{{ route('subscription.create') }}" method="post" id="subscribe-form">
                                    @csrf    
                                        <h4> Billing Detail:</h4>    
                                        <hr>           
                                        <input type="hidden" name="plan" id="plan" value="{{ $plan->stripe_plan_id }}">
                                        <div class="col-md-6">
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="">Name</label>
                                                <input id="card-holder-name"  name="name" type="text" class="form-control" required>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="">Email</label>
                                                <input id="email"  name="email" type="email" class="form-control" required>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="">Country</label>
                                                <select name="country" id="country" class="form-control">
                                                @foreach($countries as $county)
                                                  <option value="{{ $county->shortcode }} ">{{ $county->shortcode }}</option>
                                                @endforeach
                                                </select>
                                              </div>
                                          </div>
                                           <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="">State</label>
                                                <input id="state"  name="state" type="text" class="form-control" required>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="">City</label>
                                                <input id="city"  name="city" type="text" class="form-control" required>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="">Postal code</label>
                                                <input id="postal_code"  name="postal_code" type="text" class="form-control" required>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="">Address</label>
                                                <textarea  id="address"  name="address" class="form-control" required></textarea>
                                              </div>
                                          </div>
                                        </div>
                                            
                                        <div class="col-md-6">
                                        <h4> Card Detail:</h4>
                                        <div class="form-row">
                                            <label for="card-element">Credit or debit card</label>
                                            <div id="card-element" class="form-control">
                                            </div>
                                            <!-- Used to display form errors. -->
                                            <div id="card-errors" role="alert"></div>
                                        </div>
                                      </div>
                                        <div class="stripe-errors"></div>
                                       
                                        <div class="form-group text-center">
                                            <button  id="card-button" data-secret="{{ $intent->client_secret }}" class="btn btn-lg btn-success btn-block">PAY(${{$plan->price}})</button>
                                        </div>
                                    </form> -->
                                  
                                     
                                </div>
                            </div>
                        </div>
                    </div>
                  
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="bg-white">
      <div class="h-100 rounded-0">
         <div class="container p-5">
            <div class="row">
               <div class="col-sm-12">
                  <h1 class="fw-bold text-center mb-5">Reachomation is loved by users worldwide</h1>
               </div>
               <div class="col-sm-12">
                  <div class="row row-cols-1 row-cols-md-3 g-4">
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm ">
                           <div class="card-body">
                              <img src="{{ asset('images/team-5.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Melissa Frank</h5>
                              <p class="card-text">The whole process of exploring websites and securing
                                 content
                                 placements is pretty smooth. Dedicated support team comes handy to quickly
                                 resolve any
                                 issues.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-4.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Shir Lapidot</h5>
                              <p class="card-text">The whole process of exploring websites and securing
                                 content
                                 placements is pretty smooth. Dedicated support team comes handy to quickly
                                 resolve any
                                 issues.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-3.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Bernd Garnot</h5>
                              <p class="card-text">It's a breeze working with the support team as they are
                                 always
                                 available to resolve any queries we may have. Their execution and output is
                                 precise and
                                 matches our expectations.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-2.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Ashton Popescu</h5>
                              <p class="card-text">The whole process of exploring websites and securing
                                 content
                                 placements is pretty smooth. Dedicated support team comes handy to quickly
                                 resolve any
                                 issues.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star text-info"></i>
                                 <i class="bi bi-star text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-1.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Robert Marshall</h5>
                              <p class="card-text">It's a breeze working with the support team as they are
                                 always
                                 available to resolve any queries we may have. Their execution and output is
                                 precise and
                                 matches our expectations.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 text-center shadow-sm">
                           <div class="card-body">
                              <img src="{{ asset('images/team-4.jpg') }}" alt="" width="52" height="52"
                                 class="rounded-circle" style="filter: blur(1px);">
                              <h5 class="card-title mt-2">Alex Smith</h5>
                              <p class="card-text">Our requirements aren't easy to match but these guys do a
                                 fairly
                                 decent job in getting us what we want. Their creative and content abilities
                                 makes them
                                 a service provider of our choice.</p>
                              <div class="d-flex justify-content-center">
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                                 <i class="bi bi-star-fill text-info"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="bg-light">
      <div class="h-100 rounded-0">
         <div class="container p-5">
            <div class="row">
               <div class="col-sm-12">
                  <h1 class="fw-bold text-center">FAQ</h1>
                  <h5 class="text-center mb-5" style="font-weight: 500;">Frequently Asked Questions</h5>
               </div>
               <div class="col-sm-12">
                  <div class="row row-cols-1 row-cols-md-2 g-4">
                     <div class="col">
                        <div class="card h-100 shadow-sm">
                           <div class="card-body">
                              <h5>Will my credit card be instantly billed?</h5>
                              <p class="mb-0">No. Your credit card will only be charged when your trial ends or if you
                                 upgrade your
                                 account manually.</p>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 shadow-sm">
                           <div class="card-body">
                              <h5>Will my credit card be billed during the trial period?</h5>
                              <p class="mb-0">No. Your credit card will not be billed if you cancel your subscription
                                 before your
                                 trial period ends. You will only be automatically billed after that time.</p>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 shadow-sm">
                           <div class="card-body">
                              <h5>Can I pay with a monthly plan?</h5>
                              <p class="mb-0">Yes, we have monthly plans as well, but they are not discounted.</p>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card h-100 shadow-sm">
                           <div class="card-body">
                              <h5>Can I pay with PayPal?</h5>
                              <p class="mb-0">Yes, contact us if you cannot pay with a credit card.</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   @include('sections.footer')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    var card = elements.create('card', {hidePostalCode: true,
        style: style});
    card.mount('#card-element');
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;
    cardButton.addEventListener('click', async (e) => {
        cardButton.disabled = true

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server.
                stripeTokenHandler(result.token);
            }
        });
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: { name: cardHolderName.value }
                }
            }
            );
        if (error) {
            cardButton.disabled = false
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
        } else {
            paymentMethodHandler(setupIntent.payment_method);
        }


    });
    function paymentMethodHandler(payment_method) {
        var form = document.getElementById('subscribe-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'payment_method');
        hiddenInput.setAttribute('value', payment_method);
        form.appendChild(hiddenInput);
        $("#form-submit-loading").addClass('show');
        form.submit();
    }

    function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('subscribe-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

          
        }
</script>
</body>

</html>
