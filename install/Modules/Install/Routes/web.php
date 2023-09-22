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
Route::group(['prefix' => 'install'], function()
{   
    Route::get('/', 'InstallController@index')->name('install.index');
    Route::get('/requirements', 'InstallController@requirements')->name('install.requirements');
    Route::get('/permissions', 'InstallController@permissions')->name('install.permissions');
    Route::get('/database', 'InstallController@database')->name('install.database');
    Route::post('/start', 'InstallController@startInstallation')->name('install.start');
    Route::get('/installation', 'InstallController@installation')->name('install.installation');
    Route::post('/install', 'InstallController@install')->name('install.install-now');
    Route::get('/success', 'InstallController@success')->name('install.success');
    Route::get('/error', 'InstallController@error')->name('install.error');
});
