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
        Route::middleware(['auth', 'verified'])
            ->name('users.')
            ->prefix('users')
            ->group(function () {
                Route::get('/query/datatable', 'UserQueryController@handle')->name('query.datatable');
            });

        Route::middleware(['auth', 'verified'])
            ->name('shipping-rates.')
            ->prefix('shipping-rates')
            ->group(function () {
                Route::get('/', 'ShippingRateController@index')->name('index');
                Route::get('/datatable', 'ShippingRateDatatableController@handle')->name('datatable');
                Route::post('/create', 'ShippingRateController@store')->name('store');
                Route::get('/{rate}/show', 'ShippingRateController@show')->name('show');
                Route::patch('{id}/update', 'ShippingRateController@update')
                    ->where('id', '[0-9]+')
                    ->name('update');
                Route::delete('/delete', 'ShippingRateController@destroy')->name('delete');
            });

        Route::middleware(['auth', 'verified'])
            ->name('tax-rates.')
            ->prefix('tax-rates')
            ->group(function () {
                Route::get('/', 'TaxRateController@index')->name('index');
                Route::get('/datatable', 'TaxRateDatatableController@handle')->name('datatable');
                Route::post('/create', 'TaxRateController@store')->name('store');
                Route::get('/{rate}/show', 'TaxRateController@show')->name('show');
                Route::patch('{id}/update', 'TaxRateController@update')
                    ->where('id', '[0-9]+')
                    ->name('update');
                Route::delete('/delete', 'TaxRateController@destroy')->name('delete');
            });

        Route::middleware(['auth', 'verified'])
            ->name('coupons.')
            ->prefix('coupons')
            ->group(function () {
                Route::get('/', 'CouponController@index')->name('index');
                Route::get('datatable', 'CouponDatatableController@handle')->name('datatable');
                Route::get('create', 'CouponController@create')->name('create');
                Route::post('create', 'CouponController@store')->name('store');
                Route::get('{coupon}', 'CouponController@show')
                    ->where('id', '[0-9]+')
                    ->name('show');
                Route::get('{coupon}/edit', 'CouponController@edit')
                    ->where('id', '[0-9]+')
                    ->name('edit');
                Route::patch('{coupon}/update', 'CouponController@update')
                    ->where('id', '[0-9]+')
                    ->name('update');
                Route::delete('{coupon}/delete', 'CouponController@destroy')
                    ->where('id', '[0-9]+')
                    ->name('destroy');

                Route::get('{coupon}/promo-codes', 'PromoCodeController@index')
                    ->where('id', '[0-9]+')
                    ->name('promo-codes.index');
                Route::get('promo-codes/{promoCodeId}', 'PromoCodeController@show')
                    ->where(['coupon' => '[0-9]+', 'promoCodeId' => '[0-9]+'])
                    ->name('promo-codes.show');
                Route::delete('promo-codes/{promoCodeId}/delete', 'PromoCodeController@destroy')
                    ->where(['coupon' => '[0-9]+', 'promoCodeId' => '[0-9]+'])
                    ->name('promo-codes.destroy');
                Route::post('{coupon}/promo-codes/create', 'PromoCodeController@store')
                    ->where('id', '[0-9]+')
                    ->name('promo-codes.store');
                Route::post('{coupon}/promo-codes/update', 'PromoCodeController@update')
                    ->where('id', '[0-9]+')
                    ->name('promo-codes.update');
                Route::post('{coupon}/promo-codes/activate', 'PromoCodeController@activate')
                    ->where('id', '[0-9]+')
                    ->name('promo-codes.activate');
                Route::post('{coupon}/promo-codes/archive', 'PromoCodeController@archive')
                    ->where('id', '[0-9]+')
                    ->name('promo-codes.archive');

                Route::get('{coupon}/subscriptions', 'CouponSubscriptionController@datatable')
                    ->name('subscriptions');

                Route::get('promo-codes/{promoCodeId}/subscriptions', 'PromoCodeSubscriptionController@datatable')
                    ->where(['coupon' => '[0-9]+', 'promoCodeId' => '[0-9]+'])
                    ->name('promo-codes.subscriptions');

                Route::post('promo-codes/{promoCodeId}/user-create', 'PromoCodeUserController@store')
                    ->where(['promoCodeId' => '[0-9]+'])
                    ->name('promo-codes.user-store');
                Route::delete('promo-codes/{promoCodeId}/{userId}/user-delete', 'PromoCodeUserController@destroy')
                    ->where(['promoCodeId' => '[0-9]+', 'userId' => '[0-9]+'])
                    ->name('promo-codes.user-destroy');
            });
    });