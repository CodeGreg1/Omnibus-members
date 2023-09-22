<?php

namespace Modules\Affiliates\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\ServiceProvider;
use Modules\Base\Support\Common\Helper;
use Modules\Affiliates\Facades\CommissionType;
use Modules\Affiliates\Events\AffiliateClicked;
use Modules\Affiliates\Listeners\UserDeposited;
use Modules\Affiliates\Events\AffiliateApproved;
use Modules\Affiliates\Events\AffiliateRejected;
use Modules\Affiliates\Events\AffiliatesCreated;
use Modules\Affiliates\Http\Middleware\DetectAffiliate;
use Modules\Affiliates\Events\AffiliateCommissionCreated;
use Modules\Affiliates\Listeners\CreateAffiliateReferral;
use Modules\Affiliates\Events\AffiliateCommissionApproved;
use Modules\Affiliates\Events\AffiliateCommissionRejected;
use Modules\Affiliates\Listeners\IncrementAffiliateClicks;
use Modules\Affiliates\Listeners\NotifyUserCommissionApproved;
use Modules\Affiliates\Listeners\NotifyUserCommissionRejected;
use Modules\Affiliates\Listeners\NotifyUserIncomingCommission;
use Modules\Affiliates\Listeners\CreateCommissionOnSubscriptionCreated;
use Modules\Affiliates\Listeners\CreateCommissionOnSubscriptionPayment;
use Modules\Affiliates\Listeners\NotifyAdminAffiliateMembershipRequest;
use Modules\Affiliates\Listeners\NotifyUserAffiliateMembershipApproved;
use Modules\Affiliates\Listeners\NotifyUserAffiliateMembershipRejected;
use Modules\Affiliates\Services\CommissionType as ServicesCommissionType;

class AffiliatesServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Affiliates';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'affiliates';

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

        $this->registerAffiliateMiddleware();
    }

    /**
     * Register the affiliate global middleware.
     *
     * @return void
     */
    protected function registerAffiliateMiddleware()
    {
        /** @var Router $router */
        $router = $this->app['router'];

        $router->aliasMiddleware('detect_affiliate', DetectAffiliate::class);

        $router->pushMiddlewareToGroup('web', DetectAffiliate::class);
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

        $this->registerCommissionTypeManager();
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
        $this->app['events']->listen(
            AffiliateClicked::class,
            IncrementAffiliateClicks::class
        );

        $this->app['events']->listen(
            Registered::class,
            CreateAffiliateReferral::class
        );

        $this->app['events']->listen(
            'Modules\Deposits\Events\DepositApproved',
            UserDeposited::class
        );

        // $this->app['events']->listen(
        //     'Modules\Subscriptions\Events\SubscriptionCreated',
        //     CreateCommissionOnSubscriptionCreated::class
        // );

        $this->app['events']->listen(
            'Modules\Subscriptions\Events\SubscriptionPaymentCompleted',
            CreateCommissionOnSubscriptionPayment::class
        );

        $this->app['events']->listen(
            AffiliatesCreated::class,
            NotifyAdminAffiliateMembershipRequest::class
        );
        $this->app['events']->listen(
            AffiliateApproved::class,
            NotifyUserAffiliateMembershipApproved::class
        );
        $this->app['events']->listen(
            AffiliateRejected::class,
            NotifyUserAffiliateMembershipRejected::class
        );
        $this->app['events']->listen(
            AffiliateCommissionCreated::class,
            NotifyUserIncomingCommission::class
        );
        $this->app['events']->listen(
            AffiliateCommissionApproved::class,
            NotifyUserCommissionApproved::class
        );
        $this->app['events']->listen(
            AffiliateCommissionRejected::class,
            NotifyUserCommissionRejected::class
        );
    }

    protected function registerCommissionTypeManager()
    {
        $this->app->singleton('affiliate_commission_type', function ($app) {
            return new ServicesCommissionType();
        });
    }

    protected function registerFacades()
    {
        $this->app->alias('affiliate_commission_type', CommissionType::class);
    }

    public function registerCommanHelpers()
    {
        Helper::loader(module_path('Affiliates') . '/Support/Common');
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
