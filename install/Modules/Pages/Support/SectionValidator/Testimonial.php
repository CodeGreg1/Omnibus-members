<?php

namespace Modules\Pages\Support\SectionValidator;

class Testimonial
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('testimonial.background_image', __('Testimonial section details: No image selected for the background.'));
        }

        if ($section->data && isset($section->data->items)) {
            if (!is_array($section->data->items)) {
                $validator->errors()->add('testimonial.data.items', __('Testimonial section data: Invalid items value format. Items must be an array'));
            }

            foreach ($section->data->items as $key => $item) {
                $keyVal = $key + 1;
                if (!isset($item->author_avatar) || $item->author_avatar === null || trim($item->author_avatar) === '') {
                    $validator->errors()->add("testimonial.data.items.{$key}.author_avatar", __('Testimonial section data: Item:value no author avatar selected.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if (!isset($item->message) || $item->message === null || trim($item->message) === '') {
                    $validator->errors()->add("testimonial.data.items.{$key}.message", __('Testimonial section data: Item:value message is requried.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if (!isset($item->author_name) || $item->author_name === null || trim($item->author_name) === '') {
                    $validator->errors()->add("testimonial.data.items.{$key}.author_name", __('Testimonial section data: Item:value author name is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }
            }
        } else {
            $validator->errors()->add('testimonial.data.items', __('Testimonial section data: Please provide atleast 1 testimonial.'));
        }

        return $validator;
    }
}
