<?php

namespace Modules\Frontend\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class FrontendUpdated
{
    use SerializesModels;

    protected $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
        
        $this->setLogActivity();
        $this->flushQueryCache();
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
     * Flush query cache
     * 
     * @return void
     */
    protected function flushQueryCache() 
    {
        $this->model->flushQueryCache();
    }

    /**
     * Created log activity
     * 
     * @return mixed
     */
    protected function setLogActivity() 
    {
        event(new LogActivityEvent(
            $this->model,
            'frontend',
            'updated'
        ));
    }
}