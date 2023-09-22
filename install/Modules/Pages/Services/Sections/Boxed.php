<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class Boxed implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="boxed-content-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="boxed-content-section ' . $bgColor . '">';
            } else {
                $html .= '<section class="boxed-content-section">';
            }
        }

        if ($section->sub_heading || $section->heading || $section->description) {
            $html .= '<div class="container heading-center position-relative z-index-1">';
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
            $html .= '<div class="container position-relative z-index-1"><div class="row justify-content-center">';
            foreach ($section->data->items as $item) {
                $html .= '<div class="col-md-4">';
                $html .= '<div class="boxed-content">';
                $html .= '<i class="' . $item->icon . '"></i>';
                $html .= '<h3 class="boxed-content-title">' . $item->title . '</h3>';
                $html .= '<p>' . $item->content . '</p>';
                $html .= '</div></div>';
            }
            $html .= '</div></div>';
        }

        $html .= '</section>';
        return $html;
    }
}