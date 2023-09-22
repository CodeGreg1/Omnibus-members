<?php

namespace Modules\Products\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Products\Repositories\ProductsRepository;

class ProductsDatatableController extends BaseController
{
    /**
     * @var ProductsRepository $products
     */
    protected $products;

    public function __construct(ProductsRepository $products)
    {
        $this->products = $products;

        parent::__construct();
    }

    /**
     * Datatable
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.products.datatable');

        $query = $this->products->getModel()->query()->with(['currency']);

        if (request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

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