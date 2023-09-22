<?php

namespace Modules\Carts\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Carts\Services\CheckoutSession;
use Illuminate\Contracts\Support\Renderable;
use Modules\Carts\Repositories\PromoCodesRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Http\Requests\ValidCheckoutPromoCodeRequest;

class CheckoutCouponController extends BaseController
{
    /**
     * @var PromoCodesRepository
     */
    public $promoCodes;

    public function __construct(PromoCodesRepository $promoCodes)
    {
        parent::__construct();

        $this->promoCodes = $promoCodes;
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $checkoutId
     * @param ValidCheckoutPromoCodeRequest $request
     * @return Renderable
     */
    public function validate(ValidCheckoutPromoCodeRequest $request, $checkoutId)
    {
        $checkout = CheckoutSession::retrieve($checkoutId);
        $result = $this->promoCodes->checkValidity($request->checkout_coupon);

        if (isset($result['error'])) {
            return $this->errorResponse($result['message']);
        }

        CheckoutSession::update($checkout, [
            'promo_code_id' => $result['coupon']->id
        ]);

        $data = [];

        if ($request->has('breakdown')) {
            $data['breakdown'] = CheckoutSession::breakdown($checkout->fresh());
        }

        return $this->successResponse(__('Coupon code applied'), $data);
    }
}