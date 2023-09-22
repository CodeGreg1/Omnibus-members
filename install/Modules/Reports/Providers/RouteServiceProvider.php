<?php

namespace Modules\Reports\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module web namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleWebNamespace = 'Modules\Reports\Http\Controllers\Web';

    /**
     * The module api namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleApiNamespace = 'Modules\Reports\Http\Controllers\Api';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->moduleWebNamespace)
            ->group([
                module_path('Reports', '/Routes/web.php'),
                module_path('Reports', '/Routes/admin.php')
            ]);
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleApiNamespace)
            ->group(module_path('Reports', '/Routes/api.php'));
    }
}
