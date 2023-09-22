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

Route::group(['prefix' => 'orders'], function()
{   
    Route::group(['middleware' => ['auth', 'verified']], function() {
        Route::get('/', 'OrdersController@index')->name('orders.index');
        Route::get('/create', 'OrdersController@create')->name('orders.create');
        Route::post('/create', 'OrdersController@store')->name('orders.store');
        Route::get('/{id}/edit', 'OrdersController@edit')->name('orders.edit');
        Route::patch('/{id}/update', 'OrdersController@update')->name('orders.update');
        Route::delete('/delete', 'OrdersController@destroy')->name('orders.delete');
    });
});
