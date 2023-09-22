<?php

namespace Modules\Users\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Users\Repositories\UserRepository;

class CanImpersonate
{   
    /**
     * @var UserRepository $users
     */
    protected $users;

    public function __construct(UserRepository $users) 
    {
        $this->users = $users;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->user()->can_impersonate) {
            abort(403, __('Unauthorized.'));
        }

        $user = $this->users->findOrFail($request->route('user'));

        if(!$user->can_be_impersonated) {
            abort(403, __("User can't be impersonated."));
        }

        return $next($request);
    }
}
