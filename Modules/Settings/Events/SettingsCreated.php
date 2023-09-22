<?php

namespace Modules\Settings\Events;

use Illuminate\Queue\SerializesModels;

class SettingsCreated
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
}
