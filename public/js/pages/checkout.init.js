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
 * Stripe Elements - Clean Implementation
 */
    const stripe = Stripe(window.Latelier.stripeKey);
    const elements = stripe.elements();

    // Card عنصر
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d'
            }
        }
    });

    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        cardButton.disabled = true;
        cardButton.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Processing...';

        // 🔍 Debug (keep for now)
        console.log({
            email: document.getElementById('email')?.value,
            line1: document.getElementById('line1')?.value,
            city: document.getElementById('city')?.value,
            postal_code: document.getElementById('postal_code')?.value,
            country: document.getElementById('country')?.value
        });

        // ✅ Confirm Payment (ONLY step needed)
        const { error: confirmError, paymentIntent } = await stripe.confirmCardPayment(
            clientSecret,
            {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: cardHolderName.value,
                        email: document.getElementById('email')?.value || '',
                        address: {
                            line1: document.getElementById('line1')?.value || '',
                            city: document.getElementById('city')?.value || '',
                            postal_code: document.getElementById('postal_code')?.value || '',
                            country: document.getElementById('country')?.value || ''
                        }
                    }
                },
                payment_method_options: {
                    card: {
                        request_three_d_secure: 'any'
                    }
                }
            }
        );

        if (confirmError) {
            document.getElementById('card-errors').textContent = confirmError.message;
            cardButton.disabled = false;
            cardButton.innerHTML = '<i class="fa fa-credit-card"></i> Pay';
            return;
        }

        // ✅ Success → send intent to backend
        if (paymentIntent.status === 'succeeded') {
            const intentInput = document.createElement('input');
            intentInput.type = 'hidden';
            intentInput.name = 'payment_intent_id';
            intentInput.value = paymentIntent.id;
            form.appendChild(intentInput);

            form.submit();
        }
    });

});