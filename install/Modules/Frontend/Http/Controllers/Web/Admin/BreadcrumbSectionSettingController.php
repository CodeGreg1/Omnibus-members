<?php

namespace Modules\Frontend\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Frontend\Http\Requests\UpdateBreadcrumbSectionSettingRequest;

class BreadcrumbSectionSettingController extends BaseController
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
        $backgroundStyle = 'color';
        if (setting('frontend_breadcrumb_bg_image')) {
            $backgroundStyle = 'image';
        }

        return view('frontend::admin.breadcrumb-section', [
            'pageTitle' => __('Breadcrumb section'),
            'background_style' => $backgroundStyle
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateBreadcrumbSectionSettingRequest $request
     * @return JsonResponse
     */
    public function update(UpdateBreadcrumbSectionSettingRequest $request)
    {
        foreach ($request->except(['redirect']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated('frontend breadcrumb section settings'));

        return $this->successResponse(__('Frontend breadcrumb section settings updated successfully.'));
    }
}
