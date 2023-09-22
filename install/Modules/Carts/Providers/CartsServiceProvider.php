<?php

namespace Modules\Carts\Providers;

use Modules\Carts\Facades\Cart;
use Modules\Carts\Services\Session;
use Illuminate\Support\Facades\View;
use Modules\Carts\Storage\DBStorage;
use Illuminate\Support\ServiceProvider;
use Modules\Base\Support\Common\Helper;
use Modules\Carts\Services\CartManager;
use Modules\Carts\Storage\CacheStorage;
use Modules\Carts\Storage\CookieStorage;
use Illuminate\Database\Eloquent\Factory;
use Modules\Carts\Storage\SessionStorage;
use Modules\Carts\Storage\StorageManager;
use Modules\Carts\Events\CheckoutCompleted;
use Modules\Carts\Listeners\RemoveCartItems;
use Modules\Carts\Services\Cart as CartService;
use Modules\Orders\Events\OrderCreatedFromCart;
use Modules\Carts\Events\CheckoutSessionCreated;
use Modules\Carts\Listeners\HandleCheckoutCompleted;
use Modules\Carts\Listeners\RemoveOldSessionCheckout;

class CartsServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Carts';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'carts';

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
        $this->registerCommanHelpers();
        $this->app->register(RouteServiceProvider::class);
        $this->registerEvents();

        $this->registerStorageManager();
        $this->registerCartManager();
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
        $this->app['events']->listen(OrderCreatedFromCart::class, RemoveCartItems::class);
        $this->app['events']->listen(CheckoutCompleted::class, HandleCheckoutCompleted::class);
        $this->app['events']->listen(
            CheckoutSessionCreated::class,
            RemoveOldSessionCheckout::class
        );
    }

    public function registerCommanHelpers()
    {
        Helper::loader(module_path('Carts') . '/Support/Helpers');
    }

    /**
     * Register Cart storage manager
     */
    protected function registerStorageManager()
    {
        $this->app->singleton('cart.storage', function ($app) {
            $class = $this->app['config']['carts.classes.storageManager'] ?? StorageManager::class;

            return new $class($app);
        });
    }

    protected function registerCartManager()
    {
        $this->app->singleton('cart', function ($app) {
            if (auth()->check()) {
                $sessionVar = [
                    'id' => auth()->id(),
                    'associatedModel' => auth()->user()
                ];

                $storage = new DBStorage(config('carts.storage.stores.db'));
            } else {
                $sessionVar = [
                    'id' => session()->getId(),
                    'associatedModel' => null
                ];

                $storage = new CookieStorage(config('carts.storage.stores.cookie'));
            }

            $session = new Session($sessionVar);

            return new CartService($session, $storage, $app['events'], 'cart');
        });

        $this->app->singleton('cart.manager', function ($app) {
            $class = $this->app['config']['carts.classes.cartManager'] ?? CartManager::class;

            return new $class($app);
        });
    }

    protected function registerFacades()
    {
        $this->app->alias('cart.storage', Cart::class);
        $this->app->alias('cart', Cart::class);
        $this->app->alias('cart.manager', Cart::class);
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
            'cart.storage',
            'cart'
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