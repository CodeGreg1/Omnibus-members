<?php

namespace Modules\Base\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Hexadog\ThemesManager\Facades\ThemesManager;
use Modules\Base\Http\Controllers\Api\BaseController as APIBaseController;

class BaseController extends APIBaseController
{
    public function __construct() 
    {
        $this->middleware(['user_invited', 'user_locale']);

        ThemesManager::set('codeanddeploy/stisla');
    }

    /**
     * Handle ajax redirect response
     * 
     * @param string $message
     * @param string $redirectTo
     * 
     * @return Response
     */
    protected function handleAjaxRedirectResponse($message, $redirectTo) 
    {   
        Session::flash('success', $message); 

        return $this->successResponse($message, [
            'redirectTo' => $redirectTo
        ]);
    }
}
