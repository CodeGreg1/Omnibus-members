<?php

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
Route::group(['prefix' => 'admin/permissions', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.permissions.index']], function() {
        Route::get('/', 'PermissionsController@index')->name('permissions.index');
        Route::get('/create', 'PermissionsController@create')->name('permissions.create');
        Route::post('/create', 'PermissionsController@store')->name('permissions.store');
        Route::get('/{id}/edit', 'PermissionsController@edit')->name('permissions.edit');
        Route::patch('/{id}/update', 'PermissionsController@update')->name('permissions.update');
        Route::delete('/delete', 'PermissionsController@destroy')->name('permissions.delete');
        Route::get('/datatable', 'PermissionsDatatableController@index')->name('permissions.datatable');
    });
});
