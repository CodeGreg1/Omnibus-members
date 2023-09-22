<?php

namespace Modules\Pages\Support\SectionValidator;

class AboutUs
{
    public static function validate($validator, $section)
    {
        if ($section->heading === null || trim($section->heading) === '') {
            $validator->errors()->add('about_us.heading', __('About us section details: Heading is required.'));
        }

        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('about_us.background_image', __('About us section details: No image selected for the background.'));
        }

        return $validator;
    }
}
