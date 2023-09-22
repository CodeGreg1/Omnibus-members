<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PasswordResetEnabled
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
        // TODO: Add settings forget password if enabled
        $forgetPassword = true;
        if (! $forgetPassword) {
            throw new NotFoundHttpException;
        }

        return $next($request);
    }
}
