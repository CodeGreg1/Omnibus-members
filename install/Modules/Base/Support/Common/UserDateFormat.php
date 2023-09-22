<?php

if (!function_exists('user_date_format')) {
    /**
     * Convert date format with user setting date format with user timezone
     * 
     * @param string $datetime (format: yyyy-mm-dd H:i:s (2022-06-05 11:22:24))
     * 
     * @return string|N/A
     */
    function user_date_format($datetime)
    {
        if(!is_null($datetime) || $datetime != "") {
            return \Carbon\Carbon::parse(
                Timezone::convertFromUTC(
                    $datetime, 
                    auth()->user()->timezone
                )
            )->format(auth()->user()->date_format);
        }

        return 'N/A';
    }
}