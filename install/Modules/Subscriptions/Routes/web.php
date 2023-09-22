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

if (setting('allow_subscriptions') === 'enable') {
    Route::name('user.packages.')
        ->prefix('user/packages')
        ->group(function () {
            Route::middleware(['auth', 'verified'])
                ->group(function () {
                    Route::get('/{tableId}/{priceId}', 'PackageCheckoutController@index')->name('pay');
                    Route::get('/{tableId}/{priceId}/checkout', 'PackageCheckoutController@checkoutFree')
                        ->name('checkout-free');
                    Route::get('/{tableId}/{priceId}/checkout-confirm', 'PackageCheckoutController@confirmCheckoutFree')
                        ->name('confirm-checkout-free');
                });
        });

    Route::name('user.subscriptions.')
        ->prefix('user/subscriptions')
        ->group(function () {
            Route::middleware(['auth', 'verified'])
                ->namespace('User')
                ->group(function () {
                    Route::get('{subscription}/manual-checkout-payment', 'SubscriptionManualPaymentController@index')
                        ->name('manual-payment-checkout');
                    Route::post('{subscription}/manual-process-payment', 'SubscriptionManualPaymentController@store')
                        ->name('manual-payment-process');

                    Route::get('{subscription}/module-usages', 'ModuleUsageController@index')->name('module-usages');
                    Route::get('module-usages/unauthorized', 'InsufficientModulePermissionController@handle')
                        ->name('module-usages.unauthorized');
                    Route::get('{subscription}/{price}/create-change-package-setup', 'ChangeSubscriptionPackageController@create')
                        ->name('change-package.create');
                    Route::get('{subscription}/change-package', 'ChangeSubscriptionPackageController@index')
                        ->name('change-package.index');
                    Route::get('{subscription}/setup-change-package', 'ChangeSubscriptionPackageController@setup')
                        ->name('change-package.setup');
                    Route::get('{subscription}/complete-change-package', 'ChangeSubscriptionPackageController@complete')
                        ->name('change-package.complete');
                });

            Route::middleware(['auth', 'verified'])
                ->group(function () {
                    Route::get('/pricing', 'PricingController@index')->name('pricings.index');
                    Route::get('/pricing/{price}', 'PricingController@show')->name('pricings.show');
                });

            Route::middleware(['auth', 'verified'])
                ->namespace('User')
                ->group(function () {
                    Route::get('/', 'SubscriptionsController@index')
                        ->name('index');
                    Route::get('/datatable', 'SubscriptionsController@datatable')
                        ->name('datatable');
                    Route::get('/payments/datatable', 'SubscriptionPaymentController@datatable')
                        ->name('payments.datatable');
                    Route::get('/{id}', 'SubscriptionsController@show')
                        ->name('show');
                    Route::get('/{id}/cancel', 'SubscriptionsController@cancel')
                        ->name('cancel');
                    Route::get('/{id}/resume', 'SubscriptionsController@resume')
                        ->name('resume');
                });
        });

    Route::middleware(['auth', 'verified'])
        ->prefix('profile')
        ->namespace('User')
        ->group(function () {
            Route::get('billing', 'SubscriptionProfileController@index')->name('profile.billing');
        });

    Route::get('/site/pricing', 'PricingController@index')->name('pricing');
}