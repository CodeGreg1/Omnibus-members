<?php

namespace Modules\Auth\Listeners;

use Modules\Auth\Events\LoggedIn;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLastLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(LoggedIn $event)
    {
        if($event->user) {
            $event->user->last_login = date('Y-m-d H:i:s');
            $event->user->timestamps = false;
            $event->user->save();
        }
    }
}
