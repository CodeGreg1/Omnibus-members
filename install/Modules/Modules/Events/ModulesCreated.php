<?php

namespace Modules\Modules\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class ModulesCreated
{
    use SerializesModels;

    /**
     * @var $module
     */
    public $module;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($module)
    {
        $this->module = $module;

        $this->setLogActivity();        
    }

    /**
     * Created log activity
     * 
     * @return mixed
     */
    protected function setLogActivity() 
    {
        event(new LogActivityEvent(
            $this->module,
            'module',
            'build'
        ));
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
}
