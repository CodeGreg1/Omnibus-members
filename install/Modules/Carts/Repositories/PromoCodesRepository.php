<?php

namespace Modules\Carts\Repositories;

use Modules\Carts\Models\PromoCode;
use Modules\Base\Repositories\BaseRepository;

class PromoCodesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = PromoCode::class;

    public function activate($entity)
    {
        $entity->active = 1;
        $entity->save();

        return $entity->fresh();
    }

    public function archive($entity)
    {
        $entity->active = 0;
        $entity->save();

        return $entity->fresh();
    }

    /**
     * Find a specific active resource with its specific related models
     *
     * @param string $code
     * @return PaymentGateway
     */
    public function getFirstActiveById($id, $price = null)
    {
        $this->newQuery();
        $model = $this->query
            ->where([
                'id' => $id,
                'active' => 1
            ])
            ->with(['coupon'])
            ->first();

        return $this->valid($model, $price);
    }

    /**
     * Find a specific active resource with its specific related models
     *
     * @param string $code
     * @return PaymentGateway
     */
    public function getFirstActive($code, $price = null)
    {
        $this->newQuery();
        $model = $this->query
            ->where([
                'code' => $code,
                'active' => 1
            ])
            ->with(['coupon'])
            ->first();

        return $this->valid($model, $price);
    }

    protected function valid($model, $price = null)
    {
        if (!$model) {
            return null;
        }

        if ($model->users()->count()) {
            if (!$model->hasUser(auth()->user()->id)) {
                return null;
            }
        }

        if ($model->coupon->packages()->count() && $price) {
            if (!$model->coupon->hasPackage($price->coupon_id)) {
                return null;
            }
        }

        $timesRedeemed = $model->subscriptions()->count();
        if ($model->max_redemptions && ($timesRedeemed >= $model->max_redemptions)) {
            return null;
        }

        if ($model->expires_at && (now()->timestamp >= $model->expires_at)) {
            return null;
        }

        $timesRedeemed = $model->coupon->promoCodes()->get()->sum(function ($promoCode) {
            return $promoCode->subscriptions()->count();
        }, 0);
        if ($model->coupon->redeem_limit_count && ($timesRedeemed >= $model->coupon->redeem_limit_count)) {
            return null;
        }

        if ($model->coupon->redeem_date_end && (now()->timestamp >= $model->coupon->redeem_date_end)) {
            return null;
        }

        return $model;
    }

    public function checkValidity($code, $price = null)
    {
        $this->newQuery();
        $model = $this->query
            ->where([
                'code' => $code,
                'active' => 1
            ])
            ->with(['coupon'])
            ->first();

        if (!$model) {
            return [
                'error' => true,
                'message' => __('Promotional code not found.')
            ];
        }

        if ($model->users()->count()) {
            if (!$model->hasUser(auth()->user()->id)) {
                return [
                    'error' => true,
                    'message' => __('The promotional code is not applicable to the current user.')
                ];
            }
        }

        // if ($model->coupon->packages()->count() && $price) {
        //     if (!$model->coupon->hasPackage($price->coupon_id)) {
        //         return [
        //             'error' => true,
        //             'message' => __('The promotional code is not applicable to the selected plan.')
        //         ];
        //     }
        // }

        $timesRedeemed = $model->subscriptions()->count();
        if ($model->max_redemptions && ($timesRedeemed >= $model->max_redemptions)) {
            return [
                'error' => true,
                'message' => __('Promotional code reach redemption limit of ' . $model->max_redemptions . __(' redemptions'))
            ];
        }

        if ($model->expires_at && (now()->timestamp >= $model->expires_at)) {
            return [
                'error' => true,
                'message' => __('Promotional code is expired.')
            ];
        }

        $timesRedeemed = $model->coupon->promoCodes()->get()->sum(function ($promoCode) {
            return $promoCode->subscriptions()->count();
        }, 0);
        if ($model->coupon->redeem_limit_count && ($timesRedeemed >= $model->coupon->redeem_limit_count)) {
            return [
                'error' => true,
                'message' => __('The coupon has already reached the redemption limit.')
            ];
        }

        if ($model->coupon->redeem_date_end && (now()->timestamp >= $model->coupon->redeem_date_end)) {
            return [
                'error' => true,
                'message' => __('The coupon has already expired.')
            ];
        }

        return [
            'coupon' => $model
        ];
    }
}