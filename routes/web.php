<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// routes/web.php
// use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SquarePaymentController;


// create (square/checkout) route for show square payment gatway form and create '/square/charge post route for send payment request then create squarepaymentController and view file of checkout 

Route::get('/square/checkout', [SquarePaymentController::class, 'checkout']);
Route::post('/square/charge', [SquarePaymentController::class, 'charge']);



Route::get('/', function () {
    return view('welcome');
});
