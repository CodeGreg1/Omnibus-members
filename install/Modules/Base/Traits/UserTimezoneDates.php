<?php

namespace Modules\Base\Traits;

use Illuminate\Support\Carbon;
use Camroncade\Timezone\Facades\Timezone;

trait UserTimezoneDates
{
    public function registerUserTimezoneDates()
    {
        // Convert UTC timestamps to date by user's timezone
        Carbon::macro('toUserTimezone', function () {
            $timezone = auth()->check() ? auth()->user()->timezone : config('app.timezone');

            return $this->parse(Timezone::convertFromUTC($this, $timezone));
        });
    }
}
