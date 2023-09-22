<?php

namespace Modules\Subscriptions\Facades;

use Illuminate\Support\Facades\Facade;

class WebhookHandler extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'subscription.webhookHandler';
    }
}