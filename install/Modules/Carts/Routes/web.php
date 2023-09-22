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

Route::name('user.')
    ->prefix('user')
    ->group(function () {
        Route::middleware(['auth', 'verified'])
            ->name('carts.')
            ->prefix('carts')
            ->group(function () {
                Route::get('/', 'CartsController@index')->name('index');
                Route::get('/datatable', 'CartDatatableController@handle')->name('datatable');
                Route::get('/notification-items', 'CartNotificationItemController@index');
                Route::post('/create', 'CartsController@store');
                Route::patch('/{cartId}/update', 'CartsController@update');
                Route::delete('/delete', 'CartsController@destroy');
                Route::get('/cart-breakdown', 'CartsController@breakdown');
            });
    });