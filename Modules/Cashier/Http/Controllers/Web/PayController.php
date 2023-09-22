<?php

namespace Modules\Cashier\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Cashier\Exceptions\InvalidCashierPayloadException;

class PayController extends BaseController
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
        $payload = session('cashier_pay');

        if (!$payload) {
            throw InvalidCashierPayloadException::notFoundPayload();
        }
        $view = 'cashier::checkout.' . $payload['view'];
        if (view()->exists($view)) {
            return view($view, $payload['attributes']);
        }

        throw InvalidCashierPayloadException::viewNotFound($view);
    }
}