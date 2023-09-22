<?php

namespace Modules\Pages\Support\SectionValidator;

class BoxedLeftIcon
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('boxed_left_icon.background_image', __('Boxed icon left section details: No image selected for the background.'));
        }

        if ($section->data && isset($section->data->items)) {
            if (!is_array($section->data->items)) {
                $validator->errors()->add('boxed_left_icon.data.items', __('Boxed icon left section data: Invalid items value format. Items must be an array'));
            }

            foreach ($section->data->items as $key => $item) {
                $keyVal = $key + 1;
                if (!isset($item->icon) || $item->icon === null || trim($item->icon) === '') {
                    $validator->errors()->add("boxed_left_icon.data.items.{$key}.icon", __('Boxed icon left section data: Item:value no icon selected.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if (!isset($item->title) || $item->title === null || trim($item->title) === '') {
                    $validator->errors()->add("boxed_left_icon.data.items.{$key}.title", __('Boxed icon left section data: Item:value title is requried.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if (!isset($item->content) || $item->content === null || trim($item->content) === '') {
                    $validator->errors()->add("boxed_left_icon.data.items.{$key}.content", __('Boxed icon left section data: Item:value content is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }
            }
        } else {
            $validator->errors()->add('boxed_left_icon.data.items', __('Boxed icon left section: Please provide atleast 1 item.'));
        }

        return $validator;
    }
}
