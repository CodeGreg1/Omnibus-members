<?php

namespace Modules\Profile\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class ProfileDetailsUpdated
{
    use SerializesModels;

    /**
     * @var $profile
     */
    public $profile;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($profile)
    {
        $this->profile = $profile;

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
            $this->profile,
            'details updated',
            'profile'
        ));
    }
}
