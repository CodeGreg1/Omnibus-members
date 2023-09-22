<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin/affiliates', 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    if (setting('allow_affiliates') === 'enable') {
        Route::group(['middleware' => ['auth', 'verified', 'can:admin.affiliates.users.index'], 'prefix' => 'users'], function () {
            Route::get('/', 'AffiliatesController@index')
                ->name('affiliates.users.index');
            Route::put('/{affiliate}/approve', 'AffiliatesController@approve')
                ->name('affiliates.users.approve');
            Route::put('/{affiliate}/reject', 'AffiliatesController@reject')
                ->name('affiliates.users.reject');
            Route::put('/{affiliate}/enable', 'AffiliatesController@enable')
                ->name('affiliates.users.enable');
            Route::put('/{affiliate}/disable', 'AffiliatesController@disable')
                ->name('affiliates.users.disable');
            Route::get('/datatable', 'AffiliatesDatatableController@index')
                ->name('affiliates.users.datatable');
        });

        Route::group(['middleware' => ['auth', 'verified', 'can:admin.affiliates.referrals.index'], 'prefix' => 'referrals'], function () {
            Route::get('/', 'AffiliateReferralController@index')
                ->name('affiliates.referrals.index');
            Route::get('/datatable', 'AffiliateReferralDatatableController@index')
                ->name('affiliates.referrals.datatable');
        });

        Route::group(['middleware' => ['auth', 'verified', 'can:admin.affiliates.commissions.index'], 'prefix' => 'commissions'], function () {
            Route::get('/', 'AffiliateCommissionController@index')
                ->name('affiliates.commissions.index');
            Route::get('/datatable', 'AffiliateCommissionDatatableController@index')
                ->name('affiliates.commissions.datatable');
            Route::put('/{commission}/approve', 'AffiliateCommissionController@approve')
                ->name('affiliates.commissions.approve');
            Route::put('/{commission}/reject', 'AffiliateCommissionController@reject')
                ->name('affiliates.commissions.reject');
        });

        Route::group(['middleware' => ['auth', 'verified', 'can:admin.affiliates.settings.index'], 'prefix' => 'settings'], function () {
            Route::get('/', 'AffiliateSettingController@index')
                ->name('affiliates.settings.index');
        });

        Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'commission-types'], function () {
            Route::get('/', 'AffiliateCommissionTypeController@index')
                ->name('affiliates.commission-types.index');
            Route::put('/{type}/enable', 'AffiliateCommissionTypeController@enable')
                ->name('affiliates.commission-types.enable');
            Route::put('/{type}/disable', 'AffiliateCommissionTypeController@disable')
                ->name('affiliates.commission-types.disable');
            Route::post('/{type}/update', 'AffiliateCommissionTypeController@update')
                ->name('affiliates.commission-types.update');
        });
    }
});

Route::group(['prefix' => 'admin/settings/affiliates', 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    Route::get('/', 'AffiliateSettingController@index')
        ->name('settings.affiliates.index');
    Route::patch('/update', 'AffiliateSettingController@update')
        ->name('settings.affiliates.update');
});