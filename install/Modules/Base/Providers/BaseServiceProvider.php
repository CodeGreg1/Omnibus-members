<?php

namespace Modules\Base\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Modules\Base\Support\Common\Helper;
use Modules\Base\Traits\UserFormatDates;
use Illuminate\Database\Eloquent\Factory;
use Modules\Base\Traits\UserTimezoneDates;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Base\Console\CronCheckerCommand;
use Modules\Base\Http\Middleware\ThemeLoader;
use Modules\Base\Http\Middleware\UserInvited;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Base\Http\Middleware\SetUserLocale;
use Modules\Base\Console\AutoRemoveModuleCommand;

class BaseServiceProvider extends ServiceProvider
{
    use UserTimezoneDates, UserFormatDates;

    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Base';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'base';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerSchedules();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->registerCollectionPaginator();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerEvents();
        $this->registerMiddleware();
        $this->registerCommanHelpers();
        $this->app->register(RouteServiceProvider::class);

        $this->registerUserTimezoneDates();
        $this->registerUserFormatDates();
        $this->registerCommands();
    }

    public function registerCommands() 
    {
        $this->commands([
            CronCheckerCommand::class,
            AutoRemoveModuleCommand::class
        ]);
    }

    public function registerCommanHelpers() 
    {
        Helper::loader(module_path('Base') . '/Support/Common');
    }

    /**
     * Register the middleware
     * 
     * @return void
     */
    protected function registerMiddleware() 
    {
        $this->app['router']->aliasMiddleware('theme', ThemeLoader::class);
        $this->app['router']->aliasMiddleware('user_invited', UserInvited::class);
        $this->app['router']->aliasMiddleware('user_locale', SetUserLocale::class);
    }

    /**
     * Register the collection paginator
     *
     * @return void
     */
    protected function registerCollectionPaginator()
    {
        Collection::macro(
            'paginate',
            function ($perPage, $total = null, $page = null, $pageName = 'page') {
                //Create new pagination
                $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
                $currentItems = array_slice($this->items, $perPage * ($currentPage - 1), $perPage);
                //with path of current page
                return (new LengthAwarePaginator($currentItems, count($this->items), $perPage, $currentPage))->setPath(route('admin.menus.links.list'));
            }
        );
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
        // all modules already registered inside auth module because of Laravel 9.5.1 bug only the first executed module loaded the translations
    }

    /**
     * Register schedules
     * 
     * @return void
     */
    public function registerSchedules() 
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            $schedule->command(
                'cron:checker'
            )->everyThirtyMinutes();

            $schedule->command(
                'module:auto-remove'
            )->everySixHours();

            if(function_exists('exec')) {
                if (!$this->osProcessIsRunning('queue:work')) {
                    $schedule->command('queue:work', [
                        // '--tries' => 3,
                        '--max-time' => 300
                    ])->withoutOverlapping();
                    $schedule->command('queue:restart')->everyThreeHours();
                }
            } else { //hosting without exec
                // $schedule->command('queue:work --tries=3')->everyMinute()->withoutOverlapping();
                $schedule->command('queue:work', [
                    // '--tries' => 3,
                    '--max-time' => 300
                ])->withoutOverlapping();
                $schedule->command('queue:restart')->everyThreeHours();
            }
        });

    }

    /**
     * Register events.
     *
     * @return void
     */
    protected function registerEvents() 
    {
        // 
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

    protected function osProcessIsRunning($needle)
    {
        // check if the os is linux
        if(PHP_OS != 'Linux') {
            return false;
        }

        // get process status. the "-ww"-option is important to get the full output!
        exec('ps aux -ww', $process_status);

        // search $needle in process status
        $result = array_filter($process_status, function($var) use ($needle) {
            return strpos($var, $needle);
        });

        // if the result is not empty, the needle exists in running processes
        if (!empty($result)) {
            return true;
        }

        return false;
    }
}
