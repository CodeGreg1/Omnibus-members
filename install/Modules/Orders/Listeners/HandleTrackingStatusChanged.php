<?php

namespace Modules\Orders\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Orders\Events\OrderCancelled;
use Modules\Orders\Events\OrderDelivered;
use Modules\Orders\Events\OrderInTransit;
use Modules\Orders\Events\OrderPending;
use Modules\Orders\Events\OrderReturned;
use Modules\Orders\States\Tracking\Cancelled;
use Modules\Orders\States\Tracking\Delivered;
use Modules\Orders\States\Tracking\InTransit;
use Modules\Orders\States\Tracking\Pending;
use Modules\Orders\States\Tracking\Returned;

class HandleTrackingStatusChanged
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->finalState instanceof Pending) {
            OrderPending::dispatch($event->model);
        }

        if ($event->finalState instanceof InTransit) {
            OrderInTransit::dispatch($event->model);
        }

        if ($event->finalState instanceof Cancelled) {
            OrderCancelled::dispatch($event->model);
        }

        if ($event->finalState instanceof Delivered) {
            OrderDelivered::dispatch($event->model);
        }

        if ($event->finalState instanceof Returned) {
            OrderReturned::dispatch($event->model);
        }
    }
}