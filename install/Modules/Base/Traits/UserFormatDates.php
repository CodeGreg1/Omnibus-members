<?php

namespace Modules\Base\Traits;

use Illuminate\Support\Carbon;

trait UserFormatDates
{
    public function registerUserFormatDates()
    {
        // Convert timestamps to date format by user's format
        Carbon::macro('toUserFormat', function () {
            $format = auth()->check() ? auth()->user()->date_format : 'Y m, d g:i A';

            return $this->format($format);
        });
    }
}