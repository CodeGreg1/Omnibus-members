<?php

namespace Modules\Auth\Providers;

use Modules\Auth\Events\LoggedIn;
use Illuminate\Auth\Events\Logout;
use Modules\Base\Support\AllModules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Auth\Listeners\UpdateSession;
use Modules\Auth\Listeners\UserLastLogin;
use Modules\Auth\Listeners\UserLastLogout;
use Modules\Auth\Listeners\LoginUpdateSession;
use Modules\Auth\Listeners\UpdateGoogle2FAFirstAuth;
use Modules\Auth\Listeners\RegistrationUpdateCountry;
use Modules\Auth\Listeners\UpdateGoogle2FAFirstAuthTest;
use Modules\Auth\Listeners\LoginUpdateGoogle2FAFirstAuth;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Auth';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'auth';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->registerEvents();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        foreach((new AllModules)->get() as $modulePath) {
            $moduleName = basename($modulePath);
            $moduleNameLower = strtolower($moduleName);

            $langPath = resource_path('lang/modules/' . $moduleNameLower);

            if (is_dir($langPath)) {
                $this->loadJsonTranslationsFrom($langPath);
            } else {
                if(file_exists($modulePath)) {
                    $this->loadJsonTranslationsFrom(module_path($moduleName, 'Resources/lang'));
                }
            }
        }
    }

    /**
     * Register events.
     *
     * @return void
     */
    protected function registerEvents() 
    {
        $this->app['events']->listen(Registered::class, RegistrationUpdateCountry::class);

        // For LoggedIn Event
        $this->app['events']->listen(LoggedIn::class, LoginUpdateSession::class);
        $this->app['events']->listen(LoggedIn::class, UserLastLogin::class);
    
        // For Logout Event
        $this->app['events']->listen(Logout::class, UserLastLogout::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
