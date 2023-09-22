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

Route::group(['prefix' => 'admin/maintenance', 'as' => 'admin.maintenance.', 'namespace' => 'Admin'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/', 'MaintenanceController@index')->name('index');
        Route::post('/settings/update', 'MaintenanceSettingController@update')
            ->name('settings.update');
    });
});