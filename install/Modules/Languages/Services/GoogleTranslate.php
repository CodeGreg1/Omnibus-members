<?php

namespace Modules\Languages\Services;

use Stichoza\GoogleTranslate\GoogleTranslate as BaseGoogleTranslate;

class GoogleTranslate
{
	/**
	 * @var array $shortcodes
	 */
	protected static $shortcodes = [];

	/**
	 * @var string SHORTCODE
	 */
	const SHORTCODE = 'shrtcde';

	/**
	 * Handle google translation using GoogleTranslate Package
	 * 
	 * @param string $phrase
	 * @param string $lang
	 * 
	 * @return string
	 */
	public static function trans($phrase, $lang) 
	{
		// Setting shortcode that is not readable so that it will exclude on the translation
		$phrase = self::setShortcode($phrase);

		// Execute google translation
		$translated = BaseGoogleTranslate::trans(
			$phrase, 
			$lang
		);

		// Revert phrase shortcodes
		foreach(self::$shortcodes as $key => $shortcode) {
			$translated = str_replace($shortcode, $key, $translated);
		}

		return $translated;
	}

	/**
	 * Generate custom shortcode if the phrase has a shortcode so that it will include in the translation
	 * 
	 * @param string $phrase
	 * 
	 * @return string
	 */
	protected static function setShortcode($phrase) 
	{
		preg_match_all(
            '/:(.*?) /s', 
            $phrase, 
            $match
        );

        foreach($match[1] as $shortcode) {

            $var = self::SHORTCODE . count(self::$shortcodes);

            self::$shortcodes[":$shortcode"] = $var;

            $phrase = str_replace(":$shortcode", $var, $phrase);
        }

        preg_match_all(
            '/:(.*?)\./s', 
            $phrase, 
            $match
        );

        foreach($match[1] as $shortcode) {

            $var = self::SHORTCODE . count(self::$shortcodes);

            self::$shortcodes[":$shortcode"] = $var;

            $phrase = str_replace(":$shortcode", $var, $phrase);
        }

        return $phrase;
	}
}