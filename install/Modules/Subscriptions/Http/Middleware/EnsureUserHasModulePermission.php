<?php

namespace Modules\Subscriptions\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Roles\Support\ExtractModuleName;
use Modules\Roles\Support\ExcludedPermissions;
use Modules\Base\Support\Route\RouteNameParser;
use Modules\Modules\Repositories\ModuleRepository;
use Modules\Subscriptions\Exceptions\ModulePermissionException;

class EnsureUserHasModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->is('install*')) {
            return $next($request);
        }
        
        $routeName = $request->route()->getName();
        if ($this->validRoute($routeName)) {
            $module = (new RouteNameParser)->parse($routeName);
            $subscription = $request->user()->subscription();
            if (
                $subscription
                && $subscription->onGracePeriod()
                && !$subscription->ended()
            ) {
                if (!$subscription->item->price->package->hasPermission($routeName, true)) {
                    throw ModulePermissionException::unauthorize($module);
                }
            } else {
                return redirect(route('user.subscriptions.pricings.index'));
            }
        }

        return $next($request);
    }

    protected function validRoute($name)
    {
        $module = explode('.', $name);

        if ($module[0] === 'admin') {
            return false;
        }

        $proModules = (new ModuleRepository)->proAccess();

        $moduleName = (new ExtractModuleName($module))->get();
        $studlyName = Str::studly($moduleName);

        return (in_array($studlyName, $proModules) && !in_array($name, ExcludedPermissions::lists()));
    }
}