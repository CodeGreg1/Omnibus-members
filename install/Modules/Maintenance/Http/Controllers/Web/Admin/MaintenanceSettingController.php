<?php

namespace Modules\Maintenance\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Maintenance\Http\Requests\UpdateMaintenanceSettingsRequest;

class MaintenanceSettingController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateMaintenanceSettingsRequest $request
     * @return Renderable
     */
    public function update(UpdateMaintenanceSettingsRequest $request)
    {
        $enabled = $request->has('maintenance_enabled');

        foreach ($request->except(['maintenance_enabled']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        if ($enabled) {
            if (app()->isDownForMaintenance()) {
                Artisan::call('up');
            }

            Artisan::call('down --secret="' . setting('maintenance_secret_code') . '"');
        } else {
            if (app()->isDownForMaintenance()) {
                Artisan::call('up');
            }
        }

        event(new SettingsUpdated('system maintenance settings'));

        return $this->successResponse(__('Maintenance settings updated successfully.'));
    }
}
