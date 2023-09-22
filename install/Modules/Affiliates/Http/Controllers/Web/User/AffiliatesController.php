<?php

namespace Modules\Affiliates\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Affiliates\Events\AffiliatesCreated;
use Modules\Affiliates\Events\AffiliatesUpdated;
use Modules\Affiliates\Services\PendingCommissions;
use Modules\Affiliates\Services\ApprovedCommissions;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\Traits\AffiliateConversionRate;
use Modules\Affiliates\Repositories\AffiliatesRepository;
use Modules\Affiliates\Http\Requests\UserStoreAffiliateRequest;
use Modules\Affiliates\Http\Requests\UserUpdateAffiliateRequest;

class AffiliatesController extends BaseController
{
    use AffiliateConversionRate;

    /**
     * @var AffiliatesRepository $affiliates
     */
    protected $affiliates;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/user/affiliates';

    /**
     * @param AffiliatesRepository $affiliates
     *
     * @return void
     */
    public function __construct(
        AffiliatesRepository $affiliates
    ) {
        $this->affiliates = $affiliates;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $this->authorize('user.affiliates.index');

        $affiliate = $request->user()->affiliate;

        return view('affiliates::user.index', [
            'pageTitle' => __('Account affiliates'),
            'policies' => JsPolicy::get('affiliates'),
            'affiliate' => $affiliate,
            'hasWithdrawables' => $affiliate ? $affiliate->hasWithdrawables() : false,
            'total_clicks' => $affiliate->total_clicks ?? 0,
            'registered_count' => $affiliate ? $affiliate->referrals()->count() : 0,
            'levels' => $this->getLevels($affiliate),
            'conversion_rate' => $this->getAffiliateConversionRate($affiliate),
            'pendingWithdrawables' => (new PendingCommissions)->get($affiliate),
            'approvedWithdrawables' => (new ApprovedCommissions)->get($affiliate),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param UserStoreAffiliateRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreAffiliateRequest $request)
    {
        $model = $this->affiliates->create($request->only('user_id', 'code', 'approved', 'active', 'allow_registration_commission', 'allow_deposit_commission', 'allow_subscription_commission'));

        event(new AffiliatesCreated($model));

        return $this->handleAjaxRedirectResponse(
            __('Affiliate user request created successfully.'),
            $this->redirectTo
        );
    }

    /**
     * Update the specified resource in storage.
     * @param UserUpdateAffiliateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UserUpdateAffiliateRequest $request, $id)
    {
        $model = $this->affiliates->findOrFail($id);

        $this->affiliates
            ->update(
                $model,
                $request->only('user_id')
            );

        event(new AffiliatesUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Affiliate updated successfully.'),
            $this->redirectTo
        );
    }

    protected function getLevels($affiliate)
    {
        if (!$affiliate) {
            return 1;
        }

        return $affiliate->referralLevels()->max('level') ?? 1;
    }
}
