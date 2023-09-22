<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Carts\Events\PromoCodeUserAdded;
use Modules\Carts\Events\PromoCodeUserRemoved;
use Modules\Carts\Repositories\PromoCodesRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class PromoCodeUserController extends BaseController
{
    /**
     * The promo code repository instance
     *
     * @var PromoCodesRepository
     */
    protected $promoCodes;


    public function __construct(PromoCodesRepository $promoCodes)
    {
        parent::__construct();

        $this->promoCodes = $promoCodes;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store(Request $request, $promoCodeId)
    {
        $this->authorize('admin.coupons.promo-codes.user-store');

        $promoCode = $this->promoCodes->findOrFail($promoCodeId);

        foreach ($request->users as $userId) {
            $user = User::findOrFail($userId);
            if (!$promoCode->hasUser($user->id)) {
                $promoCode->users()->attach($user->id);
                PromoCodeUserAdded::dispatch($promoCode, $user);
            }
        }

        return $this->successResponse(__('User added to promo code applicable users'), [
            'redirectTo' => route('admin.coupons.promo-codes.show', [$promoCode])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($promoCodeId, $userId)
    {
        $this->authorize('admin.coupons.promo-codes.user-destroy');

        $promoCode = $this->promoCodes->findOrFail($promoCodeId);
        $user = User::findOrFail($userId);
        $promoCode->users()->detach([$user->id]);
        PromoCodeUserRemoved::dispatch($promoCode, $user);

        return $this->successResponse(__('User removed from promo code applicable users'), [
            'redirectTo' => route('admin.coupons.promo-codes.show', [$promoCode])
        ]);
    }
}