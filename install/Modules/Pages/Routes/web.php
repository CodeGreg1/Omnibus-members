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

Route::group(['prefix' => 'pages'], function () {
    Route::get('/{slug}', 'PagesController@show')->name('pages.show');
});

Route::group(['prefix' => 'contact-us'], function () {
    Route::post('/', 'ContactUsController@send')->name('contact-us.send');
});

Route::group(['prefix' => 'admin/pages', 'as' => 'admin.pages.'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('{page}/preview', 'PagesController@preview')
            ->where('id', '[0-9]+')
            ->name('preview');
    });
});