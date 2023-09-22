<?php

namespace Modules\Pages\Support\SectionValidator;

class RecentWorks
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('recent_works.background_image', __('Recent works section details: No image selected for the background.'));
        }

        if ($section->data && isset($section->data->items)) {
            if (!is_array($section->data->items)) {
                $validator->errors()->add('recent_works.data.items', __('Recent works section data: Invalid items value format. Items must be an array'));
            }

            foreach ($section->data->items as $key => $item) {
                $keyVal = $key + 1;
                if (!isset($item->image) || $item->image === null || trim($item->image) === '') {
                    $validator->errors()->add("recent_works.data.items.{$key}.image", __('Recent works section data: Item:value no image selected.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if (!isset($item->title) || $item->title === null || trim($item->title) === '') {
                    $validator->errors()->add("recent_works.data.items.{$key}.title", __('Recent works section data: Item:value title is requried.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if (!isset($item->btn_link) || $item->btn_link === null || trim($item->btn_link) === '') {
                    $validator->errors()->add("recent_works.data.items.{$key}.value", __('Recent works section data: Item:value button link is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }
            }
        } else {
            $validator->errors()->add('recent_works.data.items', __('Recent works section data: Please provide atleast 1 item.'));
        }

        return $validator;
    }
}
