<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Support\Renderable;
use Modules\Subscriptions\Events\FeatureCreated;
use Modules\Subscriptions\Events\FeatureDeleted;
use Modules\Subscriptions\Events\FeatureUpdated;
use Modules\Subscriptions\Events\FeatureReordered;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\FeaturesRepository;
use Modules\Subscriptions\Http\Requests\StoreFeatureRequest;
use Modules\Subscriptions\Http\Requests\UpdateFeatureRequest;

class FeaturesController extends BaseController
{
    /**
     * @var Modules\Subscriptions\Repositories\FeaturesRepository
     */
    protected $features;

    public function __construct(FeaturesRepository $features)
    {
        parent::__construct();

        $this->features = $features;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.subscriptions.packages.features.index');

        return view('subscriptions::admin.feature.index', [
            'pageTitle' => __('Features'),
            'features' => $this->features->paginate(20)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreFeatureRequest $request
     * @return Renderable
     */
    public function store(StoreFeatureRequest $request)
    {
        $feature = $this->features->create(array_merge(
            $request->only('title', 'description'),
            [
                'ordering' => $this->features->getModel()->max('ordering') + 1
            ]
        ));

        FeatureCreated::dispatch($feature);

        return $this->successResponse(__('New feature created.'), [
            'redirectTo' => route('admin.subscriptions.packages.features.index'),
            'feature' => $feature
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateFeatureRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateFeatureRequest $request, $id)
    {
        $feature = $this->features->findOrFail($id);

        $this->features->update(
            $feature,
            $request->only('title', 'description')
        );

        FeatureUpdated::dispatch($feature->fresh());

        return $this->successResponse(__('Feature updated'), [
            'redirectTo' => route('admin.subscriptions.packages.features.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->authorize('admin.subscriptions.packages.features.destroy');

        $feature = $this->features->findOrFail($id);

        $packageUsingCount = $feature->packages()->count();
        if ($packageUsingCount) {
            return $this->errorResponse(__(
                ':total package/s using this feature. Unable to remove',
                ['total' => $packageUsingCount]
            ));
        }

        $this->features->delete($feature);

        FeatureDeleted::dispatch($feature);

        return $this->successResponse(__('Feature removed'), [
            'redirectTo' => route('admin.subscriptions.packages.features.index')
        ]);
    }

    public function reorder(Request $request)
    {
        $this->authorize('admin.subscriptions.packages.features.reorder');

        $ordering = $request->get('ordering');

        // $ordering = array_reverse($ordering);

        $cntr = 1;

        foreach ($ordering as $data) {
            $model = $this->features->findOrFail($data['id']);

            $this->features
                ->update($model, [
                    'ordering' => $cntr
                ]);

            $cntr++;
        }

        FeatureReordered::dispatch();

        return $this->successResponse(__('Features reordered'));
    }
}