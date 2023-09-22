<?php

namespace Modules\Reports\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class ReportsController extends BaseController
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
        $this->authorize('admin.reports.index');

        return view('reports::index', [
            'pageTitle' => __('Transaction reports')
        ]);
    }
}
