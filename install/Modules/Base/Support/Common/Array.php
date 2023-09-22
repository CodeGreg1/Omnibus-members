<?php

if (!function_exists('array_except')) {
    /**
     * Remove excluded keys in an array
     * 
     * @param array $array
     * @param array|string $excludedKeys
     * 
     * @return array
     */
    function array_except($array, $excludedKeys)
    {
        // for array
        if(is_array($excludedKeys)) {
            foreach($excludedKeys as $key){
                if(isset($array[$key])) {
                    unset($array[$key]);
                }
            }
        }

        // for string
        if(is_string($excludedKeys)) {
            if(isset($array[$excludedKeys])) {
                unset($array[$excludedKeys]);
            }
        }

        return $array;
    }
}