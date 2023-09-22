<?php

namespace Modules\Auth\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Profile\Models\SessionLocation;
use Modules\Base\Support\Location\VisitorLocation;

class LoginUpdateSession
{
    /**
     * @var VisitorLocation
     */
    protected $visitorLocation;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(VisitorLocation $visitorLocation)
    {
        $this->visitorLocation = $visitorLocation;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->visitorLocation->store();
    }
}
