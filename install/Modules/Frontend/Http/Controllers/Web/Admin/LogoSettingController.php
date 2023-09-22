<?php

namespace Modules\Frontend\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;

class LogoSettingController extends BaseController
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
        return view('frontend::admin.logo-section', [
            'pageTitle' => __('Logos')
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        foreach ($request->except(['redirect']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated('frontend logo settings'));

        return $this->successResponse(__('Frontend social link settings updated successfully.'));
    }
}
