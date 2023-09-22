<?php

namespace Modules\Frontend\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Frontend\Http\Requests\UpdateGeneralFrontendSettingsRequest;

class GeneralSettingController extends BaseController
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
        return view('frontend::admin.index', [
            'pageTitle' => __('General settings'),
            'primary_menus' => $this->menus->where('type', 'Frontend')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateGeneralFrontendSettingsRequest $request
     * @return JsonResponse
     */
    public function update(UpdateGeneralFrontendSettingsRequest $request)
    {
        foreach ($request->except(['redirect']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated('frontend general settings'));

        return $this->successResponse(__('Frontend general settings were updated successfully.'));
    }
}
