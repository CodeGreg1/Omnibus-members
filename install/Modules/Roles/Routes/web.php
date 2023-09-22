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
Route::group(['prefix' => 'admin/roles', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.roles.index']], function() {
        Route::get('/', 'RolesController@index')->name('roles.index');
        Route::get('/create', 'RolesController@create')->name('roles.create');
        Route::post('/create', 'RolesController@store')->name('roles.store');
        Route::get('/{id}/edit', 'RolesController@edit')->name('roles.edit');
        Route::patch('/{id}/update', 'RolesController@update')->name('roles.update');
        Route::delete('/delete', 'RolesController@destroy')->name('roles.delete');
        Route::get('/datatable', 'RolesDatatableController@index')->name('roles.datatable');
    });
});
