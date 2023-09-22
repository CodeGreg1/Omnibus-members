<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Events\PricingTableCreated;
use Modules\Subscriptions\Events\PricingTableDeleted;
use Modules\Subscriptions\Events\PricingTableEnabled;
use Modules\Subscriptions\Events\PricingTableUpdated;
use Modules\Subscriptions\Events\PricingTableDisabled;
use Modules\Subscriptions\Repositories\PricingTablesRepository;
use Modules\Subscriptions\Http\Requests\StorePricingTableRequest;
use Modules\Subscriptions\Http\Requests\UpdatePricingTableRequest;
use Modules\Subscriptions\Http\Requests\DestroyPricingTableRequest;

class PricingTableController extends BaseController
{
    /**
     * @var PricingTablesRepository
     */
    protected $pricingTables;

    public function __construct(PricingTablesRepository $pricingTables)
    {
        parent::__construct();

        $this->pricingTables = $pricingTables;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('subscriptions::admin.pricing-table.index', [
            'pageTitle' => __('Pricing tables')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('subscriptions::admin.pricing-table.create', [
            'pageTitle' => __('Customize your pricing table'),
            'max' => $this->pricingTables->getModel()::MAX_PER_GROUP
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StorePricingTableRequest $request
     * @return Renderable
     */
    public function store(StorePricingTableRequest $request)
    {
        $pricingTable = $this->pricingTables->new(
            $request->only('name', 'description', 'featured', 'items')
        );

        PricingTableCreated::dispatch($pricingTable);

        return $this->successResponse(__('New pricing table created'), [
            'redirectTo' => route('admin.subscriptions.pricing-tables.index')
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $pricingTable = $this->pricingTables->findOrFail($id);
        $packageList = $pricingTable->getPackageList();

        return view('subscriptions::admin.pricing-table.show', [
            'pageTitle' => $pricingTable->name,
            'pricingTable' => $pricingTable,
            'packageList' => $packageList
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.subscriptions.pricing-tables.edit');
        $pricingTable = $this->pricingTables->with(['items'])->findOrFail($id);
        $featured = $pricingTable->items->where('featured', 1)->first();
        $packages = $pricingTable->items->pluck('price.package')
            ->unique('id')->values()
            ->when($featured, function ($collection, $value) {
                return $collection->map(function ($package) use ($value) {
                    $package->featured = $value->price->package_id === $package->id;
                    return $package;
                })->unique('id')->values();
            });

        return view('subscriptions::admin.pricing-table.edit', [
            'pageTitle' => 'Edit pricing table',
            'pricingTable' => $pricingTable,
            'packages' => $packages
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdatePricingTableRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdatePricingTableRequest $request, $id)
    {
        $pricingTable = $this->pricingTables->findOrFail($id);
        $this->pricingTables->updateReource($pricingTable, $request->only('name', 'description', 'featured', 'items'));

        PricingTableUpdated::dispatch($pricingTable->fresh());

        return $this->successResponse(__('Pricing table update'), [
            'redirectTo' => route('admin.subscriptions.pricing-tables.index')
        ]);
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param Request $request
     * @return Renderable
     */
    public function enable(Request $request, $id)
    {
        $pricingTable = $this->pricingTables->find($id);
        if ($pricingTable) {
            $this->pricingTables->enable($pricingTable);
            PricingTableEnabled::dispatch($pricingTable);
        }

        return $this->successResponse(__('Pricing table enabled.'));
    }

    /**
     * Disable the specified resource from storage.
     *
     * @param Request $request
     * @return Renderable
     */
    public function disable(Request $request, $id)
    {
        $pricingTable = $this->pricingTables->find($id);
        if ($pricingTable) {
            $this->pricingTables->disable($pricingTable);
            PricingTableDisabled::dispatch($pricingTable);
        }

        return $this->successResponse(__('Pricing table disabled.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPricingTableRequest $request
     * @return Renderable
     */
    public function destroy(DestroyPricingTableRequest $request)
    {
        foreach ($request->items as $id) {
            $pricingTable = $this->pricingTables->find($id);
            if ($pricingTable) {
                $this->pricingTables->delete($pricingTable);
                PricingTableDeleted::dispatch($pricingTable);
            }
        }

        return $this->successResponse(__('Pricing table/s removed.'));
    }
}