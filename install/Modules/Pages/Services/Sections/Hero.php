<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class Hero implements SectionInterface
{
    public function get($section)
    {
        $hasButtons = false;
        if (isset($section->data->buttons) && count($section->data->buttons)) {
            $hasButtons = true;
        }
        $html = '';
        $textColor = '';
        $secondaryBtnColor = 'btn-outline-white';
        if ($section->backgroundImage) {
            $html .= '<section class="hero-section d-flex align-items-center ' . ((!$hasButtons && !$section->description) ? '' : 'min-vh-100') . ' bg-overlay default-overlay cover-background" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
            $textColor = 'text-white';
        } else {
            $secondaryBtnColor = 'btn-outline-secondary';
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="hero-section d-flex align-items-center ' . $bgColor . ' ' . ((!$hasButtons && !$section->description) ? '' : 'min-vh-100') . '">';
            } else {
                $bgColor = 'bg-white';
                if (setting('frontend_color_scheme') === 'dark') {
                    $bgColor = 'bg-darken';
                }
                $html .= '<section class="hero-section d-flex align-items-center ' . $bgColor . ' ' . ((!$hasButtons && !$section->description) ? '' : 'min-vh-100') . '">';
            }
        }
        $html .= '<div class="container heading-center"><div class="row align-items-center"><div class="col-lg-12"><div class="hero-content-wrapper text-center">';
        if ($section->sub_heading) {
            $html .= '<h2 class="mb-25 ' . $textColor . ' text-capitalize" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInDown;">' . $section->sub_heading . '</h2>';
        }
        if ($section->heading) {
            $html .= '<h1 class="' . ((!$hasButtons && !$section->description) ? '' : 'mb-25') . ' ' . $textColor . ' text-uppercase" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInDown;">' . $section->heading . '</h1>';
        }

        if ($section->description) {
            $html .= '<p class="' . $textColor . ' text-uppercase ' . ($hasButtons ? 'mb-35' : 'mb-0') . '" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInLeft;">' . $section->description . '</p>';
        }

        if ($hasButtons) {
            $html .= '<div class="hero-section-buttons">';
            foreach ($section->data->buttons as $button) {

                if ($button->primary) {
                    $html .= '<a href="' . $button->link . '" class="btn btn-hover-arrow flex-shrink-0 btn-primary py-lg-3 px-lg-5 btn-outline-2px" target="' . ($button->new_tab ? '_blank' : '_self') . '">
                            <span>' . $button->label . '</span>
                        </a>';
                } else {
                    $html .= '<a href="' . $button->link . '" class="btn btn-hover-arrow flex-shrink-0 ' . $secondaryBtnColor . ' btn-outline-2px py-lg-3 px-lg-5" target="' . ($button->new_tab ? '_blank' : '_self') . '">
                            <span>' . $button->label . '</span>
                        </a>';
                }
            }
            $html .= '</div>';
        }

        $html .= '</div></div></div></div>';
        $html .= '</section>';
        return $html;
    }
}