<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Carts\Events\TaxRateCreated;
use Modules\Carts\Events\TaxRateDeleted;
use Modules\Carts\Events\TaxRateUpdated;
use Illuminate\Contracts\Support\Renderable;
use Modules\Carts\Repositories\TaxRatesRepository;
use Modules\Carts\Http\Requests\StoreTaxRateRequest;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Http\Requests\UpdateTaxRateRequest;
use Modules\Carts\Http\Requests\DestroyTaxRateRequest;

class TaxRateController extends BaseController
{
    /**
     * @var TaxRatesRepository
     */
    protected $taxRates;

    public function __construct(TaxRatesRepository $taxRates)
    {
        parent::__construct();

        $this->taxRates = $taxRates;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('carts::admin.tax-rate.index', [
            'pageTitle' => __('Tax rates')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreTaxRateRequest $request
     * @return Renderable
     */
    public function store(StoreTaxRateRequest $request)
    {
        $response = $this->taxRates->create($request->only(
            'title',
            'description',
            'percentage',
            'active',
            'inclusive',
            'tax_type'
        ));

        if ($response) {
            TaxRateCreated::dispatch($response);
            return $this->successResponse(__('Tax rate created.'));
        }

        return $this->errorResponse(__('Tax rate not created.'));
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateTaxRateRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateTaxRateRequest $request, $id)
    {
        $taxRate = $this->taxRates->findOrFail($id);
        $response = $this->taxRates->update(
            $taxRate,
            $request->only('title', 'description', 'percentage', 'active', 'inclusive', 'tax_type')
        );

        if ($response) {
            TaxRateUpdated::dispatch($taxRate->fresh());
            return $this->successResponse(__('Tax rate updated.'));
        }

        return $this->errorResponse(__('Tax rate not updated.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param DestroyTaxRateRequest $request
     * @return Renderable
     */
    public function destroy(DestroyTaxRateRequest $request)
    {
        foreach ($request->rates as $id) {
            $taxRate = $this->taxRates->find($id);
            if ($taxRate) {
                $this->taxRates->delete($taxRate);
                TaxRateDeleted::dispatch($taxRate);
            }
        }

        return $this->successResponse(__('Tax rate/s deleted.'));
    }
}