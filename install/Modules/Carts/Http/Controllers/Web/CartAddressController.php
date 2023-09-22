<?php

namespace Modules\Carts\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Http\Requests\StoreCartAddressRequest;

class CartAddressController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreCartAddressRequest $request
     * @return Renderable
     */
    public function store(StoreCartAddressRequest $request)
    {
        auth()->user()->addresses()->create($request->only('country_id', 'name', 'description', 'address_1', 'address_2', 'city', 'state', 'zip_code'));

        return $this->successResponse(__('Address added'), ['location' => route(url()->previous())]);
    }
}