<?php

if (!function_exists('string_to_stars')) {
    /**
     * Convert string to *; it will use to secure the string in view
     * 
     * @param string $string
     * @param int|null $total - Total affected characters to be converted into stars
     * 
     * @return string
     */
    function string_to_stars($string, $total = null)
    {   
        // fix issue with null warning
        if(is_null($string)) {
            return $string;
        }

        if(is_numeric($total)) {
            $stars = substr($string, 0, $total);

            return str_repeat('*', strlen($stars)) . substr($string, $total);
        }
        
        return str_repeat('*', strlen($string));
    }
}