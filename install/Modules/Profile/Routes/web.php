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

Route::group(['namespace' => '\Modules\Profile\Http\Controllers\Web', 'prefix' => 'profile'], function()
{   
    Route::group(['middleware' => ['auth', 'verified', 'can:profile.index']], function() {
        Route::get('/', 'ProfileController@index')
            ->name('profile.index');
        Route::get('/security', 'ProfileController@security')
            ->name('profile.security');

        // Update profile avatar
        Route::post('/avatar', 'UpdateProfileAvatarController@update')
            ->name('profile.avatar-update');
        Route::post('/avatar/remove', 'UpdateProfileAvatarController@remove')
            ->name('profile.avatar-remove');

        // Update profile details
        Route::post('/details', 'UpdateProfileDetailsController@update')
            ->name('profile.details-update');

        // Added profile social login
        Route::get('/connect/{provider}', 'UpdateProfileLoginServiceController@redirectToProvider')
            ->name('profile.social-connect');
        Route::post('/disconnect-social', 'UpdateProfileLoginServiceController@disconnect')
            ->name('profile.social-disconnect');

        // Update profile address details
        Route::post('/address', 'UpdateProfileAddressController@update')
            ->name('profile.address-update');
        
        // Update profile company details
        Route::post('/company', 'UpdateProfileCompanyController@update')
            ->name('profile.company-update');

        // Update profile timezone details
        Route::post('/timezone', 'UpdateProfileTimezoneController@update')
            ->name('profile.timezone-update');

        // Update profile language details
        Route::post('/language', 'UpdateProfileLanguageController@update')
            ->name('profile.language-update');

        // Update profile currency details
        Route::post('/currency', 'UpdateProfileCurrencyController@update')
            ->name('profile.currency-update');

        // Update profile password details
        Route::post('/password', 'UpdateProfilePasswordController@update')
            ->name('profile.password-update');

        // Enable profile two factor
        Route::post('/two-factor/enable', 'EnableProfileTwoFactorController@perform')
            ->name('profile.two-factor.enable');

        // Verify Enable profile two factor
        Route::post('/two-factor/verify-enable', 'VerifyEnableTwoFactorController@perform')
            ->name('profile.two-factor.verify-enable');

        // Disable profile two factor
        Route::post('/two-factor/disable', 'DisableProfileTwoFactorController@perform')
            ->name('profile.two-factor.disable');

        // Download profile two factor recovery codes
        Route::get('/two-factor/download', 'DownloadProfileTwoFactorRecoveryController@perform')
            ->name('profile.two-factor.download-recovery');

        // Logout profile devices
        Route::post('/device/logout', 'LogoutProfileDeviceController@perform')
            ->name('profile.device.logout');

        // Logout profile all devices
        Route::post('/all-device/logout', 'LogoutAllProfileDeviceController@perform')
            ->name('profile.all-device.logout');

        // Personal access tokens
        Route::get('/personal-access-tokens', 'PersonalAccessTokensController@index')
            ->name('profile.personal-access-tokens');
        Route::get('/personal-access-tokens/datatable', 'PersonalAccessTokensController@datatable')
            ->name('profile.personal-access-tokens-datatable');
        Route::post('/personal-access-tokens/create', 'PersonalAccessTokensController@create')
            ->name('profile.personal-access-tokens-create');
        Route::delete('/personal-access-tokens/revoke', 'PersonalAccessTokensController@revoke')
            ->name('profile.personal-access-tokens-revoke');
    });

    
});