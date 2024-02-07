   <link rel="stylesheet" href="{{asset('css/views/purchase-credit-model.css')}}">

<form action="{{ route('purchase.credit') }}" method="post" id="topup-form" name="topup-form">
  <input type="hidden" name="price" id="credit_price" value="{{ $data['price'] }}">
  <input type="hidden" name="credits" id="nocredits" value="{{ $data['credtis'] }}">
  @csrf
<div class="modal-header pd-x-20">
  <h6 class="modal-title"> Buy Credits </h6>
  <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-4 pt-2 pb-0">
     
<div class="h-100 position-relative">
        <div class="5">
          <div class="">
            <div class="">
              <div class="row">
                <div class="col-sm-12">
                <div class="col-sm-12">
                  <h5 class="card-title fw-500 mb-3">Details</h5>
                  <div class="card shadow-sm" style="border-radius: 0.50rem!important;">
                      
                      <ul class="list-group mt-3">
                        <li class="list-group-item">
                          <span class="fw-500">Price: {{ $data['price'] }}</span>
                        </li>
                        <li class="list-group-item">
                          <span class="fw-500">Credits: {{ $data['credtis'] }}</span>
                        </li>
                        
                      </ul>
                   
                  </div>
                </div>
                <br>
                <div class="col-sm-12">
                  <p class="fw-bold mb-3">Payment Details</p> 
                   <div class="payment-errors" id="payment-errors"></div>
                  <form action="{{ route('subscription.create') }}" method="post" id="subscribe-form" name="subscribe-form"> 
                    
                     <input type="hidden" name="cardId" id="cardId" value="">
                     @csrf 
                    
                     @if(count($cards) > 0 )
                   
                    <div class="form-row">
                        <div class="col-50">
                           <p class="fw-bold mb-3">Select Card</p>
                           @if(count($cards) > 0 )
                           @foreach($cards  as $card)
                           <label class="custom-control custom-checkbox mysaveCrad">
                            <input type="radio" class="colorinput-input custom-control-input savedCard" name="card_token" id="card_token" value="{{ $card->id }}" {{ ($default_payment_method
                                       == $card->id ) ? 'checked' :'' }}>
                            <span class="custom-control-label">{!!
                              @$card->card['brand'] !!}</span>**** **** **** **** {!! @$card->card['last4'] !!}</span>
                          </label>
                          @endforeach
                          @endif
                       
                        
                        </div>
                         <span id="errordiv"></span>
                    </div>
                   
                    @endif
                    <br>
                    
                  </form>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>

  </div>
  <div class="modal-footer">
  <button  class="btn btn-primary rounded-4" id="card-button"
                  data-secret="{{ Helper::paymentIntent() }}" ><small>Pay</small></button>
  <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" >Close</button>
</div>
</div>
</form>

 {!! Html::script('js/jquery.validate.js') !!}
<script type="text/javascript">

$("#topup-form").validate({
 rules: {
    card_token: {
    required: true,
   
  },
  name : {
      required: true,
  },
   email: {
        required: true,
        email: true,
        
    },
   country : {
      required: true,
  },
   city : {
      required: true,
  },
   state : {
      required: true,
  },
   postal_code : {
      required: true,
  },
   address : {
      required: true,
  }
 },
 messages: {
    card_token: {
        required: "Please select any one card",
      },
   name:{
        required: "Full Name is required",
   },
   email: {
    required: "Email address is required",
    email: "Email address is invalid.",
    },
   country:{
        required: "country is required",
   },
   city:{
        required: "city is required",
   },
   state:{
        required: "state is required",
   },
   postal_code:{
        required: "postal code is required",
   },
   address:{
        required: "address is required",
   }

 },
  errorElement: 'div',
    errorPlacement: function(error, element) {
      error.insertBefore(element.parent());
    }
})
$(document).ready(function(){
 var stripe = Stripe('{{ env('STRIPE_KEY') }} ');

    const cardButton = document.getElementById('card-button');
    cardButton.addEventListener('click', async (e) => {
        if($("#topup-form").valid()) {
            $("#form-submit-loading").addClass('show');
            $("#topup-form").submit();
            
        }
       

    });



});
</script>
