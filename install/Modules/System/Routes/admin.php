<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/system-info', 'SystemInfoController@index')->name('system.info');
        Route::get('/server-info', 'SystemServerInfoController@index')
            ->name('server.info');
        Route::get('/system-optimize', 'SystemCacheController@index')
            ->name('optimize.index');
        Route::post('/system-optimize', 'SystemCacheController@update')
            ->name('optimize.update');
        Route::get('/system-migration', 'MigrationController@index')
            ->name('migration.index');
        Route::post('/system-migration', 'MigrationController@run')
            ->name('migration.run');
    });
});