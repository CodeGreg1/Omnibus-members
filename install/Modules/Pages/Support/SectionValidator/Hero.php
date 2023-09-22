<?php

namespace Modules\Pages\Support\SectionValidator;

class Hero
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('hero.background_image', __('Hero section details: No image selected for the background.'));
        }

        if ($section->data && isset($section->data->buttons)) {
            if (!is_array($section->data->buttons)) {
                $validator->errors()->add('hero.data.buttons', __('Hero section data: Invalid buttons value format. Buttons must be an array'));
            }
            foreach ($section->data->buttons as $key => $button) {
                $keyVal = $key + 1;
                if ($button->label === null || trim($button->label) === '') {
                    $validator->errors()->add("hero.data.buttons.{$key}.label", __('Hero section data: Button attribute:value label is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }
                if ($button->link === null || trim($button->link) === '') {
                    $validator->errors()->add("hero.data.buttons.{$key}.link", __('Hero section data: Button attribute:value link is required.', [
                        'value' => "[{$keyVal}]"
                    ]));
                }
            }
        }

        return $validator;
    }
}
