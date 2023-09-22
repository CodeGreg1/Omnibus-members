<?php

namespace Modules\Sitemap\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Sitemap\Http\Requests\UpdateSitemapSettingsRequest;

class SitemapController extends BaseController
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
        return view('sitemap::admin.index', [
            'pageTitle' => config('sitemap.name')
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateSitemapSettingsRequest $request
     * @return Renderable
     */
    public function update(UpdateSitemapSettingsRequest $request)
    {
        foreach ($request->all() as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated('sitemap settings'));

        return $this->successResponse(__('Sitemap settings successfully updated.'));
    }

    public function reBuild()
    {
        Artisan::call('sitemap:generate');

        return $this->successResponse(__('Sitemap successfully re-build.'));
    }
}
