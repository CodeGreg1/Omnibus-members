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
Route::group(['prefix' => 'admin/categories', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.categories.index']], function() {
        Route::get('/', 'CategoriesController@index')->name('categories.index');
        Route::get('/create', 'CategoriesController@create')->name('categories.create');
        Route::post('/create', 'CategoriesController@store')->name('categories.store');
        Route::get('/{id}/show', 'CategoriesController@show')->name('categories.show');
        Route::get('/{id}/edit', 'CategoriesController@edit')->name('categories.edit');
        Route::patch('/{id}/update', 'CategoriesController@update')->name('categories.update');
        Route::delete('/delete', 'CategoriesController@destroy')->name('categories.delete');
        Route::delete('/multi-delete', 'CategoriesController@multiDestroy')->name('categories.multi-delete');
        Route::post('/restore', 'CategoriesController@restore')->name('categories.restore');
        Route::delete('/force-delete', 'CategoriesController@forceDelete')->name('categories.force-delete');
        Route::get('/datatable', 'CategoriesDatatableController@index')->name('categories.datatable');
    });
});
