<?php

namespace Modules\Subscriptions\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Subscriptions\Models\Subscription;

class SubscriptionPaymentCompleted
{
    use SerializesModels, Dispatchable;

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
        $subscription->sendEmailOnPaymentCompleted();
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
            'subscription payment',
            'Completed'
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
