<?php

namespace Modules\Subscriptions\Services\Service\ApiResources;

trait Retrieve
{
    public function retrieve($id)
    {
        return $this->api->{$this->resourceName}->retrieve($id);
    }
}