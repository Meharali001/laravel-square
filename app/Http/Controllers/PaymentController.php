<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Square\SquareClient;
use Square\Exceptions\ApiException;
use Square\Models\CreatePaymentRequest;

class PaymentController extends Controller
{
    public function showForm()
    {
        return view('payment');
    }

    public function processPayment(Request $request)
    {
        $client = SquareClient::builder()
        ->accessToken(config('square.access_token'))
        ->environment(config('square.env') === 'production' ? 'production' : 'sandbox')
        ->build();

        $paymentsApi = $client->getPaymentsApi();
        $amount = 100; // 1 USD

        try {
            $body = new CreatePaymentRequest(
                $request->token,
                uniqid(), // idempotency key
                new \Square\Models\Money($amount, 'USD')
            );

            $body->setLocationId(config('square.location_id'));
            $result = $paymentsApi->createPayment($body);

            if ($result->isSuccess()) {
                return redirect()->back()->with('success', 'Payment successful!');
            } else {
                return redirect()->back()->with('error', 'Payment failed.');
            }
        } catch (ApiException $e) {
            return redirect()->back()->with('error', 'Exception: ' . $e->getMessage());
        }
    }
}
