<?php

namespace Modules\Cashier\Providers;

use Modules\Cashier\Facades\Gateway;
use Modules\Cashier\Services\Client;
use Modules\Cashier\Services\Cashier;
use Illuminate\Support\ServiceProvider;
use Modules\Base\Support\Common\Helper;
use Modules\Cashier\Services\Webhook\Webhook;
use Modules\Cashier\Facades\Cashier as FacadesCashier;
use Modules\Cashier\Facades\Gateway as FacadesGateway;
use Modules\Cashier\Facades\Webhook as FacadesWebhook;
use Modules\Cashier\Events\CashierGatewaySettingsUpdating;
use Modules\Cashier\Services\Clients\Mollie\MollieApiService;
use Modules\Cashier\Services\Clients\Paypal\PaypalApiService;
use Modules\Cashier\Services\Clients\Stripe\StripeApiService;
use Modules\Cashier\Services\Clients\Razorpay\RazorpayApiService;
use Modules\Cashier\Listeners\HandleCashierGatewaySettingsUpdating;

class CashierServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Cashier';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'cashier';

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

        $this->registerClients();
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

        $this->registerCashierManager();
        $this->registerFacades();
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
            CashierGatewaySettingsUpdating::class,
            HandleCashierGatewaySettingsUpdating::class
        );
    }

    /**
     * Register module helpers
     */
    public function registerHelpers()
    {
        Helper::loader(module_path('Cashier') . '/Helpers');
    }

    protected function registerCashierManager()
    {
        $this->app->singleton('cashier', function ($app) {
            return new Cashier($app->config->get('cashier', []), $app['cache']);
        });

        $this->app->singleton('cashier.gateway', function ($app) {
            return new Gateway();
        });

        $this->app->singleton('cashier.webhook', function ($app) {
            return new Webhook();
        });
    }

    protected function registerFacades()
    {
        $this->app->alias('cashier', FacadesCashier::class);
        $this->app->alias('cashier.gateway', FacadesGateway::class);
        $this->app->alias('cashier.webhook', FacadesWebhook::class);
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
        return [
            'cashier',
            'cashier.gateway',
            'cashier.webhook'
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

    private function registerClients()
    {
        FacadesCashier::clients([
            new Client('paypal', 'Paypal', [], PaypalApiService::class),
            new Client('stripe', 'Stripe', [], StripeApiService::class),
            new Client('razorpay', 'Razorpay', [], RazorpayApiService::class),
            new Client('mollie', 'Mollie', [], MollieApiService::class)
        ]);
    }
}
