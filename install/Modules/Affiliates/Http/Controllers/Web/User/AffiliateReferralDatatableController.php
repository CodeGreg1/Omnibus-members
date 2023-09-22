<?php

namespace Modules\Affiliates\Http\Controllers\Web\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\Repositories\AffiliateReferralLevelsRepository;

class AffiliateReferralDatatableController extends BaseController
{
    /**
     * @var AffiliateReferralLevelsRepository $referralLevels
     */
    protected $referrals;

    /**
     * @param AffiliateReferralLevelsRepository $referralLevels
     *
     * @return void
     */
    public function __construct(AffiliateReferralLevelsRepository $referralLevels)
    {
        $this->referralLevels = $referralLevels;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('user.affiliates.referrals.datatable');

        $query = $this->referralLevels->getModel()
            ->wherehas('affiliate', function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->when($request->get('level'), function ($query, $level) {
                return $query->where('level', $level);
            })
            ->with(['user', 'commissions']);

        if (request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

        return DataTables::eloquent($query)
            ->addColumn('created', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->addColumn('user', function ($row) {
                $length = strlen($row->user->full_name);
                return Str::mask($row->user->full_name, '*', 1, $length - 2);
            })
            ->addColumn('email', function ($row) {
                $last = strpos($row->user->email, '@');
                return Str::mask($row->user->email, '*', 1, ($last - 2));
            })
            ->addColumn('verification', function ($row) use ($request) {
                $currency = $request->user()->currency;
                $commissions = $row->commissions
                    ->where('reffered_id', $row->reffered_id)
                    ->where('status', 'pending')
                    ->where('approve_on_end', '>', now())
                    ->whereNull('approved_at');
                return currency_format(
                    $commissions->sum(function ($commission) use ($currency) {
                        return currency($commission->amount, $commission->currency, $currency, false);
                    }),
                    $currency
                );
            })
            ->addColumn('withdrawable', function ($row) use ($request) {
                $currency = $request->user()->currency;
                $commissions = $row->commissions
                    ->where('reffered_id', $row->reffered_id)
                    ->where('status', 'pending')
                    ->where('approve_on_end', '<=', now());
                return currency_format(
                    $commissions->sum(function ($commission) use ($currency) {
                        return currency($commission->amount, $commission->currency, $currency, false);
                    }),
                    $currency
                );
            })
            ->addColumn('completed', function ($row) use ($request) {
                $currency = $request->user()->currency;
                $commissions = $row->commissions
                    ->where('reffered_id', $row->reffered_id)
                    ->where('status', 'completed');
                return currency_format(
                    $commissions->sum(function ($commission) use ($currency) {
                        return currency($commission->amount, $commission->currency, $currency, false);
                    }),
                    $currency
                );
            })
            ->addColumn('rejected', function ($row) use ($request) {
                $currency = $request->user()->currency;
                $commissions = $row->commissions
                    ->where('reffered_id', $row->reffered_id)
                    ->whereNotNull('rejected_at');
                return currency_format(
                    $commissions->sum(function ($commission) use ($currency) {
                        return currency($commission->amount, $commission->currency, $currency, false);
                    }),
                    $currency
                );
            })
            ->only(['user', 'email', 'verification', 'withdrawable', 'completed', 'rejected', 'created'])
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
