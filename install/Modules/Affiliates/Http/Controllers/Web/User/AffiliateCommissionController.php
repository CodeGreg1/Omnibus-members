<?php

namespace Modules\Affiliates\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\States\CommissionStatus\Completed;
use Modules\Affiliates\Events\AffiliateCommissionCompleted;
use Modules\Affiliates\Repositories\AffiliateCommissionsRepository;

class AffiliateCommissionController extends BaseController
{
    /**
     * @var AffiliateCommissionsRepository $commissions
     */
    protected $commissions;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/affiliates/users';

    /**
     * @param AffiliateCommissionsRepository $commissions
     *
     * @return void
     */
    public function __construct(AffiliateCommissionsRepository $commissions)
    {
        $this->commissions = $commissions;

        parent::__construct();
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('user.commissions.index');

        return view('affiliates::user/commission.index', [
            'pageTitle' => __('Commissions'),
            'policies' => JsPolicy::get('affiliates')
        ]);
    }

    /**
     * Withdraw commission
     * @return Response
     */
    public function withdraw($id)
    {
        $this->authorize('user.commissions.withdraw');

        $commission = $this->commissions->findOrFail($id);
        if ($commission->status->toArray() === 'completed') {
            return $this->errorResponse(__('Commission is already completed.'));
        }

        if ($commission->status->toArray() === 'rejected') {
            return $this->errorResponse(__('Commission is rejected. Cannot process.'));
        }

        if ($commission->approve_on_end->isFuture()) {
            return $this->errorResponse(__('Commission is still in verification.'));
        }

        event(new AffiliateCommissionCompleted($this->commissions->withdraw($commission)));

        return $this->successResponse(__('Affiliate commission successfully moved to wallet.'));
    }

    public function withdrawAll(Request $request)
    {
        $this->authorize('user.commissions.withdraw-all');

        $commissions = $this->commissions->getAllWithdrawables(auth()->user());

        if (!$commissions->count()) {
            return $this->errorResponse(__('No withdrawable commissions found.'));
        }

        $commissions->map(function ($commission) {
            event(new AffiliateCommissionCompleted($this->commissions->withdraw($commission)));
        });

        return $this->successResponse(
            __('All affiliate commission successfully moved to wallet.'),
            ['redirectTo' => route('user.affiliates.index')]
        );
    }
}
