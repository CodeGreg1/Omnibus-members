<?php

if (!function_exists('is_route_excluded')) {
    /**
     * Check if route name is excluded to menus
     * 
     * @param string $route
     * 
     * @return boolean
     */
    function is_route_excluded($route)
    {
        $excludedKeywords = [
            '.datatable'
        ];

        $excludedRouteNames = [
            'debugbar.openhandler',
            'debugbar.assets.css',
            'debugbar.assets.js',
            'ignition.healthCheck',
            'auth.verification.notice',
            'auth.recovery.show',
            'auth.user-invitation.change-password'
        ];

        if(in_array($route, $excludedRouteNames)) {
            return true;
        }

        foreach($excludedKeywords as $keyword) {
            if (strpos($route, $keyword) !== false) {
                return true;
            }
        }
    }
}

if (!function_exists('is_route_included')) {
    /**
     * Check route name if included
     * 
     * @param string $route
     * 
     * @return boolean
     */
    function is_route_included($route)
    {
        return !empty($route->getName()) && $route->methods[0] == 'GET' && !uri_has_params($route->uri) && !is_route_excluded($route->getName());
    }
}