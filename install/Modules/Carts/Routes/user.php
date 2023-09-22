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

Route::name('user.')
    ->prefix('user')
    ->namespace('User')
    ->group(function () {
        Route::middleware(['auth', 'verified'])
            ->name('carts.')
            ->prefix('carts')
            ->group(function () {
                Route::get('/checkout', 'CartCheckoutController@index')->name('checkout.index');
                Route::post('/checkout/create', 'CartCheckoutController@create')->name('checkout.create');
                Route::get('/checkout/approve', 'CartCheckoutController@store')->name('checkout.store');
            });

        Route::middleware(['auth', 'verified'])
            ->name('pay.')
            ->prefix('pay')
            ->group(function () {
                Route::get('{checkout}', 'CheckoutController@show')->name('show');
                Route::patch('{checkout}', 'CheckoutController@update')->name('update');
                Route::post('{checkout}/checkout-process', 'CheckoutController@process')->name('process');
                Route::get('{checkout}/checkout-approval', 'CheckoutController@approve')->name('approval');
                Route::post('{checkout}/validate-coupon', 'CheckoutCouponController@validate')
                    ->name('coupon.vaidate');
                Route::get('{checkout}/confirm-subscription', 'ConfirmCheckoutController@subscription')->name('confirm-subscription');
                Route::get('{checkout}/confirm-subscription-onetime', 'ConfirmCheckoutController@subscription')->name('confirm-subscription-onetime');
                Route::get('{checkout}/confirm-order', 'ConfirmCheckoutController@order')->name('confirm-order');
            });
    });