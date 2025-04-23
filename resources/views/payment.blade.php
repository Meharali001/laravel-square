<!DOCTYPE html>
<html>
<head>
    <title>Square Payment</title>
    <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
</head>
<body>
    <h2>Square Payment</h2>

    <form id="payment-form" method="POST" action="{{ route('payment.process') }}">
        @csrf
        <div id="card-container"></div>
        <button type="submit" id="card-button">Pay Now</button>
    </form>

    <script>
        const appId = "{{ config('square.application_id') }}";
        const locationId = "{{ config('square.location_id') }}";

        async function initializeCard(payments) {
            const card = await payments.card();
            await card.attach('#card-container');
            return card;
        }

        document.addEventListener('DOMContentLoaded', async function () {
            const payments = Square.payments(appId, locationId);
            const card = await initializeCard(payments);

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                const result = await card.tokenize();

                if (result.status === 'OK') {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'token');
                    hiddenInput.setAttribute('value', result.token);
                    form.appendChild(hiddenInput);
                    form.submit();
                } else {
                    alert('Card error: ' + result.errors[0].message);
                }
            });
        });
    </script>
</body>
</html>
