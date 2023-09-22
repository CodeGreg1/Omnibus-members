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
Route::group(['prefix' => 'admin/email-templates', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.email-templates.index']], function() {
        Route::get('/', 'EmailTemplatesController@index')->name('email-templates.index');
        Route::get('/create', 'EmailTemplatesController@create')->name('email-templates.create');
        Route::post('/create', 'EmailTemplatesController@store')->name('email-templates.store');
        Route::get('/{id}/edit', 'EmailTemplatesController@edit')->name('email-templates.edit');
        Route::patch('/{id}/update', 'EmailTemplatesController@update')->name('email-templates.update');
        Route::delete('/delete', 'EmailTemplatesController@destroy')->name('email-templates.delete');
        Route::get('/test', 'EmailTemplatesController@test')->name('email-templates.test');
        Route::get('/get', 'EmailTemplatesController@get')->name('email-templates.get');
        Route::post('/send', 'EmailTemplatesController@send')->name('email-templates.send');
        Route::get('/datatable', 'EmailTemplatesDatatableController@index')->name('email-templates.datatable');
    });
});
