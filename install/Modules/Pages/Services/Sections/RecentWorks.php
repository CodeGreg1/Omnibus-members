<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class RecentWorks implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="recent-works-section slick-carousel bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="recent-works-section slick-carousel ' . $bgColor . '">';
            } else {
                $html .= '<section class="recent-works-section slick-carousel">';
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
            $html .= '<div class="container">';
            $html .= '<div class="portfolio-container">';
            foreach ($section->data->items as $item) {
                $html .= '<div class="portfolio-item">';
                $html .= '<div class="overlay"></div>';
                $html .= '<div class="info">';
                $html .= '<h4 class="title">' . $item->title . '</h4>';
                if ($item->description) {
                    $html .= '<p class="brief-description">' . $item->description . '</p>';
                }
                $html .= '<a href="' . ($item->btn_link ?? '/') . '" class="btn btn-sm btn-hover-arrow flex-shrink-0 btn-outline-white btn-outline-2px"><span>' . ($item->btn_label ?? 'read more') . '</span></a>';
                $html .= '</div>';
                $html .= '<div class="media-wrapper">';
                if (isset($item->image) && $item->image) {
                    $html .= '<img src="' . $item->image . '" alt="' . $item->title . '" onerror="this.src=\'' . url('/upload/media/placeholders/500x500.png') . '\'">';
                }
                $html .= '</div></div>';
            }
            $html .= '</div></div>';
        }

        $html .= '</section>';
        return $html;
    }
}