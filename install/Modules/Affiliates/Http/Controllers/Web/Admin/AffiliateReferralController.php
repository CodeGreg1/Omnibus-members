<?php

namespace Modules\Affiliates\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class AffiliateReferralController extends BaseController
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
        $this->authorize('admin.affiliates.referrals.index');

        return view('affiliates::admin.referrals.index', [
            'pageTitle' => __('Affiliate referrals'),
            'policies' => JsPolicy::get('affiliates')
        ]);
    }
}
