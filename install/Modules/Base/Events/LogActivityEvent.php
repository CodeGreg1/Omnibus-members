<?php

namespace Modules\Base\Events;

use Modules\Base\Support\Activity;
use Illuminate\Queue\SerializesModels;

class LogActivityEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        $data, 
        $moduleName, 
        $eventName)
    {
        Activity::log(
            $moduleName, 
            $eventName, 
            $data != '' 
                ? $data 
                : null,
            $data != '' 
                ? $this->getReference($data)
                : null
        );
    }

    protected function getReference($data) 
    {
        if(isset($data->full_name)) {
            return $data->full_name;
        } elseif(isset($data->name)) {
            return $data->name;
        } elseif(isset($data->title)) {
            return $data->title;
        } elseif(isset($data->id)) {
            return 'ID: ' . $data->id;
        }
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
