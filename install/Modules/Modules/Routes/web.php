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
Route::group(['prefix' => 'admin/module', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.modules.index']], function() {
        Route::get('/', 'ModulesController@index')->name('modules.index');
        Route::get('/create', 'ModulesController@create')->name('modules.create');
        Route::post('/create', 'ModulesController@store')->name('modules.store');
        Route::get('/{id}/edit', 'ModulesController@edit')->name('modules.edit');
        Route::get('/{id}/show/languages', 'ModulesController@showLanguages')->name('modules.show-language');
        Route::get('/{id}/edit/language/{code}', 'ModulesController@editLanguage')->name('modules.edit-language');
        Route::patch('/{id}/update', 'ModulesController@update')->name('modules.update');
        Route::delete('/delete', 'ModulesController@destroy')->name('modules.delete');
        Route::get('/get-models', 'ModulesController@getModels')->name('modules.get-models');
        Route::get('/table-columns', 'ModulesController@tableColumns')->name('modules.table-columns');
        Route::get('/name/{name}', 'ModulesController@moduleName')->name('modules.name');
        Route::get('/datatable', 'ModulesDatatableController@index')->name('modules.datatable');
        Route::get('/language-datatable', 'ModulesLanguageDatatableController@index')->name('modules.language-datatable');
        Route::get('/edit-language-datatable/{id}/{code}', 'ModulesEditLanguageDatatableController@index')->name('modules.edit-language-datatable');
        Route::post('/update-module-language', 'ModulesController@updateModuleLanguage')->name('modules.update-module-language');
        Route::post('/translate-module-language', 'ModulesController@translateModuleLanguage')->name('modules.translate-module-language');
        Route::post('/add-language-phrase/{id}', 'ModulesController@addLanguagePhrase')->name('modules.add-language-phrase');
        Route::get('/migration-check', 'ModulesController@migrationCheck')->name('modules.migration-check');
        Route::get('/migration-run', 'ModulesController@migrationRun')->name('modules.migration-run');
    });
});
