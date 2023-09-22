<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (setting('allow_wallet') === 'enable') {
    Route::name('user.')
        ->prefix('user')
        ->namespace('User')
        ->group(function () {
            Route::middleware(['auth', 'verified'])
                ->name('deposits.')
                ->prefix('deposits')
                ->group(function () {
                    Route::get('/', 'DepositController@index')
                        ->name('index');
                    Route::get('/histories', 'DepositController@history')
                        ->name('histories.index');
                    Route::get('/histories/datatable', 'DepositController@datatable')
                        ->name('histories.datatable');

                    // Manual checkout
                    Route::get('/manual/{method}/checkout', 'ManualCheckoutController@index')
                        ->name('checkout.manual');
                    Route::post('/manual/{method}/checkout/process', 'ManualCheckoutController@process')
                        ->name('checkout.manual.process');

                    Route::get('/{gateway}/checkout', 'AutomaticCheckoutController@index')
                        ->name('checkout.automatic');

                    // Paypal checkout
                    Route::post('/paypal/checkout/process', 'PaypalCheckoutController@process')
                        ->name('checkout.paypal.process');
                    Route::get('/paypal/checkout/process-approve', 'PaypalCheckoutController@approve')
                        ->name('checkout.paypal.process-approve');

                    // Stripe checkout
                    Route::post('/stripe/checkout/process', 'StripeCheckoutController@process')
                        ->name('checkout.stripe.process');
                    Route::get('/stripe/checkout/process-approve', 'StripeCheckoutController@approve')
                        ->name('checkout.stripe.process-approve');

                    // Mollie checkout
                    Route::post('/mollie/checkout/process', 'MollieCheckoutController@process')
                        ->name('checkout.mollie.process');
                    Route::get('/mollie/checkout/process-approve', 'MollieCheckoutController@approve')
                        ->name('checkout.mollie.process-approve');

                    // Razorpay checkout
                    Route::post('/razorpay/checkout/process', 'RazorpayCheckoutController@process')
                        ->name('checkout.razorpay.process');
                    Route::get('/razorpay/checkout/process-approve', 'RazorpayCheckoutController@approve')
                        ->name('checkout.razorpay.process-approve');
                });
        });
}