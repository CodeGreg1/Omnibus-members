<?php

namespace Modules\Subscriptions\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Subscriptions\Models\Subscription;

class SubscriptionCancelled
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
        $subscription->sendEmailOnCanceled();
    }

    /**
     * Created log activity
     *
     * @return mixed
     */
    protected function setLogActivity()
    {
        $user = $this->subscription->subscribable;
        if (auth()->check() && $user->id === auth()->id()) {
            $description = 'cancelled';
        } else {
            $description = 'cancelled ' . $user->getName();
        }

        event(new LogActivityEvent(
            $this->subscription->item->price->package,
            'subscription',
            $description
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