<!-- resources/views/square/checkout.blade.php -->
<html>
<head>
    <title>Square Payment</title>
    <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
</head>
<body>
    <h1>Pay $1.00 with Square</h1>

<!-- HTML Part -->
<div id="card-container"></div>
<button id="card-button">Pay</button>

<script>
    const appId = "{{ config('square.app_id') }}";
    const locationId = "{{ config('square.location_id') }}";

    if (!appId || appId.length < 10) {
        alert("Invalid or missing Square Application ID");
    }

    const payments = Square.payments(appId, locationId);

    async function initializeCard(payments) {
        const card = await payments.card();
        await card.attach('#card-container');
        return card;
    }

    async function createPayment(token) {
        const response = await fetch('/square/charge', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nonce: token })
        });

        // Try to parse JSON even for failed responses
        const data = await response.json().catch(() => {
            throw new Error("Server returned an invalid JSON response.");
        });

        if (!response.ok) {
            const message = data?.error || "Payment failed";
            throw new Error(message);
        }

        return data;
    }

    document.addEventListener('DOMContentLoaded', async function () {
        const card = await initializeCard(payments);
        const cardButton = document.getElementById('card-button');

        cardButton.addEventListener('click', async function () {
            const result = await card.tokenize();

            if (result.status === 'OK') {
                try {
                    const paymentResult = await createPayment(result.token);
                    alert("✅ Payment successful:\n" + JSON.stringify(paymentResult, null, 2));
                } catch (err) {
                    console.error("Payment error:", err.message);
                    alert("❌ Payment failed: " + err.message);
                }
            } else {
                const errors = result.errors.map(e => e.message).join(", ");
                alert('❌ Tokenization failed: ' + errors);
            }
        });
    });
</script>

    
</body>
</html>
