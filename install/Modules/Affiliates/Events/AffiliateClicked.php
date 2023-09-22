<?php

namespace Modules\Affiliates\Events;

use Illuminate\Queue\SerializesModels;

class AffiliateClicked
{
    use SerializesModels;

    public $code;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
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