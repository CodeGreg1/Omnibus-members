<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin/blogs', 'as' => 'admin.blogs.', 'namespace' => 'Admin'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/', 'BlogController@index')->name('index');
        Route::get('datatable', 'BlogDatatableController@handle')->name('datatable');
        Route::get('create', 'BlogController@create')->name('create');
        Route::post('create', 'BlogController@store')->name('store');
        Route::post('move-to-pending', 'BlogController@moveToPending')
            ->name('move-to-pending');
        Route::post('move-to-draft', 'BlogController@moveToDraft')
            ->name('move-to-draft');
        Route::post('move-to-published', 'BlogController@moveToPublished')
            ->name('move-to-published');
        Route::delete('destroy', 'BlogController@destroy')
            ->name('destroy');
        Route::get('{blog}', 'BlogController@show')
            ->where('id', '[0-9]+')
            ->name('show');
        Route::get('{blog}/edit', 'BlogController@edit')
            ->where('id', '[0-9]+')
            ->name('edit');
        Route::patch('{blog}/update', 'BlogController@update')
            ->where('id', '[0-9]+')
            ->name('update');
    });
});