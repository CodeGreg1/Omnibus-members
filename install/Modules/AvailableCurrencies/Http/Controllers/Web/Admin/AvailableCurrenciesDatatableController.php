<?php

namespace Modules\AvailableCurrencies\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class AvailableCurrenciesDatatableController extends BaseController
{   
    /**
     * @var AvailableCurrenciesRepository $availableCurrencies
     */
    protected $availableCurrencies;

    public function __construct(AvailableCurrenciesRepository $availableCurrencies) 
    {
        $this->availableCurrencies = $availableCurrencies;

        parent::__construct();
    }

    /**
     * Datatable
     * 
     * @return JsonResponse
     */
    public function index()
    {   
        $this->authorize('admin.available-currencies.datatable');
        
        $query = $this->availableCurrencies->getModel()->query()->with(['currency']);

        if(request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

        if(request()->has('system_currency')) {
            if(request('system_currency') == 1) {
                $query = $query->where('code', setting(SETTING_CURRENCY_KEY));
            } else {
                $query = $query->whereNot('code', setting(SETTING_CURRENCY_KEY));
            }
        }

        if(request()->has('currency_status')) {
            $query = $query->where('status', request('currency_status'));
        }

        return DataTables::eloquent($query)
            ->addColumn('exchange_rate', function ($row) {
                return floatval($row->exchange_rate);
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
