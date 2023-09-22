<?php

namespace Modules\Auth\Listeners;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Users\Repositories\UserRepository;

class LoginUpdateGoogle2FAFirstAuth
{   
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var Guard
     */
    protected $guard;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRepository $users, Guard $guard)
    {
        $this->users = $users;
        $this->guard = $guard;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {   
        if ( $this->guard->user()->google_2fa_status == 0 ) {
            return ;
        }

        $this->users->update(
            $this->guard->user(),
            ['google_2fa_first_auth' => 1]
        );
    }
}
