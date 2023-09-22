<?php

namespace Modules\Auth\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLastLogout
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
    public function handle($event)
    {
        if($event->user) {
            $event->user->last_logout = date('Y-m-d H:i:s');
            $event->user->last_activity = null; //set to null for check online user
            $event->user->timestamps = false;
            $event->user->save();

            $this->setLogActivity();
        }
    }

    /**
     * Created log activity
     * 
     * @return mixed
     */
    protected function setLogActivity() 
    {
        event(new LogActivityEvent(
            '',
            '',
            'logout to the system'
        ));
    }
}
