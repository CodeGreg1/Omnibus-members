<?php

namespace Modules\System\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class SystemServerInfoController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $this->authorize('admin.server.info');

        return view('system::admin.server', [
            'pageTitle' => __('Server Information'),
            'ip' => $request->ip(),
            'scheme' => $request->getScheme(),
            'host' => $request->getHttpHost(),
            'port' => $request->getPort()
        ]);
    }
}
