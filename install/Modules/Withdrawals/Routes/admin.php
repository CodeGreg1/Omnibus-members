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

if (setting('allow_wallet') === 'enable' && setting('allow_withdrawal') === 'enable') {
    Route::name('admin.')
        ->prefix('admin')
        ->namespace('Admin')
        ->group(function () {
            Route::middleware(['auth', 'verified'])
                ->name('withdrawals.')
                ->prefix('withdrawals')
                ->group(function () {
                    Route::get('/', 'WithdrawController@index')->name('index');
                    Route::get('/datatable', 'WithdrawController@datatable')->name('datatable');
                    Route::post('/{trx}/update', 'WithdrawAdditionalInformationController@update')
                        ->name('update');
                    Route::delete('/remove-media', 'WithdrawAdditionalInformationController@removeMedia')
                        ->name('remove-media');

                    Route::get('/methods', 'WithdrawMethodController@index')
                        ->name('methods.index');
                    Route::get('/methods/create', 'WithdrawMethodController@create')
                        ->name('methods.create');
                    Route::get('/methods/datatable', 'WithdrawMethodController@datatable')
                        ->name('methods.datatable');
                    Route::get('/methods/{id}/edit', 'WithdrawMethodController@edit')
                        ->name('methods.edit');

                    Route::get('/{trx}', 'WithdrawController@show')->name('show');
                    Route::post('/{trx}/approve', 'WithdrawController@approve')->name('approve');
                    Route::post('/{trx}/reject', 'WithdrawController@reject')->name('reject');
                });
        });
}