<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'admin/pages', 'as' => 'admin.pages.', 'namespace' => 'Admin'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/', 'PageController@index')->name('index');
        Route::get('create', 'PageController@create')->name('create');
        Route::post('create', 'PageController@store')->name('store');
        Route::get('datatable', 'PageDatatableController@handle')->name('datatable');
        Route::post('move-to-draft', 'PageController@moveToDraft')
            ->name('move-to-draft');
        Route::post('move-to-published', 'PageController@moveToPublished')
            ->name('move-to-published');
        Route::delete('destroy', 'PageController@destroy')
            ->name('destroy');
        Route::get('{page}/edit', 'PageController@edit')
            ->where('id', '[0-9]+')
            ->name('edit');
        Route::patch('{page}/update', 'PageController@update')
            ->where('id', '[0-9]+')
            ->name('update');
        Route::post('{post}/duplicate', 'PageController@duplicate')
            ->where('id', '[0-9]+')
            ->name('duplicate');
    });
}); // 'can:admin.pages.index'