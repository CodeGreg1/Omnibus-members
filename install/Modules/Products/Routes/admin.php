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
Route::group(['prefix' => 'admin/products', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.products.index']], function() {
        Route::get('/', 'ProductsController@index')->name('products.index');
        Route::get('/create', 'ProductsController@create')->name('products.create');
        Route::post('/create', 'ProductsController@store')->name('products.store');
        Route::get('/{id}/show', 'ProductsController@show')->name('products.show');
        Route::get('/{id}/edit', 'ProductsController@edit')->name('products.edit');
        Route::patch('/{id}/update', 'ProductsController@update')->name('products.update');
        Route::delete('/delete', 'ProductsController@destroy')->name('products.delete');
        Route::delete('/multi-delete', 'ProductsController@multiDestroy')->name('products.multi-delete');
        Route::post('/restore', 'ProductsController@restore')->name('products.restore');
        Route::delete('/force-delete', 'ProductsController@forceDelete')->name('products.force-delete');
        Route::get('/datatable', 'ProductsDatatableController@index')->name('products.datatable');
    });
});
