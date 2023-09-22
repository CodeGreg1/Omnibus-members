<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VerifyInstaller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!file_exists(base_path('.env')) && ! $request->is('install*')) {
            return redirect()->to('install');
        }

        if(file_exists(base_path('.env')) && $request->is('install*') && !$request->is('install/success')) {
            throw new NotFoundHttpException;
        }

        return $next($request);
    }
}
