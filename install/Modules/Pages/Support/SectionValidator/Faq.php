<?php

namespace Modules\Pages\Support\SectionValidator;

class Faq
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('faq.background_image', __('Faq section details: No image selected for the background.'));
        }

        if ($section->data && isset($section->data->items)) {
            if (!is_array($section->data->items)) {
                $validator->errors()->add('faq.data.items', __('Faq section data: Invalid items value format. Items must be an array'));
            }

            foreach ($section->data->items as $key => $item) {
                $keyVal = $key + 1;

                if (!isset($item->title) || $item->title === null || trim($item->title) === '') {
                    $validator->errors()->add("faq.data.items.{$key}.title", __('Faq section data: Item:value title is requried.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if (!isset($item->content) || $item->content === null || trim($item->content) === '') {
                    $validator->errors()->add("faq.data.items.{$key}.content", __('Faq section data: Item:value content is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }
            }
        } else {
            $validator->errors()->add('faq.data.items', __('Faq section data: Please provide atleast 1 item.'));
        }

        return $validator;
    }
}
