<?php

namespace Modules\AvailableCurrencies\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Base\Support\Common\Helper;
use Illuminate\Database\Eloquent\Factory;
use Modules\AvailableCurrencies\Services\Currency;
use Modules\AvailableCurrencies\Console\SyncCurrencyRates;
use Modules\AvailableCurrencies\Listeners\AddUserCurrency;
use Modules\AvailableCurrencies\Facades\Currency as FacadesCurrency;
use Modules\AvailableCurrencies\Services\ApiServices\LaravelCurrency;
use Modules\AvailableCurrencies\Services\ApiServices\ApiServiceInterface;

class AvailableCurrenciesServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'AvailableCurrencies';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'availablecurrencies';

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

        $this->app->bind(ApiServiceInterface::class, LaravelCurrency::class);

        if (env('APP_KEY')) {
            View::share('appCurrencies', array_values(currency()->getCurrencies()));
        }
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
        $this->registerCommands();

        $this->registerCurrencyManager();
        $this->registerFacades();
        $this->registerCommanHelpers();
    }

    /**
     * Register events.
     *
     * @return void
     */
    protected function registerEvents()
    {
        // For Registered Event
        $this->app['events']->listen(
            'eloquent.created: App\Models\User',
            AddUserCurrency::class
        );
    }

    /**
     * Register the commands
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            SyncCurrencyRates::class
        ]);
    }

    protected function registerCurrencyManager()
    {
        $this->app->singleton('currency', function ($app) {
            $currency = auth()->check() ?
                auth()->user()->currency :
                $app->config->get('availablecurrencies.default');

            return new Currency(
                $app->config->get('availablecurrencies', []),
                $app['cache'],
                $currency
            );
        });
    }

    protected function registerFacades()
    {
        $this->app->alias('currency', FacadesCurrency::class);
    }

    public function registerCommanHelpers()
    {
        Helper::loader(module_path('AvailableCurrencies') . '/Support/Common');
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
        // all modules already registered inside auth module because of Laravel 9.5.1 bug because only the first executed module loaded the translations
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'currency'
        ];
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
