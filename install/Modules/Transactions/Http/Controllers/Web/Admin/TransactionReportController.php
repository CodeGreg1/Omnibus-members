<?php

namespace Modules\Transactions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class TransactionReportController extends BaseController
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
        $this->authorize('admin.transactions.reports.index');

        return view('transactions::admin.reports', [
            'pageTitle' => __('Transaction reports')
        ]);
    }
}
