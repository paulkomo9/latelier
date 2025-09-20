$(function () {

    /**
     * Pass Header Token
     */
    $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
    });

    if (typeof Stripe === 'undefined') {
        //console.error("Stripe is not loaded yet.");
        return;
    }

 

    /**
     * integrates Stripe Elements for card payment
     */
    const stripe = Stripe(window.Latelier.stripeKey);

    const elements = stripe.elements();

    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        cardButton.disabled = true;
        cardButton.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Processing...';

        // Step 1: Create Payment Method
        const { error: methodError, paymentMethod } = await stripe.createPaymentMethod(
            'card',
            cardElement,
            {
                billing_details: { name: cardHolderName.value }
            }
        );

        if (methodError) {
            document.getElementById('card-errors').textContent = methodError.message;
            cardButton.disabled = false;
            cardButton.innerHTML = '<i class="fa fa-credit-card"></i> Pay';
            return;
        }

        // Step 2: Confirm Payment Intent
        const { error: confirmError, paymentIntent } = await stripe.confirmCardPayment(
            clientSecret,
            {
                payment_method: paymentMethod.id
            }
        );

        if (confirmError) {
            document.getElementById('card-errors').textContent = confirmError.message;
            cardButton.disabled = false;
            cardButton.innerHTML = '<i class="fa fa-credit-card"></i> Pay';
            return;
        }

        // Step 3: If succeeded, send intent ID back to server
        if (paymentIntent.status === 'succeeded') {
            const intentInput = document.createElement('input');
            intentInput.type = 'hidden';
            intentInput.name = 'payment_intent_id';
            intentInput.value = paymentIntent.id;
            form.appendChild(intentInput);

            const pmInput = document.createElement('input');
            pmInput.type = 'hidden';
            pmInput.name = 'payment_method';
            pmInput.value = paymentMethod.id;
            form.appendChild(pmInput);

            form.submit();
        }
    });


});