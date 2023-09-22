<?php

namespace Modules\Subscriptions\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\FeaturesRepository;
use Modules\Subscriptions\Repositories\PackagePricesRepository;
use Modules\Subscriptions\Repositories\PricingTablesRepository;

class PricingController extends BaseController
{
    /**
     * @var PackagePricesRepository
     */
    protected $prices;

    /**
     * @var FeaturesRepository
     */
    protected $features;

    /**
     * @var PricingTablesRepository
     */
    protected $pricingTables;

    public function __construct(
        PackagePricesRepository $prices,
        FeaturesRepository $features,
        PricingTablesRepository $pricingTables
    ) {
        parent::__construct();

        $this->prices = $prices;
        $this->features = $features;
        $this->pricingTables = $pricingTables;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $pricingTable = $this->pricingTables->active();

        return view('subscriptions::pricing.index', [
            'pageTitle' => 'Pricing',
            'title' => $pricingTable ? $pricingTable->name : '',
            'description' => $pricingTable ? $pricingTable->description : '',
            'pricingTable' => $pricingTable ? $pricingTable->table() : collect([]),
            'features' => $this->features->orderBy('ordering', 'asc')->all()
        ]);
    }
}