<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\FeaturesRepository;
use Yajra\DataTables\Facades\DataTables;

class FeatureDatatableController extends BaseController
{
    /**
     * The package feature instance.
     *
     * @var FeaturesRepository
     */
    protected $features;

    public function __construct(FeaturesRepository $features)
    {
        parent::__construct();

        $this->features = $features;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle()
    {
        $this->authorize('admin.subscriptions.packages.features.datatable');

        return DataTables::eloquent($this->features->getModel()->query())
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->toJson();
    }
}