<?php

namespace Modules\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Hexadog\ThemesManager\Http\Middleware\ThemeLoader as HexadogThemeLoader;

class ThemeLoader extends HexadogThemeLoader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $theme = null)
    {
        // Check if request url starts with admin prefix
        // if ($request->segment(1) === 'admin') {
        //     // Set a specific theme for matching urls
        //     $theme = 'hexadog/admin';
        // }

        // Call parent Middleware handle method
        return parent::handle($request, $next, $theme);
    }
}
