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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <script type="text/javascript">
         var appurl = '{{url("/")}}/';
      </script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/3.0.0/jquery.payment.min.js"></script>
   <title>Billing - Reachomation</title>
     <link rel="stylesheet" href="{{asset('css/views/front-pages/plan-detail.css')}}">
</head>
  <body class="bg-body"> 
    @include('sections.front-navbar') 
    @include('sections.message') 
     <?php       
        $plan_type = ($plan->plan_type =='1') ? 'month' :'Year';

       ?>
    <section class="position-relative">
         <div id="form-submit-loading">
        <div class="close-loader">
          <i class="fa fa-times"></i>
        </div>
      </div>
      <svg viewBox="0 0 220 192" width="220" height="192" fill="none" class="position-absolute text-muted bottom-0 start-0">
        <defs class="ng-tns-c213-0">
          <pattern id="837c3e70-6c3a-44e6-8854-cc48c737b659" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse" class="ng-tns-c213-0">
            <rect x="0" y="0" width="4" height="4" fill="currentColor" class="ng-tns-c213-0"></rect>
          </pattern>
        </defs>
        <rect width="220" height="192" fill="url(#837c3e70-6c3a-44e6-8854-cc48c737b659)" class="ng-tns-c213-0"></rect>
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
                        <div>  <h5 class="mb-0">{{ ucfirst($plan->name) }}</h5> </div>
                        <div class="ms-auto">  <h5 class="mb-0">${{$plan->price}}/{{$plan_type}}</h5>   </div>
                      </div>
                      <ul class="list-group mt-3">
                        <li class="list-group-item">
                          <span class="fw-500">Credits: {{ $plan->credits }}</span>
                        </li>
                        <li class="list-group-item">
                          <span class="fw-500">Size Limit: {{ $plan->size_limit }}</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <p class="fw-bold mb-3">Payment Details</p> 
                   <div class="payment-errors" id="payment-errors"></div>
                  <form action="{{ route('subscription.create') }}" method="post" id="subscribe-form" name="subscribe-form"> 
                     <input type="hidden" name="plan" id="plan" value="{{ $plan->stripe_plan_id }}">
                     <input type="hidden" name="cardId" id="cardId" value="">
                     @csrf 
                    
                     @if(count($cards) > 0 )
                   
                    <div class="form-row">
                        <div class="col-50">
                           <p class="fw-bold mb-3">Select Card</p>
                           @if(count($cards) > 0 )
                           @foreach($cards  as $card)
                           <label class="custom-control custom-checkbox mysaveCrad">
                            <input type="radio" class="colorinput-input custom-control-input savedCard" name="card_token" id="card_token" value="{{ $card->id }}">
                            <span class="custom-control-label">{!! @$card->details['brand'] !!}**** **** ****{!! @$card->details['last4'] !!}</span>
                          </label>
                          @endforeach
                          @endif
                       
                        
                        </div>
                         <span id="errordiv"></span>
                    </div>
                   
                    @endif
                    <br>
                    <div class="form-row">
                        <div class="col-50">
                         <a  href="javascript:;" id="addCard" class="fw-bold mb-3 addCard" > <i class="bi bi-plus-lg"></i> Add New card</a>
                       </div>
                    </div>
                    <br>
                    <div class="address">
                      <p class="fw-bold mb-3">Billing address</p>
                      <div class="form-floating mb-3">
                        <textarea class="form-control shadow-none" id="adr" name="address" placeholder="542 W. 15th Street" style="resize: none;" >{{ Auth::user()->line1 }}</textarea>
                         <span id="errordiv"></span>
                      </div>
                      <div class="form-floating mb-3">
                        <select class="form-select shadow-none country" id="floatingSelect" aria-label="Floating label select example" name="country" >
                          @foreach($countries as $county)
                            <option  value="{{ $county->shortcode }}"   countryid ="{{ $county->id }}"  @if(Auth::user()->country == $county->shortcode )  selected  @endif>
                              {{ ucfirst($county->country) }}
                            </option>
                            @endforeach
                        </select>
                         <span id="errordiv"></span>
                      </div>
                      <div class="form-floating mb-3">
                        <select class="form-select shadow-none state" id="floatingSelect" aria-label="Floating label select example" name="state">
                          <option value="" selected>Select State</option>
                         @if(count($statsList) >0)
                           @foreach($statsList as $state)
                           <option value="{{ $state->name }}"  @if(Auth::user()->state == $state->name )  selected  @endif  >{{ ucfirst($state->name) }}</option>

                           @endforeach
                           @endif
                        </select>
                         <span id="errordiv"></span>
                      </div>
                      <div class="row g-2">
                        <div class="col-md">
                          <div class="form-floating">
                            <input type="text" class="form-control rounded-4 shadow-none" id="city" name="city" placeholder="New York" value="{{ Auth::user()->city }}">
                             <span id="errordiv"></span>
                          </div>
                        </div>
                        <div class="col-md">
                          <div class="form-floating">
                            <input type="text" class="form-control rounded-4 shadow-none" id="postal_code" name="postal_code" placeholder="10001" value="{{ Auth::user()->postal_code }}">
                            <span id="errordiv"></span>
                          </div>
                        </div>
                      </div>
                      <hr> 
                      <div class="d-flex flex-column mt-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                          <p class="fw-bold">Total</p>
                          <p class="fw-bold">
                            <span class="fas fa-dollar-sign"></span>
                            <i class="bi bi-currency-dollar"></i>{{$plan->price}}
                          </p>
                        </div>
                        
                        <div class="d-grid">
                          <button id="card-button"  class="btn btn-primary mt-1 fw-500" data-token="" data-secret="{{ $intent->client_secret }}"> 
                          @if(auth()->user()->plan !='1' && auth()->user()->plan !='')
                            UPGRADE PLAN
                          @else
                          PAY <i class="bi bi-currency-dollar"></i>{{$plan->price}}
                          @endif
                          </button>
                        </div>
                        @if(auth()->user()->plan !='1' && auth()->user()->plan !='')
                        <p>Note:*Your upgrade will adjust the amount you have already paid for the current subscription and current billing will be done after adjusting the same.</p>
                        @endif
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
 <div id="add-cards" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
       <div class="modal-dialog modal-md" role="document">
         <div class="text-center"> 
            <div class="spinner4">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
         </div>
         </div>
           <div class="modal-content" id="add-cards-section">
               
           </div>
       </div>
   </div>

     @include('sections.footer')

      {!! Html::script('js/jquery.validate.js') !!} 
     <script src="https://js.stripe.com/v3/"></script>
    <script>

      $('document').ready(function() {
       
        var totalCard = '{{ count($cards) }}';
        if(totalCard <=0){
          cardButton.disabled = true
        }

       $('.country').change(function() {
          var country_id =  $(".country option:selected").attr('countryid');
          $.ajax({
            url: appurl+"get-state",
            type: "post",
            data: {country_id :country_id },
            success: function(text) {
                $(".state").html(text);
          
            }
          });
         
        });
   
        $('#addCard').click(function() {
          $.ajax({
            url: appurl+"add-card-model",
            type: "post",
            data: {},
            success: function(text) {
                $("#add-cards").modal('show');
                $('#add-cards-section').html(text);
            }
          });
         
        });
     });
      $(".savedCard").click(function() {
          $(".savedCard").attr("checked", false); //uncheck all checkboxes
          $(this).attr("checked", true);  //check the clicked one
          var value = $(this).val();  //check the clicked one
          $('#cardId').val(value);
          $('#card-button').attr('data-token',value);


        });
     
      $('.mysaveCrad input:checkbox').click(function() {
          $('.mysaveCrad input:checkbox').not(this).prop('checked', false);
      });

      $("#subscribe-form").validate({
        rules: {
          /*card_holder_name: {
            required: true,
          },
          name: {
            required: true,
          },
          
          email: {
            required: true,
            email: true,
          },
          */
          card_token: {
            required: true,
           
          },
          
          country: {
            required: true,
          },
          /*city: {
            required: true,
          },*/
          state: {
            required: true,
          },
          postal_code: {
            required: true,
          },
          address: {
            required: true,
          }
        },
        messages: {
          /*card_holder_name: {
            required: "Card holder Name is required",
          },
           name: {
            required: "Full Name is required",
          },
          email: {
            required: "Email address is required",
            email: "Email address is invalid.",
          },
          */
          card_token: {
            required: "Please select any one card",
          },
          country: {
            required: "Please select country",
          },
          /*city: {
            required: "city is required",
          },*/
          state: {
            required: "Please select state",
          },
          postal_code: {
            required: "Please enter psotal code",
          },
          address: {
            required: "Please enter full address",
          }
        },
        errorElement: 'div',
    errorPlacement: function(error, element) {
      error.insertBefore(element.parent());
    }


      })


      var stripe = Stripe('{{ env('STRIPE_KEY') }}');
      const cardButton = document.getElementById('card-button');
      const clientSecret = cardButton.dataset.secret;
      
        cardButton.addEventListener('click', async (e) => {
          const cardToken = cardButton.dataset.token
          if($("#subscribe-form").valid()) {
            $("#form-submit-loading").addClass('show');
            $("#subscribe-form").submit();
            
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
