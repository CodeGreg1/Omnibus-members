<?php

namespace Modules\Subscriptions\Providers;

use Nwidart\Modules\Facades\Module;
use Modules\Pages\Facades\PageBuilder;
use Illuminate\Support\ServiceProvider;
use Modules\Base\Support\Common\Helper;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Pages\Services\PageSectionBuilder;
use Modules\Cashier\Events\WebhookEventReceived;
use Modules\Subscriptions\Events\FeatureDeleted;
use Modules\Subscriptions\Events\PackageDeleted;
use Modules\Subscriptions\Listeners\ReorderFeatures;
use Modules\Subscriptions\Events\PackagePriceDeleted;
use Modules\Carts\Events\CheckoutSubscrptionCompleted;
use Modules\Subscriptions\Services\PricingPageSection;
use Modules\Subscriptions\Listeners\HandleWebhookEvent;
use Modules\Subscriptions\Services\Service\Price\Price;
use Modules\Subscriptions\Services\Service\Invoice\Invoice;
use Modules\Subscriptions\Services\Service\Product\Product;
use Modules\Subscriptions\Console\NotifyExpiredSubscriptions;
use Modules\Subscriptions\Providers\SubscriptionsViewProvider;
use Modules\Subscriptions\Services\PricingPageSectionValidator;
use Modules\Subscriptions\Console\NotifyUserSubscriptionPayment;
use Modules\Subscriptions\Listeners\RemovePriceGatewayIntegration;
use Modules\Subscriptions\Listeners\CreateSubscriptionFromCheckout;
use Modules\Subscriptions\Console\CheckManualRecurringSubscriptions;
use Modules\Subscriptions\Console\ClearExpiredLastNotifySubscription;
use Modules\Subscriptions\Services\Service\Subscription\Subscription;
use Modules\Subscriptions\Services\Service\WebhookHandler\WebhookHandler;
use Modules\Subscriptions\Listeners\RemovePriceGatewayIntegrationFromPackage;

class SubscriptionsServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Subscriptions';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'subscriptions';

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
        $this->registerSchedules();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->registerPageBuilderSections();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(SubscriptionsViewProvider::class);

        $this->registerHelpers();
        $this->registerEvents();
        $this->registerCommands();
        $this->registerSubscriptionManager();

        $this->commands([
            NotifyUserSubscriptionPayment::class,
            ClearExpiredLastNotifySubscription::class,
            NotifyExpiredSubscriptions::class
        ]);
    }

    public function registerHelpers()
    {
        Helper::loader(module_path('Subscriptions') . '/Support/Helper');
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

    public function registerSchedules()
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            if (setting('subscription_payment_incoming_email_status', 'disable') === 'enable') {
                $schedule->command('subscriptions:email-send-upcoming-payments')->dailyAt('13:00');
            }

            if (setting('subscription_expired_email_status', 'disable') === 'enable') {
                $schedule->command('subscription:notify-expired')->hourly();
            }

            if (Module::has('Wallet')) {
                $schedule->command('subscription:check-manual-recurring')->hourly();
            }
        });
    }

    protected function registerPageBuilderSections()
    {
        PageBuilder::sections([
            new PageSectionBuilder(...[
                __('Pricing'),
                'pricing',
                '/upload/media/icon/pricing.svg',
                false,
                PricingPageSection::class,
                [
                    PricingPageSectionValidator::class
                ]
            ])
        ]);
    }

    /**
     * Register the commands
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            CheckManualRecurringSubscriptions::class
        ]);
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
     * Register events.
     *
     * @return void
     */
    protected function registerEvents()
    {
        $this->app['events']->listen(
            PackagePriceDeleted::class,
            RemovePriceGatewayIntegration::class
        );
        $this->app['events']->listen(
            PackageDeleted::class,
            RemovePriceGatewayIntegrationFromPackage::class
        );
        $this->app['events']->listen(FeatureDeleted::class, ReorderFeatures::class);
        $this->app['events']->listen(WebhookEventReceived::class, HandleWebhookEvent::class);
        $this->app['events']->listen(
            CheckoutSubscrptionCompleted::class,
            CreateSubscriptionFromCheckout::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'subscription',
            'subscription.product',
            'subscription.price',
            'subscription.webhookHandler'
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

    /**
     * Bind the default subscription manager.
     *
     * @return void
     */
    protected function registerSubscriptionManager()
    {
        $this->app->bind('subscription', function () {
            return new Subscription;
        });

        $this->app->bind('subscription.product', function () {
            return new Product;
        });

        $this->app->bind('subscription.price', function () {
            return new Price;
        });

        $this->app->bind('subscription.webhookHandler', function () {
            return new WebhookHandler;
        });

        $this->app->bind('subscription.invoice', function () {
            return new Invoice;
        });
    }
}