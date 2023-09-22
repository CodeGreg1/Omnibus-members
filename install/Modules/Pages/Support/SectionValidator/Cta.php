<?php

namespace Modules\Pages\Support\SectionValidator;

class Cta
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('cta.background_image', __('Cta section details: No image selected for the background.'));
        }

        if ($section->data) {
            if ($section->data->link === null || trim($section->data->link) === '') {
                $validator->errors()->add('cta.data.link', __('Cta section data: Button link is required.'));
            }
        }

        return $validator;
    }
}
