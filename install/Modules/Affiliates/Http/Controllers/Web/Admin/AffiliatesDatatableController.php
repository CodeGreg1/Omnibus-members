<?php

namespace Modules\Affiliates\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\Repositories\AffiliatesRepository;

class AffiliatesDatatableController extends BaseController
{
    /**
     * @var AffiliatesRepository $affiliates
     */
    protected $affiliates;

    /**
     * @param AffiliatesRepository $affiliates
     *
     * @return void
     */
    public function __construct(AffiliatesRepository $affiliates)
    {
        $this->affiliates = $affiliates;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('admin.affiliates.users.datatable');

        $query = $this->affiliates->getModel()->query()->with(['user'])->withCount('referrals');

        if (request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

        return DataTables::eloquent($query
            ->when($request->get('status'), function ($query, $status) {
                $query->when($status === 'Active', function ($query) {
                    $query->where([
                        'active' => 1,
                        'approved' => 1
                    ])->whereNull('rejected_at');
                })->when($status === 'Pending', function ($query) {
                    $query->where([
                        'active' => 0,
                        'approved' => 0
                    ])->whereNull('rejected_at');
                })->when($status === 'Rejected', function ($query) {
                    $query->whereNotNull('rejected_at');
                })
                    ->when($status === 'Disabled', function ($query) {
                        $query->where([
                            'approved' => 1,
                            'active' => 0
                        ])->whereNull('rejected_at');
                    });
            }))
            ->editColumn('user.email', function($row) {
                if(env('APP_DEMO')) {
                    return protected_data($row->user->email, 'email');
                }
                return $row->user->email;
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->toJson();
    }
}
