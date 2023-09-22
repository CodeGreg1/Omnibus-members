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

Route::group(['prefix' => 'admin/menus', 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/', 'MenusController@index')->name('menus.index');
        Route::get('/create', 'MenusController@create')->name('menus.create');
        Route::post('/create', 'MenusController@store')->name('menus.store');
        Route::get('/{id}/edit', 'MenusController@edit')->name('menus.edit');
        Route::patch('/{id}/update', 'MenusController@update')->name('menus.update');
        Route::delete('/delete', 'MenusController@destroy')->name('menus.delete');
        Route::get('/datatable', 'MenusDatatableController@index')->name('menus.datatable');
        Route::get('/menu-lists', 'MenuLinkSelectListController@handle')->name('menus.links.list');

        /*
        * Menu items
        */
        Route::group(['prefix' => 'menu-items'], function () {
            Route::delete('/delete', 'MenuItemsController@destroy')->name('menus.delete-menu-item');
        });
    });
});
