<?php

namespace Modules\Wallet\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Wallet\Events\ManualGatewayCreated;
use Modules\Wallet\Events\ManualGatewayDeleted;
use Modules\Wallet\Events\ManualGatewayEnabled;
use Modules\Wallet\Events\ManualGatewayUpdated;
use Modules\Wallet\Events\ManualGatewayDisabled;
use Modules\Base\Http\Controllers\Web\BaseController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\Wallet\Repositories\ManualGatewaysRepository;
use Modules\Wallet\Http\Requests\StoreModuleGatewayRequest;
use Modules\Wallet\Http\Requests\UpdateManualGatewayRequest;

class ManualGatewayController extends BaseController
{
    /**
     * @var WalletsRepManualGatewaysRepositoryository
     */
    public $manualGateways;

    public function __construct(
        ManualGatewaysRepository $manualGateways
    ) {
        parent::__construct();
        $this->manualGateways = $manualGateways;
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreModuleGatewayRequest $request
     * @return Renderable
     */
    public function store(StoreModuleGatewayRequest $request)
    {
        $model = $this->manualGateways->create($request->only([
            'type',
            'name',
            'min_limit',
            'max_limit',
            'delay',
            'fixed_charge',
            'percent_charge',
            'currency',
            'user_data',
            'instructions',
            'status',
            'media_id'
        ]));

        event(new ManualGatewayCreated($model));

        return $this->handleAjaxRedirectResponse(
            __('Manual gateway created successfully.'),
            $request->get('redirectTo')
        );
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateManualGatewayRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateManualGatewayRequest $request, $id)
    {
        $model = $this->manualGateways->findOrFail($id);

        if (
            $model->currency !== $request->get('currency')
            && ($model->deposits()->count() || $model->withdrawals()->count())
        ) {
            return $this->errorResponse(__("You cant't change currency for this method."));
        }

        $this->manualGateways
            ->update(
                $model,
                $request->only([
                    'type',
                    'name',
                    'min_limit',
                    'max_limit',
                    'delay',
                    'fixed_charge',
                    'percent_charge',
                    'currency',
                    'user_data',
                    'instructions',
                    'media_id'
                ])
            );

        event(new ManualGatewayUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Manual gateway updated successfully.'),
            $request->get('redirectTo')
        );
    }

    /**
     * Enable the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function enable(Request $request)
    {
        $this->authorize('admin.manual-gateways.enable');

        foreach ($request->resources as $id) {
            $manualGateway = $this->manualGateways->find($id);
            if ($manualGateway) {
                $this->manualGateways->enable($manualGateway);
                ManualGatewayEnabled::dispatch($manualGateway);
            }
        }

        return $this->successResponse(__('Manual gateway enabled.'));
    }

    /**
     * Disable the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function disable(Request $request)
    {
        $this->authorize('admin.manual-gateways.disable');

        foreach ($request->resources as $id) {
            $manualGateway = $this->manualGateways->find($id);
            if ($manualGateway) {
                $this->manualGateways->disable($manualGateway);
                ManualGatewayDisabled::dispatch($manualGateway);
            }
        }

        return $this->successResponse(__('Manual gateway disabled.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.manual-gateways.destroy');

        foreach ($request->resources as $id) {
            $manualGateway = $this->manualGateways->find($id);
            if ($manualGateway->deposits()->count() || $manualGateway->withdrawals()->count()) {
                return $this->errorResponse(__("You cant't delete this method. Method has transactions."));
            }

            if ($manualGateway) {
                $this->manualGateways->delete($manualGateway);
                ManualGatewayDeleted::dispatch($manualGateway);
            }
        }

        return $this->successResponse(__('Manual gateway deleted.'));
    }

    /**
     * Handle on removing media
     *
     * @param Request $request
     *
     * @return void
     */
    public function removeMedia(Request $request)
    {
        $this->authorize('admin.wearables.remove-media');

        $media = Media::where('uuid', $request->get('uid'))->first();

        $media->delete();

        return $this->successResponse(__('Media deleted successfully.'));
    }
}
