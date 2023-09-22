<?php

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
Route::group(['prefix' => 'admin/currencies', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.available-currencies.index']], function() {
        Route::get('/', 'AvailableCurrenciesController@index')->name('available-currencies.index');
        Route::get('/create', 'AvailableCurrenciesController@create')->name('available-currencies.create');
        Route::post('/create', 'AvailableCurrenciesController@store')->name('available-currencies.store');
        Route::get('/{id}/edit', 'AvailableCurrenciesController@edit')->name('available-currencies.edit');
        Route::patch('/{id}/update', 'AvailableCurrenciesController@update')->name('available-currencies.update');
        Route::get('/datatable', 'AvailableCurrenciesDatatableController@index')->name('available-currencies.datatable');
        Route::get('/{id}/get-currency', 'AvailableCurrenciesController@getCurrency')->name('available-currencies.get-currency');
    });
});
