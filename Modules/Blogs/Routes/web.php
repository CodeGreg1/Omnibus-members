<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'blogs'], function () {
    Route::get('/', 'BlogsController@index')->name('blogs.index');
    Route::get('/{slug}', 'BlogsController@show')->name('blogs.show');
    Route::get('/category/{category}', 'CategoryBlogController@index')->name('blogs.categories.index');
    Route::get('/tag/{tag}', 'TagBlogController@index')->name('blogs.tags.index');
});
