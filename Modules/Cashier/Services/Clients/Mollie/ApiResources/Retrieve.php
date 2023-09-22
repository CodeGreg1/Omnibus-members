<?php

namespace Modules\Cashier\Services\Clients\Mollie\ApiResources;

trait Retrieve
{
    public function retrieve($id)
    {
        $url = $this->resourceName . '/' . $id;

        try {
            $result = $this->request('get', $url);
            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}