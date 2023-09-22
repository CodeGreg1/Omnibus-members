<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        config([
            'auth.passwords.users.expire' => 
                setting('auth_reset_token_lifetime') 
                ?? 
                config('auth.passwords.users.expire')
        ]);

        config([
            'auth.passwords.users.throttle' => 
                setting('auth_reset_token_lifetime') 
                ?? 
                config('auth.passwords.users.throttle')
        ]);
    }
}
