<?php

namespace Modules\Frontend\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Frontend\Http\Requests\UpdateThemeColorsSettingRequest;

class ThemeColorsSettingController extends BaseController
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
        $colorScheme = 'light';
        if (setting('frontend_color_scheme') == 'dark') {
            $colorScheme = 'dark';
        }

        return view('frontend::admin.theme-colors', [
            'pageTitle' => __('Theme colors'),
            'colorScheme' => $colorScheme
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateThemeColorsSettingRequest $request
     * @return JsonResponse
     */
    public function update(UpdateThemeColorsSettingRequest $request)
    {
        foreach ($request->except(['redirect']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated());

        return $this->successResponse(__('Frontend navbar section settings updated successfully.'));
    }
}
