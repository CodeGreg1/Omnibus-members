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
Route::group(['prefix' => 'admin/users', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{   
    Route::group(['middleware' => ['auth', 'verified']], function() {
        
        Route::group(['middleware' => ['can:admin.users.index']], function() {
            Route::get('/', 'UsersController@index')
                ->name('users.index');

            Route::get('/create', 'UsersController@create')
                ->name('users.create');

            Route::post('/create', 'UsersController@store')
                ->name('users.store');

            Route::get('/{id}/show', 'UsersController@show')
                ->name('users.show');

            Route::get('/{id}/edit/user-information', 'UsersController@edit')
                ->name('users.edit');

            Route::get('/{id}/edit/user-settings', 'UsersController@editUserSettings')
                ->name('users.edit-user-settings');

            Route::patch('/{id}/update', 'UsersController@update')
                ->name('users.update');

            Route::patch('/{id}/update/user-settings', 'UsersController@updateUserSettings')
                ->name('users.update-user-settings');

            Route::delete('/delete', 'UsersController@destroy')
                ->name('users.delete');

            Route::patch('/multi-ban', 'UsersController@multiBan')
                ->name('users.multi-ban');

            Route::patch('/multi-enable', 'UsersController@multiEnable')
                ->name('users.multi-enable');

            Route::patch('/multi-confirm', 'UsersController@multiConfirm')
                ->name('users.multi-confirm');

            Route::get('/datatable', 'UsersDatatableController@index')
                ->name('users.datatable');


            Route::group(['middleware' => ['can.impersonate']], function () {
                Route::get('/{user}/impersonate', 'UsersController@impersonate')
                    ->name('users.impersonate');
            });

            Route::get('/all-roles', 'UsersController@allRoles')
                ->name('users.all-roles');
        });

        Route::get('/leave-impersonate', 'UsersController@leaveImpersonate')
            ->name('users.leave-impersonate');
    });
});

Route::group(['prefix' => 'admin/activities', 'as' => 'admin.', 'namespace' => 'Admin'], function()
{
    Route::group(['middleware' => ['auth', 'verified', 'can:admin.activities.index']], function() {
        Route::get('/', 'UserActivitiesController@index')
            ->name('activities.index');

        Route::get('/datatable', 'UserActivitiesDatatableController@index')
            ->name('activities.datatable');
    });
});

Route::group(['prefix' => 'profile', 'as' => 'user.', 'namespace' => 'User'], function()
{
    Route::group(['middleware' => ['auth', 'verified', 'can:user.activities.index']], function() {
        Route::get('/activities', 'UserActivitiesController@index')
            ->name('activities.index');

        Route::get('/activities/datatable', 'UserActivitiesDatatableController@index')
            ->name('activities.datatable');
    });
});