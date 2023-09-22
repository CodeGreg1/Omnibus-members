<?php

namespace Modules\Subscriptions\Traits;

trait HasPackageExtraCondition
{
    /**
     * Get extra condition from subscription
     * @param string $shortcode
     * @return int
     */
    public function getCondition($shortcode)
    {
        $package = $this->item->price->package;

        $model = $package->extraConditions->first(function ($value, $key) use ($shortcode) {
            return $value->shortcode === $shortcode;
        });

        if ($model) {
            return $model->value;
        }

        return null;
    }
}