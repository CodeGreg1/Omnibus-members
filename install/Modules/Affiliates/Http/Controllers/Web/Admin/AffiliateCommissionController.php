<?php

namespace Modules\Affiliates\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\States\CommissionStatus\Rejected;
use Modules\Affiliates\Events\AffiliateCommissionApproved;
use Modules\Affiliates\Events\AffiliateCommissionRejected;
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
        $this->authorize('admin.affiliates.commissions.index');

        return view('affiliates::admin.commissions.index', [
            'pageTitle' => __('Affiliate commissions'),
            'policies' => JsPolicy::get('affiliates')
        ]);
    }

    /**
     * Approve affiliate commission.
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function approve($id, Request $request)
    {
        $this->authorize('admin.affiliates.commissions.approve');

        $model = $this->commissions->findOrFail($id);

        $this->commissions
            ->update(
                $model,
                [
                    'approved_at' => now(),
                    'approve_on_end' => now()
                ]
            );

        event(new AffiliateCommissionApproved($model->fresh()));

        return $this->successResponse(__('Affiliate commission successfully approved.'));
    }

    /**
     * Reject affiliate commission.
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function reject($id, Request $request)
    {
        $this->authorize('admin.affiliates.commissions.reject');

        $model = $this->commissions->findOrFail($id);

        if ($model->status->toArray() === 'completed') {
            return $this->errorResponse(__('Affiliate commission already completed.'));
        }

        $model->status->transitionTo(Rejected::class);

        $this->commissions
            ->update(
                $model,
                [
                    'rejected_at' => now(),
                    'rejected_reason' => $request->get('reason')
                ]
            );

        event(new AffiliateCommissionRejected($model->fresh()));

        return $this->successResponse(__('Affiliate commission rejected.'));
    }
}
