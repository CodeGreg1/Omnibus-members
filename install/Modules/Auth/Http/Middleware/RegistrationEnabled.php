<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RegistrationEnabled
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
        // TODO: Add settings registration if enabled
        $regEnabled = true;
        if (! $regEnabled) {
            throw new NotFoundHttpException;
        }

        return $next($request);
    }
}
