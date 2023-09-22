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
Route::group(['prefix' => 'user/tickets', 'as' => 'user.', 'namespace' => 'User'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:user.tickets.index']], function() {
        Route::get('/', 'TicketsController@index')->name('tickets.index');
        Route::get('/create', 'TicketsController@create')->name('tickets.create');
        Route::post('/create', 'TicketsController@store')->name('tickets.store');
        Route::get('/{id}/show', 'TicketsController@show')->name('tickets.show');
        Route::get('/{id}/edit', 'TicketsController@edit')->name('tickets.edit');
        Route::patch('/{id}/update', 'TicketsController@update')->name('tickets.update');
        Route::delete('/delete', 'TicketsController@destroy')->name('tickets.delete');
        Route::delete('/multi-delete', 'TicketsController@multiDestroy')->name('tickets.multi-delete');
        Route::post('/restore', 'TicketsController@restore')->name('tickets.restore');
        Route::delete('/force-delete', 'TicketsController@forceDelete')->name('tickets.force-delete');
        Route::get('/datatable', 'TicketsDatatableController@index')->name('tickets.datatable');
        Route::delete('/remove-media', 'TicketsController@removeMedia')->name('tickets.remove-media');
        Route::post('/reply', 'TicketsController@reply')->name('tickets.reply');
        Route::patch('/{ticket_id}/{convo_id}/update-convo', 'TicketsController@updateConvo')->name('tickets.update-convo');
        Route::delete('/{ticket_id}/{convo_id}/delete-convo', 'TicketsController@deleteConvo')->name('tickets.delete-convo');
        Route::patch('/{ticket_id}/update-subject', 'TicketsController@updateSubject')->name('tickets.update-subject');
    });
});
