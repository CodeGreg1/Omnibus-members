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

Route::group(['prefix' => 'admin/languages', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.languages.index']], function() {
        Route::get('/', 'LanguagesController@index')->name('languages.index');
        Route::get('/create', 'LanguagesController@create')->name('languages.create');
        Route::post('/create', 'LanguagesController@store')->name('languages.store');
        Route::get('/{id}/show', 'LanguagesController@show')->name('languages.show');
        Route::get('/{id}/edit', 'LanguagesController@edit')->name('languages.edit');
        Route::get('/{id}/edit/{code}', 'LanguagesController@editCode')->name('languages.edit-code');
        Route::patch('/{id}/update', 'LanguagesController@update')->name('languages.update');
        Route::delete('/delete', 'LanguagesController@destroy')->name('languages.delete');
        Route::delete('/multi-delete', 'LanguagesController@multiDestroy')->name('languages.multi-delete');
        Route::post('/restore', 'LanguagesController@restore')->name('languages.restore');
        Route::delete('/force-delete', 'LanguagesController@forceDelete')->name('languages.force-delete');
        Route::get('/datatable', 'LanguagesDatatableController@index')->name('languages.datatable');
        Route::get('/edit-datatable/{id}', 'LanguagesEditDatatableController@index')->name('languages.edit-datatable');
        Route::post('/translate-phrase', 'LanguagesController@translateLanguage')->name('languages.translate-language');
        Route::post('/update-phrase', 'LanguagesController@updateLanguage')->name('languages.update-language');
        Route::post('/add-language-phrase', 'LanguagesController@addLanguagePhrase')->name('languages.add-language-phrase');
        Route::post('/sync-all-language', 'LanguagesController@syncAllLanguage')->name('languages.sync-all-language');
        Route::post('/auto-translate-adding-phrase', 'LanguagesController@autoTranslateAddingPhrase')->name('languages.auto-translate-adding-phrase');
    });
});
