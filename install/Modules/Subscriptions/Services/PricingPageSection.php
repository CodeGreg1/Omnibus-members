<?php

namespace Modules\Subscriptions\Services;

use Illuminate\Support\Str;
use Modules\Subscriptions\Models\Feature;
use Modules\Pages\Services\SectionInterface;
use Modules\Subscriptions\Models\PricingTable;

class PricingPageSection implements SectionInterface
{
    public function get($section)
    {
        $table = PricingTable::where('active', 1)->first();
        $prices = $table ? $table->table() : collect([]);
        $features = Feature::orderBy('ordering', 'asc')->get();
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="pricing-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="pricing-section ' . $bgColor . '">';
            } else {
                $html .= '<section class="pricing-section">';
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

        if ($prices->count()) {
            $html .= '<div class="container position-relative z-index-1">';

            $html .= '<ul class="nav nav-pills d-flex align-items-center justify-content-center mb-25" id="pills-tab" role="tablist">';
            foreach ($prices as $index => $priceItemCollection) {
                $html .= '<li class="nav-item" role="presentation">';
                if (!$index) {
                    $html .= '<button class="nav-link active" id="pricing-' . $priceItemCollection->id . '-tab" data-bs-toggle="pill" data-bs-target="#pricing-' . $priceItemCollection->id . '" type="button" role="tab" aria-controls="pricing-' . $priceItemCollection->id . '" aria-selected="true">' . $priceItemCollection->term->title . '</button>';
                } else {
                    $html .= '<button class="nav-link" id="pricing-' . $priceItemCollection->id . '-tab" data-bs-toggle="pill" data-bs-target="#pricing-' . $priceItemCollection->id . '" type="button" role="tab" aria-controls="pricing-' . $priceItemCollection->id . '" aria-selected="false">' . $priceItemCollection->term->title . '</button>';
                }
                $html .= '</li>';
            }
            $html .= '</ul>';

            $html .= '<div class="tab-content" id="pills-tabContent">';
            foreach ($prices as $index => $priceItemCollection) {

                if (!$index) {
                    $html .= '<div class="tab-pane fade show active" id="pricing-' . $priceItemCollection->id . '" role="tabpanel" aria-labelledby="pricing-' . $priceItemCollection->id . '-tab">';
                } else {
                    $html .= '<div class="tab-pane fade show" id="pricing-' . $priceItemCollection->id . '" role="tabpanel" aria-labelledby="pricing-' . $priceItemCollection->id . '-tab">';
                }

                $html .= '<div class="row justify-content-center">';
                foreach ($priceItemCollection->prices as $i => $priceItem) {
                    $price = $priceItem->price;
                    $html .= '<div class="col-xl-4 col-lg-4 col-md-6">';
                    $html .= '<div class="single-pricing ' . ($priceItem->featured ? 'active' : '') . '">';

                    $html .= '<h4>' . $price->package->name . '</h4>';

                    $html .= '<h3>' . $price->amount_display . '</h3>';

                    if ($price->type === 'recurring') {
                        $html .= '<p class="text-center">';
                        $html .= '<span class="pricing-frequency">';
                        $html .= $price->currency;
                        $html .= ' / ';
                        if ($priceItemCollection->term->interval_count > 1) {
                            $html .= $priceItemCollection->term->interval_count . ' ';
                        }
                        $html .= Str::plural($priceItemCollection->term->interval, $priceItemCollection->term->interval_count);
                        $html .= '</span>';
                        $html .= '</p>';
                    }

                    $html .= '<ul>';
                    foreach ($price->features as $feature) {
                        $html .= '<li class="d-flex align-items-center">';
                        $html .= '<div class="pricing-item-icon">';
                        $html .= '<i class="fas fa-check"></i>';
                        $html .= '</div>';
                        $html .= '<div class="pricing-item-details">' . $feature->title . '</div>';
                        $html .= '</li>';
                    }
                    foreach ($features->whereNotIn('id', $price->features->pluck('id')) as $feature) {
                        $html .= '<li class="d-flex align-items-center">';
                        $html .= '<div class="pricing-item-icon bg-danger text-white">';
                        $html .= '<i class="fas fa-times"></i>';
                        $html .= '</div>';
                        $html .= '<div class="pricing-item-details">' . $feature->title . '</div>';
                        $html .= '</li>';
                    }
                    $html .= '</ul>';

                    $class = $priceItem->featured ? 'btn btn-hover-arrow flex-shrink-0 btn-outline-primary btn-outline-2px px-lg-5 btn-pricing' : 'btn btn-hover-arrow flex-shrink-0 btn-outline-primary btn-outline-2px px-lg-5 btn-pricing';
                    $btnLabel = $priceItem->button_label ?? __('Subscribe');
                    $btnLink = $priceItem->button_link ?? route('user.packages.pay', [
                        'tableId' => $table->id,
                        'priceId' => $price->id
                    ]);

                    $html .= '<a href="' . $btnLink . '" class="' . $class . '"><span>' . $btnLabel . '</span>';
                    $html .= '</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                }

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
