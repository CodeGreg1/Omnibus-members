<?php

use Mexitek\PHPColors\Color;

if (!function_exists('navbar_color_scheme')) {
    /**
     * Navbar color scheme
     * 
     * @param Model|null $page
     * 
     * @return string
     */
    function navbar_color_scheme($page = null) {
        $darkScheme = 'dark-scheme';

        if(!is_null($page)) {
            if(isset($page) && $page->dark_mode) {
                return $darkScheme;
            }
        }

        if(setting('frontend_navbar_bg_color')) {
            $color = new Color(setting('frontend_navbar_bg_color'));

            if(setting('frontend_color_scheme') === 'dark') {
                return $darkScheme;
            }

            if($color->isDark()) {
                return $darkScheme;
            }
        }
    }
}

if (!function_exists('footer_color_scheme')) {
    /**
     * Footer color scheme
     * 
     * @param Model|null $page
     * 
     * @return string
     */
    function footer_color_scheme($page = null) {
        $darkScheme = 'dark-scheme';

        if(!is_null($page)) {
            if(isset($page) && $page->dark_mode) {
                return $darkScheme;
            }
        }

        if(setting('frontend_footer_bg_color')) {
            $color = new Color(setting('frontend_footer_bg_color'));

            if(setting('frontend_color_scheme') === 'dark') {
                return $darkScheme;
            }

            if($color->isDark()) {
                return $darkScheme;
            }
        }
    }
}


if (!function_exists('color_scheme')) {
    /**
     * Color scheme
     * 
     * @param Model|null $page
     * 
     * @return string
     */
    function color_scheme($page = null) {
        $darkScheme = 'dark-scheme';

        if(!is_null($page)) {
            if(isset($page) && $page->dark_mode) {
                return $darkScheme;
            } else {
                return null;
            }
        }

        if(setting('frontend_color_scheme') === 'dark') {
            return $darkScheme;
        }
    }
}


if (!function_exists('color_lighten')) {
    /**
     * Darken the color
     * 
     * @param str $hex
     * @param float $percent (1-99)
     * 
     * @return string
     */
    function color_lighten($hex, $percent) {
        $color = new Color($hex);

        return '#'.$color->lighten($percent);
    }
}

if (!function_exists('color_darken')) {
    /**
     * Darken the color
     * 
     * @param str $hex
     * @param float $percent (1-99)
     * 
     * @return string
     */
    function color_darken($hex, $percent) {
        $color = new Color($hex);

        return '#'.$color->darken($percent);
    }
}

if (!function_exists('color_hex_to_rgb')) {
    /**
     * Convert hex to rgb
     * 
     * @param str $hex
     * @param float $percent (0.1 - 0.9)
     * 
     * @return string sample output: 255, 255, 255
     */
    function color_hex_to_rgb($hex) {
        if ($hex[0] == '#') {
            $hex = substr($hex, 1);
        }
        list($r, $g, $b) = array_map("hexdec", str_split($hex, (strlen( $hex ) / 3)));

        return $r . ', ' . $g . ', ' . $b;
    }
}
