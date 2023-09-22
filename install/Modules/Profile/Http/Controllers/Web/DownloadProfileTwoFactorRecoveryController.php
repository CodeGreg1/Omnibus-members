<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Users\Repositories\UserRepository;
use Modules\Profile\Services\TwoFactor\DownloadTwoFactorRecoveryCode;

class DownloadProfileTwoFactorRecoveryController extends BaseController
{
    use DownloadTwoFactorRecoveryCode;
    
    /**
     * @var UserRepository $users
     */
    public function __construct(UserRepository $users) 
    {
        parent::__construct();
    }

    /**
     * Handle downloading two factor recovery code
     * 
     * @return Response
     */
    public function perform()
    {
        $this->authorize('profile.two-factor.download-recovery');
        
        return $this->downloadTwoFactorRecoveryCode(auth()->user()->recovery_codes);
    }
}
