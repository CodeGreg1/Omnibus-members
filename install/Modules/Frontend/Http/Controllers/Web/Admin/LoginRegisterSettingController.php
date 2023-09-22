<?php

namespace Modules\Frontend\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;

class LoginRegisterSettingController extends BaseController
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
        return view('frontend::admin.login-register', [
            'pageTitle' => __('Login and Register page')
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

        event(new SettingsUpdated('frontend login and register page settings'));

        return $this->successResponse(__('Frontend login and register settings updated successfully.'));
    }
}
