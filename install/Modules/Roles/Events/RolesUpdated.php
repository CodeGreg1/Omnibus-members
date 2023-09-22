<?php

namespace Modules\Roles\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class RolesUpdated
{
    use SerializesModels;

    /**
     * @var $role
     */
    public $role;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($role)
    {
        $this->role = $role;

        $this->setLogActivity();        
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    /**
     * Created log activity
     * 
     * @return mixed
     */
    protected function setLogActivity() 
    {
        event(new LogActivityEvent(
            $this->role,
            'role',
            'updated'
        ));
    }
}
