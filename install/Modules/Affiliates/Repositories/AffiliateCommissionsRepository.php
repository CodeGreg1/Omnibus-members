<?php

namespace Modules\Affiliates\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Affiliates\Models\AffiliateCommission;
use Modules\Affiliates\States\CommissionStatus\Completed;

class AffiliateCommissionsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = AffiliateCommission::class;

    /**
     * Withdraw commission
     */
    public function withdraw($entity)
    {
        $wallet = $entity->affiliate->user->addWallet($entity->currency, $entity->amount);
        $entity->status->transitionTo(Completed::class);

        $description = 'Added ' . currency_format($entity->amount, $entity->currency) . ' via Deposit commission';
        $wallet->transactions()->create([
            'amount' => $entity->amount,
            'description' => $description,
            'user_id' => $wallet->user->id,
            'currency' => $entity->currency,
            'initial_balance' => $wallet->balance - $entity->amount
        ]);

        return $entity->fresh();
    }

    public function getAllWithdrawables($user)
    {
        return $this->getModel()->wherehas('affiliate', function ($query) use ($user) {
            return $query->where('user_id', $user->id);
        })->where('status', 'pending')
            ->where('approve_on_end', '<=', now())->get();
    }
}
