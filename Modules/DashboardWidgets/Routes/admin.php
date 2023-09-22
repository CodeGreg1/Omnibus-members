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
Route::group(['prefix' => 'admin/dashboard-widgets', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.dashboard-widgets.index']], function() {
        Route::get('/', 'DashboardWidgetsController@index')->name('dashboard-widgets.index');
        Route::get('/create', 'DashboardWidgetsController@create')->name('dashboard-widgets.create');
        Route::post('/create', 'DashboardWidgetsController@store')->name('dashboard-widgets.store');
        Route::get('/{id}/edit', 'DashboardWidgetsController@edit')->name('dashboard-widgets.edit');
        Route::patch('/{id}/update', 'DashboardWidgetsController@update')->name('dashboard-widgets.update');
        Route::delete('/delete', 'DashboardWidgetsController@destroy')->name('dashboard-widgets.delete');
        Route::get('/datatable', 'DashboardWidgetsDatatableController@index')->name('dashboard-widgets.datatable');
        Route::get('/table-columns', 'DashboardWidgetsController@tableColumns')->name('dashboard-widgets.table-columns');
        Route::post('/reorder', 'DashboardWidgetsController@reorder')->name('dashboard-widgets.reorder');
    });
});
