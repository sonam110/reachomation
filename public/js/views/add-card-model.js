$('document').ready(function() {
    var stripe = Stripe('pk_test_iUaDSK4PZIl8VGM2PhYujvc900jmB0iFmE');
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
    var card = elements.create('card', {
      hidePostalCode: true,
      style: style
    });
    card.mount('#card-element');
    card.addEventListener('change', function(event) {
      var displayError = document.getElementById('card-errors');
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });
    
    const addcardButton = document.getElementById('add-card-button');

    const cardHolderName = $('.card_holder_name').val();
    
    addcardButton.addEventListener('click', async (e) => {
      addcardButton.disabled = false
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
       const {
          setupIntent,
          error
        } = await stripe.confirmCardSetup(clientSecret, {
          payment_method: {
            card: card,
            billing_details: {
              name: cardHolderName
            }
          }
        });
        if (error) {
          cardButton.disabled = false
          $('#card-errors').show();
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = error.message;
        } else {
          paymentMethodHandle(setupIntent.payment_method);
        }
   
    });
     function paymentMethodHandle(payment_method) {
      var form = document.getElementById('payment-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'payment-method');
      hiddenInput.setAttribute('value', payment_method);
      form.appendChild(hiddenInput);
      $("#form-submit-loading").addClass('show');
      form.submit();
    }

    function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      var form = document.getElementById('payment-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);
      $("#form-submit-loading").addClass('show');
      form.submit();
    }

   }); 