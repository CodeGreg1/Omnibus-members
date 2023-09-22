<?php

namespace Modules\Affiliates\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Affiliates\Models\AffiliateUser;

class IncrementAffiliateClicks
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
        if (setting('allow_affiliates') !== 'enable') {
            return;
        }

        $affiliate = AffiliateUser::where('code', $event->code)->first();
        if ($affiliate && $affiliate->active) {
            $affiliate->increment('total_clicks');
        }
    }
}
