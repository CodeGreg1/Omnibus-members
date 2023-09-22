<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('code') @yield('title')</title>

        <!-- Start Site Styles Section -->
        <link media="all" type="text/css" rel="stylesheet" href="{{ url(mix('css/site/base.min.css')) }}">

        <style>
            :root {
                --frontend-dark-color-scheme: {{ setting('frontend_dark_color_scheme') 
                    ? setting('frontend_dark_color_scheme') 
                    : '#616a78' 
                }};
                --frontend-dark-color-scheme-lighten-1: {{ setting('frontend_dark_color_scheme') 
                    ? color_lighten(setting('frontend_dark_color_scheme'), 1) 
                    : color_lighten('#616a78', 1) 
                }};
                --frontend-dark-color-scheme-lighten-2: {{ setting('frontend_dark_color_scheme') 
                    ? color_lighten(setting('frontend_dark_color_scheme'), 2) 
                    : color_lighten('#616a78', 2) 
                }};
                --frontend-dark-color-scheme-lighten-4: {{ setting('frontend_dark_color_scheme') 
                    ? color_lighten(setting('frontend_dark_color_scheme'), 4) 
                    : color_lighten('#616a78', 4) 
                }};
                --frontend-dark-color-scheme-darken-1: {{ setting('frontend_dark_color_scheme') 
                    ? color_darken(setting('frontend_dark_color_scheme'), 1) 
                    : color_darken('#616a78', 1) 
                }};
                --frontend-dark-color-scheme-darken-2: {{ setting('frontend_dark_color_scheme') 
                    ? color_darken(setting('frontend_dark_color_scheme'), 2) 
                    : color_darken('#616a78', 2) 
                }};
                --frontend-dark-color-scheme-darken-4: {{ setting('frontend_dark_color_scheme') 
                    ? color_darken(setting('frontend_dark_color_scheme'), 4) 
                    : color_darken('#616a78', 4) 
                }};
                --frontend-dark-light-color-scheme-lighten-4: {{ setting('frontend_dark_color_scheme') 
                    ? color_lighten(color_lighten(setting('frontend_dark_color_scheme'), 2), 4) 
                    : color_lighten(color_lighten('#616a78', 2), 4) 
                }};
                --frontend-dark-light-color-scheme-darken-4: {{ setting('frontend_dark_color_scheme') 
                    ? color_darken(color_lighten(setting('frontend_dark_color_scheme'), 2), 4) 
                    : color_darken(color_lighten('#616a78', 2), 4) 
                }};
                --frontend-primary-color: {{ setting('frontend_primary_color') 
                    ? setting('frontend_primary_color') 
                    : '#0084ff' 
                }};
                --frontend-primary-lighten-color: {{ setting('frontend_primary_color') 
                    ? color_lighten(setting('frontend_primary_color'), 2.5) 
                    : color_lighten('#0084ff', 2.5) 
                }};
                --frontend-primary-lighten-10-color: {{ setting('frontend_primary_color') 
                    ? color_lighten(setting('frontend_primary_color'), 10) 
                    : color_lighten('#0084ff', 10) 
                }};
                --frontend-primary-lighten-20-color: {{ setting('frontend_primary_color') 
                    ? color_lighten(setting('frontend_primary_color'), 20) 
                    : color_lighten('#0084ff', 20) 
                }};
                --frontend-primary-lighten-30-color: {{ setting('frontend_primary_color') 
                    ? color_lighten(setting('frontend_primary_color'), 30) 
                    : color_lighten('#0084ff', 30) 
                }};
                --frontend-primary-lighten-35-color: {{ setting('frontend_primary_color') 
                    ? color_lighten(setting('frontend_primary_color'), 35) 
                    : color_lighten('#0084ff', 35) 
                }};
                --frontend-primary-lighten-40-color: {{ setting('frontend_primary_color') 
                    ? color_lighten(setting('frontend_primary_color'), 40) 
                    : color_lighten('#0084ff', 40) 
                }};
                --frontend-primary-lighten-50-color: {{ setting('frontend_primary_color') 
                    ? color_lighten(setting('frontend_primary_color'), 50) 
                    : color_lighten('#0084ff', 50)
                }};
                --frontend-primary-darken-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 2.5)
                    : color_darken('#0084ff', 2.5) 
                }};
                --frontend-primary-darken-10-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 10) 
                    : color_darken('#0084ff', 10) 
                }};
                --frontend-primary-darken-12-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 12) 
                    : color_darken('#0084ff', 12) 
                }};
                --frontend-primary-darken-20-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 20) 
                    : color_darken('#0084ff', 20)
                }};
                --frontend-primary-darken-30-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 30) 
                    : color_darken('#0084ff', 30) 
                }};
                --frontend-primary-darken-35-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 35) 
                    : color_darken('#0084ff', 35) 
                }};
                --frontend-primary-darken-40-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 40) 
                    : color_darken('#0084ff', 40)
                }};
                --frontend-primary-darken-50-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 50) 
                    : color_darken('#0084ff', 50)
                }};
                --frontend-primary-hover-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 5) 
                    : color_darken('#0084ff', 5)
                }};
                --frontend-primary-hover-darken-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 7) 
                    : color_darken('#0084ff', 7)
                }};
                --frontend-primary-active-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 7) 
                    : color_darken('#0084ff', 7)
                }};
                --frontend-primary-active-darken-color: {{ setting('frontend_primary_color') 
                    ? color_darken(setting('frontend_primary_color'), 9) 
                    : color_darken('#0084ff', 9)
                }};
                --frontend-primary-rgb: {{ setting('frontend_primary_color') 
                    ? color_hex_to_rgb(setting('frontend_primary_color'))
                    : color_hex_to_rgb('#0084ff') 
                }};
                --frontend-secondary-color: {{ setting('frontend_secondary_color') 
                    ? setting('frontend_secondary_color') 
                    : '#6c757d' 
                }};
                --frontend-secondary-lighten-color: {{ setting('frontend_secondary_color') 
                    ? color_lighten(setting('frontend_secondary_color'), 2.5) 
                    : color_lighten('#6c757d', 2.5) 
                }};
                --frontend-secondary-lighten-10-color: {{ setting('frontend_secondary_color') 
                    ? color_lighten(setting('frontend_secondary_color'), 10) 
                    : color_lighten('#6c757d', 10) 
                }};
                --frontend-secondary-lighten-20-color: {{ setting('frontend_secondary_color') 
                    ? color_lighten(setting('frontend_secondary_color'), 20) 
                    : color_lighten('#6c757d', 20) 
                }};
                --frontend-secondary-lighten-30-color: {{ setting('frontend_secondary_color') 
                    ? color_lighten(setting('frontend_secondary_color'), 30) 
                    : color_lighten('#6c757d', 30) 
                }};
                --frontend-secondary-lighten-35-color: {{ setting('frontend_secondary_color') 
                    ? color_lighten(setting('frontend_secondary_color'), 35) 
                    : color_lighten('#6c757d', 35) 
                }};
                --frontend-secondary-lighten-40-color: {{ setting('frontend_secondary_color') 
                    ? color_lighten(setting('frontend_secondary_color'), 40) 
                    : color_lighten('#6c757d', 40) 
                }};
                --frontend-secondary-lighten-50-color: {{ setting('frontend_secondary_color') 
                    ? color_lighten(setting('frontend_secondary_color'), 50) 
                    : color_lighten('#6c757d', 50)
                }};
                --frontend-secondary-darken-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 2.5)
                    : color_darken('#6c757d', 2.5) 
                }};
                --frontend-secondary-darken-10-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 10) 
                    : color_darken('#6c757d', 10) 
                }};
                --frontend-secondary-darken-12-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 12) 
                    : color_darken('#6c757d', 12) 
                }};
                --frontend-secondary-darken-20-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 20) 
                    : color_darken('#6c757d', 20)
                }};
                --frontend-secondary-darken-30-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 30) 
                    : color_darken('#6c757d', 30) 
                }};
                --frontend-secondary-darken-35-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 35) 
                    : color_darken('#6c757d', 35) 
                }};
                --frontend-secondary-darken-40-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 40) 
                    : color_darken('#6c757d', 40)
                }};
                --frontend-secondary-darken-50-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 50) 
                    : color_darken('#6c757d', 50)
                }};
                --frontend-secondary-hover-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 5) 
                    : color_darken('#6c757d', 5)
                }};
                --frontend-secondary-hover-darken-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 7) 
                    : color_darken('#6c757d', 7)
                }};
                --frontend-secondary-active-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 7) 
                    : color_darken('#6c757d', 7)
                }};
                --frontend-secondary-active-darken-color: {{ setting('frontend_secondary_color') 
                    ? color_darken(setting('frontend_secondary_color'), 9) 
                    : color_darken('#6c757d', 9)
                }};
                --frontend-secondary-rgb: {{ setting('frontend_secondary_color') 
                    ? color_hex_to_rgb(setting('frontend_secondary_color'))
                    : color_hex_to_rgb('#6c757d') 
                }};
                --frontend-info-color: {{ setting('frontend_info_color') 
                    ? setting('frontend_info_color') 
                    : '#0dcaf0' 
                }};
                --frontend-info-lighten-color: {{ setting('frontend_info_color') 
                    ? color_lighten(setting('frontend_info_color'), 2.5) 
                    : color_lighten('#0dcaf0', 2.5) 
                }};
                --frontend-info-lighten-10-color: {{ setting('frontend_info_color') 
                    ? color_lighten(setting('frontend_info_color'), 10) 
                    : color_lighten('#0dcaf0', 10) 
                }};
                --frontend-info-lighten-20-color: {{ setting('frontend_info_color') 
                    ? color_lighten(setting('frontend_info_color'), 20) 
                    : color_lighten('#0dcaf0', 20) 
                }};
                --frontend-info-lighten-30-color: {{ setting('frontend_info_color') 
                    ? color_lighten(setting('frontend_info_color'), 30) 
                    : color_lighten('#0dcaf0', 30) 
                }};
                --frontend-info-lighten-35-color: {{ setting('frontend_info_color') 
                    ? color_lighten(setting('frontend_info_color'), 35) 
                    : color_lighten('#0dcaf0', 35) 
                }};
                --frontend-info-lighten-40-color: {{ setting('frontend_info_color') 
                    ? color_lighten(setting('frontend_info_color'), 40) 
                    : color_lighten('#0dcaf0', 40) 
                }};
                --frontend-info-lighten-50-color: {{ setting('frontend_info_color') 
                    ? color_lighten(setting('frontend_info_color'), 50) 
                    : color_lighten('#0dcaf0', 50)
                }};
                --frontend-info-darken-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 2.5)
                    : color_darken('#0dcaf0', 2.5) 
                }};
                --frontend-info-darken-10-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 10) 
                    : color_darken('#0dcaf0', 10) 
                }};
                --frontend-info-darken-12-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 12) 
                    : color_darken('#0dcaf0', 12) 
                }};
                --frontend-info-darken-20-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 20) 
                    : color_darken('#0dcaf0', 20)
                }};
                --frontend-info-darken-30-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 30) 
                    : color_darken('#0dcaf0', 30) 
                }};
                --frontend-info-darken-35-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 35) 
                    : color_darken('#0dcaf0', 35) 
                }};
                --frontend-info-darken-40-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 40) 
                    : color_darken('#0dcaf0', 40)
                }};
                --frontend-info-darken-50-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 50) 
                    : color_darken('#0dcaf0', 50)
                }};
                --frontend-info-hover-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 5) 
                    : color_darken('#0dcaf0', 5)
                }};
                --frontend-info-hover-darken-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 7) 
                    : color_darken('#0dcaf0', 7)
                }};
                --frontend-info-active-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 7) 
                    : color_darken('#0dcaf0', 7)
                }};
                --frontend-info-active-darken-color: {{ setting('frontend_info_color') 
                    ? color_darken(setting('frontend_info_color'), 9) 
                    : color_darken('#0dcaf0', 9)
                }};
                --frontend-info-rgb: {{ setting('frontend_info_color') 
                    ? color_hex_to_rgb(setting('frontend_info_color'))
                    : color_hex_to_rgb('#0dcaf0') 
                }};
                --frontend-success-color: {{ setting('frontend_success_color') 
                    ? setting('frontend_success_color') 
                    : '#10b981' 
                }};
                --frontend-success-lighten-color: {{ setting('frontend_success_color') 
                    ? color_lighten(setting('frontend_success_color'), 2.5) 
                    : color_lighten('#10b981', 2.5) 
                }};
                --frontend-success-lighten-10-color: {{ setting('frontend_success_color') 
                    ? color_lighten(setting('frontend_success_color'), 10) 
                    : color_lighten('#10b981', 10) 
                }};
                --frontend-success-lighten-20-color: {{ setting('frontend_success_color') 
                    ? color_lighten(setting('frontend_success_color'), 20) 
                    : color_lighten('#10b981', 20) 
                }};
                --frontend-success-lighten-30-color: {{ setting('frontend_success_color') 
                    ? color_lighten(setting('frontend_success_color'), 30) 
                    : color_lighten('#10b981', 30) 
                }};
                --frontend-success-lighten-35-color: {{ setting('frontend_success_color') 
                    ? color_lighten(setting('frontend_success_color'), 35) 
                    : color_lighten('#10b981', 35) 
                }};
                --frontend-success-lighten-40-color: {{ setting('frontend_success_color') 
                    ? color_lighten(setting('frontend_success_color'), 40) 
                    : color_lighten('#10b981', 40) 
                }};
                --frontend-success-lighten-50-color: {{ setting('frontend_success_color') 
                    ? color_lighten(setting('frontend_success_color'), 50) 
                    : color_lighten('#10b981', 50)
                }};
                --frontend-success-darken-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 2.5)
                    : color_darken('#10b981', 2.5) 
                }};
                --frontend-success-darken-10-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 10) 
                    : color_darken('#10b981', 10) 
                }};
                --frontend-success-darken-12-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 12) 
                    : color_darken('#10b981', 12) 
                }};
                --frontend-success-darken-20-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 20) 
                    : color_darken('#10b981', 20)
                }};
                --frontend-success-darken-30-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 30) 
                    : color_darken('#10b981', 30) 
                }};
                --frontend-success-darken-35-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 35) 
                    : color_darken('#10b981', 35) 
                }};
                --frontend-success-darken-40-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 40) 
                    : color_darken('#10b981', 40)
                }};
                --frontend-success-darken-50-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 50) 
                    : color_darken('#10b981', 50)
                }};
                --frontend-success-hover-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 5) 
                    : color_darken('#10b981', 5)
                }};
                --frontend-success-hover-darken-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 7) 
                    : color_darken('#10b981', 7)
                }};
                --frontend-success-active-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 7) 
                    : color_darken('#10b981', 7)
                }};
                --frontend-success-active-darken-color: {{ setting('frontend_success_color') 
                    ? color_darken(setting('frontend_success_color'), 9) 
                    : color_darken('#10b981', 9)
                }};
                --frontend-success-rgb: {{ setting('frontend_success_color') 
                    ? color_hex_to_rgb(setting('frontend_success_color'))
                    : color_hex_to_rgb('#10b981') 
                }};
                --frontend-warning-color: {{ setting('frontend_warning_color') 
                    ? setting('frontend_warning_color') 
                    : '#ffc107' 
                }};
                --frontend-warning-lighten-color: {{ setting('frontend_warning_color') 
                    ? color_lighten(setting('frontend_warning_color'), 2.5) 
                    : color_lighten('#ffc107', 2.5) 
                }};
                --frontend-warning-lighten-10-color: {{ setting('frontend_warning_color') 
                    ? color_lighten(setting('frontend_warning_color'), 10) 
                    : color_lighten('#ffc107', 10) 
                }};
                --frontend-warning-lighten-20-color: {{ setting('frontend_warning_color') 
                    ? color_lighten(setting('frontend_warning_color'), 20) 
                    : color_lighten('#ffc107', 20) 
                }};
                --frontend-warning-lighten-30-color: {{ setting('frontend_warning_color') 
                    ? color_lighten(setting('frontend_warning_color'), 30) 
                    : color_lighten('#ffc107', 30) 
                }};
                --frontend-warning-lighten-35-color: {{ setting('frontend_warning_color') 
                    ? color_lighten(setting('frontend_warning_color'), 35) 
                    : color_lighten('#ffc107', 35) 
                }};
                --frontend-warning-lighten-40-color: {{ setting('frontend_warning_color') 
                    ? color_lighten(setting('frontend_warning_color'), 40) 
                    : color_lighten('#ffc107', 40) 
                }};
                --frontend-warning-lighten-50-color: {{ setting('frontend_warning_color') 
                    ? color_lighten(setting('frontend_warning_color'), 50) 
                    : color_lighten('#ffc107', 50)
                }};
                --frontend-warning-darken-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 2.5)
                    : color_darken('#ffc107', 2.5) 
                }};
                --frontend-warning-darken-10-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 10) 
                    : color_darken('#ffc107', 10) 
                }};
                --frontend-warning-darken-12-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 12) 
                    : color_darken('#ffc107', 12) 
                }};
                --frontend-warning-darken-20-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 20) 
                    : color_darken('#ffc107', 20)
                }};
                --frontend-warning-darken-30-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 30) 
                    : color_darken('#ffc107', 30) 
                }};
                --frontend-warning-darken-35-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 35) 
                    : color_darken('#ffc107', 35) 
                }};
                --frontend-warning-darken-40-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 40) 
                    : color_darken('#ffc107', 40)
                }};
                --frontend-warning-darken-50-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 50) 
                    : color_darken('#ffc107', 50)
                }};
                --frontend-warning-hover-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 5) 
                    : color_darken('#ffc107', 5)
                }};
                --frontend-warning-hover-darken-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 7) 
                    : color_darken('#ffc107', 7)
                }};
                --frontend-warning-active-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 7) 
                    : color_darken('#ffc107', 7)
                }};
                --frontend-warning-active-darken-color: {{ setting('frontend_warning_color') 
                    ? color_darken(setting('frontend_warning_color'), 9) 
                    : color_darken('#ffc107', 9)
                }};
                --frontend-warning-rgb: {{ setting('frontend_warning_color') 
                    ? color_hex_to_rgb(setting('frontend_warning_color'))
                    : color_hex_to_rgb('#ffc107') 
                }};
                --frontend-danger-color: {{ setting('frontend_danger_color') 
                    ? setting('frontend_danger_color') 
                    : '#f65660' 
                }};
                --frontend-danger-lighten-color: {{ setting('frontend_danger_color') 
                    ? color_lighten(setting('frontend_danger_color'), 2.5) 
                    : color_lighten('#f65660', 2.5) 
                }};
                --frontend-danger-lighten-10-color: {{ setting('frontend_danger_color') 
                    ? color_lighten(setting('frontend_danger_color'), 10) 
                    : color_lighten('#f65660', 10) 
                }};
                --frontend-danger-lighten-20-color: {{ setting('frontend_danger_color') 
                    ? color_lighten(setting('frontend_danger_color'), 20) 
                    : color_lighten('#f65660', 20) 
                }};
                --frontend-danger-lighten-30-color: {{ setting('frontend_danger_color') 
                    ? color_lighten(setting('frontend_danger_color'), 30) 
                    : color_lighten('#f65660', 30) 
                }};
                --frontend-danger-lighten-35-color: {{ setting('frontend_danger_color') 
                    ? color_lighten(setting('frontend_danger_color'), 35) 
                    : color_lighten('#f65660', 35) 
                }};
                --frontend-danger-lighten-40-color: {{ setting('frontend_danger_color') 
                    ? color_lighten(setting('frontend_danger_color'), 40) 
                    : color_lighten('#f65660', 40) 
                }};
                --frontend-danger-lighten-50-color: {{ setting('frontend_danger_color') 
                    ? color_lighten(setting('frontend_danger_color'), 50) 
                    : color_lighten('#f65660', 50)
                }};
                --frontend-danger-darken-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 2.5)
                    : color_darken('#f65660', 2.5) 
                }};
                --frontend-danger-darken-10-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 10) 
                    : color_darken('#f65660', 10) 
                }};
                --frontend-danger-darken-12-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 12) 
                    : color_darken('#f65660', 12) 
                }};
                --frontend-danger-darken-20-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 20) 
                    : color_darken('#f65660', 20)
                }};
                --frontend-danger-darken-30-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 30) 
                    : color_darken('#f65660', 30) 
                }};
                --frontend-danger-darken-35-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 35) 
                    : color_darken('#f65660', 35) 
                }};
                --frontend-danger-darken-40-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 40) 
                    : color_darken('#f65660', 40)
                }};
                --frontend-danger-darken-50-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 50) 
                    : color_darken('#f65660', 50)
                }};
                --frontend-danger-hover-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 5) 
                    : color_darken('#f65660', 5)
                }};
                --frontend-danger-hover-darken-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 7) 
                    : color_darken('#f65660', 7)
                }};
                --frontend-danger-active-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 7) 
                    : color_darken('#f65660', 7)
                }};
                --frontend-danger-active-darken-color: {{ setting('frontend_danger_color') 
                    ? color_darken(setting('frontend_danger_color'), 9) 
                    : color_darken('#f65660', 9)
                }};
                --frontend-danger-rgb: {{ setting('frontend_danger_color') 
                    ? color_hex_to_rgb(setting('frontend_danger_color'))
                    : color_hex_to_rgb('#f65660') 
                }};
                --frontend-breadcrumb-bg-color: {{ setting('frontend_breadcrumb_bg_color') 
                    ? setting('frontend_breadcrumb_bg_color') 
                    : '#F3FFFF' 
                }};
                --frontend-breadcrumb-text-color: {{ setting('frontend_breadcrumb_text_color') 
                    ? setting('frontend_breadcrumb_text_color') 
                    : '#6A7C92' 
                }};
                --frontend-breadcrumb-text-hover-color: {{ setting('frontend_breadcrumb_text_hover_color') 
                    ? setting('frontend_breadcrumb_text_hover_color') 
                    : '#0084ff'
                }};
                --frontend-breadcrumb-page-title-color: {{ setting('frontend_breadcrumb_page_title_color') 
                    ? setting('frontend_breadcrumb_page_title_color') 
                    : '#051441'
                }};
                --frontend-navbar-bg-color: {{ setting('frontend_navbar_bg_color') 
                    ? setting('frontend_navbar_bg_color') 
                    : '#ffffff'
                }};
                --frontend-navbar-menu-toggler-icon-color: {{ setting('frontend_navbar_menu_toggler_icon_color') 
                    ? setting('frontend_navbar_menu_toggler_icon_color', '#222') 
                    : '#222'
                }};
                --frontend-navbar-menu-text-color: {{ setting('frontend_navbar_menu_text_color') 
                    ? setting('frontend_navbar_menu_text_color') 
                    : '#051441'
                }};
                --frontend-navbar-menu-text-hover-color: {{ setting('frontend_navbar_menu_text_hover_color') 
                    ? setting('frontend_navbar_menu_text_hover_color') 
                    : '#0084ff'
                }};
                --frontend-navbar-menu-text-active-color: {{ setting('frontend_navbar_menu_text_active_color') 
                    ? setting('frontend_navbar_menu_text_active_color') 
                    : '#0084ff'
                }};
                --frontend-footer-bg-color: {{ setting('frontend_footer_bg_color') 
                    ? setting('frontend_footer_bg_color') 
                    : '#1f232f'
                }};
                --frontend-footer-heading-color: {{ setting('frontend_footer_heading_color') 
                    ? setting('frontend_footer_heading_color') 
                    : '#fff'
                }};
                --frontend-footer-text-color: {{ setting('frontend_footer_text_color') 
                    ? setting('frontend_footer_text_color') 
                    : '#ffffffbf'
                }};
                --frontend-footer-text-hover-color: {{ setting('frontend_footer_text_hover_color') 
                    ? setting('frontend_footer_text_hover_color') 
                    : '#fff'
                }};
            }   
        </style>

        <link media="all" type="text/css" rel="stylesheet" href="{{ url('css/errors.min.css') }}">
    </head>
    <body class="page-error">
        <div class="container d-flex flex-column">
            <div class="row align-items-center justify-content-center min-vh-100">
                <div class="col-sm-11 col-md-9 col-lg-7 col-xl-6">
                    <div class="page-inner text-center">
                        <h1>@yield('code')</h1>
                        <h3>@yield('title')</h3>
                        <p class="lead">@yield('message')</p>
                        <div class="action-wrapper">
                            <a href="/" class="btn btn-primary"><i class="fa fa-arrow-left"></i><span>Back to Home Page</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
