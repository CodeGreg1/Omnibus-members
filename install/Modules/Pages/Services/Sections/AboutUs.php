<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class AboutUs implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="about-us-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="about-us-section ' . $bgColor . '">';
            } else {
                $html .= '<section class="about-us-section">';
            }
        }
        $html .= '<div class="container heading-center ' . (!$section->data->label && !$section->description ? 'mb-0' : '') . '">';
        if ($section->sub_heading) {
            $html .= '<p class="sub-heading">' . $section->sub_heading . '</p>';
        }
        if ($section->heading) {
            $html .= '<h2 class="section-heading">' . $section->heading . '</h2>';
        }
        $html .= '</div>';
        $html .= '<div class="container content-center">';
        if ($section->description) {
            $html .= '<p class="lead ' . ($section->data->label ? '' : 'mb-0') . '">' . $section->description . '</p>';
        }

        if ($section->data->label) {
            if ($section->data->primary) {
                $html .= '<a href="' . $section->data->link . '" class="btn btn-hover-arrow flex-shrink-0 btn-primary py-lg-3 px-lg-5 btn-outline-2px" target="' . ($section->data->new_tab ? '_blank' : '_self') . '">
                            <span>' . $section->data->label . '</span>
                        </a>';
            } else {
                $html .= '<a href="' . $section->data->link . '" class="btn btn-hover-arrow flex-shrink-0 btn-outline-primary btn-outline-2px py-lg-3 px-lg-5" target="' . ($section->data->new_tab ? '_blank' : '_self') . '">
                            <span>' . $section->data->label . '</span>
                        </a>';
            }
        }

        $html .= '</div>';
        $html .= '</section>';
        return $html;
    }
}