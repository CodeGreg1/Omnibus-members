<?php

namespace Modules\Subscriptions\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Subscriptions\Models\PackagePrice;

class PackagePriceCreated
{
    use SerializesModels, Dispatchable;

    /**
     * The package price model instance.
     *
     * @var PackagePrice
     */
    public $price;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PackagePrice $price)
    {
        $this->price = $price;

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
            $this->price,
            'package price',
            'created'
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