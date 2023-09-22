<?php

namespace Modules\Deposits\Http\Controllers\Web\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deposits\Events\DepositApproved;
use Modules\Deposits\Events\DepositRejected;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Deposits\Repositories\DepositsRepository;

class DepositController extends BaseController
{
    /**
     * @var DepositsRepository
     */
    public $deposits;

    public function __construct(
        DepositsRepository $deposits
    ) {
        parent::__construct();

        $this->deposits = $deposits;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.deposits.index');

        return view('deposits::admin.index', [
            'pageTitle' => __('Deposit requests')
        ]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('admin.deposits.datatable');

        return DataTables::eloquent(
            $this->deposits->getModel()->with(['method', 'user'])
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
            ->addColumn('payable_display', function ($row) {
                return currency_format($row->charge + $row->amount, $row->currency);
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
     * @param int $id
     * @return Renderable
     */
    public function show($trx)
    {
        $this->authorize('admin.deposits.show');

        $deposit = $this->deposits->getModel()->with(['user'])->where('trx', $trx)->firstOrFail();
        if ($deposit->method) {
            $method_name = $deposit->method->name;
            $method_image = $deposit->method->getPreviewImage();
        } else {
            $gateway = Cashier::getClient($deposit->gateway);
            $method_name = $gateway->name;
            $method_image = $gateway->getConfig('logo');
        }

        return view('deposits::admin.show', [
            'pageTitle' => __('Deposit details'),
            'deposit' => $deposit,
            'method_name' => $method_name,
            'method_image' => $method_image
        ]);
    }

    /**
     * Approve the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function approve(Request $request, $trx)
    {
        $this->authorize('admin.deposits.approve');

        $deposit = $this->deposits->getModel()->with(['method'])->where('trx', $trx)->firstOrFail();

        if ($deposit->status) {
            return $this->errorResponse(__('Deposit request already completed.'));
        }

        $deposit->status = 1;
        $deposit->save();

        $newDeposit = $deposit->fresh();

        DepositApproved::dispatch($newDeposit);

        return $this->successResponse(__('Successfully approved the deposit request.'), [
            'redirectTo' => route('admin.deposits.show', $newDeposit)
        ]);
    }

    /**
     * Reject the specified resource in storage.
     * @param int $id
     * @return Renderable
     */
    public function reject(Request $request, $trx)
    {
        $this->authorize('admin.deposits.reject');
        $deposit = $this->deposits->getModel()->with(['method'])->where('trx', $trx)->firstOrFail();

        if ($deposit->status) {
            return $this->errorResponse(__('Cannot reject a completed deposit request.'));
        }

        $deposit->rejected_at = now();
        $deposit->reject_reason = $request->get('reason');
        $deposit->save();

        $newDeposit = $deposit->fresh();
        DepositRejected::dispatch($newDeposit);

        return $this->successResponse(__('Successfully rejected the deposit request.'), [
            'redirectTo' => route('admin.deposits.show', $newDeposit)
        ]);
    }
}
