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

if (setting('allow_subscriptions') === 'enable') {
    Route::name('admin.subscriptions.')
        ->prefix('admin/subscriptions')
        ->namespace('Admin')
        ->group(function () {
            Route::name('users.')
                ->middleware(['auth', 'verified'])
                ->prefix('users')
                ->group(function () {
                    Route::name('invoices.')
                        ->prefix('{user}/invoices')
                        ->group(function () {
                            Route::get('/{id}/send', 'SendInvoiceController@handle')->name('send');
                        });
                });

            Route::name('payments.')
                ->middleware(['auth', 'verified'])
                ->prefix('payments')
                ->group(function () {
                    Route::get('/', 'SubscriptionPaymentController@index')->name('index');
                    Route::get('/datatable', 'SubscriptionPaymentDatatableController@handle')->name('datatable');
                });

            Route::name('packages.')
                ->middleware(['auth', 'verified'])
                ->prefix('packages')
                ->group(function () {
                    Route::get('/', 'PackageController@index')->name('index');
                    Route::get('datatable', 'PackageDatatableController@handle')
                        ->name('datatable');
                    Route::get('create', 'PackageController@create')->name('create');
                    Route::post('create', 'PackageController@store')->name('store');
                    Route::post('delete', 'PackageController@destroy')->name('delete');
                    Route::get('{id}', 'PackageController@show')->where('id', '[0-9]+')
                        ->name('show');
                    Route::patch('{id}/update', 'PackageController@update')
                        ->where('id', '[0-9]+')
                        ->name('update');
                    Route::post('{id}/update-features', 'PackageController@updateFeatures')
                        ->name('update-feature');
                    Route::post('{id}/reorder-features', 'PackageController@reorderFeatures')
                        ->name('reorder-feature');
                    Route::post('{id}/update-module-limits', 'PackageController@updateModuleLimits')
                        ->name('update-module-limits');
                    Route::delete('{package}/features/{feature}', 'PackageController@removeFeature')
                        ->name('delete-feature');

                    Route::get('{id}/extra-conditions/datatable', 'PackageExtraConditionController@datatable')
                        ->name('extra-conditions.datatable');
                    Route::post('{id}/extra-conditions/create', 'PackageExtraConditionController@store')
                        ->name('extra-conditions.store');
                    Route::patch('{id}/extra-conditions/{extraid}/update', 'PackageExtraConditionController@update')
                        ->name('extra-conditions.update');
                    Route::delete('{id}/extra-conditions/{extraid}/delete', 'PackageExtraConditionController@destroy')
                        ->name('extra-conditions.destroy');

                    Route::get('{id}/prices/datatable', 'PackagePriceDatatableController@handle')
                        ->where('id', '[0-9]+')
                        ->name('prices.datatable');
                    Route::post('{package}/prices/create', 'PriceController@store')
                        ->where('package', '[0-9]+')
                        ->name('prices.store');
                    Route::patch('{package}/prices/{price}/update', 'PriceController@update')
                        ->where(['package' => '[0-9]+', 'price' => '[0-9]+'])
                        ->name('prices.update');
                    Route::get('{package}/prices/{price}', 'PriceController@show')
                        ->where(['package' => '[0-9]+', 'price' => '[0-9]+'])
                        ->name('prices.show');
                    Route::delete('{package}/prices/{price}/delete', 'PriceController@destroy')
                        ->where(['package' => '[0-9]+', 'price' => '[0-9]+'])
                        ->name('prices.delete');

                    Route::get('features', 'FeaturesController@index')
                        ->name('features.index');
                    Route::get('features/datatable', 'FeatureDatatableController@handle')
                        ->name('features.datatable');
                    Route::post('features/create', 'FeaturesController@store')
                        ->name('features.store');
                    Route::patch('features/{id}/update', 'FeaturesController@update')
                        ->where('id', '[0-9]+')
                        ->name('features.update');
                    Route::delete('features/{id}/delete', 'FeaturesController@destroy')
                        ->where('id', '[0-9]+')
                        ->name('features.destroy');
                    Route::post('features/reorder', 'FeaturesController@reorder')
                        ->name('features.reorder');
                });

            Route::name('pricing-tables.')
                ->middleware(['auth', 'verified'])
                ->prefix('pricing-tables')
                ->group(function () {
                    Route::get('/', 'PricingTableController@index')->name('index');
                    Route::get('datatable', 'PricingTableDatatableController@handle')
                        ->name('datatable');
                    Route::get('/create', 'PricingTableController@create')->name('create');
                    Route::get('/{id}/show', 'PricingTableController@show')->name('show');
                    Route::post('/create', 'PricingTableController@store')->name('store');
                    Route::get('/{id}/edit', 'PricingTableController@edit')->name('edit');
                    Route::patch('/{id}/update', 'PricingTableController@update')->name('update');
                    Route::delete('/delete', 'PricingTableController@destroy')->name('destroy');
                    Route::get('/{id}/enable', 'PricingTableController@enable')->name('enable');
                    Route::get('/{id}/disable', 'PricingTableController@disable')->name('disable');
                });

            Route::get('/', 'SubscriptionsController@index')->name('index');
            Route::get('/datatable', 'SubscriptionDatatableController@handle')->name('datatable');
            Route::get('{subscription}', 'SubscriptionsController@show')->name('show');
            Route::get('{subscription}/cancel', 'SubscriptionsController@cancel')->name('cancel');
            Route::get('{subscription}/resume', 'SubscriptionsController@resume')->name('resume');
        });
}

Route::name('admin.settings.')
    ->prefix('admin/settings')
    ->namespace('Admin')
    ->group(function () {
        Route::middleware(['auth', 'verified', 'can:admin.settings.index'])
            ->group(function () {
                Route::get('subscriptions', 'SubscriptionSettingController@index')->name('subscriptions');
            });
    });

if (setting('allow_subscriptions') === 'enable') {
    Route::name('admin.users.')
        ->prefix('admin/users')
        ->namespace('Admin')
        ->group(function () {
            Route::middleware(['auth', 'verified', 'can:admin.users.index'])
                ->group(function () {
                    Route::get('/{user}/billing', 'UserBillingController@handle')->name('billing');
                });
        });
}