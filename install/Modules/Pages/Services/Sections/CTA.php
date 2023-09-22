<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class CTA implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="cta-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'info') {
                $html .= '<section class="cta-section pt-70 pb-70 bg-info">';
            } else if ($section->background_color === 'danger') {
                $html .= '<section class="cta-section pt-70 pb-70 bg-danger">';
            } else {
                $html .= '<section class="cta-section pt-70 pb-70 bg-primary">';
            }
        }
        $html .= '<div class="container">';
        $html .= '<div class="row align-items-center">';
        $html .= '<div class="col-lg-8">';
        $html .= '<div class="section-title mb-30">';
        if ($section->heading) {
            $html .= '<h2 class="text-white mb-40">' . $section->heading . '</h2>';
        }
        if ($section->description) {
            $html .= '<p class="lead text-white">' . $section->description . '</p>';
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-lg-4">';
        $html .= '<a href="' . (isset($section->data->link) ? $section->data->link : '/') . '" class="btn btn-hover-arrow flex-shrink-0 btn-outline-white btn-outline-2px py-lg-3 px-lg-5" target="' . ($section->data->new_tab ? '_blank' : '_self') . '">';
        $html .= '<span>' . (isset($section->data->label) ? $section->data->label : 'Purchase Now') . '</span>';
        $html .= '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</section>';
        return $html;
    }
}