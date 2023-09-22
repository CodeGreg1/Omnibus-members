<?php

namespace Modules\Users\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\ActivityRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class UserActivitiesDatatableController extends BaseController
{
    /**
     * @var ActivityRepository
     */
    protected $activities;
    
    /**
     * @param ActivityRepository $activities
     */
    public function __construct(ActivityRepository $activities) 
    {
        $this->activities = $activities;

        parent::__construct();
    }

    /**
     * Display activities list
     * 
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('user.activities.index');
           
        $query = $this->activities
            ->getModel()
            ->query()
            ->where('causer_id', auth()->id())
            ->with([
                'causer'
            ]);

        return DataTables::eloquent($query)
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->toJson();
    }
}
