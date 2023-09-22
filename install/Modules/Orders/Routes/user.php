<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::name('user.')
    ->prefix('user')
    ->namespace('User')
    ->group(function () {
        Route::middleware(['auth', 'verified'])
            ->name('orders.')
            ->prefix('orders')
            ->group(function () {
                Route::get('/', 'OrderController@index')->name('index');
                Route::get('/datatable', 'OrderDatatableController@handle')->name('datatable');
                Route::get('/{order}/show', 'OrderController@show')->name('show');
                Route::post('/{order}/cancel', 'OrderController@cancel')->name('cancel');
            });
    });