<?php

namespace Modules\Affiliates\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\Http\Requests\UpdateAffiliateSettingsRequest;

class AffiliateSettingController extends BaseController
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
        $this->authorize('admin.settings.affiliates.index');

        return view('affiliates::admin.settings', [
            'pageTitle' => __('Affiliate settings')
        ]);
    }

    /**
     * Update affiliate settings
     *
     * @param UpdateAffiliateSettingsRequest $request
     */
    public function update(UpdateAffiliateSettingsRequest $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated());

        return $this->successResponse(__('Affiliate settings updated successfully.'));
    }
}
