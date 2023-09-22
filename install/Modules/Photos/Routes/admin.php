<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'admin/photos', 'as' => 'admin.photos.', 'namespace' => 'Admin'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/', 'PhotoController@index')->name('index');
        Route::post('/create', 'PhotoController@store')->name('store');
        Route::post('/{id}/update', 'PhotoController@update')->name('update');
        Route::get('/datatable', 'PhotoDatatableController@handle')->name('datatable');
        Route::get('/gallery', 'PhotoListController@handle')->name('gallery');
        Route::get('/gallery-select-list', 'GalleryListController@handle')
            ->name('gallery-select-list');
        Route::get('/folders', 'PhotoController@folders')->name('folders.list');
        Route::delete('/{photo}/delete', 'PhotoController@destroy')->name('destroy');

        Route::post('/upload', 'UploadPhotoController@handle')->name('upload');
        Route::get('download', 'DownloadPhotoController@handle')->name('download');
        Route::delete('delete-images', 'DeletePhotoController@handle')->name('images.destroy');
    });
}); // 'can:admin.pages.index'