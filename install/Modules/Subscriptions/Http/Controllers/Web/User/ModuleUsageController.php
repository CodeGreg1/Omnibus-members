<?php

namespace Modules\Subscriptions\Http\Controllers\Web\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Modules\Roles\Support\ExtractModuleName;
use Modules\Base\Http\Controllers\Web\BaseController;

class ModuleUsageController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $this->authorize('user.subscriptions.module-usages');

        $subscription = auth()->user()->subscriptions()->where('id', $id)->firstOrFail();
        $modulePermissions = [];

        if ($subscription) {
            $modulePermissions = $this->handleParsingPermissionValues($subscription->item->price->package->moduleLimits);
        }

        // return $modulePermissions;
        return view('subscriptions::user.module-usage.index', [
            'pageTitle' => __('Module usages'),
            'isSubscribed' => !!$subscription,
            'modulePermissions' => $modulePermissions
        ]);
    }

    protected function handleParsingPermissionValues($modulePermissions)
    {
        $resultPermissions = [];

        foreach ($modulePermissions as $modulePermission) {
            $module = explode('.', $modulePermission->permission->name);

            $moduleKey = (new ExtractModuleName($module))->get();

            $ends = 'unlimited';
            $used = 0;
            if ($modulePermission->limit) {
                $ends = now()->endOfDay()->isoFormat('lll');

                if ($modulePermission->term === 'month') {
                    $ends = now()->endOfMonth()->isoFormat('lll');
                }
            }

            if ($modulePermission->counter) {
                $date = Carbon::create($modulePermission->counter->date)->addDay();

                if ($modulePermission->term === 'month') {
                    $date = Carbon::create($modulePermission->counter->date)->endOfMonth()->addDay();
                }

                if ($date->isFuture()) {
                    $used = $modulePermission->counter->count;
                }
            }

            $resultPermissions[$moduleKey][] = (object) [
                'name' => $modulePermission->permission->name,
                'display_name' => $modulePermission->permission->display_name,
                'limit' => $modulePermission->limit,
                'used' => $used,
                'ends' => $ends
            ];
        }

        $modulePermissions = [];
        foreach ($resultPermissions as $modulePermission => $permissions) {
            $moduleName = Str::kebab($modulePermission);
            $moduleName = Str::ucfirst(str_replace('-', ' ', $moduleName));
            $modulePermissions[] = [
                'key' => $modulePermission,
                'title' => $moduleName,
                'permissions' => $permissions
            ];
        }

        return $modulePermissions;
    }
}