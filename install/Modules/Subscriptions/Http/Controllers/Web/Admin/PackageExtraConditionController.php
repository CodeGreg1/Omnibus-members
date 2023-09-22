<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\PackagesRepository;
use Modules\Subscriptions\Repositories\PackageExtraConditionsRepository;
use Modules\Subscriptions\Http\Requests\StorePackageExtraConditionRequest;
use Modules\Subscriptions\Http\Requests\UpdatePackageExtraConditionRequest;

class PackageExtraConditionController extends BaseController
{
    /**
     * @var PackagesRepository
     */
    public $packages;

    /**
     * @var PackageExtraConditionsRepository
     */
    public $packageExtraConditions;

    public function __construct(
        PackagesRepository $packages,
        PackageExtraConditionsRepository $packageExtraConditions
    ) {
        parent::__construct();

        $this->packages = $packages;
        $this->packageExtraConditions = $packageExtraConditions;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function datatable($packageId)
    {
        $this->authorize('admin.subscriptions.packages.extra-conditions.datatable');

        $package = $this->packages->findOrFail($packageId);

        return DataTables::eloquent(
            $package->extraConditions()
        )
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StorePackageExtraConditionRequest $request, $id)
    {
        $package = $this->packages->findOrFail($id);

        $package->extraConditions()->create($request->only(
            'name',
            'description',
            'shortcode',
            'value'
        ));

        return $this->successResponse(__('Package extra condition created'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdatePackageExtraConditionRequest $request, $packageId, $id)
    {
        $package = $this->packages->findOrFail($packageId);

        $model = $package->extraConditions()->findOrFail($id);

        $this->packageExtraConditions->update(
            $model,
            $request->only('name', 'description', 'value')
        );

        return $this->successResponse(__('Package extra condition updated'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($packageId, $id)
    {
        $this->authorize('admin.subscriptions.packages.extra-conditions.destroy');
        $package = $this->packages->findOrFail($packageId);

        $model = $package->extraConditions()->findOrFail($id);

        $this->packageExtraConditions->delete($model);

        return $this->successResponse(__('Package extra condition deleted'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }
}
