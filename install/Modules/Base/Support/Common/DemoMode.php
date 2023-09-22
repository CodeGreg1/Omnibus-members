<?php

if (!function_exists('protected_data')) {
    /**
     * Protect data in demo version
     * 
     * @param string $data
     * @param string $name
     * 
     * @return string
     */
    function protected_data($data, $name)
    {
        if(env('APP_DEMO') && $data != '') {
            if(filter_var($data, FILTER_VALIDATE_EMAIL)) {
                \Log::info(app()->runningInConsole());
                if(!app()->runningInConsole()) {
                    return strtoupper('[' . ucfirst($name) . ' is protected in demo]');
                }
            } else {
                return strtoupper('[' . ucfirst($name) . ' is protected in demo]');
            }
        }   

        if($name == 'mail username') {
            return strtoupper('[' . ucfirst($name) . ' is protected]');
        }

        if($name == 'mail password') {
            return strtoupper('[' . ucfirst($name) . ' is protected]');
        }

        if($name == 'AWS access key id') {
            return strtoupper('[' . ucfirst($name) . ' is protected]');
        }

        if($name == 'AWS secret access key') {
            return strtoupper('[' . ucfirst($name) . ' is protected]');
        }

        return $data;
    }
}