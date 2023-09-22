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
Route::group(['prefix' => 'admin/services', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.services.index']], function() {
        Route::get('/', 'ServicesController@index')->name('services.index');
        Route::get('/create', 'ServicesController@create')->name('services.create');
        Route::post('/create', 'ServicesController@store')->name('services.store');
        Route::get('/{id}/show', 'ServicesController@show')->name('services.show');
        Route::get('/{id}/edit', 'ServicesController@edit')->name('services.edit');
        Route::patch('/{id}/update', 'ServicesController@update')->name('services.update');
        Route::delete('/delete', 'ServicesController@destroy')->name('services.delete');
        Route::delete('/multi-delete', 'ServicesController@multiDestroy')->name('services.multi-delete');
        Route::post('/restore', 'ServicesController@restore')->name('services.restore');
        Route::delete('/force-delete', 'ServicesController@forceDelete')->name('services.force-delete');
        Route::get('/datatable', 'ServicesDatatableController@index')->name('services.datatable');
    });
});
