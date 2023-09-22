<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin/sitemap', 'as' => 'admin.sitemap.', 'namespace' => 'Admin'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/', 'SitemapController@index')->name('index');
        Route::post('/update', 'SitemapController@update')->name('update');
        Route::post('/re-build', 'SitemapController@reBuild')->name('re-build');
    });
});