<?php

namespace Modules\Affiliates\Http\Controllers\Web\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\Repositories\AffiliateCommissionsRepository;

class AffiliateCommissionDatatableController extends BaseController
{
    /**
     * @var AffiliateCommissionsRepository $commissions
     */
    protected $commissions;

    /**
     * @param AffiliateCommissionsRepository $commissions
     *
     * @return void
     */
    public function __construct(AffiliateCommissionsRepository $commissions)
    {
        $this->commissions = $commissions;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('user.commissions.datatable');

        $query = $this->commissions->getModel()
            ->wherehas('affiliate', function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->with(['affiliate', 'affiliate.user', 'user']);

        if (request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

        return DataTables::eloquent(
            $query->when($request->get('status'), function ($query, $status) {
                $query->when($status === 'For verification', function ($query) {
                    $query->where('status', 'pending')
                        ->where('approve_on_end', '>', now())
                        ->whereNull('approved_at');
                })->when($status === 'Withdrawable', function ($query) {
                    $query->where('status', 'pending')
                        ->where('approve_on_end', '<=', now());
                })->when($status === 'Completed', function ($query) {
                    $query->where('status', 'completed');
                })->when($status === 'Rejected', function ($query) {
                    $query->whereNotNull('rejected_at');
                });
            })
        )
            ->addColumn('user', function ($row) {
                $length = strlen($row->user->full_name);
                return Str::mask($row->user->full_name, '*', 1, $length - 2);
            })
            ->addColumn('user_email', function ($row) {
                $last = strpos($row->user->email, '@');
                return Str::mask($row->user->email, '*', 1, ($last - 2));
            })
            ->addColumn('status_color', function ($row) {
                $color = $row->status->color();
                $label = $row->status->getLabel();
                if ($label === 'Pending') {
                    $color = 'success';
                    if (!is_null($row->approved_at)) {
                        $color = 'success';
                    }

                    if ($row->approve_on_end->isFuture()) {
                        $color = 'secondary';
                    }
                }

                return $color;
            })
            ->addColumn('status_label', function ($row) {
                $label = $row->status->getLabel();

                if ($label === 'Pending') {
                    $label = 'Withdrawable';
                    if (!is_null($row->approved_at)) {
                        $label = 'Withdrawable';
                    }

                    if ($row->approve_on_end->isFuture()) {
                        $label = 'For verification until ' . $row->approve_on_end->toUserTimezone()->toUserFormat();
                    }
                }
                return $label;
            })
            ->addColumn('withdrawable_amount', function ($row) {
                return currency_format($row->amount, $row->currency);
            })
            ->addColumn('date', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                } else {
                    $query->orderBy('id', 'desc');
                }
            })
            ->only(['id', 'user', 'user_email', 'withdrawable_amount', 'type', 'status_color', 'status_label', 'rejected_reason', 'date'])
            ->toJson();
    }
}
