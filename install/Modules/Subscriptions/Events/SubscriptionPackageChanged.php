<?php

namespace Modules\Subscriptions\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Subscriptions\Models\Subscription;

class SubscriptionPackageChanged
{
    use SerializesModels, Dispatchable;

    /**
     * @var Subscription
     */
    public $subscription;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;

        $this->setLogActivity();

        $subscription->sendEmailOnPackageChange();
    }

    /**
     * Created log activity
     *
     * @return mixed
     */
    protected function setLogActivity()
    {
        event(new LogActivityEvent(
            $this->subscription->item->price->package,
            'package',
            'Subscribed to'
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
