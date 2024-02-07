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
        const {
          setupIntent,
          error
        } = await stripe.confirmCardSetup(clientSecret, {
          payment_method: {
            card: card,
            billing_details: {
              //name: cardHolderName.value
            }
          }
        });
        if (error) {
          cardButton.disabled = false
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = error.message;
        } else {
          paymentMethodHandler(setupIntent.payment_method);
        }
      });

      function paymentMethodHandler(payment_method) {
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

      