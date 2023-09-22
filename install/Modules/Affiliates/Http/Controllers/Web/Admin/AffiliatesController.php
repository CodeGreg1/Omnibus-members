<?php

namespace Modules\Affiliates\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Affiliates\Events\AffiliateApproved;
use Modules\Affiliates\Events\AffiliateRejected;
use Modules\Affiliates\Events\AffiliatesUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\Repositories\AffiliatesRepository;

class AffiliatesController extends BaseController
{
    /**
     * @var AffiliatesRepository $affiliates
     */
    protected $affiliates;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/affiliates/users';

    /**
     * @param AffiliatesRepository $affiliates
     *
     * @return void
     */
    public function __construct(AffiliatesRepository $affiliates)
    {
        $this->affiliates = $affiliates;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.affiliates.users.index');

        return view('affiliates::admin.users.index', [
            'pageTitle' => __('Affiliate users'),
            'policies' => JsPolicy::get('affiliates')
        ]);
    }

    /**
     * Approve affiliate user request.
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function approve($id, Request $request)
    {
        $this->authorize('admin.affiliates.users.approve');

        $model = $this->affiliates->findOrFail($id);

        $this->affiliates
            ->update(
                $model,
                [
                    'rejected_at' => null,
                    'rejected_reason' => null,
                    'approved' => 1,
                    'active' => 1
                ]
            );

        event(new AffiliateApproved($model->fresh()));

        return $this->successResponse(__('Affiliate request successfully approved.'));
    }

    /**
     * Reject affiliate user request.
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function reject($id, Request $request)
    {
        $this->authorize('admin.affiliates.users.reject');

        $model = $this->affiliates->findOrFail($id);

        $this->affiliates
            ->update(
                $model,
                [
                    'approved' => 0,
                    'active' => 0,
                    'rejected_at' => now(),
                    'rejected_reason' => $request->get('reason')
                ]
            );

        event(new AffiliateRejected($model->fresh()));

        return $this->successResponse(__('Affiliate request rejected.'));
    }

    /**
     * Enable affiliate user request.
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function enable($id, Request $request)
    {
        $this->authorize('admin.affiliates.users.enable');

        $model = $this->affiliates->findOrFail($id);

        $this->affiliates
            ->update(
                $model,
                [
                    'active' => 1
                ]
            );

        event(new AffiliatesUpdated($model->fresh()));

        return $this->successResponse(__('Affiliate membership enabled.'));
    }

    /**
     * Disable affiliate user request.
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function disable($id, Request $request)
    {
        $this->authorize('admin.affiliates.users.disable');

        $model = $this->affiliates->findOrFail($id);

        $this->affiliates
            ->update(
                $model,
                [
                    'active' => 0
                ]
            );

        event(new AffiliatesUpdated($model->fresh()));

        return $this->successResponse(__('Affiliate membership disabled.'));
    }
}
