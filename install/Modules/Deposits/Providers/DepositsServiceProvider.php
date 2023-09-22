<?php

namespace Modules\Deposits\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Base\Support\Common\Helper;
use Illuminate\Database\Eloquent\Factory;
use Modules\Deposits\Events\DepositCreated;
use Modules\Deposits\Events\DepositApproved;
use Modules\Deposits\Events\DepositRejected;
use Modules\Deposits\Listeners\SendDepositApprovalEmail;
use Modules\Deposits\Listeners\SendDepositCompletedEmail;
use Modules\Deposits\Listeners\SendDepositRequestApprovedEmail;
use Modules\Deposits\Listeners\SendDepositRequestRejectedEmail;

class DepositsServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Deposits';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'deposits';

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
        $this->registerHelpers();
    }

    /**
     * Register events.
     *
     * @return void
     */
    public function registerEvents()
    {
        $this->app['events']->listen(
            DepositCreated::class,
            SendDepositApprovalEmail::class
        );
        $this->app['events']->listen(
            DepositApproved::class,
            SendDepositRequestApprovedEmail::class
        );
        $this->app['events']->listen(
            DepositApproved::class,
            SendDepositCompletedEmail::class
        );
        $this->app['events']->listen(
            DepositRejected::class,
            SendDepositRequestRejectedEmail::class
        );
    }

    public function registerHelpers()
    {
        Helper::loader(module_path($this->moduleName) . '/Support/Helper');
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
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
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
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
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
