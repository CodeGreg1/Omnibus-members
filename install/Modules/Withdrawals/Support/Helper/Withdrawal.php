<?php

if (!function_exists('withdrawal_request_count')) {
    /**
     * Get withdrawal request count
     *
     * @param int $count
     * @param boolean $add
     *
     * @return mixed
     */
    function withdrawal_request_count($count = 0, $add = true)
    {
        $requests = cache()->get('withdrawal_request_count', 0);
        if (!$count) {
            return $requests;
        }

        if ($add) {
            $requests = $requests + $count;
        } else {
            $requests = $requests - $count;
            $requests = $requests >= 0 ? $requests : 0;
        }

        cache()->put('withdrawal_request_count', $requests);
        return $requests;
    }
}