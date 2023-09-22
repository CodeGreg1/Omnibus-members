<?php

namespace Modules\Languages\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class LanguagesDeleted
{
    use SerializesModels;

    protected $model;

    protected $ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model, $ids)
    {
        $this->model = $model;
        $this->ids = !is_array($ids) ? [$ids] : $ids;
        
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
            '',
            'language with ids: ' . implode(', ', $this->ids),
            'deleted'
        ));
    }
}
