<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Dashboard\Services\Widgets;
use Illuminate\Contracts\Support\Renderable;
use Modules\Transactions\Models\Transaction;
use Modules\Base\Support\Widgets\DashboardWidgets;
use Modules\Dashboard\Services\UserWalletOverview;
use Modules\Dashboard\Services\AdminWalletOverview;
use Modules\Base\Http\Controllers\Web\BaseController;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a dashboard widgets
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('dashboard.index');

        $user = auth()->user();

        return view('dashboard::index', array_filter([
            'pageTitle' => __('Dashboard'),
            'removePageHeader' => 1,
            'widgets' => (new DashboardWidgets(
                (new Widgets())->get()
            ))->get(),
            'user_wallet_overview' => (new UserWalletOverview)->get($user),
            'admin_wallet_overview' => (new AdminWalletOverview)->get($user)
        ]));
    }
}
