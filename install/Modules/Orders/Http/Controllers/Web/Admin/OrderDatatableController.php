<?php

namespace Modules\Orders\Http\Controllers\Web\Admin;

use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Orders\Repositories\OrdersRepository;
use Modules\Orders\States\Tracking\Cancelled;
use Modules\Orders\States\Tracking\Delivered;
use Modules\Orders\States\Tracking\InTransit;
use Modules\Orders\States\Tracking\Pending;
use Modules\Orders\States\Tracking\Returned;
use Yajra\DataTables\Facades\DataTables;

class OrderDatatableController extends BaseController
{
    /**
     * @var OrdersRepository
     */
    protected $orders;

    public function __construct(OrdersRepository $orders)
    {
        parent::__construct();

        $this->orders = $orders;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle()
    {
        // $this->authorize('admin.orders.datatable');

        return DataTables::eloquent(
            $this->orders->getModel()
                ->withCount(['items'])
        )
            ->addColumn('date', function ($row) {
                $timezone = auth()->user() ? auth()->user()->timezone : 'UTC';
                $format = auth()->user() ? auth()->user()->date_format : 'Y m, d';

                return Carbon::parse(
                    Timezone::convertFromUTC(
                        $row->created_at,
                        $timezone
                    )
                )->format($format);
            })
            ->addColumn('amount', function ($row) {
                return currency($row->total_price, $row->currency, Currency::getUserCurrency());
            })
            ->addColumn('tracking_status_color', function ($row) {
                return $row->status->color();
            })
            ->addColumn('tracking_transitions', function ($row) {
                $states = [];
                if ($row->status->canTransitionTo(InTransit::class)) {
                    $states[] = 'in_transit';
                }
                if ($row->status->canTransitionTo(Cancelled::class)) {
                    $states[] = 'cancelled';
                }
                if ($row->status->canTransitionTo(Delivered::class)) {
                    $states[] = 'delivered';
                }
                if ($row->status->canTransitionTo(Pending::class)) {
                    $states[] = 'pending';
                }
                if ($row->status->canTransitionTo(Returned::class)) {
                    $states[] = 'returned';
                }

                return $states;
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