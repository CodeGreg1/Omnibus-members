<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class Statistics implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="number-info-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="number-info-section ' . $bgColor . '">';
            } else {
                $html .= '<section class="number-info-section">';
            }
        }

        if ($section->sub_heading || $section->heading || $section->description) {
            $html .= '<div class="container heading-center">';
        }

        if ($section->sub_heading) {
            $html .= '<p class="sub-heading">' . $section->sub_heading . '</p>';
        }

        if ($section->heading) {
            $html .= '<h2 class="section-heading">' . $section->heading . '</h2>';
        }

        if ($section->description) {
            $html .= '<p class="section-description">' . $section->description . '</p>';
        }

        if ($section->sub_heading || $section->heading || $section->description) {
            $html .= '</div>';
        }

        if (isset($section->data->items)) {
            $html .= '<div class="container"><div class="row justify-content-center">';
            foreach ($section->data->items as $item) {
                $html .= '<div class="col-xl-3 col-sm-6 text-center">';
                $html .= '<div class="number-info vertical boxed">';
                $html .= '<i class="' . $item->icon . '"></i>';
                $html .= '<p>' . $item->value . '<span>' . $item->title . '</span></p>';
                $html .= '</div></div>';
            }
            $html .= '</div></div>';
        }
        $html .= '</section>';
        return $html;
    }
}