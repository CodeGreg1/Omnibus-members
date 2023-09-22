<?php

namespace Modules\Pages\Support\SectionValidator;

class Team
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('team.background_image', __('Team section details: No image selected for the background.'));
        }

        if ($section->data && isset($section->data->items)) {
            if (!is_array($section->data->items)) {
                $validator->errors()->add('team.data.items', __('Team section data: Invalid items value format. Items must be an array'));
            }

            foreach ($section->data->items as $key => $item) {
                $keyVal = $key + 1;
                if (!isset($item->avatar) || $item->avatar === null || trim($item->avatar) === '') {
                    $validator->errors()->add("team.data.items.{$key}.avatar", __('Team section data: Item:value no avatar selected.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if (!isset($item->name) || $item->name === null || trim($item->name) === '') {
                    $validator->errors()->add("team.data.items.{$key}.name", __('Team section data: Item:value name is requried.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }

                if (!isset($item->position) || $item->position === null || trim($item->position) === '') {
                    $validator->errors()->add("team.data.items.{$key}.position", __('Team section data: Item:value position is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }
            }
        } else {
            $validator->errors()->add('team.data.items', __('Team section data: Please provide atleast 1 member.'));
        }

        return $validator;
    }
}

