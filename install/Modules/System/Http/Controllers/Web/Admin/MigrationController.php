<?php

namespace Modules\System\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class MigrationController extends BaseController
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
        $this->authorize('dashboard.index');

        return view('system::admin.migration', [
            'pageTitle' => __('Database Migration')
        ]);
    }

    /**
     * Run the migration
     * @return Json
     */
    public function run()
    {
        $this->authorize('dashboard.index');

        Artisan::call('migrate', [
            '--force' => true,
        ]);

        Artisan::call('optimize:clear');

        return $this->successResponse(__('The system database migrated successfully.'));
    }
}
