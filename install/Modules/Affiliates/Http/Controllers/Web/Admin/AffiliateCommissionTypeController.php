<?php

namespace Modules\Affiliates\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Affiliates\Events\AffiliateCommissionTypeUpdated;
use Modules\Affiliates\Repositories\CommissionTypesRepository;
use Modules\Affiliates\Http\Requests\UpdateCommissionTypeRequest;

class AffiliateCommissionTypeController extends BaseController
{
    /**
     * @var CommissionTypesRepository $commissionTypes
     */
    protected $commissionTypes;

    /**
     * @param CommissionTypesRepository $commissionTypes
     *
     * @return void
     */
    public function __construct(CommissionTypesRepository $commissionTypes)
    {
        parent::__construct();

        $this->commissionTypes = $commissionTypes;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.affiliates.commission-types.index');

        return view('affiliates::admin.commissions.types', [
            'pageTitle' => __('Commission types'),
            'policies' => JsPolicy::get('affiliates'),
            'commission_types' => commission_type()->getAll()
        ]);
    }

    /**
     * Update affiliate commission type.
     * @param int $id
     * @param UpdateCommissionTypeRequest $request
     * @return JsonResponse
     */
    public function update($id, UpdateCommissionTypeRequest $request)
    {
        $this->authorize('admin.affiliates.commission-types.update');

        $model = $this->commissionTypes->findOrFail($id);

        $this->commissionTypes
            ->update(
                $model,
                $request->only('active', 'levels', 'conditions')
            );

        event(new AffiliateCommissionTypeUpdated($model->fresh()));

        return $this->handleAjaxRedirectResponse(__('Affiliate commission type updated.'), route('admin.affiliates.commission-types.index'));
    }

    /**
     * Enable affiliate commission type.
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function enable($id, Request $request)
    {
        $this->authorize('admin.affiliates.commission-types.enable');

        $model = $this->commissionTypes->findOrFail($id);

        $this->commissionTypes
            ->update(
                $model,
                [
                    'active' => 1
                ]
            );

        event(new AffiliateCommissionTypeUpdated($model->fresh()));

        return $this->handleAjaxRedirectResponse(__('Affiliate commission type enabled.'), route('admin.affiliates.commission-types.index'));
    }

    /**
     * Bisable affiliate commission type.
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function disable($id, Request $request)
    {
        $this->authorize('admin.affiliates.commission-types.disable');

        $model = $this->commissionTypes->findOrFail($id);

        $this->commissionTypes
            ->update(
                $model,
                [
                    'active' => 0
                ]
            );

        event(new AffiliateCommissionTypeUpdated($model->fresh()));

        return $this->handleAjaxRedirectResponse(__('Affiliate commission type disabled.'), route('admin.affiliates.commission-types.index'));
    }
}
