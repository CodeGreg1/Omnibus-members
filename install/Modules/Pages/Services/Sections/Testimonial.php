<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class Testimonial implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="testimonial-section testimonial-fancy slick-carousel bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="testimonial-section testimonial-fancy slick-carousel ' . $bgColor . '">';
            } else {
                $html .= '<section class="testimonial-section testimonial-fancy slick-carousel">';
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
            $html .= '<div class="container testimonial-container position-relative z-index-1">';
            foreach ($section->data->items as $item) {
                $html .= '<div class="testimonial-body">';
                $html .= '<h2 class="sr-only">TESTIMOMIAL</h2>';
                $html .= '<i class="fa fa-quote-left text-primary"></i>';
                $html .= '<p class="testimonial-bubble">' . $item->message . '</p>';
                $html .= '<div class="testimonial-author">';
                $html .= '<img src="' . $item->author_avatar . '" alt="' . $item->author_name . '" class="pull-left" onerror="this.src=\'' . url('/upload/media/placeholders/100x100.png') . '\'">';
                $html .= '<span><span class="author-name">' . $item->author_name . '</span> <em>' . $item->author_description . '</em></span>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        $html .= '</section>';
        return $html;
    }
}