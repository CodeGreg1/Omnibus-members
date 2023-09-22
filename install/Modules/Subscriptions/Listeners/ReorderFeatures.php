<?php

namespace Modules\Subscriptions\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Subscriptions\Repositories\FeaturesRepository;

class ReorderFeatures
{
    public $features;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(FeaturesRepository $features)
    {
        $this->features = $features;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $features = $this->features->orderBy('ordering', 'desc')->all();

        foreach ($features->keys() as $key) {
            $this->features->update($features->get($key), [
                'ordering' => $key + 1
            ]);
        }
    }
}