<?php

namespace Modules\Roles\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Modules\Roles\Support\ExtractModuleName;
use Modules\Roles\Support\ExcludedPermissions;
use Modules\Base\Support\Route\RouteNameParser;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateRoutePermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:create-permission-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a permission routes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routes = Route::getRoutes()->getRoutes();

        foreach ($routes as $route) {
            if ($route->getName() != '') {

                $permission = Permission::where('name', $route->getName())->first();

                $module = explode('.', $route->getName());
            
                $moduleName = (new ExtractModuleName($module))->get();

                if (is_null($permission) && ! in_array($moduleName, ExcludedPermissions::lists())) {
                    Permission::create([
                        'name' => $route->getName(),
                        'display_name' => (new RouteNameParser)->parse($route->getName()),
                        'guard_name' => 'web' //$route->getAction()['middleware']['0']
                    ]);
                }
            }
        }

        $this->info('Permission routes added successfully.');
    }
}
