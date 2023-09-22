<?php

namespace Modules\Cashier\Services\Clients\Razorpay\Conditions;

class CheckoutViewAvailability
{
    public function handle($checkout)
    {
        $lineItem = $checkout->lineItems->first();
        if (!$lineItem) {
            return true;
        }

        if (
            setting('cashier_mode', 'sandbox') === 'sandbox'
            && $lineItem->checkoutable->currency !== 'INR'
            && $lineItem->checkoutable->type !== 'onetime'
        ) {
            return false;
        }

        if ($lineItem->checkoutable->type === 'onetime') {
            return true;
        }

        if ($lineItem->checkoutable->term->interval !== 'day') {
            return true;
        }

        return $lineItem->checkoutable->term->interval_count >= 7;
    }
}