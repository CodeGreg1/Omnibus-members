<?php

namespace Modules\System\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class SystemCacheController extends BaseController
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
        $this->authorize('admin.optimize.index');

        return view('system::admin.optimize', [
            'pageTitle' => __('Clear System Cache')
        ]);
    }

    /**
     * Optimize system caches
     * @return Renderable
     */
    public function update()
    {
        $this->authorize('admin.optimize.update');

        Artisan::call('optimize:clear');

        return $this->successResponse(__('The system cache was cleared successfully.'));
    }
}
