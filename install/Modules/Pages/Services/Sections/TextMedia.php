<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class TextMedia implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="text-image-section bg-image bg-fixed bg-overlay default-overlay ' . ($section->data->left ? 'left-image' : 'right-image') . '" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="text-image-section ' . $bgColor . ' ' . ($section->data->left ? 'left-image' : 'right-image') . '">';
            } else {
                $html .= '<section class="text-image-section ' . ($section->data->left ? 'left-image' : 'right-image') . '">';
            }
        }

        if ($section->data->source_type === 'image') {
            $mediaHtml = '<div class="image-wrapper">
                        <img src="' . $section->data->media_source . '">
                    </div>';
        } else {
            $mediaHtml = '<div class="video-wrapper">
                        <iframe width="500" height="300" src="' . $section->data->media_source . '" frameborder="0" allowfullscreen=""></iframe>
                    </div>';
        }

        $textHtml = '<div class="text-image-content ' . (isset($section->data->text_center) && $section->data->text_center ? 'text-center' : '') . '">';
        if($section->sub_heading) {
            $textHtml .= '<p class="sub-heading heading-left">' . $section->sub_heading . '</p>';
        }
        $textHtml .= '<h2 class="section-heading">' . $section->heading . '</h2>';
        if (isset($section->data->items)) {
            foreach ($section->data->items as $item) {
                $textHtml .= '<p>' . $item->content . '</p>';
            }
        }

        if (isset($section->data->checkmarks) && count($section->data->checkmarks)) {
            $odds = collect($section->data->checkmarks)->filter(function ($value, $key) {
                return $key % 2 == 0;
            })->values()->all();
            $even = collect($section->data->checkmarks)->filter(function ($value, $key) {
                return $key % 2 != 0;
            })->values()->all();

            $textHtml .= '<div class="row gy-3">';
            if (count($odds)) {
                $textHtml .= '<div class="col-md-6">';
                $textHtml .= '<ul class="icon-list mb-0">';
                foreach ($odds as $key => $item) {
                    $textHtml .= '<li class="' . ($key ? 'mt-3' : '') . '">';
                    $textHtml .= '<span><i></i></span><span>' . $item->content . '</span>';
                    $textHtml .= '</li>';
                }
                $textHtml .= '</ul>';
                $textHtml .= '</div>';
            }

            if (count($even)) {
                $textHtml .= '<div class="col-md-6">';
                $textHtml .= '<ul class="icon-list mb-0">';
                foreach ($even as $key => $item) {
                    $textHtml .= '<li class="' . ($key ? 'mt-3' : '') . '">';
                    $textHtml .= '<span><i></i></span><span>' . $item->content . '</span>';
                    $textHtml .= '</li>';
                }
                $textHtml .= '</ul>';
                $textHtml .= '</div>';
            }
            $textHtml .= '</div>';
        }

        $textHtml .= '</div>';

        $html .= '<div class="container z-index-1">';
        $html .= '<div class="row align-items-center">';

        $html .= '<div class="col-lg-6">';
        if ($section->data->left) {
            $html .= $mediaHtml;
        } else {
            $html .= $textHtml;
        }
        $html .= '</div>';
        $html .= '<div class="col-lg-6">';
        if ($section->data->left) {
            $html .= $textHtml;
        } else {
            $html .= $mediaHtml;
        }
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';

        $html .= '</section>';
        return $html;
    }
}