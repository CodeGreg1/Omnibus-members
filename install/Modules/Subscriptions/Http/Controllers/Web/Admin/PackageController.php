<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Illuminate\Contracts\Support\Renderable;
use Modules\Roles\Support\ExtractModuleName;
use Modules\Roles\Support\ExcludedPermissions;
use Modules\Subscriptions\Events\PackageDeleted;
use Modules\Subscriptions\Events\PackageUpdated;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Subscriptions\Support\PermissionValues;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Events\PackageFeaturesUpdated;
use Modules\Subscriptions\Events\PackageFeaturesReordered;
use Modules\Subscriptions\Repositories\FeaturesRepository;
use Modules\Subscriptions\Repositories\PackagesRepository;
use Modules\Subscriptions\Events\PackageModuleLimitsUpdated;
use Modules\Subscriptions\Http\Requests\StorePackageRequest;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Modules\Subscriptions\Http\Requests\UpdatePackageRequest;
use Modules\Subscriptions\Repositories\PackageTermsRepository;
use Modules\Subscriptions\Repositories\PricingTablesRepository;
use Modules\Subscriptions\Http\Requests\UpdatePackageFeaturesRequest;
use Modules\Subscriptions\Repositories\PackageModuleLimitsRepository;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class PackageController extends BaseController
{
    use PermissionValues;

    /**
     * @var PackagesRepository
     */
    protected $packages;

    /**
     * @var AvailableCurrenciesRepository
     */
    protected $currencies;

    /**
     * @var PackageTermsRepository
     */
    protected $terms;

    /**
     * @var FeaturesRepository
     */
    protected $features;

    /**
     * @var PackageModuleLimitsRepository
     */
    protected $packageModuleLimits;

    /**
     * @var PricingTablesRepository
     */
    protected $pricingTables;

    public function __construct(
        PackagesRepository $packages,
        AvailableCurrenciesRepository $currencies,
        PackageTermsRepository $terms,
        FeaturesRepository $features,
        PackageModuleLimitsRepository $packageModuleLimits,
        PricingTablesRepository $pricingTables
    ) {
        parent::__construct();

        $this->packages = $packages;
        $this->currencies = $currencies;
        $this->terms = $terms;
        $this->features = $features;
        $this->packageModuleLimits = $packageModuleLimits;
        $this->pricingTables = $pricingTables;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.subscriptions.packages.index');

        return view('subscriptions::admin.package.index', [
            'pageTitle' => __('Packages'),
            'hasActiveGateways' => !!count(Cashier::getActiveClients()),
            'hasPricingTable' => $this->pricingTables->count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Renderable
     */
    public function create(Request $request)
    {
        $this->authorize('admin.subscriptions.packages.create');

        if (!Cashier::hasValidCilents()) {
            return redirect(route('admin.subscriptions.settings.payment-gateway'));
        }

        $permissions = $this->getValidValues(ModelsPermission::all());
        $currencies = $this->currencies->getActive();

        return view('subscriptions::admin.package.create', [
            'pageTitle' => __('New Package'),
            'modulePermissions' => $this->handleParsingPermissionValues($permissions),
            'terms' => $this->terms->all(),
            'features' => $this->features->orderBy('ordering', 'asc')->all(),
            'currency' => Currency::getUserCurrency(),
            'currencies' => $currencies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StorePackageRequest $request
     * @return Renderable
     */
    public function store(StorePackageRequest $request)
    {
        $this->packages->store($request->only(
            'name',
            'primary_heading',
            'secondary_heading',
            'prices',
            'features',
            'limits',
            'extra_conditions'
        ));

        return $this->successResponse(__('Package created'), [
            'redirectTo' => route('admin.subscriptions.packages.index')
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $this->authorize('admin.subscriptions.packages.show');

        $package = $this->packages->with(['features', 'moduleLimits'])->findOrFail($id);
        $permissions = $this->getValidValues(ModelsPermission::all());
        $availableFeatures = $this->features->getModel()
            ->whereNotIn('id', $package->features->pluck('id'))
            ->get();
        $currencies = $this->currencies->getActive();

        return view('subscriptions::admin.package.show', [
            'pageTitle' => $package->name,
            'package' => $package,
            'terms' => $this->terms->all(),
            'features' => $availableFeatures,
            'packageFeatures' => $package->features,
            'currency' => Currency::getUserCurrency(),
            'currencies' => $currencies,
            'modulePermissions' => $this->handleParsingPermissionValues($permissions),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdatePackageRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdatePackageRequest $request, $id)
    {
        $package = $this->packages->findOrFail($id);

        $this->packages->update(
            $package,
            $request->only('name', 'primary_heading', 'secondary_heading')
        );

        PackageUpdated::dispatch($package->fresh());

        return $this->successResponse(__('Package updated'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.subscriptions.packages.delete');
        $packages = $request->packages;
        if (is_string($packages)) {
            $packages = explode(",", $packages);
        }

        $unsuccessfull = [];
        foreach ($packages as $id) {
            $package = $this->packages->findOrFail($id);
            if (!$package->hasSubscription()) {
                $this->packages->destroy(
                    $package
                );

                PackageDeleted::dispatch($package);
            } else {
                $unsuccessfull[] = $package->name;
            }
        }

        if (count($unsuccessfull)) {
            return $this->errorResponse('Package has subscriptions. Unable to remove');
        }

        return $this->successResponse(__('Packages deleted'), [
            'redirectTo' => route('admin.subscriptions.packages.index')
        ]);
    }

    public function updateFeatures($id, UpdatePackageFeaturesRequest $request)
    {
        $package = $this->packages->findOrFail($id);

        $package->features()->sync($request->features);

        PackageFeaturesUpdated::dispatch($package);

        return $this->successResponse(__('Package features updated'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }

    public function removeFeature($packageId, $featureId)
    {
        $this->authorize('admin.subscriptions.packages.delete-feature');

        $package = $this->packages->findOrFail($packageId);

        $package->features()->detach($featureId);

        PackageFeaturesUpdated::dispatch($package);

        return $this->successResponse(__('Package feature removed'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }

    public function reorderFeatures($id, Request $request)
    {
        $this->authorize('admin.subscriptions.packages.reorder-feature');

        $package = $this->packages->findOrFail($id);

        foreach ($request->ordering as $key => $value) {
            $package->features()->updateExistingPivot($value['id'], [
                'order' => $key + 1
            ]);
        }

        PackageFeaturesReordered::dispatch($package);

        return $this->successResponse(__('Package features reordered'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }

    public function updateModuleLimits($id, Request $request)
    {
        $this->authorize('admin.subscriptions.packages.update-module-limits');

        $package = $this->packages->findOrFail($id);

        $this->packageModuleLimits->deleteAllItems($package, $request->limits);

        collect($request->limits)->map(function ($item) use ($package) {
            $item['package_id'] = $package->id;
            $this->packageModuleLimits->updateOrCreateResource($package, $item);
        });

        PackageModuleLimitsUpdated::dispatch($package);

        return $this->successResponse(__('Package module limits updated'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }

    protected function handleParsingPermissionValues($permissions)
    {
        $resultPermissions = [];
        foreach ($permissions as $permission) {
            $module = explode('.', $permission->name);

            $moduleKey = (new ExtractModuleName($module))->get();

            if (!in_array($permission->name, ExcludedPermissions::lists())) {
                $action = Arr::last($module);
                $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
                if ($action !== 'datatable') {
                    if ($action === 'store') {
                        if (!$permissions->where('name', $parateRoute . '.create')) {
                            $resultPermissions[$moduleKey][] = $permission;
                        }
                    } else if ($action === 'update') {
                        if (!$permissions->where('name', $parateRoute . '.edit')) {
                            $resultPermissions[$moduleKey][] = $permission;
                        }
                    } else if ($action === 'multi-delete') {
                        if (!$permissions->where('name', $parateRoute . '.delete')) {
                            $resultPermissions[$moduleKey][] = $permission;
                        }
                    } else {
                        $resultPermissions[$moduleKey][] = $permission;
                    }
                } else {
                    if (!$permissions->where('name', $parateRoute . '.index')) {
                        $resultPermissions[$moduleKey][] = $permission;
                    }
                }
            }
        }

        $modulePermissions = [];
        foreach ($resultPermissions as $modulePermission => $permissions) {
            $moduleName = Str::kebab($modulePermission);
            $moduleName = Str::ucfirst(str_replace('-', ' ', $moduleName));
            $modulePermissions[] = [
                'key' => $modulePermission,
                'title' => $moduleName,
                'permissions' => $permissions
            ];
        }

        return $modulePermissions;
    }
}