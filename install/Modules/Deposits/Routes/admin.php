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

if (setting('allow_wallet') === 'enable') {
    Route::name('admin.')
        ->prefix('admin')
        ->namespace('Admin')
        ->group(function () {
            Route::middleware(['auth', 'verified'])
                ->name('deposits.')
                ->prefix('deposits')
                ->group(function () {
                    Route::get('/', 'DepositController@index')->name('index');
                    Route::get('/datatable', 'DepositController@datatable')->name('datatable');
                    Route::get('/{trx}', 'DepositController@show')->name('show');
                    Route::post('/{trx}/approve', 'DepositController@approve')->name('approve');
                    Route::post('/{trx}/reject', 'DepositController@reject')->name('reject');

                    Route::get('/gateway/manual', 'ManualMethodController@index')
                        ->name('gateways.manuals.index');
                    Route::get('/gateway/manual/create', 'ManualMethodController@create')
                        ->name('gateways.manuals.create');
                    Route::get('/gateway/manual/datatable', 'ManualMethodDatatableController@handle')
                        ->name('gateways.manuals.datatable');
                    Route::get('/gateway/manual/{id}/edit', 'ManualMethodController@edit')
                        ->name('gateways.manuals.edit');
                });
        });
}