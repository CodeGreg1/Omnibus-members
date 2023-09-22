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

Route::name('admin.reports.')
    ->prefix('admin/reports')
    ->namespace('Admin')
    ->group(function () {
        Route::get('/', 'ReportsController@index')->name('index');
        Route::get('/billing', 'BillingReportsController@index')->name('billing');
        Route::get('/billing/datatable', 'BillingReportsController@datatable')->name('billing.datatable');
        Route::get('/billing/overview', 'BillingReportsController@report')->name('billing.overview');
    });