<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Base\Http\Controllers\Api\BaseController;

class DemoApp
{
    protected $restrictedRoutes = [
        'profile/details',
        'profile/avatar',
        'profile/connect/facebook',
        'profile/connect/google',
        'profile/address',
        'profile/company',
        'profile/timezone',
        'profile/language',
        'profile/currency',
        'profile/password',
        'profile/all-device/logout',
        'profile/device/logout',
        'admin/users/multi-enable',
        'admin/users/multi-ban',
        'admin/users/multi-confirm',
        'admin/languages/translate-phrase',
        'admin/module/translate-module-language',
        'delete', 
        'remove', 
        'revoke',
        'drop', 
        'edit', 
        'update',
        'destroy'
    ];

    protected $excludedRoutes = [
        'edit-language-datatable',
        'languages/edit-datatable'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(env('APP_DEMO') && Str::contains($request->path(), $this->excludedRoutes)) {
            return $next($request);
        }

        if(env('APP_DEMO')) {

            $response = new BaseController;

            if($request->ajax() || $request->wantsJson()) {
                // if(Str::contains($request->path(), ['admin/module/create'])) {
                //     return $response->errorResponse('You cannot generate module in demo version. You should purchase a license to do it.');
                // }

                if(Str::contains($request->path(), ['admin/dashboard-widgets/create'])) {
                    return $response->errorResponse('You cannot create/update dashboard widgets in demo version. You should purchase a license to do it.');
                }

                if(Str::contains($request->path(), ['admin/dashboard-widgets/reorder'])) {
                    return $response->successResponse('You cannot re-order dashboard widgets in demo version. You should purchase a license to do it.');
                }

                if(Str::contains($request->path(), 'admin/frontends/settings')) {
                    return $response->errorResponse('You cannot change in demo version.');
                }
            }
        }

        if(env('APP_DEMO') && Str::contains($request->path(), $this->restrictedRoutes)) {
            
            $response = new BaseController;

            if($request->ajax() || $request->wantsJson()) {
                if(Str::contains($request->path(), ['admin/module/delete', 'profile/avatar/remove'])) {
                    return $response->errorResponse('You cannot change in demo version.');
                }

                if(Str::contains($request->path(), ['delete', 'remove', 'drop'])) {
                    return $response->successResponse('You cannot change in demo version.');
                }

                if(Str::contains($request->path(), $this->restrictedRoutes)) {
                    return $response->errorResponse('You cannot change in demo version.');
                }
            } else {
                if($request->method() == 'GET' & !Str::contains($request->path(), ['delete', 'remove', 'revoke', 'drop', 'edit', 'update'])) {
                    die('You cannot change in demo version.');
                }
            }
        }

        return $next($request);
    }
}
