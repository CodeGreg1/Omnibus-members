<?php

namespace Modules\Cashier\Services\Contracts;

use Illuminate\Http\Request;

interface WebhookServiceClientInterface
{
    public function install();

    public function verifyIPN($request);

    public function handle(array $payload, Request $request);
}