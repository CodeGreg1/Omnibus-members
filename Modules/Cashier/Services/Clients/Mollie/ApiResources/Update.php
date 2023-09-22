<?php

namespace Modules\Cashier\Services\Clients\Mollie\ApiResources;

trait Update
{
    public function update($id, $params = [])
    {
        $url = $this->resourceName . '/' . $id;

        try {
            $result = $this->request('patch', $url, $params);
            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}