<?php

namespace Modules\AvailableCurrencies\Services;

use Modules\AvailableCurrencies\Services\ApiServices\ApiServiceInterface;

class ExchangeRate
{
    protected $api;

    public function __construct(ApiServiceInterface $api)
    {
        $this->api = $api;
    }

    public function get($from, $to)
    {
        return $this->api->get($from, $to);
    }

    public function getAll($base, array $currencies)
    {
        return $this->api->getAll($base, $currencies);
    }
}