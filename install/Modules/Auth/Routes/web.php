<?php
Route::group(['namespace' => '\Modules\Auth\Http\Controllers\Web'], function()
{   
    Route::group(['middleware' => ['guest']], function() {
        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')
            ->name('auth.login.show');
        Route::post('/login', 'LoginController@login')
            ->name('auth.login.perform');


        /**
         * Register Routes
         */
        if(setting('allow_registration')) {
            Route::get('/register', 'RegisterController@show')
                ->name('auth.register.show');
            Route::post('/register', 'RegisterController@register')
                ->name('auth.register.perform');
        }
        

        /**
         * Reset Password Routes
         */
        if(setting('forgot_password')) {
            Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')
                ->name('password.request');
            Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')
                ->name('password.email');
            Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')
                ->name('password.reset');
            Route::post('/password/reset', 'ResetPasswordController@reset')
                ->name('password.update');
        }
        

        /**
         * Social Login
         */
        Route::get('/login/{provider}', 'SocialLoginController@redirectToProvider')
            ->name('auth.login.social');
        Route::get('/login/{provider}/callback', 'SocialLoginController@handleProviderCallback');
    });
    

    /**
     * 2FA Verify Routes
     */
    Route::get('/verify/two-factor', 'TwoFactorController@show')
        ->name('auth.two-factor.verify-page');
    Route::post('/verify/two-factor', 'TwoFactorController@verify')
        ->name('auth.two-factor.verify');
        
    
    Route::group(['middleware' => ['auth']], function() {
        /**
         * Logout Routes
         */
        Route::get('/logout', 'LogoutController@perform')
            ->name('auth.logout.perform');

        /**
         * Verification Routes
         */
        Route::get('/email/verify', 'VerificationController@show')
            ->name('auth.verification.notice');
        Route::get('/email/verify', 'VerificationController@show')
            ->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', 'VerificationController@verify')
            ->name('verification.verify')
            ->middleware(['signed']);
        Route::post('/email/resend', 'VerificationController@resend')
            ->name('auth.verification.resend');

        /**
         * Account Recovery Routes
         */
        Route::get('/recovery', 'RecoveryController@show')
            ->name('auth.recovery.show');
        Route::post('/recovery', 'RecoveryController@perform')
            ->name('auth.recovery.perform');


        /**
         * 
         */
        Route::get('/user-invitation-change-password', 'UserInvitationChangePasswordController@index')
            ->name('auth.user-invitation.change-password');
    });
});