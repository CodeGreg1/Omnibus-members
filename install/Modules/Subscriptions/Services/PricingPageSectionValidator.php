<?php

namespace Modules\Subscriptions\Services;

class PricingPageSectionValidator
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('pricing.background_image', __('Pricing section details: No image selected for the background.'));
        }

        return $validator;
    }
}