<?php

namespace Modules\Cashier\Services\Concerns;

trait InstallWebhook
{
    public function installWebhook()
    {
        $this->api()->webhooks->create();
    }
}