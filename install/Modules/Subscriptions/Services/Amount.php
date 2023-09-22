<?php

namespace Modules\Subscriptions\Services;

use Modules\AvailableCurrencies\Models\AvailableCurrency;

class Amount
{
    /**
     * The value of the instance.
     *
     * @var int
     */
    public $value;

    /**
     * The currency instance.
     *
     * @var string
     */
    public $currency;

    public function __construct(int $value, AvailableCurrency $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }
}