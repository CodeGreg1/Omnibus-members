<?php

namespace Modules\Carts\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Facades\Cart;

class CartDatatableController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle()
    {
        $items = Cart::items();

        $items = $items->map(function ($item) {
            return (object) [
                'id' => $item->cart_item_id,
                'name' => $item->name,
                'description' => $item->purchasable->cartItemDescription(),
                'image' => $item->purchasable->cartImage(),
                'price' => $item->getUnitPrice(),
                'quantity' => $item->quantity,
                'stock' => $item->purchasable->cartStock(),
                'isStockable' => $item->purchasable->isStockable(),
                'total' => $item->getTotalPrice(),
                'purchasable_path' => $item->purchasable->purchasable_path
            ];
        });

        return datatables($items)->toJson();
    }
}