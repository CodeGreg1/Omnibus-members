<?php

namespace Modules\Frontend\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Frontend\Http\Requests\UpdateFooterSectionSettingRequest;

class FooterSectionSettingController extends BaseController
{
    /**
     * @var MenuRepository
     */
    protected $menus;

    public function __construct(MenuRepository $menus)
    {
        $this->menus = $menus;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('frontend::admin.footer-section', [
            'pageTitle' => __('Footer section'),
            'frontend_menus' => $this->menus->where('type', 'Frontend')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateFooterSectionSettingRequest $request
     * @return JsonResponse
     */
    public function update(UpdateFooterSectionSettingRequest $request)
    {
        foreach ($request->except(['redirect']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated('frontend footer section settings'));

        return $this->successResponse(__('Frontend footer section settings updated successfully.'));
    }
}
