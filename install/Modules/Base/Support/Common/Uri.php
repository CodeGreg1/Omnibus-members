<?php

if (!function_exists('uri_has_params')) {
    /**
     * Check if uri has a parameters
     * 
     * @param $uri
     * 
     * @return array
     */
    function uri_has_params($uri)
    {
        preg_match_all('/{(.*?)}/s', $uri, $match);

        return count($match[0]);
    }
}

if (!function_exists('url_remove_domain')) {
    /**
     * Remove url domain
     * 
     * Example: https://domain.com/blog/blog-title-1
     * Result: /blog/blog-title-1
     * 
     * @param $url
     * 
     * @return string|Exception
     */
    function url_remove_domain($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            throw new Exception("Please provide a valid URL!", 1);
        }

        return preg_replace('#^.+://[^/]+#', '', $url); 
    }
}