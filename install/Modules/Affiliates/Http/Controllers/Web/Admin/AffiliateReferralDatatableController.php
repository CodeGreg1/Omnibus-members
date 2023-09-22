<?php

namespace Modules\Affiliates\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\Repositories\AffiliateReferralsRepository;

class AffiliateReferralDatatableController extends BaseController
{
    /**
     * @var AffiliateReferralsRepository $referrals
     */
    protected $referrals;

    /**
     * @param AffiliateReferralsRepository $referrals
     *
     * @return void
     */
    public function __construct(AffiliateReferralsRepository $referrals)
    {
        $this->referrals = $referrals;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('admin.affiliates.referrals.datatable');

        $query = $this->referrals->getModel()->query()->with(['affiliate', 'user', 'affiliate.user']);

        if (request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

        return DataTables::eloquent($query)
            ->addColumn('created', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->editColumn('user.email', function($row) {
                if(env('APP_DEMO')) {
                    return protected_data($row->user->email, 'email');
                }
                return $row->user->email;
            })
            ->editColumn('affiliate.user.email', function($row) {
                if(env('APP_DEMO')) {
                    return protected_data($row->affiliate->user->email, 'email');
                }
                return $row->affiliate->user->email;
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                } else {
                    $query->orderBy('id', 'desc');
                }
            })
            ->toJson();
    }
}
