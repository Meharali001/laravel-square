<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Square\SquareClient;
use Square\Models\Money;
use Square\Models\CreatePaymentRequest;
use Illuminate\Support\Str;
use Square\Environment;

class SquarePaymentController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new SquareClient([
            'accessToken' => config('square.access_token'),
            'environment' => config('square.env') === 'production'
                ? Environment::PRODUCTION
                : Environment::SANDBOX,
        ]);
    }

    public function checkout()
    {
        return view('square.checkout');
    }

    public function charge(Request $request)
    {
        
        $request->validate([
            'nonce' => 'required|string',
        ]);
    
        $nonce = $request->input('nonce');
    
        try {
            $paymentsApi = $this->client->getPaymentsApi();
    
            // ✅ Create the Money object correctly
            $money = new Money();
            $money->setAmount(200); // 1.00 in cents
            $money->setCurrency('USD'); // Or 'CAD'
    
            // ✅ Properly create the payment request
            $createPaymentRequest = new CreatePaymentRequest(
                $nonce,
                Str::uuid()
            );
    
            // ✅ Attach amount_money separately
            $createPaymentRequest->setAmountMoney($money);
            $createPaymentRequest->setLocationId(config('square.location_id'));
    
            \Log::info('Create Payment Request', $createPaymentRequest->jsonSerialize());
    
            $response = $paymentsApi->createPayment($createPaymentRequest);
    
            return response()->json($response->getResult());
    
        } catch (\Square\Exceptions\ApiException $e) {
            \Log::error('Square API Error: ' . $e->getMessage(), ['errors' => $e->getErrors()]);
            return response()->json([
                'error' => $e->getMessage(),
                'details' => $e->getErrors(),
            ], 400);
        } catch (\Exception $e) {
            \Log::error('General Error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
}
