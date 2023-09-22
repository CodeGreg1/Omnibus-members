<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => '\Modules\Auth\Http\Controllers\Api\V1'], function () {

	Route::group(['prefix' => 'v1'], function () {

		Route::post('login', 'LoginController@login');

		if(setting('allow_registration')) {
			Route::post('register', 'RegisterController@register');
		}

		if(setting('forgot_password')) {
			Route::group(['middleware' => ['guest']], function () {
			    Route::post('password/remind', 'Password\RemindController@index');
			    Route::post('password/reset', 'Password\ResetController@index');
			});
		}

		if(setting('allow_registration')) {
			Route::group(['middleware' => ['auth:sanctum']], function () {
			    Route::post('email/resend', 'VerificationController@resend');
			    Route::post('email/verify', 'VerificationController@verify');
			});
		}

		Route::group(['middleware' => ['auth:sanctum']], function() {
	        Route::post('/logout', 'LogoutController@logout');
	    });

	});
	
});