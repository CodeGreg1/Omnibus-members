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

Route::name('admin.settings.')
    ->prefix('admin/settings')
    ->namespace('Admin')
    ->group(function () {
        Route::middleware(['auth', 'verified', 'can:admin.settings.index'])
            ->group(function () {
                Route::get('/payment-gateways', 'SettingController@index')
                    ->name('payment-gateways');
                Route::post('/payment-gateways/update-general-settings', 'SettingController@updateGeneralSettings')
                    ->name('payment-gateways.general-settings.update');
                Route::post('/payment-gateways/update-api-settings', 'SettingController@updateApiSettings')
                    ->name('payment-gateways.api-settings.update');
                Route::post('/payment-gateways/update-deposit-settings', 'SettingController@updateDepositSettings')
                    ->name('payment-gateways.deposit-settings.update');
                Route::get('/payment-gateways/{gateway}/install-webhook', 'SettingController@installWebhook')
                    ->name('payment-gateways.install-webhook');
            });
    });