<?php

namespace Modules\Subscriptions\Traits;

use Modules\AvailableCurrencies\Facades\Currency;

trait ManagePayment
{
    /**
     * Get the total payments of the subscription.
     *
     * @param bool $formatted
     * @param string|null $currency
     *
     * @return string|int|float
     */
    public function getTotalPayments($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $total = $this->payables->where('state', 'paid')
            ->sum(function ($payment) use ($currency) {
                return $payment->getAmount(false, $currency);
            });

        if (!$formatted) {
            return $total;
        }

        return currency_format(
            $total,
            $currency
        );
    }
}