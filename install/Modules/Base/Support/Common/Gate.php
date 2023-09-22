<?php

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;

if (!function_exists('authorize')) {
    /**
     * Throw authorization exception if response was denied.
     * 
     * @param array $abilities Example: ['permission1','permission2']
     * 
     * @return mixed
     */
    function authorize($abilities)
    {   
        if( !Gate::any($abilities) ) {
            throw (new AuthorizationException('This action is unauthorized.', Response::HTTP_FORBIDDEN));
        }
    }
}