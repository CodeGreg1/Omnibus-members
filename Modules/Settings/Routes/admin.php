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
Route::group(['prefix' => 'admin/settings', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.settings.index']], function() {
        Route::get('/', 'SettingsController@index')->name('settings.index');
        Route::get('/authentication', 'SettingsController@authentication')->name('settings.authentication');
        Route::get('/registration', 'SettingsController@registration')->name('settings.registration');
        Route::get('/recaptcha', 'SettingsController@recaptcha')->name('settings.recaptcha');
        Route::get('/google-analytics', 'SettingsController@googleAnalytics')->name('settings.google-analytics');
        Route::get('/mail', 'SettingsController@email')->name('settings.email');
        Route::get('/storage', 'SettingsController@storage')->name('settings.storage');
        Route::get('/media', 'SettingsController@media')->name('settings.media');
        Route::get('/ticket', 'SettingsController@ticket')->name('settings.ticket');
        Route::patch('/update', 'SettingsController@update')->name('settings.update');
    });
});
