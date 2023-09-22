<?php

namespace Modules\Auth\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class UserInvitationChangePasswordController extends BaseController
{
    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('auth::user-invitation-change-password', [
            'pageTitle' => __('Change Password')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }
}
