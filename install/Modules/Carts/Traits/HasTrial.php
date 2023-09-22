<?php

namespace Modules\Carts\Traits;

trait HasTrial
{
    /**
     * Check if checkout has trial
     *
     * @return bool
     */
    public function hasTrial()
    {
        if ($this->mode !== 'subscription') {
            return false;
        }

        $lineItem = $this->lineItems->first();
        return isset($lineItem->checkoutable->trial_days_count) && $lineItem->checkoutable->trial_days_count;
    }

    public function getTotalTrialDays()
    {
        if ($this->mode !== 'subscription') {
            return 0;
        }

        $lineItem = $this->lineItems->first();
        return $lineItem->checkoutable->trial_days_count;
    }

    /**
     * Get start date payment
     *
     * @return Carbon
     */
    public function getStartDate()
    {
        if ($this->hasTrial()) {
            $lineItem = $this->lineItems->first();
            return now()->addDays($lineItem->checkoutable->trial_days_count)
                ->toUserTimezone();
        }

        return now()->toUserTimezone();
    }
}