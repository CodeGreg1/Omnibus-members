<?php

namespace Modules\Withdrawals\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Withdrawals\Events\WithdrawRequestApproved;
use Modules\Withdrawals\Events\WithdrawRequestRejected;
use Modules\Withdrawals\Repositories\WithdrawalsRepository;

class WithdrawController extends BaseController
{
    /**
     * @var WithdrawalsRepository
     */
    protected $withdraws;

    public function __construct(WithdrawalsRepository $withdraws)
    {
        parent::__construct();

        $this->withdraws = $withdraws;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.withdrawals.index');

        return view('withdrawals::admin.index', [
            'pageTitle' => __('Withdrawal requests')
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function datatable(Request $request)
    {
        $this->authorize('admin.withdrawals.datatable');

        return DataTables::eloquent(
            $this->withdraws->getModel()->with(['method', 'user'])
                ->when($request->get('status'), function ($query, $status) {
                    $query->when($status === 'Completed', function ($query) {
                        $query->where('status', 1)->whereNull('rejected_at');
                    })->when($status === 'Pending', function ($query) {
                        $query->where('status', 0)->whereNull('rejected_at');
                    })->when($status === 'Rejected', function ($query) {
                        $query->whereNotNull('rejected_at');
                    });
                })
        )
            ->addColumn('total', function ($row) {
                return currency_format($row->amount, $row->currency);
            })
            ->addColumn('fixed_charge_display', function ($row) {
                return currency_format($row->fixed_charge, $row->currency);
            })
            ->addColumn('charge_display', function ($row) {
                return currency_format($row->charge, $row->currency);
            })
            ->addColumn('receivable', function ($row) {
                return currency_format($row->amount - $row->charge, $row->currency);
            })
            ->addColumn('date', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();;
            })
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
                } else {
                    $query->latest();
                }
            })
            ->toJson();
    }

    /**
     * Show the specified resource.
     * @param string $trx
     * @return Renderable
     */
    public function show(Request $request, $trx)
    {
        $this->authorize('admin.withdrawals.show');

        $withdraw = $this->withdraws->getModel()->with(['user', 'method'])->where('trx', $trx)->firstOrFail();
        $hasFunds = false;

        $wallet = $withdraw->user->getWalletByCurrency($withdraw->currency);
        if ($wallet) {
            $hasFunds = $wallet->balance >= $withdraw->amount;
        }
        $details = collect($withdraw->details)->filter(function ($detail, $index) {
            return isset($detail->value) && $detail->value;
        })->toArray();

        return view('withdrawals::admin.show', [
            'pageTitle' => __('Withdrawal request details'),
            'withdraw' => $withdraw,
            'details' => $details,
            'hasFunds' => $hasFunds
        ]);
    }

    /**
     * Approve withdrawal request.
     * @param string $trx
     * @return Renderable
     */
    public function approve(Request $request, $trx)
    {
        $this->authorize('admin.withdrawals.approve');

        $withdraw = $this->withdraws->getModel()->with(['method'])
            ->where('trx', $trx)
            ->firstOrFail();

        if ($withdraw->status) {
            return $this->errorResponse(__('Withdraw request already completed.'));
        }

        $wallet = $withdraw->user->getWalletByCurrency($withdraw->currency);

        if (!$wallet) {
            return $this->errorResponse(__('No available wallet for this :currency currency. Kindly reject this request.', [
                'currency' => $withdraw->currency
            ]));
        }

        if ($wallet->balance < $withdraw->amount) {
            return $this->errorResponse(__('The user has insufficient balance from this :currency wallet. Kindly reject this request.', [
                'currency' => $wallet->currency
            ]));
        }

        $this->withdraws->update($withdraw, $request->only(['note']));

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $entry) {
                if ($entry->isValid()) {
                    $withdraw->addMedia($entry)->toMediaCollection('additional_image');
                }
            }
        }

        $withdraw->status = 1;
        $withdraw->save();

        $newWithdraw = $withdraw->fresh();

        WithdrawRequestApproved::dispatch($newWithdraw);

        return $this->successResponse(__('Successfully approved withdrawal request.'), [
            'redirectTo' => route('admin.withdrawals.show', $newWithdraw)
        ]);
    }

    /**
     * Reject withdrawal request.
     * @param Request $request
     * @param string $trx
     * @return Renderable
     */
    public function reject(Request $request, $trx)
    {
        $this->authorize('admin.withdrawals.reject');

        $withdraw = $this->withdraws->getModel()->with(['method'])
            ->where('trx', $trx)
            ->firstOrFail();

        if ($withdraw->status) {
            return $this->errorResponse(__('Cannot reject the completed withdrawal request.'));
        }

        $withdraw->rejected_at = now();
        $withdraw->reject_reason = $request->get('reason');
        $withdraw->save();

        WithdrawRequestRejected::dispatch($withdraw->fresh());

        return $this->successResponse(__('Successfully rejected the withdrawal request.'), [
            'redirectTo' => route('admin.withdrawals.show', $withdraw->fresh())
        ]);
    }
}
