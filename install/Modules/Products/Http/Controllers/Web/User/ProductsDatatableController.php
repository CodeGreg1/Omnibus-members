<?php

namespace Modules\Products\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Helpers\Helpers;
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
        // $this->authorize('user.products.datatable');

        $query = $this->products->getModel()->query();

        return DataTables::eloquent($query)
            ->addColumn('image', function ($row) {
                if (count($row->media)) {
                    return $row->media[0]->preview_url;
                }
                return '/themes/stisla/assets/img/products/product-5-50.png';
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