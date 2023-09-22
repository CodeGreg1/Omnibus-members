<?php

namespace Modules\Subscriptions\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Modules\Subscriptions\Models\Package;
use Illuminate\Foundation\Events\Dispatchable;

class PackageFeaturesUpdated
{
    use SerializesModels, Dispatchable;

    /**
     * The package model instance.
     *
     * @var Package
     */
    public $package;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Package $package)
    {
        $this->package = $package;

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
            $this->package,
            'package features',
            'updated'
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
