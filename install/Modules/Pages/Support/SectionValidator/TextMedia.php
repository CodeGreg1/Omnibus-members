<?php

namespace Modules\Pages\Support\SectionValidator;

class TextMedia
{
    public static function validate($validator, $section)
    {
        if (!$section->background_color && !$section->media_id) {
            $validator->errors()->add('text_media.background_image', __('Text media section details: No image selected for the background.'));
        }

        if (!$section->data->media_source) {
            if ($section->data->source_type === 'image') {
                $validator->errors()->add('text_media.data.media_source', __('Text media section data: No image selected for the media element.'));
            }

            if ($section->data->source_type === 'embedded') {
                $validator->errors()->add('text_media.data.media_source', __('Text media section data: Media url attribute is required.'));
            }
        }

        if ($section->data && isset($section->data->items)) {
            if (!is_array($section->data->items)) {
                $validator->errors()->add('text_media.data.items', __('Text media section data: Invalid items value format. Items must be an array'));
            } else {
                if (!count($section->data->items)) {
                    $validator->errors()->add('text_media.data.items', __('Text media section data: Please provide atleast 1 item.'));
                }
            }
        } else {
            $validator->errors()->add('text_media.data.items', __('Text media section data: Please provide atleast 1 item.'));
        }

        return $validator;
    }
}