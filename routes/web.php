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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SquarePaymentController;

Route::get('/square/checkout', [SquarePaymentController::class, 'checkout']);
Route::post('/square/charge', [SquarePaymentController::class, 'charge']);

Route::get('/pay', [PaymentController::class, 'showForm'])->name('payment.form');
Route::post('/process', [PaymentController::class, 'processPayment'])->name('payment.process');

Route::get('/', function () {
    return view('welcome');
});
