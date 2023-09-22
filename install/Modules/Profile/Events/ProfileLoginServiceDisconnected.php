<?php

namespace Modules\Profile\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class ProfileLoginServiceDisconnected
{
    use SerializesModels;

    /**
     * @var $provider
     */
    public $provider;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($provider)
    {
        $this->provider = $provider;

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
            '',
            'to profile',
            'disconnected ' . $this->provider
        ));
    }
}
