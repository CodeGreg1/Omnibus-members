<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['as' => 'site.', 'namespace' => 'Site'], function () {
    Route::get('/', 'WebsiteController@index')->name('website.index');
    Route::get('/about', 'WebsiteController@about')->name('website.about');
    Route::get('/contact', 'WebsiteController@contact')->name('website.contact');
    Route::get('/pricing', 'WebsiteController@pricing')->name('website.pricing');
    Route::post('/cookie-consent', 'WebsiteController@cookieConsent')->name('website.cookie-consent');
});
