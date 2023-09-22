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
Route::group(['prefix' => 'admin/category-types', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.category-types.index']], function() {
        Route::get('/', 'CategoryTypesController@index')->name('category-types.index');
        Route::get('/create', 'CategoryTypesController@create')->name('category-types.create');
        Route::post('/create', 'CategoryTypesController@store')->name('category-types.store');
        Route::get('/{id}/show', 'CategoryTypesController@show')->name('category-types.show');
        Route::get('/{id}/edit', 'CategoryTypesController@edit')->name('category-types.edit');
        Route::patch('/{id}/update', 'CategoryTypesController@update')->name('category-types.update');
        Route::delete('/delete', 'CategoryTypesController@destroy')->name('category-types.delete');
        Route::delete('/multi-delete', 'CategoryTypesController@multiDestroy')->name('category-types.multi-delete');
        Route::post('/restore', 'CategoryTypesController@restore')->name('category-types.restore');
        Route::delete('/force-delete', 'CategoryTypesController@forceDelete')->name('category-types.force-delete');
        Route::get('/datatable', 'CategoryTypesDatatableController@index')->name('category-types.datatable');
    });
});
