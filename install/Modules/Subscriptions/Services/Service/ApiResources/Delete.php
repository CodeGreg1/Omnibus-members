<?php

namespace Modules\Subscriptions\Services\Service\ApiResources;

trait Delete
{
    public function delete($id)
    {
        return $this->api->{$this->resourceName}->delete($id);
    }
}