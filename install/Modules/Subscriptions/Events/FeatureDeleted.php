<?php

namespace Modules\Subscriptions\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Modules\Subscriptions\Models\Feature;
use Illuminate\Foundation\Events\Dispatchable;

class FeatureDeleted
{
    use SerializesModels, Dispatchable;

    public $feature;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Feature $feature)
    {
        $this->feature = $feature;

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
            $this->feature,
            'package feature',
            'deleted'
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