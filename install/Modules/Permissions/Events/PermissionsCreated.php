<?php

namespace Modules\Permissions\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class PermissionsCreated
{
    use SerializesModels;

    /**
     * @var $permission
     */
    public $permission;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($permission)
    {
        $this->permission = $permission;

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
            $this->permission,
            'permission',
            'created'
        ));
    }
}
