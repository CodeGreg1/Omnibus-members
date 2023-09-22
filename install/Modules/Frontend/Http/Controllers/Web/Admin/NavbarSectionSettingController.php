<?php

namespace Modules\Frontend\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Frontend\Http\Requests\UpdateNavbarSectionSettingRequest;

class NavbarSectionSettingController extends BaseController
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
        return view('frontend::admin.navbar-section', [
            'pageTitle' => __('Navbar section')
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateNavbarSectionSettingRequest $request
     * @return JsonResponse
     */
    public function update(UpdateNavbarSectionSettingRequest $request)
    {
        foreach ($request->except(['redirect']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated('frontend navbar section settings'));

        return $this->successResponse(__('The frontend navbar section settings updated successfully.'));
    }
}
