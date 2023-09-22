<?php

namespace Modules\Subscriptions\Exceptions;

use Exception;
use Modules\Subscriptions\Models\SubscriptionPayment;

class InvalidInvoice extends Exception
{
    /**
     * Create a new InvalidInvoice instance.
     *
     * @param  \Modules\Subscriptions\Models\SubscriptionPayment  $payment
     * @param  \Illuminate\Database\Eloquent\Model  $owner
     * @return static
     */
    public static function invalidOwner(SubscriptionPayment $payment, $owner)
    {
        return new static("The invoice `{$payment->id}` does not belong to this customer `$owner->id.");
    }
}