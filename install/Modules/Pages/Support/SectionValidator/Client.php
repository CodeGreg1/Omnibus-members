<?php

namespace Modules\Pages\Support\SectionValidator;

class Client
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('client.background_image', __('Client section details: No image selected for the background.'));
        }

        if ($section->data && isset($section->data->items)) {
            if (!is_array($section->data->items)) {
                $validator->errors()->add('client.data.items', __('Client section data: Invalid items value format. Items must be an array'));
            }

            foreach ($section->data->items as $key => $item) {
                $keyVal = $key + 1;
                if (!isset($item->logo) || $item->logo === null || trim($item->logo) === '') {
                    $validator->errors()->add("client.data.items.{$key}.logo", __('Client section data: Item:value no logo selected.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }
            }
        } else {
            $validator->errors()->add('client.data.items', __('Client section data: Please provide atleast 1 client.'));
        }

        return $validator;
    }
}
