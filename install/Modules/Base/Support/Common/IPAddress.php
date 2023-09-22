<?php

if (!function_exists('get_ip_address')) {
    /**
     * Get ip address
     * 
     * @return bool|null
     */
    function get_ip_address() 
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip);

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }

        return null;
    }
}


if (!function_exists('is_localhost')) {
    /**
     * Check if localhost
     * 
     * @return bool
     */
    function is_localhost() 
    {
        if(is_null(get_ip_address())) {
            return true;
        }

        if(in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
            return true;
        }

        return false;
    }
}

