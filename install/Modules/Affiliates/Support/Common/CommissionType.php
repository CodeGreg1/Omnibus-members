<?php

if (!function_exists('commission_type')) {
    /**
     * Get commission typ service.
     *
     * @return \Modules\Affiliates\Services\CommissionType|string
     */
    function commission_type()
    {
        return app('affiliate_commission_type');
    }
}