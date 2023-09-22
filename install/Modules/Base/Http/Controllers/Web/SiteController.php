<?php

namespace Modules\Base\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Hexadog\ThemesManager\Facades\ThemesManager;
use Modules\Base\Http\Controllers\Web\BaseController;

class SiteController extends BaseController
{
    public function __construct() 
    {
        parent::__construct();
        
        ThemesManager::set('codeanddeploy/uno');
    }
}
