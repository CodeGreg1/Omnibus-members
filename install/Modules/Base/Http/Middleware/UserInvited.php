<?php

namespace Modules\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInvited
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
        $allowedRoutes = [
            'auth.user-invitation.change-password',
            'profile.password-update',
            'auth.logout.perform',
            'admin.users.leave-impersonate'
        ];
        
        if(Auth::check() && auth()->user()->invited && !in_array($request->route()->getName(), $allowedRoutes)) {
            return redirect()->route('auth.user-invitation.change-password');
        }

        return $next($request);
    }
}
