<?php

namespace Modules\AvailableCurrencies\Services\ApiServices;

interface ApiServiceInterface
{
    public function get(string $base, string $currency);

    public function getAll(string $base, array $currencies);
}