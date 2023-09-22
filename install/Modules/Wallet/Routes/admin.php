<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::name('admin.')
    ->prefix('admin')
    ->namespace('Admin')
    ->group(function () {
        Route::get('/settings/wallet', 'WalletSettingController@index')
            ->name('settings.wallet');
        Route::patch('/settings/wallet/update', 'WalletSettingController@update')
            ->name('settings.wallet.update');

        Route::get('/users/{user}/overview', 'UserOverviewController@index')
            ->name('users.overview');

        if (setting('allow_wallet') === 'enable') {
            Route::middleware(['auth', 'verified'])
                ->name('manual-gateways.')
                ->prefix('manual-gateways')
                ->group(function () {
                    Route::post('/create', 'ManualGatewayController@store')
                        ->name('store');
                    Route::post('/{id}/update', 'ManualGatewayController@update')
                        ->name('update');
                    Route::post('/enable', 'ManualGatewayController@enable')
                        ->name('enable');
                    Route::post('/disable', 'ManualGatewayController@disable')
                        ->name('disable');
                    Route::delete('/delete', 'ManualGatewayController@destroy')
                        ->name('destroy');
                    Route::delete('/remove-media', 'ManualGatewayController@removeMedia')
                        ->name('remove-media');
                });

            Route::post('/wallet/add-balance', 'WalletController@addBalance')
                ->name('wallets.balances.add');
            Route::post('/wallet/deduct-balance', 'WalletController@deductBalance')
                ->name('wallets.balances.deduct');
        }
    });