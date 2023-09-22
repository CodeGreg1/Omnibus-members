<?php

namespace Modules\Pages\Services\Sections;

use Illuminate\Support\Arr;
use Modules\Pages\Services\SectionInterface;

class Faq implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="faq-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="faq-section ' . $bgColor . '">';
            } else {
                $html .= '<section class="faq-section">';
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
            $odds = collect($section->data->items)->filter(function ($value, $key) {
                return $key % 2 == 0;
            });
            $even = collect($section->data->items)->filter(function ($value, $key) {
                return $key % 2 != 0;
            });

            $html .= '<div class="container position-relative z-index-1">';
                $html .= '<div class="row justify-content-center">';

                if ($odds->count()) {
                    $html .= '<div class="col-md-6">';
                        $html .= '<div class="accordion-style">';
                            $html .= '<div class="accordion" id="faqCollapseOdd">';
                                foreach ($odds as $key => $item) {
                                    $html .= '<div class="single-accordion">';
                                        $html .= '<div class="accordion-btn">';
                                        $html .= '<button class="d-block text-start w-100 collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseOdd' . $key . '" aria-expanded="true"
                                                            aria-controls="collapseOdd' . $key . '">
                                                            <h3>' . $item->title . '</h3>
                                                        </button>';
                                        $html .= '</div>';
                                        $html .= '<div id="collapseOdd' . $key . '" class="collapse" data-parent="#faqCollapseOdd">';
                                            $html .= '<div class="accordion-content">' . $item->content . '</div>';
                                        $html .= '</div>';
                                    $html .= '</div>';
                                }
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';
                }

                if ($even->count()) {
                    $html .= '<div class="col-md-6">';
                        $html .= '<div class="accordion-style">';
                            $html .= '<div class="accordion" id="faqCollapseEven">';
                                foreach ($even as $key => $item) {
                                    $html .= '<div class="single-accordion">';
                                        $html .= '<div class="accordion-btn">';
                                            $html .= '<button class="d-block text-start w-100 collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseEven' . $key . '" aria-expanded="true"
                                                        aria-controls="collapseEven' . $key . '">
                                                        <h3>' . $item->title . '</h3>
                                                    </button>';
                                        $html .= '</div>';
                                    
                                        $html .= '<div id="collapseEven' . $key . '" class="collapse" data-parent="#faqCollapseEven">';
                                            $html .= '<div class="accordion-content">' . $item->content . '</div>';
                                        $html .= '</div>';
                                    $html .= '</div>';
                                }
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';
                }

                $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</section>';
        return $html;
    }
}
