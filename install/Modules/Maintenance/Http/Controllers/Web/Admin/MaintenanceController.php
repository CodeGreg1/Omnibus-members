<?php

namespace Modules\Maintenance\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class MaintenanceController extends BaseController
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
        $this->authorize('admin.maintenance.index');

        return view('maintenance::admin.index', [
            'pageTitle' => __('Maintenance'),
            'enabled' => app()->isDownForMaintenance()
        ]);
    }
}
