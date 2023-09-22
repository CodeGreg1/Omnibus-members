<?php

namespace Modules\Pages\Services\Sections;

use function GuzzleHttp\Promise\settle;

use Modules\Pages\Services\SectionInterface;

class ContactUs implements SectionInterface
{
    public function get($section)
    {
        $html = '';
        if ($section->backgroundImage) {
            $html .= '<section class="contact-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(' . $section->backgroundImage->original_url . '), url(' . url('/upload/media/placeholders/1519x870.png') . ');">';
        } else {
            if ($section->background_color === 'gray') {
                $bgColor = 'bg-gray';
                if ($section->page->dark_mode) {
                    $bgColor = 'bg-lighten';
                }
                $html .= '<section class="contact-section ' . $bgColor . '">';
            } else {
                $html .= '<section class="contact-section">';
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

        $html .= '<div class="container"><div class="row">';
        $html .= '<div class="col-md-5">';
        $html .= '<ul class="contact-info contact-half mb-50 fa-ul">';
        if (setting('app_address')) {
            $html .= '<li><i class="fa fa-li fa-location-pin"></i>';
            $html .= '<h3 class="title">' . __('Address') . '</h3>';
            $html .= '<p class="description">' . setting('app_address') . '</p>';
            $html .= '</li>';
        }

        if (setting('app_phone')) {
            $html .= '<li><i class="fa fa-li fa-phone"></i>';
            $html .= '<h3 class="title">' . __('Phone') . '</h3>';
            $html .= '<p class="description">' . setting('app_phone') . '</p>';
            $html .= '</li>';
        }

        if (setting('app_email')) {
            $html .= '<li><i class="fa fa-li fa-envelope"></i>';
            $html .= '<h3 class="title">' . __('Email') . '</h3>';
            $html .= '<p class="description"><a href="mailto:' . setting('app_email') . '">' . setting('app_email') . '</a></p>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</div>';

        $html .= '<div class="col-md-7">';
        $html .= '<div class="contact-form-wrapper">';
        $html .= '<form id="contact-us-form" class="form-horizontal mb-30"  novalidate data-route="' . route('contact-us.send') . '">';
        $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '" >';

        $html .= '<div class="row mb-3 mt-3">';
        $html .= '<div class="col-sm-6">';
        $html .= '<div class="form-group">';
        $html .= '<label for="contact-name" class="form-label">' . __('Name') . '</label>';
        $html .= '<input type="text" class="form-control" id="contact-name" name="name" placeholder="' . __('Name') . '" required>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-6">';
        $html .= '<div class="form-group">';
        $html .= '<label for="contact-name" class="form-label">' . __('Email') . '</label>';
        $html .= '<input type="email" class="form-control" id="contact-email" name="email" placeholder="' . __('Email') . '" required>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="mb-3 mt-3 form-group">';
        $html .= '<label for="contact-subject" class="form-label">' . __('Subject') . '</label>';
        $html .= '<div class="col-sm-12">';
        $html .= '<input type="text" class="form-control" id="contact-subject" name="subject" placeholder="' . __('Subject') . '">';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="mb-3 mt-3 form-group">';
        $html .= '<label for="contact-message" class="form-label">' . __('Message') . '</label>';
        $html .= '<div class="col-sm-12">';
        $html .= '<textarea class="form-control" id="contact-message" name="message" rows="5" cols="30" placeholder="' . __('Message') . '" required></textarea>';
        $html .= '</div>';
        $html .= '</div>';

        if (setting('recaptcha_site_key') && setting('recaptcha_secret_key')) {
            $html .= '<div class="mb-3 mt-3 d-flex justify-content-end">';
            $html .= '<div class="form-group">';
            $html .= htmlFormSnippet();
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '<div class="form-group">';
        $html .= '<div class="col-sm-12">';
        $html .= '<button id="contact-us-submit-button" type="button" class="btn btn-lg btn-primary pull-right">' . __('Send Message') . '</button>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '</form>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div></div>';

        $html .= '</section>';
        return $html;
    }
}