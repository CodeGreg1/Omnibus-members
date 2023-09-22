<?php

namespace Modules\Carts\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Facades\Cart;

class CartNotificationItemController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $items = Cart::items(['limit' => 5]);

        $items = $items->map(function ($item) {
            return (object) [
                'id' => $item->cart_item_id,
                'name' => $item->name,
                'description' => $item->purchasable->cartItemDescription(),
                'image' => $item->purchasable->cartImage(),
                'quantity' => $item->quantity,
                'stock' => $item->purchasable->cartStock(),
                'isStockable' => $item->purchasable->isStockable(),
                'price' => $item->getUnitPrice(),
                'total' => $item->getTotalPrice(),
                'purchasablePath' => $item->purchasable->showPath()
            ];
        });

        return [
            'count' => Cart::count(),
            'items' => $items
        ];
    }
}