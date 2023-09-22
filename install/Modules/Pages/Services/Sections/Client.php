<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class Client implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="client-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="client-section ' . $bgColor . '">';
            } else {
                $html .= '<section class="client-section">';
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
            $html .= '<div class="container position-relative z-index-1"><div class="row text-center">';
            $html .= '<div class="col-md-12"><ul class="client-list">';
            foreach ($section->data->items as $item) {
                $html .= '<li><a href="' . ($item->url ?? '/') . '"><img src="' . $item->logo . '" alt="Client Logo" onerror="this.src=\'' . url('/upload/media/placeholders/135x100.png') . '\'"></a></li>';
            }
            $html .= '</ul></div></div></div>';
        }

        $html .= '</section>';
        return $html;
    }
}