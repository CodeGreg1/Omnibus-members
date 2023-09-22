<?php

namespace Modules\Pages\Support\SectionValidator;

class ContactUs
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('contact_us.background_image', __('Contact us section details: No image selected for the background.'));
        }

        return $validator;
    }
}
