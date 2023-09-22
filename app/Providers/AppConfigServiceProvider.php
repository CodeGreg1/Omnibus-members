<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppConfigServiceProvider extends ServiceProvider
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
            'app.name' => 
                setting('app_name') 
                ?? 
                config('app.name')
        ]);

        app()->setLocale(
            setting('locale') 
            ?? 
            config('app.locale')
        );
    }
}
