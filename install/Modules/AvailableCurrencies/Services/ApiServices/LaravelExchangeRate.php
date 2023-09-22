<?php

namespace Modules\AvailableCurrencies\Services\ApiServices;

use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;

class LaravelExchangeRate implements ApiServiceInterface
{
    protected $api;

    public function __construct()
    {
        $this->api = new ExchangeRate();
    }

    public function get($base, $currency)
    {
        return $this->api->exchangeRate($base, $currency);
    }

    public function getAll($base, $currencies)
    {
    }
}