<?php

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

if (setting('allow_wallet') === 'enable') {
    Route::name('user.')
        ->prefix('user')
        ->namespace('User')
        ->group(function () {
            Route::middleware(['auth', 'verified'])
                ->name('transactions.')
                ->prefix('transactions')
                ->group(function () {
                    Route::get('/', 'TransactionController@index')
                        ->name('index');
                    Route::get('/datatable', 'TransactionController@datatable')
                        ->name('datatable');
                });
        });
}