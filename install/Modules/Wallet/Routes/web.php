<?php

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

if (setting('allow_wallet') === 'enable') {
    Route::group(['prefix' => 'profile/wallet'], function () {
        Route::group(['middleware' => ['auth', 'verified']], function () {
            Route::get('/', 'WalletController@index')
                ->name('user.profile.wallet.index');
            Route::get('/send-otp', 'WalletController@sendOtp')
                ->name('user.profile.wallet.send-otp');
            Route::get('/resend-otp', 'WalletController@resenOtp')
                ->name('user.profile.wallet.resend-otp');
            Route::post('/verify-otp', 'WalletController@verifyOtp')
                ->name('user.profile.wallet.verify-otp');
            Route::post('/exchange/process', 'WalletExchangeController@process')
                ->name('user.profile.wallet.exchange.process');
            Route::post('/exchange/checkout', 'WalletExchangeController@checkout')
                ->name('user.profile.wallet.exchange.checkout');

            Route::post('/send-money/process', 'WalletSendMoneyController@process')->name('user.profile.wallet.send-money.process');
            Route::post('/send-money/checkout', 'WalletSendMoneyController@checkout')->name('user.profile.wallet.send-money.checkout');

            Route::get('/transactions/datatable', 'UserTransactionController@datatable')
                ->name('user.profile.wallet.transactions.datatable');
        });
    });
}