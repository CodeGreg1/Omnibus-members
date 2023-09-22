<?php

namespace Modules\Affiliates\Http\Controllers\Web\User;

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
    public function index()
    {   
        $this->authorize('user.affiliates.datatable');
        
        $query = $this->affiliates->getModel()->query()->with(['user']);

        if(request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

        return DataTables::eloquent($query)
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
