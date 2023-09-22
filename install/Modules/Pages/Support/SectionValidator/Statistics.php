<?php

namespace Modules\Pages\Support\SectionValidator;

class Statistics
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('statistics.background_image', __('Statistics section details: No image selected for the background.'));
        }

        if ($section->data && isset($section->data->items)) {
            if (!is_array($section->data->items)) {
                $validator->errors()->add('statistics.data.items', __('Statistics section data: Invalid items value format. Items must be an array'));
            }

            foreach ($section->data->items as $key => $item) {
                $keyVal = $key + 1;
                if ($item->icon === null || trim($item->icon) === '') {
                    $validator->errors()->add("statistics.data.items.{$key}.icon", __('Statistics section data: Item:value icon is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if ($item->title === null || trim($item->title) === '') {
                    $validator->errors()->add("statistics.data.items.{$key}.title", __('Statistics section data: Item:value title is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if ($item->value === null || trim($item->value) === '') {
                    $validator->errors()->add("statistics.data.items.{$key}.value", __('Statistics section data: Item:value value is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }
            }
        } else {
            $validator->errors()->add('statistics.data.items', __('Statistics section data: Please provide atleast 1 item.'));
        }

        return $validator;
    }
}
