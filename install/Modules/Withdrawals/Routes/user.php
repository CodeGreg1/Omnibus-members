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

if (setting('allow_wallet') === 'enable' && setting('allow_withdrawal') === 'enable') {
    Route::name('user.')
        ->prefix('user')
        ->namespace('User')
        ->group(function () {
            Route::middleware(['auth', 'verified'])
                ->name('withdrawals.')
                ->prefix('withdrawals')
                ->group(function () {
                    Route::get('/', 'WithdrawController@index')
                        ->name('index');
                    Route::get('/histories', 'WithdrawController@history')
                        ->name('histories.index');
                    Route::get('/histories/datatable', 'WithdrawController@datatable')
                        ->name('histories.datatable');

                    // Checkout
                    Route::post('/checkout/process', 'WithdrawCheckoutController@process')
                        ->name('checkout.process');
                    Route::post('/checkout/store', 'WithdrawCheckoutController@store')
                        ->name('checkout.store');
                    Route::get('/checkout/{method}', 'WithdrawCheckoutController@create')
                        ->name('checkout.create');
                });
        });
}
