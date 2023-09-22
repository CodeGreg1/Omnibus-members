<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (setting('allow_affiliates') === 'enable') {
    Route::group(['prefix' => 'user/affiliates', 'as' => 'user.', 'namespace' => 'User'], function () {
        Route::group(['middleware' => ['auth', 'verified']], function () {
            Route::get('/', 'AffiliatesController@index')->name('affiliates.index');
            Route::post('/create', 'AffiliatesController@store')->name('affiliates.store');
            Route::patch('/{id}/update', 'AffiliatesController@update')->name('affiliates.update');
            Route::get('/datatable', 'AffiliatesDatatableController@index')->name('affiliates.datatable');

            Route::get('/referrals/datatable', 'AffiliateReferralDatatableController@index')
                ->name('affiliates.referrals.datatable');
        });
    });

    Route::group(['prefix' => 'user/commissions', 'as' => 'user.', 'namespace' => 'User'], function () {
        Route::group(['middleware' => ['auth', 'verified']], function () {
            Route::get('/', 'AffiliateCommissionController@index')
                ->name('commissions.index');
            Route::get('/withdraw-all', 'AffiliateCommissionController@withdrawAll')
                ->name('commissions.withdraw-all');
            Route::get('/{commission}/withdraw', 'AffiliateCommissionController@withdraw')
                ->name('commissions.withdraw');
            Route::get('/datatable', 'AffiliateCommissionDatatableController@index')
                ->name('commissions.datatable');
        });
    });
}