<?php

namespace Modules\AvailableCurrencies\Services\ApiServices;

use AmrShawky\LaravelCurrency\Facade\Currency;

class LaravelCurrency implements ApiServiceInterface
{
    public function get($base, $currency)
    {
        return Currency::convert()
            ->from($base)
            ->to($currency)
            ->get();
    }

    public function getAll($base, $currencies)
    {
        return Currency::rates()
            ->latest()
            ->symbols($currencies)
            ->base($base)
            ->get();
    }
}