<?php

namespace Modules\Pages\Services\Sections;

use Modules\Pages\Services\SectionInterface;

class Team implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="team-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="team-section ' . $bgColor . '">';
            } else {
                $html .= '<section class="team-section">';
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
                $html .= '<div class="col-md-6"><div class="team-member d-flex">';
                $html .= '<div class="flex-shrink-0">';
                $html .= '<img src="' . $item->avatar . '" class="rounded-circle" alt="' . $item->name . '" onerror="this.src=\'' . url('/upload/media/placeholders/100x100.png') . '\'">';
                $html .= '</div>';
                $html .= '<div class="flex-grow-1 ms-3">';
                $html .= '<h5 class="team-name">' . $item->name . '</h5>';
                $html .= '<strong>' . $item->position . '</strong>';
                $html .= '<div class="team-content">';
                $html .= '<p>' . $item->description . '</p>';
                $html .= '</div>';
                $html .= '<ul class="list-inline social-icon">';
                if ($item->facebook) {
                    $html .= '<li><a href="' . $item->facebook . '"><i class="lni lni-facebook-filled"></i></a></li>';
                }
                if ($item->twitter) {
                    $html .= '<li><a href="' . $item->twitter . '"><i class="lni lni-twitter-filled"></i></a></li>';
                }
                if ($item->linkedin) {
                    $html .= '<li><a href="' . $item->linkedin . '"><i class="lni lni-linkedin-original"></i></a></li>';
                }
                if ($item->dribbble) {
                    $html .= '<li><a href="' . $item->dribbble . '"><i class="lni lni-dribbble"></i></a></li>';
                }
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</div></div>';
            }
            $html .= '</div></div>';
        }

        $html .= '</section>';
        return $html;
    }
}