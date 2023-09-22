<?php

namespace Modules\Users\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class UserActivitiesController extends BaseController
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
        $this->authorize('user.activities.index');

        return view('users::user.activities', [
            'pageTitle' => 'My activities',
            'policies' => JsPolicy::get('users', '.')
        ]);
    }
}
