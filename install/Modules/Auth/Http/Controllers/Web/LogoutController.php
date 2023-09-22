<?php

namespace Modules\Auth\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Modules\Auth\Support\Recovery\RecoverySession;
use Modules\Base\Http\Controllers\Web\BaseController;

class LogoutController extends BaseController
{
    /**
     * Log out account user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function perform()
    {
        // Regenerate session
        request()->session()->regenerate();

        // Logout auth
        Auth::logout();

        return redirect('/');
    }
}
