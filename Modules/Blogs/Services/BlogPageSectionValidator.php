<?php

namespace Modules\Blogs\Services;

class BlogPageSectionValidator
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('blog.background_image', __('Blog section details: No image selected for the background.'));
        }

        return $validator;
    }
}